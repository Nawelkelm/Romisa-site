<?php
session_start();

// Asegurar que los errores no interfieran con la respuesta JSON
error_reporting(0);
header('Content-Type: application/json');

try {
    // Limpiar y destruir la sesión
    $_SESSION = array();
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time()-3600, '/');
    }
    session_destroy();
    
    // Devolver respuesta de éxito
    echo json_encode(array(
        'success' => true,
        'message' => 'Sesión cerrada correctamente'
    ));
} catch (Exception $e) {
    // Devolver respuesta de error
    http_response_code(500);
    echo json_encode(array(
        'success' => false,
        'message' => 'Error al cerrar la sesión: ' . $e->getMessage()
    ));
}
?>
