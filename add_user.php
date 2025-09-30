<?php
// Suprimir la salida de errores HTML
ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL);

// Iniciar sesión y configurar headers
session_start();
header('Content-Type: application/json');
header('Cache-Control: no-cache, must-revalidate');

// Función para enviar respuesta JSON
function sendJsonResponse($success, $message, $data = null) {
    $response = ['success' => $success, 'message' => $message];
    if ($data !== null) {
        $response['data'] = $data;
    }
    echo json_encode($response);
    exit;
}

// Verificar si el usuario está autenticado y es administrador
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || !isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    sendJsonResponse(false, 'Acceso denegado. Se requieren privilegios de administrador');
}

// Obtener y decodificar los datos JSON
$rawData = file_get_contents('php://input');
if ($rawData === false) {
    sendJsonResponse(false, 'Error al leer los datos de entrada');
}

$data = json_decode($rawData, true);
if ($data === null) {
    sendJsonResponse(false, 'Error al decodificar JSON: ' . json_last_error_msg());
}

// Verificar datos requeridos
if (!isset($data['username']) || !isset($data['password']) || !isset($data['nombre'])) {
    sendJsonResponse(false, 'Faltan datos requeridos');
}

// Validar que los campos no estén vacíos
if (empty(trim($data['username'])) || empty(trim($data['password'])) || empty(trim($data['nombre']))) {
    sendJsonResponse(false, 'Todos los campos son obligatorios');
}

try {
    require_once 'connect.php';
    
    if (!$pdo instanceof PDO) {
        throw new Exception('Error de conexión a la base de datos');
    }

    // Verificar si el usuario ya existe
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM usuarios WHERE username = ?");
    $username = trim($data['username']);
    $stmt->execute([$username]);
    
    if ($stmt->fetchColumn() > 0) {
        sendJsonResponse(false, 'El nombre de usuario ya existe');
    }

    // Preparar los datos para la inserción
    $hashedPassword = password_hash(trim($data['password']), PASSWORD_DEFAULT);
    $nombre = trim($data['nombre']);

    // Insertar nuevo usuario
    $stmt = $pdo->prepare("INSERT INTO usuarios (username, password, nombre) VALUES (?, ?, ?)");
    $result = $stmt->execute([$username, $hashedPassword, $nombre]);

    if ($result) {
        $userId = $pdo->lastInsertId();
        if ($userId) {
            sendJsonResponse(true, 'Usuario agregado exitosamente', [
                'id' => $userId,
                'username' => $username,
                'nombre' => $nombre
            ]);
        }
    }
    
    // Si no se insertó correctamente
    if (!$result) {
        error_log("Error al insertar usuario: la inserción falló");
        sendJsonResponse(false, 'Error al insertar el usuario en la base de datos');
    }

} catch(PDOException $e) {
    error_log("Error de base de datos en add_user.php: " . $e->getMessage());
    sendJsonResponse(false, 'Error en la base de datos: ' . $e->getMessage());

} catch(Exception $e) {
    error_log("Error en add_user.php: " . $e->getMessage());
    sendJsonResponse(false, 'Error al procesar la solicitud: ' . $e->getMessage());
}