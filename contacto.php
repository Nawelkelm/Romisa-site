<?php
  
if($_POST) {
    // Validar reCAPTCHA
    $recaptcha_secret = "6Lc0iQ8sAAAAAPSgwRU0l2qumL6Aca4_SkCxdcmX";
    $recaptcha_response = $_POST['g-recaptcha-response'] ?? '';
    
    // Verificar que el reCAPTCHA fue completado
    if (empty($recaptcha_response)) {
        header("Location: contacto.html?error=captcha");
        exit;
    }
    
    // Verificar el reCAPTCHA con Google
    $verify_url = 'https://www.google.com/recaptcha/api/siteverify';
    $data = array(
        'secret' => $recaptcha_secret,
        'response' => $recaptcha_response,
        'remoteip' => $_SERVER['REMOTE_ADDR']
    );
    
    $options = array(
        'http' => array(
            'method' => 'POST',
            'header' => 'Content-Type: application/x-www-form-urlencoded',
            'content' => http_build_query($data)
        )
    );
    
    $context = stream_context_create($options);
    $verify_response = file_get_contents($verify_url, false, $context);
    $response_data = json_decode($verify_response);
    
    // Si la verificación falla, redirigir con error
    if (!$response_data->success) {
        header("Location: contacto.html?error=captcha_invalid");
        exit;
    }
    
    // Continuar con el procesamiento del formulario
    $nombre_cliente = "";
    $nombre_solicitante = "";
    $email_cliente = "";
    $nombre_empresa = "";
    $rubro_empresa = "";
    $cuil_cuit_cliente = "";
    $mensaje_cliente = "";
    $email_body = "<div>";
      
    if(isset($_POST['nombre_solicitante'])) {
        $nombre_nolicitante = filter_var($_POST['nombre_solicitante'], FILTER_SANITIZE_STRING);
        $email_body .= "<div>
                           <label><b>Nombre del Solicitante:</b></label>&nbsp;<span>".$nombre_solicitante."</span>
                        </div>";
    }
    if(isset($_POST['nombre_cliente'])) {
        $nombre_cliente = filter_var($_POST['nombre_cliente'], FILTER_SANITIZE_STRING);
        $email_body .= "<div>
                           <label><b>Nombre:</b></label>&nbsp;<span>".$nombre_cliente."</span>
                        </div>";
    }
    if(isset($_POST['email_cliente'])) {
        $email_cliente = str_replace(array("\r", "\n", "%0a", "%0d"), '', $_POST['email_cliente']);
        $email_cliente = filter_var($email_cliente, FILTER_VALIDATE_EMAIL);
        $email_body .= "<div>
                           <label><b>Email:</b></label>&nbsp;<span>".$email_cliente."</span>
                        </div>";
    }
      
    if(isset($_POST['nombre_empresa'])) {
        $nombre_empresa = filter_var($_POST['nombre_empresa'], FILTER_SANITIZE_STRING);
        $email_body .= "<div>
                           <label><b>Nombre de la Empresa:</b></label>&nbsp;<span>".$nombre_empresa."</span>
                        </div>";
    }

    if(isset($_POST['rubro_empresa'])) {
        $rubro_empresa = filter_var($_POST['rubro_empresa'], FILTER_SANITIZE_STRING);
        $email_body .= "<div>
                           <label><b>Rubro que se dedica:</b></label>&nbsp;<span>".$rubro_empresa."</span>
                        </div>";
    }
      
    if(isset($_POST['cuil_cuit_cliente'])) {
        $cuil_cuit_cliente = filter_var($_POST['cuil_cuit_cliente'], FILTER_SANITIZE_STRING);
        $email_body .= "<div>
                           <label><b>CUIL/CUIT:</b></label>&nbsp;<span>".$cuil_cuit_cliente."</span>
                        </div>";
    }
      
    if(isset($_POST['mensaje_cliente'])) {
        $mensaje_cliente = htmlspecialchars($_POST['mensaje_cliente']);
        $email_body .= "<div>
                           <label><b>Mensaje del cliente:</b></label>
                           <div>".$mensaje_cliente."</div>
                        </div>";
    }
      
    $email_body .= "</div>";
 
    $headers  = 'MIME-Version: 1.0' . "\r\n"
    .'Content-type: text/html; charset=utf-8' . "\r\n"
    .'From: ' . $email_cliente . "\r\n";

    $recipient = "ventas@romisa.com.ar";
    $email_title ="Nuevo mensaje de cliente en ROMISA página web";
      
    if(mail($recipient, $email_title, $email_body, $headers)) {
        header('Location: recibido.html');
    } else {
        echo '<p>Hubo un problema, por favor intente nuevamente..</p>';
    }
      
} else {
    echo '<p>Por favor intente nuevamente.</p>';
}
?>