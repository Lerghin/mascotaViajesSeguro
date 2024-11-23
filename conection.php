<?php
// Datos de conexión a la base de datos
$servername = "localhost"; // o el servidor de tu base de datos
$username = "root";        // tu nombre de usuario
$password = "";            // tu contraseña (en blanco para XAMPP por defecto)
$dbname = "pet-travel"; // el nombre de la base de datos

// Crear la conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Comprobar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}


?>
