<?php
/**
 * Middleware de Autenticación para ROMISA
 * Proporciona funciones centralizadas para verificación de sesión,
 * roles de usuario, rate limiting y logging de seguridad.
 * 
 * Compatible con el sistema de usuarios existente (gestor_login.php)
 * Usa $_SESSION['is_admin'] como sistema de permisos
 */

// Configuración de seguridad
define('SESSION_TIMEOUT', 1800); // 30 minutos de inactividad
define('MAX_LOGIN_ATTEMPTS', 5); // Máximo intentos de login
define('LOCKOUT_TIME', 900); // 15 minutos de bloqueo

/**
 * Inicia sesión segura si no está iniciada
 */
function iniciarSesionSegura() {
    if (session_status() === PHP_SESSION_NONE) {
        // Configuración segura de cookies de sesión
        session_set_cookie_params([
            'lifetime' => 0,
            'path' => '/',
            'domain' => '',
            'secure' => isset($_SERVER['HTTPS']), // Solo HTTPS en producción
            'httponly' => true,
            'samesite' => 'Strict'
        ]);
        session_start();
    }
}

/**
 * Verifica si el usuario está autenticado
 * @return bool
 */
function estaAutenticado() {
    iniciarSesionSegura();
    
    // Verificar que existe sesión válida (compatible con gestor_login.php)
    if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
        return false;
    }
    
    // Verificar que existe user_id
    if (!isset($_SESSION['user_id'])) {
        return false;
    }
    
    // Verificar timeout de inactividad
    if (isset($_SESSION['last_activity'])) {
        if (time() - $_SESSION['last_activity'] > SESSION_TIMEOUT) {
            cerrarSesion();
            return false;
        }
    }
    
    // Actualizar tiempo de actividad
    $_SESSION['last_activity'] = time();
    
    return true;
}

/**
 * Verifica si el usuario tiene rol de administrador
 * Compatible con sistema existente que usa $_SESSION['is_admin']
 * @return bool
 */
function esAdmin() {
    if (!estaAutenticado()) {
        return false;
    }
    
    // Usar is_admin del sistema existente
    return isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true;
}

/**
 * Verifica si el usuario tiene permisos de editor o superior
 * En este sistema, todos los usuarios autenticados pueden editar noticias
 * Solo los admins pueden eliminar permanentemente
 * @return bool
 */
function esEditor() {
    // Todos los usuarios autenticados son editores
    return estaAutenticado();
}

/**
 * Obtiene información del usuario actual
 * Compatible con sistema existente de usuarios
 * @return array|null
 */
function obtenerUsuarioActual() {
    if (!estaAutenticado()) {
        return null;
    }
    
    return [
        'id' => $_SESSION['user_id'] ?? null,
        'username' => $_SESSION['username'] ?? 'Usuario',
        'nombre' => $_SESSION['nombre'] ?? '',
        'is_admin' => $_SESSION['is_admin'] ?? false,
        'rol' => ($_SESSION['is_admin'] ?? false) ? 'admin' : 'editor'
    ];
}

/**
 * Cierra la sesión de forma segura
 */
function cerrarSesion() {
    iniciarSesionSegura();
    
    // Limpiar todas las variables de sesión
    $_SESSION = [];
    
    // Eliminar cookie de sesión
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time() - 3600, '/');
    }
    
    // Destruir la sesión
    session_destroy();
}

/**
 * Middleware que requiere autenticación
 * Envía error JSON si no está autenticado
 * @param bool $requiereAdmin Si true, requiere rol admin
 */
function requerirAutenticacion($requiereAdmin = false) {
    header('Content-Type: application/json; charset=utf-8');
    
    if (!estaAutenticado()) {
        http_response_code(401);
        echo json_encode([
            'success' => false,
            'message' => 'No autorizado. Debe iniciar sesión.',
            'code' => 'UNAUTHORIZED'
        ]);
        exit();
    }
    
    if ($requiereAdmin && !esAdmin()) {
        http_response_code(403);
        echo json_encode([
            'success' => false,
            'message' => 'No tiene permisos suficientes para esta acción.',
            'code' => 'FORBIDDEN'
        ]);
        exit();
    }
}

/**
 * Middleware que requiere rol de editor o superior
 */
function requerirEditor() {
    header('Content-Type: application/json; charset=utf-8');
    
    if (!estaAutenticado()) {
        http_response_code(401);
        echo json_encode([
            'success' => false,
            'message' => 'No autorizado. Debe iniciar sesión.',
            'code' => 'UNAUTHORIZED'
        ]);
        exit();
    }
    
    if (!esEditor()) {
        http_response_code(403);
        echo json_encode([
            'success' => false,
            'message' => 'No tiene permisos de editor para esta acción.',
            'code' => 'FORBIDDEN'
        ]);
        exit();
    }
}

// =====================================================
// SISTEMA DE RATE LIMITING
// =====================================================

/**
 * Obtiene la conexión PDO a la base de datos
 * @return PDO
 */
function obtenerConexionDB() {
    static $pdo = null;
    
    if ($pdo === null) {
        try {
            $pdo = new PDO("mysql:host=localhost;dbname=romisa;charset=utf8", "root", "");
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            error_log("Error de conexión DB: " . $e->getMessage());
            return null;
        }
    }
    
    return $pdo;
}

/**
 * Verifica si una IP está bloqueada por demasiados intentos
 * @param string $ip
 * @return bool
 */
function ipEstaBloqueada($ip) {
    $pdo = obtenerConexionDB();
    if (!$pdo) return false;
    
    try {
        // Crear tabla si no existe
        $pdo->exec("CREATE TABLE IF NOT EXISTS login_attempts (
            id INT AUTO_INCREMENT PRIMARY KEY,
            ip_address VARCHAR(45) NOT NULL,
            username VARCHAR(100),
            attempt_time DATETIME DEFAULT CURRENT_TIMESTAMP,
            success TINYINT(1) DEFAULT 0,
            INDEX idx_ip (ip_address),
            INDEX idx_time (attempt_time)
        )");
        
        // Limpiar registros antiguos (más de 24 horas)
        $pdo->exec("DELETE FROM login_attempts WHERE attempt_time < DATE_SUB(NOW(), INTERVAL 24 HOUR)");
        
        // Contar intentos fallidos recientes
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
        
        return ($result['intentos'] >= MAX_LOGIN_ATTEMPTS);
        
    } catch (PDOException $e) {
        error_log("Error en rate limiting: " . $e->getMessage());
        return false;
    }
}

/**
 * Registra un intento de login
 * @param string $ip
 * @param string $username
 * @param bool $exitoso
 */
function registrarIntentoLogin($ip, $username, $exitoso = false) {
    $pdo = obtenerConexionDB();
    if (!$pdo) return;
    
    try {
        $stmt = $pdo->prepare("
            INSERT INTO login_attempts (ip_address, username, success) 
            VALUES (:ip, :username, :success)
        ");
        $success = $exitoso ? 1 : 0;
        $stmt->bindParam(':ip', $ip);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':success', $success, PDO::PARAM_INT);
        $stmt->execute();
        
        // Si el login fue exitoso, limpiar intentos fallidos de esta IP
        if ($exitoso) {
            $stmt = $pdo->prepare("DELETE FROM login_attempts WHERE ip_address = :ip AND success = 0");
            $stmt->bindParam(':ip', $ip);
            $stmt->execute();
        }
        
    } catch (PDOException $e) {
        error_log("Error al registrar intento: " . $e->getMessage());
    }
}

/**
 * Obtiene tiempo restante de bloqueo en segundos
 * @param string $ip
 * @return int
 */
function tiempoRestanteBloqueo($ip) {
    $pdo = obtenerConexionDB();
    if (!$pdo) return 0;
    
    try {
        $stmt = $pdo->prepare("
            SELECT MAX(attempt_time) as ultimo_intento 
            FROM login_attempts 
            WHERE ip_address = :ip AND success = 0
        ");
        $stmt->bindParam(':ip', $ip);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result && $result['ultimo_intento']) {
            $ultimoIntento = strtotime($result['ultimo_intento']);
            $tiempoTranscurrido = time() - $ultimoIntento;
            $tiempoRestante = LOCKOUT_TIME - $tiempoTranscurrido;
            return max(0, $tiempoRestante);
        }
        
    } catch (PDOException $e) {
        error_log("Error al obtener tiempo bloqueo: " . $e->getMessage());
    }
    
    return 0;
}

// =====================================================
// SISTEMA DE LOGGING DE ACCIONES
// =====================================================

/**
 * Registra una acción en el log de auditoría
 * @param string $accion Tipo de acción (crear, editar, eliminar, login, etc.)
 * @param string $entidad Entidad afectada (noticia, usuario, etc.)
 * @param int|null $entidadId ID de la entidad
 * @param string|null $detalles Detalles adicionales
 */
function registrarAccion($accion, $entidad, $entidadId = null, $detalles = null) {
    $pdo = obtenerConexionDB();
    if (!$pdo) return;
    
    try {
        // Crear tabla si no existe
        $pdo->exec("CREATE TABLE IF NOT EXISTS audit_log (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT,
            username VARCHAR(100),
            accion VARCHAR(50) NOT NULL,
            entidad VARCHAR(50) NOT NULL,
            entidad_id INT,
            detalles TEXT,
            ip_address VARCHAR(45),
            user_agent VARCHAR(255),
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_user (user_id),
            INDEX idx_accion (accion),
            INDEX idx_entidad (entidad, entidad_id),
            INDEX idx_created (created_at)
        )");
        
        $usuario = obtenerUsuarioActual();
        $userId = $usuario['id'] ?? null;
        $username = $usuario['username'] ?? 'Sistema';
        $ip = obtenerIPCliente();
        $userAgent = substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 255);
        
        $stmt = $pdo->prepare("
            INSERT INTO audit_log (user_id, username, accion, entidad, entidad_id, detalles, ip_address, user_agent) 
            VALUES (:user_id, :username, :accion, :entidad, :entidad_id, :detalles, :ip, :user_agent)
        ");
        
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':accion', $accion);
        $stmt->bindParam(':entidad', $entidad);
        $stmt->bindParam(':entidad_id', $entidadId, PDO::PARAM_INT);
        $stmt->bindParam(':detalles', $detalles);
        $stmt->bindParam(':ip', $ip);
        $stmt->bindParam(':user_agent', $userAgent);
        $stmt->execute();
        
    } catch (PDOException $e) {
        error_log("Error en audit log: " . $e->getMessage());
    }
}

/**
 * Obtiene la IP real del cliente
 * @return string
 */
function obtenerIPCliente() {
    $headers = ['HTTP_CF_CONNECTING_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_REAL_IP', 'REMOTE_ADDR'];
    
    foreach ($headers as $header) {
        if (!empty($_SERVER[$header])) {
            $ip = $_SERVER[$header];
            // Si hay múltiples IPs (X-Forwarded-For), tomar la primera
            if (strpos($ip, ',') !== false) {
                $ip = trim(explode(',', $ip)[0]);
            }
            if (filter_var($ip, FILTER_VALIDATE_IP)) {
                return $ip;
            }
        }
    }
    
    return '0.0.0.0';
}

// =====================================================
// VALIDACIÓN DE TOKENS Y SEGURIDAD ADICIONAL
// =====================================================

/**
 * Genera un token de seguridad único
 * @return string
 */
function generarToken() {
    return bin2hex(random_bytes(32));
}

/**
 * Valida que la solicitud venga del mismo origen
 * @return bool
 */
function validarOrigen() {
    if (!isset($_SERVER['HTTP_REFERER'])) {
        return true; // Permitir si no hay referer (solicitudes directas)
    }
    
    $referer = parse_url($_SERVER['HTTP_REFERER']);
    $host = $_SERVER['HTTP_HOST'];
    
    return isset($referer['host']) && $referer['host'] === $host;
}

/**
 * Verifica encabezados de seguridad para solicitudes AJAX
 * @return bool
 */
function esAjaxValido() {
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
           strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
}
?>
