<?php
// Archivo config.php cifrado

define('ENCRYPTION_KEY', 'clave-secreta-de-encripcion');

// Función para cifrar datos
function data_encrypt($data) {
    $key = ENCRYPTION_KEY;
    return openssl_encrypt($data, 'AES-256-CBC', $key, 0, substr(hash('sha256', $key), 0, 16));
}

// Función para descifrar datos
function data_decrypt($encrypted_data) {
    $key = ENCRYPTION_KEY;
    return openssl_decrypt($encrypted_data, 'AES-256-CBC', $key, 0, substr(hash('sha256', $key), 0, 16));
}

// Cifrado previo
$encrypted_recaptcha_secret = data_encrypt('6LceupYqAAAAAPiYdBQz7fMbOHGUCdtsHsD8a3Xl');
$encrypted_mail_recipients = data_encrypt('marketing@romisa.com.ar, at.clientes@romisa.com.ar, contaduria@romisa.com.ar');
$encrypted_mail_sender = data_encrypt('marketing@romisa.com.ar');

// Definiendo las variables cifradas
define('RECAPTCHA_SECRET', data_decrypt($encrypted_recaptcha_secret));
define('MAIL_RECIPIENTS', data_decrypt($encrypted_mail_recipients));
define('MAIL_SENDER', data_decrypt($encrypted_mail_sender));

?>