<?php
// Suprimir warnings para asegurar JSON puro
error_reporting(0);
ini_set('display_errors', 0);

session_start();
require_once 'csrf_protection.php';

header('Content-Type: application/json');

// Generar o retornar el token CSRF existente
$token = generateCSRFToken();

echo json_encode([
    'success' => true,
    'csrf_token' => $token
]);
