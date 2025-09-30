<?php
// Configuración de la base de datos
$host = 'localhost';
$dbname = 'romisa';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Crear la base de datos si no existe
    $sql = "CREATE DATABASE IF NOT EXISTS $dbname";
    $pdo->exec($sql);
    
    // Seleccionar la base de datos
    $pdo->exec("USE $dbname");

    // Crear la tabla de usuarios si no existe
    $sql = "CREATE TABLE IF NOT EXISTS usuarios (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        nombre VARCHAR(100) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    
    $pdo->exec($sql);

    // Verificar si ya existe un usuario administrador
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM usuarios WHERE username = ?");
    $stmt->execute(['admin']);
    $count = $stmt->fetchColumn();

    if ($count == 0) {
        // Crear usuario administrador por defecto si no existe
        $defaultPassword = password_hash('RomisaAdmin2023', PASSWORD_DEFAULT);
        $sql = "INSERT INTO usuarios (username, password, nombre) VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['admin', $defaultPassword, 'Administrador']);
        
        echo "Base de datos y usuario administrador creados exitosamente.\n";
        echo "Usuario: admin\n";
        echo "Contraseña: RomisaAdmin2023\n";
    } else {
        echo "La base de datos y las tablas ya están configuradas.\n";
    }

} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>