<?php
session_start();
header('Content-Type: application/json');

// Verificar si el usuario está autenticado
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    echo json_encode(['success' => false, 'message' => 'No autorizado']);
    exit;
}

// Obtener y decodificar los datos JSON
$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (!isset($data['id']) || !isset($data['username']) || !isset($data['nombre'])) {
    echo json_encode(['success' => false, 'message' => 'Faltan datos requeridos']);
    exit;
}

require_once 'connect.php';

try {
    // Verificar si el nombre de usuario ya existe (excluyendo el usuario actual)
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM usuarios WHERE username = ? AND id != ?");
    $stmt->execute([$data['username'], $data['id']]);
    if ($stmt->fetchColumn() > 0) {
        echo json_encode(['success' => false, 'message' => 'El nombre de usuario ya existe']);
        exit;
    }

    if (!empty($data['password'])) {
        // Actualizar con nueva contraseña
        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE usuarios SET username = ?, password = ?, nombre = ? WHERE id = ?");
        $stmt->execute([$data['username'], $hashedPassword, $data['nombre'], $data['id']]);
    } else {
        // Actualizar sin cambiar la contraseña
        $stmt = $pdo->prepare("UPDATE usuarios SET username = ?, nombre = ? WHERE id = ?");
        $stmt->execute([$data['username'], $data['nombre'], $data['id']]);
    }
    
    echo json_encode([
        'success' => true,
        'message' => 'Usuario actualizado exitosamente'
    ]);
} catch(PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error al actualizar usuario'
    ]);
}