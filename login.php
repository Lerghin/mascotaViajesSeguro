<?php

$conexion= new mysqli("localhost", "root", "","swguro_mascotas");

if ($conexion connect_error) {
   die("Conexion fallida: " . $conexion connect_error);
}

$username = $_POST[´username´];
$password = $_POST[´password´];

$sql = "SELECT * FROM usuarios WHERE username = ´$username´ AND ´$pasword´";

$resultado = $conexion query($sql);

if ($resultado num_rows > 0) {
   session_star();
   $_SESSION[´username´] = $username;
   header("Location: dashboard.php");
   exit;
} esle{
   ecgo "Credenciales incorrectas";
}
$conexion close();
?>


