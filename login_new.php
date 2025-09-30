<?php
require_once 'security_headers.php';
require_once 'csrf_protection.php';
session_start();

// Configuración de la base de datos
$host = 'localhost';
$dbname = 'romisite';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die(json_encode(['success' => false, 'message' => 'Error de conexión']));
}

// Función para sanitizar entradas
function sanitizar($input) {
    return htmlspecialchars(strip_tags(trim($input)), ENT_QUOTES, 'UTF-8');
}

// Procesar solicitudes POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificar token CSRF para todas las solicitudes POST
    if (!isset($_POST['csrf_token']) || !validateCSRFToken($_POST['csrf_token'])) {
        die(json_encode(['success' => false, 'message' => 'Token de seguridad inválido']));
    }

    $action = $_POST['action'] ?? '';

    if ($action === 'login') {
        $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            die(json_encode(['success' => false, 'message' => 'Todos los campos son requeridos']));
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            die(json_encode(['success' => false, 'message' => 'Correo electrónico no válido']));
        }

        try {
            // Prevenir timing attacks usando hash_equals
            $stmt = $conn->prepare("SELECT * FROM usuarios WHERE email = :email LIMIT 1");
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                // Regenerar ID de sesión para prevenir session fixation
                session_regenerate_id(true);

                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['logged_in'] = true;
                $_SESSION['last_activity'] = time();
                $_SESSION['is_admin'] = (bool)$user['is_admin'];
                
                // Generar nuevo token CSRF después del login
                $newToken = refreshCSRFToken();
                
                echo json_encode([
                    'success' => true,
                    'message' => 'Inicio de sesión exitoso',
                    'csrf_token' => $newToken
                ]);
            } else {
                // Mensaje genérico para no revelar si el usuario existe
                echo json_encode([
                    'success' => false,
                    'message' => 'Credenciales inválidas'
                ]);
            }
        } catch (PDOException $e) {
            error_log("Error de login: " . $e->getMessage());
            echo json_encode([
                'success' => false,
                'message' => 'Error al procesar la solicitud'
            ]);
        }
    }
} else {
    // Método no permitido
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}