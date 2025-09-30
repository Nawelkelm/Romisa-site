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

// Obtener los PDFs de ambas carpetas
$pdfs = [];

function scanDirectory($dir) {
    $files = [];
    if (is_dir($dir)) {
        $scan = scandir($dir);
        foreach ($scan as $file) {
            if ($file !== '.' && $file !== '..' && pathinfo($file, PATHINFO_EXTENSION) === 'pdf') {
                $files[] = [
                    'name' => $file,
                    'path' => $dir . '/' . $file,
                    'folder' => basename($dir),
                    'size' => filesize($dir . '/' . $file),
                    'modified' => date("Y-m-d H:i:s", filemtime($dir . '/' . $file))
                ];
            }
        }
    }
    return $files;
}

try {
    $catalogos = scanDirectory('assets/files/catalogos');
    $infoTecnica = scanDirectory('assets/files/info-tecnica');
    
    $pdfs = array_merge($catalogos, $infoTecnica);

    echo json_encode([
        'success' => true,
        'files' => $pdfs
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error al obtener los archivos: ' . $e->getMessage()
    ]);
}
?>