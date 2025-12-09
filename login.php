<?php
/**
 * Sistema de Login Seguro para ROMISA
 * Con rate limiting, logging y protección CSRF
 * Compatible con el sistema de usuarios existente (gestor_login.php)
 */

// Suprimir warnings para respuestas JSON limpias
error_reporting(E_ERROR | E_PARSE);
ini_set('display_errors', 0);

header('Content-Type: application/json; charset=utf-8');

require_once 'auth_middleware.php';

// Conexión a base de datos
require_once 'connect.php';
$conn = $pdo;

// Función para sanitizar entradas
function sanitizarInput($input) {
    return htmlspecialchars(strip_tags(trim($input)), ENT_QUOTES, 'UTF-8');
}

// Verificar si csrf_protection.php existe
$csrfEnabled = file_exists(__DIR__ . '/csrf_protection.php');
if ($csrfEnabled) {
    require_once 'csrf_protection.php';
}

// Procesar solicitudes POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Detectar si es JSON o form data
    $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
    
    if (strpos($contentType, 'application/json') !== false) {
        // Datos enviados como JSON
        $jsonInput = file_get_contents('php://input');
        $data = json_decode($jsonInput, true);
        
        if ($data === null) {
            echo json_encode([
                'success' => false,
                'message' => 'Datos JSON inválidos'
            ]);
            exit;
        }
        
        $action = $data['action'] ?? 'login'; // Por defecto es login
        $usernameInput = sanitizarInput($data['username'] ?? $data['email'] ?? '');
        $password = $data['password'] ?? '';
        $csrfToken = $data['csrf_token'] ?? null;
    } else {
        // Datos enviados como form data
        $action = $_POST['action'] ?? 'login';
        $usernameInput = sanitizarInput($_POST['username'] ?? $_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $csrfToken = $_POST['csrf_token'] ?? null;
    }
    
    $ip = obtenerIPCliente();
    
    if ($action === 'login') {
        
        // Verificar si la IP está bloqueada
        if (ipEstaBloqueada($ip)) {
            $tiempoRestante = tiempoRestanteBloqueo($ip);
            $minutos = ceil($tiempoRestante / 60);
            
            // Registrar intento bloqueado
            registrarAccion('login_bloqueado', 'usuario', null, "IP: $ip, Usuario: $usernameInput");
            
            echo json_encode([
                'success' => false,
                'message' => "Demasiados intentos fallidos. Intente nuevamente en $minutos minutos.",
                'blocked' => true,
                'retry_after' => $tiempoRestante
            ]);
            exit;
        }
        
        // Verificar token CSRF solo si está habilitado
        if ($csrfEnabled && $csrfToken !== null) {
            if (!validateCSRFToken($csrfToken)) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Token de seguridad inválido. Recargue la página e intente nuevamente.'
                ]);
                exit;
            }
        }
        
        if (empty($usernameInput) || empty($password)) {
            echo json_encode([
                'success' => false,
                'message' => 'Por favor, complete todos los campos'
            ]);
            exit;
        }
        
        try {
            // Buscar usuario - Compatible con esquema existente (usa is_admin, no rol)
            $stmt = $conn->prepare("SELECT id, username, password, nombre FROM usuarios WHERE username = :username LIMIT 1");
            $stmt->bindParam(':username', $usernameInput, PDO::PARAM_STR);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Verificar credenciales
            if ($user && password_verify($password, $user['password'])) {
                
                // Obtener is_admin de manera segura (por si la columna existe)
                $isAdmin = false;
                try {
                    $stmtAdmin = $conn->prepare("SELECT is_admin FROM usuarios WHERE id = :id");
                    $stmtAdmin->bindParam(':id', $user['id'], PDO::PARAM_INT);
                    $stmtAdmin->execute();
                    $adminData = $stmtAdmin->fetch(PDO::FETCH_ASSOC);
                    $isAdmin = isset($adminData['is_admin']) && $adminData['is_admin'] == 1;
                } catch(PDOException $e) {
                    // Si la columna is_admin no existe, asumimos false
                    $isAdmin = false;
                }
                
                // Login exitoso
                registrarIntentoLogin($ip, $usernameInput, true);
                
                // Regenerar ID de sesión para prevenir session fixation
                iniciarSesionSegura();
                session_regenerate_id(true);
                
                // Establecer variables de sesión - Compatible con sistema existente
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['nombre'] = $user['nombre'] ?? '';
                $_SESSION['is_admin'] = $isAdmin;  // Usar is_admin como en gestor_login.php
                $_SESSION['logged_in'] = true;
                $_SESSION['last_activity'] = time();
                $_SESSION['ip_address'] = $ip; // Guardar IP para validación adicional
                
                // Generar nuevo token CSRF si está habilitado
                $newToken = '';
                if ($csrfEnabled && function_exists('refreshCSRFToken')) {
                    $newToken = refreshCSRFToken();
                }
                
                // Registrar login exitoso
                registrarAccion('login', 'usuario', $user['id'], "Login exitoso desde IP: $ip");
                
                $response = [
                    'success' => true,
                    'message' => 'Inicio de sesión exitoso',
                    'user' => [
                        'username' => $user['username'],
                        'nombre' => $user['nombre'] ?? '',
                        'is_admin' => $isAdmin
                    ]
                ];
                
                if ($newToken) {
                    $response['csrf_token'] = $newToken;
                }
                
                echo json_encode($response);
                exit;
                
            } else {
                // Login fallido
                registrarIntentoLogin($ip, $usernameInput, false);
                
                // Registrar intento fallido
                registrarAccion('login_fallido', 'usuario', null, "Usuario: $usernameInput, IP: $ip");
                
                // Calcular intentos restantes
                $intentosRestantes = MAX_LOGIN_ATTEMPTS - contarIntentosFallidos($ip);
                
                $mensaje = 'Usuario o contraseña incorrectos';
                if ($intentosRestantes > 0 && $intentosRestantes <= 3) {
                    $mensaje .= ". Quedan $intentosRestantes intentos.";
                }
                
                echo json_encode([
                    'success' => false,
                    'message' => $mensaje
                ]);
                exit;
            }
            
        } catch (PDOException $e) {
            error_log("Error en login: " . $e->getMessage());
            echo json_encode([
                'success' => false,
                'message' => 'Error del servidor. Intente nuevamente.'
            ]);
            exit;
        }
        
    } elseif ($action === 'logout') {
        iniciarSesionSegura();
        
        // Registrar logout
        if (estaAutenticado()) {
            $usuario = obtenerUsuarioActual();
            registrarAccion('logout', 'usuario', $usuario['id'] ?? null, null);
        }
        
        cerrarSesion();
        
        echo json_encode([
            'success' => true,
            'message' => 'Sesión cerrada correctamente'
        ]);
        exit;
        
    } elseif ($action === 'check') {
        // Verificar estado de sesión
        iniciarSesionSegura();
        
        if (estaAutenticado()) {
            $usuario = obtenerUsuarioActual();
            echo json_encode([
                'success' => true,
                'authenticated' => true,
                'user' => $usuario
            ]);
        } else {
            echo json_encode([
                'success' => true,
                'authenticated' => false
            ]);
        }
        exit;
    }
}

// Función auxiliar para contar intentos fallidos
function contarIntentosFallidos($ip) {
    $pdo = obtenerConexionDB();
    if (!$pdo) return 0;
    
    try {
        $stmt = $pdo->prepare("
            SELECT COUNT(*) as intentos 
            FROM login_attempts 
            WHERE ip_address = :ip 
            AND success = 0 
            AND attempt_time > DATE_SUB(NOW(), INTERVAL :lockout SECOND)
        ");
        $lockout = LOCKOUT_TIME;
        $stmt->bindParam(':ip', $ip);
        $stmt->bindParam(':lockout', $lockout, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return (int)$result['intentos'];
        
    } catch (PDOException $e) {
        return 0;
    }
}

// Si es GET, mostrar error
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Método no permitido'
    ]);
}
?>
