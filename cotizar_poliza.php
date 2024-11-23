<?php
//cotizar_poliza.php
session_start();
if (!isset($_SESSION[´username´])) {
    header("location: login.html");
    exit;
}

$conexion = new mysqli("localhost", "root", "seguro_mascotas");

if ($conexion_connect_error){ orregir va una flecha
   die("conexion fallida: ". $conexion_connect_error); //corregir va una flecha
}

$id_mascota = $_POST[´id_mascota´];
$costo = $_POST[´costo´];
$fecha_inicio = $_POST[´fecha_inicio´];
$fecha_fin = $_POST[´fecha_fin´];

$sql = "INSERT INTO polizas (id_mascota, costo, fecha_inicio, fecha_fin) VALUES ($id_mascota, $costo, ´$fecha_inicio´, ´$fecha_fin´)";

if ($conexion_query($sql) = TRUE ) { //corregir va una flecha
   echo "Póliza cotizada exitosamente.";
}else {
    echo "error: " . $sql . "<br>" . $conexion_error; //corregir va una flecha
}

$conexion_close(); //corregir va una flecha

?>