<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'No autorizado']);
    exit;
}

// Verificar que se haya enviado un archivo
if (!isset($_FILES['pdfFile']) || !isset($_POST['folder'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'No se proporcionó un archivo o carpeta']);
    exit;
}

// Configurar las carpetas permitidas
$allowed_folders = [
    'catalogos' => './assets/files/catalogos/',
    'info-tecnica' => './assets/files/info-tecnica/'
];

// Verificar que la carpeta seleccionada sea válida
if (!array_key_exists($_POST['folder'], $allowed_folders)) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Carpeta no válida']);
    exit;
}

$upload_folder = $allowed_folders[$_POST['folder']];

// Crear la carpeta si no existe
if (!file_exists($upload_folder)) {
    mkdir($upload_folder, 0777, true);
}

// Configurar el archivo
$file = $_FILES['pdfFile'];
$file_name = basename($file['name']);
$target_path = $upload_folder . $file_name;

// Verificar que sea un PDF
$file_type = strtolower(pathinfo($target_path, PATHINFO_EXTENSION));
if ($file_type != 'pdf') {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Solo se permiten archivos PDF']);
    exit;
}

// Intentar mover el archivo
if (move_uploaded_file($file['tmp_name'], $target_path)) {
    // Establecer permisos del archivo
    chmod($target_path, 0644);
    
    header('Content-Type: application/json');
    echo json_encode([
        'success' => true,
        'message' => 'Archivo subido exitosamente',
        'file' => $file_name
    ]);
} else {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'message' => 'Error al subir el archivo'
    ]);
}