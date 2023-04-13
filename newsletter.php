<?php
   $conn = mysqli_connect("localhost", "u930231922_romisa", "hrNSB4NNenh6KQws", "u930231922_romisadb");
   $subscriberEmail = $_POST["subscriberEmail"];
   mysqli_query($conn, "INSERT INTO suscriptores (email) VALUES ('$subscriberEmail')");
// Check de conexion
if($link === false){
   die("ERROR: Could not connect. " . mysqli_connect_error());
}

if (isset($_POST['submit']) and isset($_POST['email'])) {
if ($_POST['email'] != "") 
{  
  $email = filter_var($_POST['email'] , FILTER_SANITIZE_EMAIL); 

     if(!filter_var($email, FILTER_VALIDATE_EMAIL)) 
     {  
               $result = "The mail you entered is not a valid email address.";
     } 
     else
     {
     mysqli_select_db($link, 'u930231922_romisadb'); 	
     $sql = 'INSERT INTO newsletter SET email = "' . $email . '"';
     $sql1 = 'SELECT email FROM newsletter WHERE email = "'.$email.'"';

        if (mysqli_query ($link, $sql1) == true) 
        {
        $result = "Your email is alredy registered.";
        }
        else
        {
        if (mysqli_query ($link, $sql)) 
        {
        $result = "Your email has been successfully registered. Thanks for your interest in SABF!";
        }
        }
} 
}
else 
{  
   $result = 'Please enter your email address.'; 
}
}
  $conn = mysqli_connect("localhost", "u930231922_romisa", "hrNSB4NNenh6KQws", "u930231922_romisadb");
  $subscriberEmail = $_POST["subscriberEmail"];
  mysqli_query($conn, "INSERT INTO suscriptores (email) VALUES ('$subscriberEmail')");
  $duplicate=mysqli_query($conn, "INSERT INTO suscriptores (email) VALUES ('$subscriberEmail')");
?>