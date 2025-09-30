<?php
// Suprimir la salida de errores HTML
ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL);

session_start();
header('Content-Type: application/json');

echo json_encode([
    'logged_in' => isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true,
    'user' => isset($_SESSION['username']) ? [
        'username' => $_SESSION['username'],
        'nombre' => $_SESSION['nombre']
    ] : null
]);