<?php
session_start();

// Verificar si el usuario está iniciado sesión
if (!isset($_SESSION['user_id'])) {
  header('Location: index.html');
  exit;
}
// Mostrar la página protegida
echo "<h1>Bienvenido, ". $_SESSION['username'] ."</h1>";
