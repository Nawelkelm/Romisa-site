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

        if (!empty($email) && !empty($password)) {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                die(json_encode(['success' => false, 'message' => 'Correo electrónico no válido']));
            }

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
                
                // Generar nuevo token CSRF después del login
                $newToken = refreshCSRFToken();
                
                echo json_encode([
                    'success' => true,
                    'message' => 'Inicio de sesión exitoso',
                    'csrf_token' => $newToken
                ]);
                exit;
                ];
                // Redirigir a la página protegida
                header('Location: pruebalistas.html');
                exit();
            } else {
                echo "Error: Correo electrónico o contraseña incorrectos.";  
                header('Location: login-lista.html');
            }
        } else {
            echo "Por favor, completa todos los campos.";
        }
    } elseif ($action === 'register') {
        // Registro de usuario
        $nombre = sanitizar($_POST['nombre'] ?? '');
        $email = sanitizar($_POST['email'] ?? '');
        $password = sanitizar($_POST['password'] ?? '');

        if (!empty($nombre) && !empty($email) && !empty($password)) {
            // Validar correo electrónico
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                die("Correo electrónico no válido.");
            }

            // Verificar si el usuario ya existe
            $stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = :email");
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                die("El usuario ya está registrado.");
            }

            // Encriptar contraseña
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Insertar usuario en la base de datos
            $stmt = $conn->prepare("INSERT INTO usuarios (nombre, email, password) VALUES (:nombre, :email, :password)");
            $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);

            if ($stmt->execute()) {
                echo "Registro exitoso. Ahora puedes iniciar sesión.";
            } else {
                echo "Error al registrar el usuario. Intenta nuevamente.";
            }
        } else {
            echo "Por favor, completa todos los campos.";
        }
    }
}
?>
