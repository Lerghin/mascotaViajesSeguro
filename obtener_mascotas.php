<?php
require 'conection.php';

$sql = "SELECT id_mascota, nombre_mascota FROM mascotas";
$result = $conn->query($sql);

$mascotas = [];
while ($row = $result->fetch_assoc()) {
    $mascotas[] = $row;
}

echo json_encode($mascotas);
$conn->close();
?>
