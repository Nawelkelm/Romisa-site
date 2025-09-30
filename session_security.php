<?php
// Iniciar o reanudar la sesión
session_start();

// Verificar si la sesión ha expirado (30 minutos)
$session_timeout = 1800; // 30 minutos en segundos
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $session_timeout)) {
    session_unset();
    session_destroy();
    die(json_encode(['success' => false, 'message' => 'Sesión expirada', 'expired' => true]));
}

// Actualizar tiempo de última actividad
$_SESSION['last_activity'] = time();

// Verificar si la IP ha cambiado (posible session hijacking)
if (isset($_SESSION['ip_address'])) {
    if ($_SESSION['ip_address'] !== $_SERVER['REMOTE_ADDR']) {
        session_unset();
        session_destroy();
        die(json_encode(['success' => false, 'message' => 'Sesión inválida', 'invalid' => true]));
    }
} else {
    $_SESSION['ip_address'] = $_SERVER['REMOTE_ADDR'];
}

// Regenerar ID de sesión periódicamente
if (!isset($_SESSION['last_regeneration']) || (time() - $_SESSION['last_regeneration'] > 300)) {
    session_regenerate_id(true);
    $_SESSION['last_regeneration'] = time();
}

// Verificar si el usuario está autenticado
function checkAuth() {
    if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
        http_response_code(401);
        die(json_encode(['success' => false, 'message' => 'No autorizado', 'auth_required' => true]));
    }
    return true;
}

// Verificar si el usuario es administrador
function checkAdmin() {
    checkAuth();
    if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
        http_response_code(403);
        die(json_encode(['success' => false, 'message' => 'Acceso denegado', 'admin_required' => true]));
    }
    return true;
}