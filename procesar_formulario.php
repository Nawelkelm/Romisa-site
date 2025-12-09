<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require('assets/tfpdf/tfpdf.php');
require('assets/tfpdf/font/unifont/ttfonts.php');


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recuperar datos del formulario
    $razon_social = $_POST['razon_social'] ?? '';
    $doc_tipo = $_POST['doc_tipo'] ?? '';
    $cuit = $_POST['cuit'] ?? '';
    $tipo_cliente = $_POST['tipo_cliente'] ?? '';
    $tipo_cliente_legible = ($tipo_cliente === 'nuevo') ? 'CLIENTE NUEVO' : (($tipo_cliente === 'registrado') ? 'CLIENTE REGISTRADO' : 'NO ESPECIFICADO');
    $nombre_comercial = $_POST['nombre_comercial'] ?? '';
    $direccion_comercial = $_POST['direccion_comercial'] ?? '';
    $localidad_comercial = $_POST['localidad_comercial'] ?? '';
    $provincia_comercial = $_POST['provincia_comercial'] ?? '';
    $direccion_entrega = $_POST['direccion_entrega'] ?? '';
    $localidad_entrega = $_POST['localidad_entrega'] ?? '';
    $provincia_entrega = $_POST['provincia_entrega'] ?? '';
    $codigo_postal_entrega = $_POST['codigo_postal_entrega'] ?? '';
    $codigo_postal_comercial = $_POST['codigo_postal_comercial'] ?? '';
    $correo = $_POST['correo'] ?? '';
    $telefono = $_POST['telefono'] ?? '';
    $persona_solicitante = $_POST['persona_solicitante'] ?? '';
    $otro_rubro = $_POST['otro_rubro'] ?? '';

    // Verificar si se capturaron los datos del tipo de documento
    if (empty($doc_tipo)) {
        echo "Error: No se ha seleccionado un tipo de documento.";
        exit;
    }


    
    // Inicializar PDF en A4 con orientación vertical
    $pdf = new tFPDF('P', 'mm', 'A4');
    $pdf->AddPage();
    
    // Agregar fuentes con soporte UTF-8
    $pdf->AddFont('DejaVu', '', 'DejaVuSansCondensed.ttf', true);
    $pdf->AddFont('DejaVu', 'B', 'DejaVuSansCondensed-Bold.ttf', true);
    
    // Configuración inicial: márgenes reducidos y fuente pequeña
    $pdf->SetMargins(20, 20, 20);
    $pdf->SetFont('DejaVu', '', 8);  // Fuente más pequeña
    
    // Agregar fecha en la esquina superior derecha
    $fecha_actual = date('d/m/Y');
    $pdf->SetXY(-50, 10);
    $pdf->Cell(40, 6, 'Fecha: ' . $fecha_actual, 0, 0, 'R');
    
    // Agregar logo
    $pdf->Image('assets/img/romisa-logo-original.png', 10, 10, 40, 0, 'PNG');
    $pdf->Ln(15); // Espaciado reducido después del logo
    
    // Título principal centrado con borde
    $pdf->SetFont('DejaVu', 'B', 10);
    $pdf->Cell(0, 8, 'Formulario Alta de Clientes', 1, 1, 'C');
    $pdf->Ln(4);  // Espaciado mínimo antes del cuadro
    
    // ====================
    // 1. Datos del Cliente
    // ====================
    $pdf->SetFont('DejaVu', 'B', 9);
    $pdf->Cell(0, 6, '1. DATOS DEL CLIENTE', 1, 1, 'C');
    $pdf->Ln(1);
    
    $pdf->SetFont('DejaVu', '', 8);
    $pdf->Cell(50, 5, 'Tipo de Cliente', 1, 0, 'L');
    $pdf->SetFont('DejaVu', 'B', 8);
    $pdf->Cell(0, 5, $tipo_cliente_legible, 1, 1, 'L');
    $pdf->SetFont('DejaVu', '', 8);

    $datos_cliente = [
        'Razón Social' => $razon_social,
        'Documento' => $doc_tipo,
        'Nro' => $cuit,
        'Nombre Comercial' => $nombre_comercial,
        'Dirección Comercial' => "$direccion_comercial, $localidad_comercial, $provincia_comercial, $codigo_postal_comercial",
        'Dirección de Entrega' => "$direccion_entrega, $localidad_entrega, $provincia_entrega, $codigo_postal_entrega",
        'Correo' => $correo,
        'Teléfono' => $telefono,
        'Persona Solicitante' => $persona_solicitante
    ];
    
    foreach ($datos_cliente as $key => $value) {
        $pdf->Cell(50, 5, $key, 1, 0, 'L');
        $pdf->Cell(0, 5, $value, 1, 1, 'L');
    }
    $pdf->Ln(3); // Espaciado mínimo entre secciones
    
    // ====================
    // 2. Información General
    // ====================
    $pdf->SetFont('DejaVu', 'B', 9);
    $pdf->Cell(0, 6, '2. INFORMACIÓN GENERAL', 1, 1, 'C');
    $pdf->Ln(1);
    
    $pdf->SetFont('DejaVu', '', 8);
    $pdf->Cell(50, 5, 'Actividad a la que se dedica:', 1, 0, 'L');
    $pdf->Cell(0, 5, $otro_rubro, 1, 1, 'L');
    $pdf->Ln(3); 
    
    // ====================
    // 3. Datos Internos
    // ====================
    $pdf->SetFont('DejaVu', 'B', 9);
    $pdf->Cell(0, 6, '3. DATOS INTERNOS', 1, 1, 'C');
    $pdf->Ln(1);
    
    $pdf->SetFont('DejaVu', '', 8);
    $datos_internos = [
        'Código de Cliente' => '',
        'Código de Zona' => '',
        'Código de Rubro' => '',
        'Código de vendedor' => '',
        'Descuento' => ''
        
    ];
    
    foreach ($datos_internos as $key => $value) {
        $pdf->Cell(50, 5, $key, 1, 0, 'L');
        $pdf->Cell(0, 5, $value, 1, 1, 'L');
    }
    $pdf->Ln(3);
    
    // Guardar PDF en archivo temporal
    $temp_pdf = tempnam(sys_get_temp_dir(), 'formulario_clientes') . '.pdf';
    $pdf->Output('F', $temp_pdf);
    
    

    // Preparar el correo electrónico
    $to = "at.clientes@romisa.com.ar, ventas@romisa.com.ar, marketing@romisa.com.ar"; // Reemplaza con la dirección de correo del destinatario
    $subject = "Nuevo Formulario de Alta de Cliente";

    // Generar un separador único para el correo multipart
    $mime_boundary = "==Multipart_Boundary_x" . md5(time()) . "x";

    // Encabezados del correo
    $headers = "From: marketing@romisa.com.ar\r\n"; // Reemplaza con tu dirección de correo
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: multipart/mixed; boundary=\"" . $mime_boundary . "\"\r\n";

    // Cuerpo del mensaje
    $message = "--" . $mime_boundary . "\r\n";
    $message .= "Content-Type: text/html; charset=\"UTF-8\"\r\n";
    $message .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
    $message .= "<html><body>";
    $message .= "<h1>Nuevo Formulario de Alta de Cliente</h1>";
    $message .= "<p><strong>Tipo de Cliente:</strong> <strong>" . strtoupper($tipo_cliente_legible) . "</strong></p>";
    $message .= "<p><strong>Razón Social:</strong> " . $razon_social . "</p>";
    $message .= "<p><strong>$doc_tipo:</strong> " . $cuit . "</p>";
    $message .= "<p><strong>Nombre Comercial:</strong> $nombre_comercial</p>";
    $message .= "<p><strong>Dirección Comercial:</strong> $direccion_comercial, $localidad_comercial, $provincia_comercial, $codigo_postal_comercial</p>";
    $message .= "<p><strong>Dirección de Entrega:</strong> $direccion_entrega, $localidad_entrega, $provincia_entrega,$codigo_postal_entrega</p>";
    $message .= "<p><strong>Correo:</strong> $correo</p>";
    $message .= "<p><strong>Teléfono:</strong> $telefono</p>";
    $message .= "<p><strong>Persona Solicitante:</strong> $persona_solicitante</p>";
    $message .= "<p><strong>Ocupación a la que se dedica:</strong> $otro_rubro</p>";
    $message .= "</ul>";
    $message .= "<p>Se adjunta el PDF con todos los detalles del formulario.</p>";
    $message .= "</body></html>\r\n";

    // Adjuntar el PDF
    $file = file_get_contents($temp_pdf);
    $message .= "--" . $mime_boundary . "\r\n";
    $message .= "Content-Type: application/pdf; name=\"formulario_cliente.pdf\"\r\n";
    $message .= "Content-Transfer-Encoding: base64\r\n";
    $message .= "Content-Disposition: attachment; filename=\"formulario_cliente.pdf\"\r\n\r\n";
    $message .= chunk_split(base64_encode($file));
    $message .= "--" . $mime_boundary . "--";

    // Enviar el correo
    $mail_sent = mail($to, $subject, $message, $headers);

    // Eliminar el archivo temporal
    unlink($temp_pdf);

    if ($mail_sent) {
        header('Location: recibidoac.html');
    } else {
        echo "Hubo un problema al enviar el correo electrónico.";
    }
} else {
    echo "Acceso no autorizado";
}
?>