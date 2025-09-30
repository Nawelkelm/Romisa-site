<?php
session_start();
// Actualizar tiempo de actividad
$_SESSION['last_activity'] = time();

// Responder con éxito si la sesión es válida
echo json_encode(['status' => 'success', 'user' => $_SESSION['usuario']]);
exit();

// Si no hay sesión, responder con error
if (!isset($_SESSION['usuario'])) {
    echo json_encode(['status' => 'error', 'message' => 'No autorizado']);
    exit();
}                                

// Verificar inactividad (30 minutos)
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1800)) {
    session_unset();
    session_destroy();
    echo json_encode(['status' => 'expired', 'message' => 'Sesión expirada']);
    exit();
}

?>