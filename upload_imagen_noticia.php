<?php
// Script para subir imágenes de noticias
error_reporting(E_ERROR | E_PARSE);
ini_set('display_errors', 0);

session_start();
header('Content-Type: application/json');

// Verificar que el usuario esté autenticado
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    echo json_encode(['success' => false, 'message' => 'No autorizado. Debe iniciar sesión.']);
    exit;
}

// Verificar que se haya enviado un archivo
if (!isset($_FILES['imagen']) || $_FILES['imagen']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['success' => false, 'message' => 'No se recibió ninguna imagen']);
    exit;
}

$archivo = $_FILES['imagen'];

// Validaciones de seguridad
$extensionesPermitidas = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
$tamanoMaximo = 5 * 1024 * 1024; // 5 MB

// Obtener extensión del archivo
$nombreArchivo = $archivo['name'];
$extension = strtolower(pathinfo($nombreArchivo, PATHINFO_EXTENSION));

// Validar extensión
if (!in_array($extension, $extensionesPermitidas)) {
    echo json_encode([
        'success' => false, 
        'message' => 'Formato no permitido. Solo se aceptan: ' . implode(', ', $extensionesPermitidas)
    ]);
    exit;
}

// Validar tamaño
if ($archivo['size'] > $tamanoMaximo) {
    $tamanoMB = round($tamanoMaximo / (1024 * 1024), 2);
    echo json_encode([
        'success' => false, 
        'message' => "La imagen es demasiado grande. Tamaño máximo: {$tamanoMB} MB"
    ]);
    exit;
}

// Validar que sea realmente una imagen
$infoImagen = getimagesize($archivo['tmp_name']);
if ($infoImagen === false) {
    echo json_encode([
        'success' => false, 
        'message' => 'El archivo no es una imagen válida'
    ]);
    exit;
}

// Validar tipo MIME
$tiposMimePermitidos = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
if (!in_array($infoImagen['mime'], $tiposMimePermitidos)) {
    echo json_encode([
        'success' => false, 
        'message' => 'Tipo de imagen no permitido'
    ]);
    exit;
}

// Crear directorio si no existe
$directorioDestino = __DIR__ . '/assets/img/novedades/';
if (!is_dir($directorioDestino)) {
    mkdir($directorioDestino, 0755, true);
}

// Generar nombre único para evitar sobrescribir archivos
$nombreUnico = uniqid('noticia_', true) . '.' . $extension;
$rutaDestino = $directorioDestino . $nombreUnico;

// Mover archivo
if (move_uploaded_file($archivo['tmp_name'], $rutaDestino)) {
    // Devolver la ruta relativa para guardar en la base de datos
    $rutaRelativa = 'assets/img/novedades/' . $nombreUnico;
    
    echo json_encode([
        'success' => true,
        'message' => 'Imagen subida exitosamente',
        'ruta' => $rutaRelativa,
        'nombre' => $nombreUnico
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Error al guardar la imagen en el servidor'
    ]);
}
