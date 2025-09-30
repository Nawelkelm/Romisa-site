<?php
session_start();
header('Content-Type: application/json');

// Verificar si el usuario está logueado
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    echo json_encode([
        'success' => false,
        'message' => 'No autorizado'
    ]);
    exit;
}

// Obtener el archivo a eliminar
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['filePath']) || empty($data['filePath'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Ruta de archivo no especificada'
    ]);
    exit;
}

$filePath = $data['filePath'];

// Verificar que el archivo existe y está dentro de las carpetas permitidas
$allowedPaths = [
    realpath('assets/files/catalogos'),
    realpath('assets/files/info-tecnica')
];

$realFilePath = realpath($filePath);

if (!$realFilePath) {
    echo json_encode([
        'success' => false,
        'message' => 'Archivo no encontrado'
    ]);
    exit;
}

$isAllowed = false;
foreach ($allowedPaths as $allowedPath) {
    if (strpos($realFilePath, $allowedPath) === 0) {
        $isAllowed = true;
        break;
    }
}

if (!$isAllowed) {
    echo json_encode([
        'success' => false,
        'message' => 'No está permitido eliminar archivos fuera de las carpetas designadas'
    ]);
    exit;
}

// Intentar eliminar el archivo
try {
    if (!file_exists($filePath)) {
        throw new Exception('El archivo no existe');
    }
    
    if (!unlink($filePath)) {
        throw new Exception('No se pudo eliminar el archivo');
    }
    
    echo json_encode([
        'success' => true,
        'message' => 'Archivo eliminado correctamente'
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error al eliminar el archivo: ' . $e->getMessage()
    ]);
}
?>