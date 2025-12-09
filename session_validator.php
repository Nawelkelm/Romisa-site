<?php
/**
 * Validador de Sesión ROMISA
 * Usa el middleware de autenticación centralizado
 */

header('Content-Type: application/json; charset=utf-8');

require_once 'auth_middleware.php';

iniciarSesionSegura();

// Verificar si hay sesión activa
if (!estaAutenticado()) {
    // Determinar si expiró o nunca existió
    if (isset($_SESSION['logged_in'])) {
        echo json_encode([
            'status' => 'expired', 
            'message' => 'Sesión expirada por inactividad'
        ]);
    } else {
        echo json_encode([
            'status' => 'error', 
            'message' => 'No autorizado'
        ]);
    }
    exit();
}

// Obtener información del usuario
$usuario = obtenerUsuarioActual();

// Responder con éxito
echo json_encode([
    'status' => 'success', 
    'user' => $usuario['username'],
    'rol' => $usuario['rol'],
    'es_admin' => esAdmin(),
    'es_editor' => esEditor()
]);
exit();
?>