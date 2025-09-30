<?php
// Suprimir la salida de errores HTML
ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL);

session_start();
header('Content-Type: application/json');

// Obtener y decodificar los datos JSON
$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (!isset($data['username']) || !isset($data['password'])) {
    echo json_encode(['success' => false, 'message' => 'Faltan credenciales']);
    exit;
}

require_once 'connect.php';

try {
    // Registro para depuración
    error_log("Intento de inicio de sesión para usuario: " . $data['username']);
    
    // Preparar la consulta sin is_admin
    $stmt = $pdo->prepare("SELECT id, username, password, nombre FROM usuarios WHERE username = ?");
    $stmt->execute([$data['username']]);
    $user = $stmt->fetch();
    
    // Registro del resultado de la consulta
    error_log("Resultado de la consulta: " . ($user ? "Usuario encontrado" : "Usuario no encontrado"));

    if ($user && password_verify($data['password'], $user['password'])) {
        // Credenciales correctas
        // Intentar obtener is_admin de manera segura
        try {
            $stmtAdmin = $pdo->prepare("SELECT is_admin FROM usuarios WHERE id = ?");
            $stmtAdmin->execute([$user['id']]);
            $adminData = $stmtAdmin->fetch();
            $isAdmin = isset($adminData['is_admin']) && $adminData['is_admin'] == 1;
        } catch(PDOException $e) {
            // Si la columna no existe, asumimos false por defecto
            $isAdmin = false;
        }

        $_SESSION['logged_in'] = true;
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['nombre'] = $user['nombre'];
        $_SESSION['is_admin'] = $isAdmin;
        
        echo json_encode([
            'success' => true,
            'message' => 'Login exitoso',
            'user' => [
                'username' => $user['username'],
                'nombre' => $user['nombre'],
                'is_admin' => $isAdmin
            ]
        ]);
    } else {
        // Credenciales incorrectas
        echo json_encode(['success' => false, 'message' => 'Usuario o contraseña incorrectos']);
    }
} catch(PDOException $e) {
    error_log("Error en login.php: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Error en el servidor']);
}