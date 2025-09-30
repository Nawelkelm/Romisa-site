<?php
if (isset($_POST['submit']) && isset($_POST['subscriberEmail'])) {
  $email = filter_var($_POST['subscriberEmail'], FILTER_SANITIZE_EMAIL);

  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $result = "El correo electrónico ingresado no es válido.";
  } else {
    $to = "kelmnahuel@gmail.com";
    $subject = "Nuevo suscriptor";
    $message = "Se ha suscrito un nuevo correo electrónico: $email";
    $headers = "From: $email\r\n";
    $headers .= "Reply-To: $email\r\n";

    if (mail($to, $subject, $message, $headers)) {
      $result = "Tu correo electrónico se ha registrado exitosamente. ¡Gracias por tu interés!";
    } else {
      $result = "Ha ocurrido un error al enviar el correo electrónico.";
    }
  }
} else {
  $result = "Por favor, ingresa tu dirección de correo electrónico.";
}
?>
