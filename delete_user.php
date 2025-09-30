<?php
// Configuración de errores para debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Asegurarse de que solo se envíe JSON
header('Content-Type: application/json');

// Función para enviar respuesta JSON y terminar
function sendJsonResponse($success, $message, $error = null) {
    $response = ['success' => $success, 'message' => $message];
    if ($error !== null && !$success) {
        $response['error_details'] = $error;
    }
    echo json_encode($response);
    exit;
}

// Log de errores mejorado
function logError($message, $context = []) {
    $error_log = date('Y-m-d H:i:s') . " - " . $message;
    if (!empty($context)) {
        $error_log .= " - Context: " . json_encode($context);
    }
    error_log($error_log);
    return $error_log;
}

try {
    session_start();
    require_once 'connect.php';

    // Verificar si el usuario está autenticado
    if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
        sendJsonResponse(false, 'No autorizado');
    }

    // Obtener y decodificar los datos JSON
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    if (!isset($data['id']) || !is_numeric($data['id']) || $data['id'] <= 0) {
        sendJsonResponse(false, 'ID de usuario no válido');
    }

    // Verificar conexión a la base de datos
    if (!isset($pdo)) {
        logError("Error de conexión a la base de datos: Variable \$pdo no disponible");
        sendJsonResponse(false, 'Error de conexión a la base de datos', 'No se pudo establecer la conexión');
    }

    // Convertir ID a entero y validar
    $userId = (int)$data['id'];
    if ($userId <= 0) {
        sendJsonResponse(false, 'ID de usuario debe ser un número positivo');
    }

    // Asegurarse de que cualquier transacción previa esté cerrada
    if ($pdo->inTransaction()) {
        try {
            $pdo->rollBack();
        } catch (PDOException $e) {
            // Ignorar errores al hacer rollback de transacciones previas
        }
    }

    // Iniciar nueva transacción
    $pdo->beginTransaction();

    try {
        // Verificar que no se intente eliminar al usuario admin principal
        $stmt = $pdo->prepare("SELECT username FROM usuarios WHERE id = ?");
        if (!$stmt->execute([$data['id']])) {
            throw new PDOException("Error al ejecutar la consulta de verificación");
        }
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            $pdo->rollBack();
            sendJsonResponse(false, 'Usuario no encontrado');
        }

        if ($user['username'] === 'admin') {
            $pdo->rollBack();
            sendJsonResponse(false, 'No se puede eliminar el usuario administrador principal');
        }

        // Eliminar el usuario
        $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = ?");
        $stmt->execute([$data['id']]);
        
        if ($stmt->rowCount() > 0) {
            // Obtener el máximo ID actual
            $stmt = $pdo->query("SELECT COALESCE(MAX(id), 0) as max_id FROM usuarios");
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $next_id = intval($row['max_id']) + 1;
            


            // Resetear el auto_increment al siguiente valor
            // No usamos prepared statement aquí porque AUTO_INCREMENT requiere un valor literal
            $next_id = (int)$next_id; // Asegurar que sea un entero
            $alter_query = "ALTER TABLE usuarios AUTO_INCREMENT = $next_id";
            
            // Primero hacemos commit de la eliminación
            $pdo->commit();
            
            // Luego intentamos actualizar el auto_increment (fuera de la transacción)
            try {
                $pdo->exec($alter_query);
                logError("Proceso completado exitosamente");
                sendJsonResponse(true, 'Usuario eliminado exitosamente');
            } catch (PDOException $alterError) {
                logError("Error al actualizar auto_increment", ['error' => $alterError->getMessage()]);
                // El usuario ya fue eliminado exitosamente, así que reportamos éxito parcial
                sendJsonResponse(true, 'Usuario eliminado exitosamente, pero hubo un error al actualizar la secuencia de IDs');
            }
        } else {
            $pdo->rollBack();
            logError("No se encontró el usuario a eliminar", ['id' => $data['id']]);
            sendJsonResponse(false, 'No se encontró el usuario a eliminar');
        }
    } catch (PDOException $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        logError("Error en operación de base de datos", ['error' => $e->getMessage()]);
        sendJsonResponse(false, 'Error al realizar la operación', $e->getMessage());
    }

} catch (Exception $e) {
    $error_msg = $e->getMessage();
    $error_trace = $e->getTraceAsString();
    logError("Excepción capturada", [
        'message' => $error_msg,
        'trace' => $error_trace,
        'file' => $e->getFile(),
        'line' => $e->getLine()
    ]);
    sendJsonResponse(false, 'Error interno del servidor', $error_msg);
} catch (Error $e) {
    $error_msg = $e->getMessage();
    $error_trace = $e->getTraceAsString();
    logError("Error fatal capturado", [
        'message' => $error_msg,
        'trace' => $error_trace,
        'file' => $e->getFile(),
        'line' => $e->getLine()
    ]);
    sendJsonResponse(false, 'Error interno del servidor', $error_msg);
} finally {
    // PDO cierra la conexión automáticamente cuando se destruye el objeto
    $pdo = null;
    $stmt = null;
}