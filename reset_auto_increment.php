<?php
require_once 'connect.php';

function resetAutoIncrement() {
    global $conn;
    try {
        // Obtener el máximo ID actual
        $sql = "SELECT MAX(id) as max_id FROM usuarios";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $next_id = ($row['max_id'] ?? 0) + 1;

        // Resetear el auto_increment al siguiente valor
        $sql = "ALTER TABLE usuarios AUTO_INCREMENT = " . $next_id;
        $conn->query($sql);
        
        return true;
    } catch (Exception $e) {
        return false;
    }
}
?>