<?php
/**
 * API para gestión de noticias ROMISA
 * Con sistema de seguridad mejorado
 */

// Suprimir warnings para respuestas JSON limpias
error_reporting(E_ERROR | E_PARSE);
ini_set('display_errors', 0);

header('Content-Type: application/json; charset=utf-8');

// Incluir middleware de autenticación
require_once 'auth_middleware.php';

// Conexión a base de datos
$host = 'localhost';
$dbname = 'romisa';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    echo json_encode([
        'success' => false, 
        'message' => 'Error de conexión a la base de datos'
    ]);
    exit;
}

// Función para sanitizar entradas
function sanitizar($input) {
    return htmlspecialchars(strip_tags(trim($input)), ENT_QUOTES, 'UTF-8');
}

// Función mejorada para validar sesión de administrador/editor
function validarPermisoEscritura() {
    iniciarSesionSegura();
    
    if (!estaAutenticado()) {
        http_response_code(401);
        echo json_encode([
            'success' => false, 
            'message' => 'Sesión no válida. Por favor, inicie sesión nuevamente.',
            'code' => 'SESSION_EXPIRED'
        ]);
        exit();
    }
    
    if (!esEditor()) {
        http_response_code(403);
        echo json_encode([
            'success' => false, 
            'message' => 'No tiene permisos para realizar esta acción.',
            'code' => 'FORBIDDEN'
        ]);
        exit();
    }
}

// Función para validar sesión de administrador (eliminar permanente)
function validarPermisoAdmin() {
    iniciarSesionSegura();
    
    if (!estaAutenticado()) {
        http_response_code(401);
        echo json_encode([
            'success' => false, 
            'message' => 'Sesión no válida. Por favor, inicie sesión nuevamente.',
            'code' => 'SESSION_EXPIRED'
        ]);
        exit();
    }
    
    if (!esAdmin()) {
        http_response_code(403);
        echo json_encode([
            'success' => false, 
            'message' => 'Se requieren permisos de administrador para esta acción.',
            'code' => 'ADMIN_REQUIRED'
        ]);
        exit();
    }
}

$action = $_GET['action'] ?? $_POST['action'] ?? '';

try {
    switch ($action) {
        
        // OBTENER TODAS LAS NOTICIAS (público)
        case 'listar':
            $limite = isset($_GET['limite']) ? (int)$_GET['limite'] : null;
            $activas_solo = isset($_GET['activas']) && $_GET['activas'] === 'true';
            
            $sql = "SELECT id, titulo, resumen, imagen, imagen_galeria_1, imagen_galeria_2, imagen_galeria_3, 
                    fecha_publicacion, autor, activo, vistas 
                    FROM noticias";
            
            if ($activas_solo) {
                $sql .= " WHERE activo = 1";
            }
            
            $sql .= " ORDER BY fecha_publicacion DESC";
            
            if ($limite) {
                $sql .= " LIMIT :limite";
            }
            
            $stmt = $pdo->prepare($sql);
            
            if ($limite) {
                $stmt->bindParam(':limite', $limite, PDO::PARAM_INT);
            }
            
            $stmt->execute();
            $noticias = $stmt->fetchAll();
            
            echo json_encode([
                'success' => true,
                'noticias' => $noticias,
                'total' => count($noticias)
            ]);
            break;
        
        // OBTENER UNA NOTICIA POR ID (público)
        case 'obtener':
            $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
            
            if ($id <= 0) {
                throw new Exception('ID de noticia inválido');
            }
            
            // Incrementar contador de vistas
            $stmt = $pdo->prepare("UPDATE noticias SET vistas = vistas + 1 WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
            // Obtener la noticia (verificar si es admin para ver inactivas)
            iniciarSesionSegura();
            if (esEditor()) {
                // Editores pueden ver todas las noticias
                $stmt = $pdo->prepare("SELECT * FROM noticias WHERE id = :id");
            } else {
                // Público solo ve activas
                $stmt = $pdo->prepare("SELECT * FROM noticias WHERE id = :id AND activo = 1");
            }
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $noticia = $stmt->fetch();
            
            if (!$noticia) {
                throw new Exception('Noticia no encontrada');
            }
            
            // Agregar array de galería
            $noticia['galeria'] = array_filter([
                $noticia['imagen_galeria_1'],
                $noticia['imagen_galeria_2'],
                $noticia['imagen_galeria_3']
            ]);
            
            echo json_encode([
                'success' => true,
                'noticia' => $noticia
            ]);
            break;
        
        // CREAR NUEVA NOTICIA (requiere autenticación de editor)
        case 'crear':
            validarPermisoEscritura();
            
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Método no permitido');
            }
            
            $titulo = sanitizar($_POST['titulo'] ?? '');
            $resumen = sanitizar($_POST['resumen'] ?? '');
            $contenido = $_POST['contenido'] ?? ''; // Permitir HTML
            $usuario = obtenerUsuarioActual();
            $autor = sanitizar($_POST['autor'] ?? ($usuario['username'] ?? 'ROMISA'));
            $imagen = sanitizar($_POST['imagen'] ?? '');
            $imagen_galeria_1 = sanitizar($_POST['imagen_galeria_1'] ?? '');
            $imagen_galeria_2 = sanitizar($_POST['imagen_galeria_2'] ?? '');
            $imagen_galeria_3 = sanitizar($_POST['imagen_galeria_3'] ?? '');
            
            if (empty($titulo) || empty($resumen) || empty($contenido)) {
                throw new Exception('Título, resumen y contenido son obligatorios');
            }
            
            $stmt = $pdo->prepare("INSERT INTO noticias (titulo, resumen, contenido, imagen, imagen_galeria_1, imagen_galeria_2, imagen_galeria_3, autor) 
                                   VALUES (:titulo, :resumen, :contenido, :imagen, :img_gal_1, :img_gal_2, :img_gal_3, :autor)");
            
            $stmt->bindParam(':titulo', $titulo);
            $stmt->bindParam(':resumen', $resumen);
            $stmt->bindParam(':contenido', $contenido);
            $stmt->bindParam(':imagen', $imagen);
            $stmt->bindParam(':img_gal_1', $imagen_galeria_1);
            $stmt->bindParam(':img_gal_2', $imagen_galeria_2);
            $stmt->bindParam(':img_gal_3', $imagen_galeria_3);
            $stmt->bindParam(':autor', $autor);
            
            if ($stmt->execute()) {
                $noticiaId = $pdo->lastInsertId();
                
                // Registrar acción en log de auditoría
                registrarAccion('crear', 'noticia', $noticiaId, "Título: $titulo");
                
                echo json_encode([
                    'success' => true,
                    'message' => 'Noticia creada exitosamente',
                    'id' => $noticiaId
                ]);
            } else {
                throw new Exception('Error al crear la noticia');
            }
            break;
        
        // ACTUALIZAR NOTICIA (requiere autenticación de editor)
        case 'actualizar':
            validarPermisoEscritura();
            
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Método no permitido');
            }
            
            $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
            $titulo = sanitizar($_POST['titulo'] ?? '');
            $resumen = sanitizar($_POST['resumen'] ?? '');
            $contenido = $_POST['contenido'] ?? '';
            $imagen = sanitizar($_POST['imagen'] ?? '');
            $imagen_galeria_1 = sanitizar($_POST['imagen_galeria_1'] ?? '');
            $imagen_galeria_2 = sanitizar($_POST['imagen_galeria_2'] ?? '');
            $imagen_galeria_3 = sanitizar($_POST['imagen_galeria_3'] ?? '');
            
            if ($id <= 0) {
                throw new Exception('ID inválido');
            }
            
            if (empty($titulo) || empty($resumen) || empty($contenido)) {
                throw new Exception('Título, resumen y contenido son obligatorios');
            }
            
            $stmt = $pdo->prepare("UPDATE noticias 
                                   SET titulo = :titulo, resumen = :resumen, 
                                       contenido = :contenido, imagen = :imagen,
                                       imagen_galeria_1 = :img_gal_1,
                                       imagen_galeria_2 = :img_gal_2,
                                       imagen_galeria_3 = :img_gal_3
                                   WHERE id = :id");
            
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':titulo', $titulo);
            $stmt->bindParam(':resumen', $resumen);
            $stmt->bindParam(':contenido', $contenido);
            $stmt->bindParam(':imagen', $imagen);
            $stmt->bindParam(':img_gal_1', $imagen_galeria_1);
            $stmt->bindParam(':img_gal_2', $imagen_galeria_2);
            $stmt->bindParam(':img_gal_3', $imagen_galeria_3);
            
            if ($stmt->execute()) {
                // Registrar acción
                registrarAccion('actualizar', 'noticia', $id, "Título: $titulo");
                
                echo json_encode([
                    'success' => true,
                    'message' => 'Noticia actualizada exitosamente'
                ]);
            } else {
                throw new Exception('Error al actualizar la noticia');
            }
            break;
        
        // ELIMINAR NOTICIA - soft delete (requiere autenticación de editor)
        case 'eliminar':
            validarPermisoEscritura();
            
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Método no permitido');
            }
            
            $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
            
            if ($id <= 0) {
                throw new Exception('ID inválido');
            }
            
            // Obtener título para el log
            $stmt = $pdo->prepare("SELECT titulo FROM noticias WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $noticia = $stmt->fetch();
            
            $stmt = $pdo->prepare("UPDATE noticias SET activo = 0 WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            
            if ($stmt->execute()) {
                // Registrar acción
                registrarAccion('eliminar', 'noticia', $id, "Título: " . ($noticia['titulo'] ?? 'Desconocido'));
                
                echo json_encode([
                    'success' => true,
                    'message' => 'Noticia eliminada exitosamente'
                ]);
            } else {
                throw new Exception('Error al eliminar la noticia');
            }
            break;
        
        // ELIMINAR PERMANENTEMENTE - hard delete (SOLO ADMIN)
        case 'eliminar_permanente':
            validarPermisoAdmin(); // Solo admin puede eliminar permanentemente
            
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Método no permitido');
            }
            
            $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
            
            if ($id <= 0) {
                throw new Exception('ID inválido');
            }
            
            // Obtener información para el log y eliminar imagen
            $stmt = $pdo->prepare("SELECT titulo, imagen, imagen_galeria_1, imagen_galeria_2, imagen_galeria_3 FROM noticias WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $noticia = $stmt->fetch();
            
            if (!$noticia) {
                throw new Exception('Noticia no encontrada');
            }
            
            // Eliminar la noticia
            $stmt = $pdo->prepare("DELETE FROM noticias WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            
            if ($stmt->execute()) {
                // Intentar eliminar imágenes del servidor
                $imagenes = [$noticia['imagen'], $noticia['imagen_galeria_1'], $noticia['imagen_galeria_2'], $noticia['imagen_galeria_3']];
                foreach ($imagenes as $img) {
                    if (!empty($img)) {
                        $rutaImagen = __DIR__ . '/' . $img;
                        if (file_exists($rutaImagen)) {
                            @unlink($rutaImagen);
                        }
                    }
                }
                
                // Registrar acción
                registrarAccion('eliminar_permanente', 'noticia', $id, "Título: " . $noticia['titulo']);
                
                echo json_encode([
                    'success' => true,
                    'message' => 'Noticia eliminada permanentemente'
                ]);
            } else {
                throw new Exception('Error al eliminar la noticia permanentemente');
            }
            break;
        
        // ACTIVAR/DESACTIVAR NOTICIA (requiere autenticación de editor)
        case 'toggle':
            validarPermisoEscritura();
            
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Método no permitido');
            }
            
            $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
            $estado = isset($_POST['activo']) ? (int)$_POST['activo'] : 0;
            
            if ($id <= 0) {
                throw new Exception('ID inválido');
            }
            
            $stmt = $pdo->prepare("UPDATE noticias SET activo = :estado WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':estado', $estado, PDO::PARAM_INT);
            
            if ($stmt->execute()) {
                $accion = $estado == 1 ? 'activar' : 'desactivar';
                $mensaje = $estado == 1 ? 'Noticia activada exitosamente' : 'Noticia desactivada exitosamente';
                
                // Registrar acción
                registrarAccion($accion, 'noticia', $id, null);
                
                echo json_encode([
                    'success' => true,
                    'message' => $mensaje
                ]);
            } else {
                throw new Exception('Error al actualizar estado');
            }
            break;
        
        // VERIFICAR ESTADO DE SESIÓN (para el frontend)
        case 'check_session':
            iniciarSesionSegura();
            
            if (estaAutenticado()) {
                $usuario = obtenerUsuarioActual();
                echo json_encode([
                    'success' => true,
                    'authenticated' => true,
                    'user' => $usuario['username'],
                    'rol' => $usuario['rol'],
                    'es_editor' => esEditor(),
                    'es_admin' => esAdmin()
                ]);
            } else {
                echo json_encode([
                    'success' => true,
                    'authenticated' => false
                ]);
            }
            break;
        
        default:
            throw new Exception('Acción no válida');
    }
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error en la base de datos'
    ]);
    // Log del error real
    error_log("API Noticias - Error DB: " . $e->getMessage());
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>
