<?php
session_start();
header('Content-Type: application/json');

// Verificar si el usuario está logueado y es administrador
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in'] || !isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    echo json_encode([
        'success' => false,
        'message' => 'Acceso denegado. Se requieren privilegios de administrador.'
    ]);
    exit;
}

echo json_encode([
    'success' => true,
    'message' => 'Sesión de administrador validada'
]);
?>