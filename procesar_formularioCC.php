<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require('assets/tfpdf/tfpdf.php');
require('assets/tfpdf/font/unifont/ttfonts.php');
require('config.php'); // Archivo externo seguro con claves y configuraciones

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validación de reCAPTCHA v3
    $recaptchaResponse = $_POST['g-recaptcha-response'] ?? '';
    if (empty($recaptchaResponse)) {
        echo "Por favor, completa el reCAPTCHA.";
        exit;
    }

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://www.google.com/recaptcha/api/siteverify');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, [
        'secret' => RECAPTCHA_SECRET,
        'response' => $recaptchaResponse,
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    $responseKeys = json_decode($response, true);

    if (!$responseKeys['success'] || $responseKeys['score'] < 0.5) {
        echo "Verificación de reCAPTCHA fallida. Intenta nuevamente.";
        exit;
    }

    // Validación de correo
    if (!filter_var($_POST['correo'], FILTER_VALIDATE_EMAIL)) {
        echo "Correo electrónico inválido.";
        exit;

    }
    // Campos del formulario
    $tipo_cliente = $_POST['tipo_cliente'] ?? '';
    $razon_social = $_POST['razon_social'] ?? '';
    $cuit = $_POST['cuit'] ?? '';
    $nombre_comercial = $_POST['nombre_comercial'] ?? '';
    $direccion_comercial = $_POST['direccion_comercial'] ?? '';
    $localidad_comercial = $_POST['localidad_cliente'] ?? '';
    $provincia_comercial = $_POST['provincia_cliente'] ?? '';
    $direccion_entrega = $_POST['direccion_entrega'] ?? '';
    $localidad_entrega = $_POST['localidad_entrega'] ?? '';
    $provincia_entrega = $_POST['provincia_entrega'] ?? '';
    $codigo_postal_entrega = $_POST['codigo_postal_entrega'] ?? '';
    $codigo_postal_comercial = $_POST['codigo_postal_comercial'] ?? '';
    $actividad = $_POST['actividad'] ?? '';
    $fecha_inicio_actividad = $_POST['fecha_inicio_actividad'] ?? '';
    $correo = $_POST['correo'] ?? '';
    $telefono = $_POST['telefono'] ?? '';
    $persona_solicitante = $_POST['persona_solicitante'] ?? '';
    $transporte_seleccionado = $_POST['transporte_seleccionado'] ?? '';
    $encargado_compras = $_POST['encargado_compras'] ?? '';
    $correo_encargado_compras = $_POST['correo_encargado_compras'] ?? '';
    $encargado_pagos = $_POST['encargado_pagos'] ?? '';
    $correo_encargado_pagos = $_POST['correo_encargado_pagos'] ?? '';
    $empresa_referencia = $_POST['empresa_referencia'] ?? '';
    $telefono_referencia = $_POST['telefono_referencia'] ?? '';
    $forma_pago_referencia = "Cuenta Corriente";
    $empresa_referencia2 = $_POST['empresa_referencia2'] ?? '';
    $telefono_referencia2 = $_POST['telefono_referencia2'] ?? '';
    $forma_pago_referencia2 = "Cuenta Corriente";
    $empresa_referencia3 = $_POST['empresa_referencia3'] ?? '';
    $telefono_referencia3 = $_POST['telefono_referencia3'] ?? '';
    $forma_pago_referencia3 = "Cuenta Corriente"; 
    $credito_solicitado = $_POST['credito_solicitado'] ?? '';

// Inicializar PDF
$pdf = new tFPDF();
$pdf->AddPage();

// Agregar fuente con soporte UTF-8
$pdf->AddFont('DejaVu', '', 'DejaVuSansCondensed.ttf', true);
$pdf->AddFont('DejaVu', 'B', 'DejaVuSansCondensed-Bold.ttf', true);

// Configuración inicial
$pdf->SetMargins(10, 10, 10);
$pdf->SetFont('DejaVu', '', 10);
// Obtener la fecha actual
$fecha_actual = date('d/m/Y');

// Agregar logo
$pdf->Image('assets/img/romisa-logo-original.png', 10, 10, 50, 0, 'PNG');
$pdf->Ln(15);

// Agregar la fecha en la esquina superior derecha
$pdf->SetFont('DejaVu', '', 10);
$pdf->SetXY(-60, 10); // Ajustar posición para que esté alineada a la derecha
$pdf->Cell(50, 6, 'Fecha: ' . $fecha_actual, 0, 0, 'R');

$pdf->Ln(15); // Espaciado después del logo

// Título principal
$pdf->SetFont('DejaVu', 'B', 12);
$pdf->Cell(0, 8, 'Formulario Apertura/Actualización de Cuenta Corriente', 1, 1, 'C');
$pdf->Ln(8);

// ====================
// 1. Datos del Cliente
// ====================
$pdf->SetFont('DejaVu', 'B', 10);
$pdf->Cell(0, 6, '1. Datos del Cliente', 1, 1, 'C');
$pdf->Ln(2);

$pdf->SetFont('DejaVu', '', 10);
$tipo_cliente_texto = ($tipo_cliente == 'nuevo') ? 'Cliente Nuevo' : 'Cliente Registrado';
$datos_cliente = [
    'Tipo de Cliente' => $tipo_cliente_texto,
    'Razón Social' => $razon_social,
    'CUIT' => $cuit,
    'Nombre Comercial' => $nombre_comercial,
    'Dirección Comercial' => "$direccion_comercial, $localidad_comercial, $provincia_comercial, $codigo_postal_comercial",
    'Dirección de Entrega' => "$direccion_entrega, $localidad_entrega, $provincia_entrega, $codigo_postal_entrega",
    'Actividad' => $actividad,
    'Fecha Inicio Actividad' => date('d/m/Y', strtotime($fecha_inicio_actividad)),
    'Correo' => $correo,
    'Teléfono' => $telefono,
    'Persona Solicitante' => $persona_solicitante
];

foreach ($datos_cliente as $key => $value) {
    $pdf->Cell(60, 6, $key . ':', 1, 0);
    $pdf->Cell(0, 6, $value, 1, 1);
}
$pdf->Ln(4);

// =====================
// 2. Datos Comerciales
// =====================
$pdf->SetFont('DejaVu', 'B', 10);
$pdf->Cell(0, 6, '2. Datos Comerciales', 1, 1, 'C');
$pdf->Ln(2);

// Transporte Seleccionado
$pdf->SetFont('DejaVu', '', 10);
$pdf->Cell(60, 6, 'Transporte Seleccionado:', 1, 0);
$pdf->Cell(0, 6, $transporte_seleccionado, 1, 1);

// Encargado de Compras y Correo Encargado Compras
$pdf->SetFont('DejaVu', 'B', 10);
$pdf->Cell(95, 6, 'Encargado de Compras', 1, 0, 'C');
$pdf->Cell(95, 6, 'Correo Encargado Compras', 1, 1, 'C');

$pdf->SetFont('DejaVu', '', 10);
$pdf->Cell(95, 6, $encargado_compras, 1, 0, 'C'); // Dato Encargado de Compras
$pdf->Cell(95, 6, $correo_encargado_compras, 1, 1, 'C'); // Dato Correo Encargado Compras

// Encargado de Pagos y Correo Encargado Pagos
$pdf->SetFont('DejaVu', 'B', 10);
$pdf->Cell(95, 6, 'Encargado de Pagos', 1, 0, 'C');
$pdf->Cell(95, 6, 'Correo Encargado Pagos', 1, 1, 'C');

$pdf->SetFont('DejaVu', '', 10);
$pdf->Cell(95, 6, $encargado_pagos, 1, 0, 'C'); // Dato Encargado de Pagos
$pdf->Cell(95, 6, $correo_encargado_pagos, 1, 1, 'C'); // Dato Correo Encargado Pagos

// Referencias en tres columnas
for ($i = 1; $i <= 3; $i++) {
    // Títulos de columnas
    $pdf->SetFont('DejaVu', 'B', 9); // Fuente más pequeña para referencias
    $pdf->Cell(63.33, 6, 'Empresa Referencia ' . $i, 1, 0, 'C');
    $pdf->Cell(63.33, 6, 'Forma de Pago', 1, 0, 'C');
    $pdf->Cell(63.33, 6, 'Teléfono Referencia', 1, 1, 'C');

    // Datos en columnas
    $pdf->SetFont('DejaVu', '', 9); // Coincidir fuente con títulos
    $empresa_var = 'empresa_referencia' . ($i == 1 ? '' : $i);
    $pago_var = 'forma_pago_referencia' . ($i == 1 ? '' : $i);
    $telefono_var = 'telefono_referencia' . ($i == 1 ? '' : $i);

    $pdf->Cell(63.33, 6, $$empresa_var, 1, 0, 'C');
    $pdf->Cell(63.33, 6, $$pago_var, 1, 0, 'C');
    $pdf->Cell(63.33, 6, $$telefono_var, 1, 1, 'C');
}   
// Crédito solicitado
$pdf->SetFont('DejaVu', 'B', 10);
$pdf->Cell(60, 6, 'Crédito Solicitado:', 1, 0);
$pdf->SetFont('DejaVu', '', 10);
$pdf->Cell(0, 6, $credito_solicitado, 1, 1);

$pdf->Ln(4);

// ====================
// 3. Datos Internos
// ====================
$pdf->SetFont('DejaVu', 'B', 10);
$pdf->Cell(0, 6, '3. Datos Internos', 1, 1, 'C');
$pdf->Ln(2);

$pdf->SetFont('DejaVu', '', 10);
$datos_internos = [
    'Codigo Cliente' => '',
    'Codigo Zona' => '',
    'Código Rubro' => '',
    'Codigo Vendedor' => '',
    'Condicion de Venta' => '',
    'Limite de Credito' => '',
    'Descuento' => ''
];

foreach ($datos_internos as $key => $value) {
    $pdf->Cell(60, 6, $key . ':', 1, 0);
    $pdf->Cell(0, 6, $value, 1, 1);
}


// Guardar PDF en archivo temporal
$temp_pdf = tempnam(sys_get_temp_dir(), 'formulario_Apertura_Cuenta_Corriente') . '.pdf';
$pdf->Output('F', $temp_pdf);


   // Preparar el correo electrónico
 $to = "marketing@romisa.com.ar, at.clientes@romisa.com.ar, contaduria@romisa.com.ar"; // Reemplaza con la dirección de correo del destinatario
 $subject = "Nuevo Formulario de apertura/Actualización de Cuenta Corriente";

 // Generar un separador único para el correo multipart
 $mime_boundary = "==Multipart_Boundary_x" . md5(time()) . "x";

 // Encabezados del correoprocesar_formularioCC.php
 $headers = "From: marketing@romisa.com.ar\r\n"; // Reemplaza con tu dirección de correo
 $headers .= "MIME-Version: 1.0\r\n";
 $headers .= "Content-Type: multipart/mixed; boundary=\"" . $mime_boundary . "\"\r\n";

 // Cuerpo del mensaje
 $message = "--" . $mime_boundary . "\r\n";
 $message .= "Content-Type: text/html; charset=\"UTF-8\"\r\n";
 $message .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
 $message .= "<html><body>";
 $message .= "<h1>Nuevo Formulario de Cuentas Corrientes</h1>";
 $message .= "<p><strong>Tipo de Cliente:</strong> " . ($tipo_cliente == 'nuevo' ? 'Cliente Nuevo' : 'Cliente Registrado') . "</p>";
 $message .= "<p><strong>Razón Social:</strong> " . $razon_social . "</p>";
 $message .= "<p><strong>CUIT:</strong> " . $cuit . "</p>";
 $message .= "<p><strong>Nombre Comercial (fantasia):</strong> " . $nombre_comercial . "</p>";
 $message .="<p><strong>Dirección Comercial:</strong> $cuit</p>";
 $message .= "<p><strong>Dirección Comercial:</strong> $direccion_comercial, $localidad_comercial, $provincia_comercial, $codigo_postal</p>";
 $message .= "<p><strong>Dirección de Entrega:</strong> $direccion_entrega, $localidad_entrega, $provincia_entrega, $codigo_postal</p>";
 $message .= "<p><strong>Correo:</strong> $correo</p>";
 $message .= "<p><strong>Teléfono:</strong> $telefono</p>";
 $message .= "<p><strong>Persona Solicitante:</strong> $persona_solicitante</p>";
 $message .= "<p><strong>Ocupación a la que se dedica:</strong> $actividad</p>";
 $message .= "<p><strong>Fecha de Inicio de Actividad:</strong> " . date('d/m/Y', strtotime($fecha_inicio_actividad)) . "</p>";
 $message .= "<p><strong>Encargado de Compras:</strong> $encargado_compras</p>";
 $message .= "<p><strong>Correo Encargado Compras:</strong> $correo_encargado_compras</p>";
 $message .= "<p><strong>Encargado de Pagos:</strong> $encargado_pagos</p>";
 $message .= "<p><strong>Correo Encargado Pagos:</strong> $correo_encargado_pagos</p>";
 $message .= "<p><strong>Transporte Seleccionado:</strong> $transporte_seleccionado</p>";
 $message .= "<p><strong>Referencias Comerciales:</strong></p>";
 $message .= "<p><strong>Empresa Referencia:</strong> $empresa_referencia</p>";
 $message .= "<p><strong>Telefono Referencia:</strong> $telefono_referencia</p>";
 $message .= "<p><strong>Forma de Pago Referencia:</strong> $forma_pago_referencia</p>";
 $message .= "<p><strong>Empresa Referencia 2:</strong> $empresa_referencia2</p>";
 $message .= "<p><strong>Teléfono Referencia 2:</strong> $telefono_referencia2</p>";
 $message .= "<p><strong>Forma de Pago Referencia 2:</strong> $forma_pago_referencia2</p>";
 $message .= "<p><strong>Empresa Referencia 3:</strong> $empresa_referencia3</p>";
 $message .= "<p><strong>Teléfono Referencia 3:</strong> $telefono_referencia3</p>";
 $message .= "<p><strong>Forma de Pago Referencia 3:</strong> $forma_pago_referencia3</p>";
 $message .= "</ul>";
 $message .= "<p>Se adjunta el PDF con todos los detalles del formulario.</p>";
 $message .= "</body></html>\r\n";

 // Adjuntar el PDF
 $file = file_get_contents($temp_pdf);
 $message .= "--" . $mime_boundary . "\r\n";
 $message .= "Content-Type: application/pdf; name=\"formulario_Cuenta_Corriente.pdf\"\r\n";
 $message .= "Content-Transfer-Encoding: base64\r\n";
 $message .= "Content-Disposition: attachment; filename=\"formulario_cuenta_corriente.pdf\"\r\n\r\n";
 $message .= chunk_split(base64_encode($file));
 $message .= "--" . $mime_boundary . "--";

 // Enviar el correo
 $mail_sent = mail($to, $subject, $message, $headers);


    // Eliminar el archivo temporal
    unlink($temp_pdf);

    if ($mail_sent) {
        header('Location: recibidocc.html');
    } else {
        echo "Hubo un problema al enviar el correo electrónico.";
    }
} else {
    echo "Acceso no autorizado.";
}
?>
