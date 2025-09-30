<?php
// Suprimir la salida de errores HTML
ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL);

// Configuración de la base de datos
$host = 'localhost';
$dbname = 'romisa';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
    // Verificar la conexión
    $pdo->query('SELECT 1');
} catch(PDOException $e) {
    error_log("Error de conexión a la base de datos: " . $e->getMessage());
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false, 
        'message' => 'Error de conexión a la base de datos',
        'error' => $e->getMessage()
    ]);
    exit;
}

// Si no hay un error, la conexión está establecida
return $pdo;