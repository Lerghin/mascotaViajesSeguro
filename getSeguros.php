<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'conection.php';

$idMascota = $_GET['id_mascota'] ?? 0;

if (!$idMascota) {
    echo json_encode(['error' => 'No se proporcionó id_mascota']);
    exit;
}

$sql = "SELECT id_seguro, fecha_inicio, fecha_fin 
        FROM seguros 
        WHERE id_mascota = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode(['error' => 'Error al preparar la consulta']);
    exit;
}

$stmt->bind_param("i", $idMascota);
$stmt->execute();

$result = $stmt->get_result();

$seguros = [];
while ($row = $result->fetch_assoc()) {
    $seguros[] = $row;
}

$stmt->close();
$conn->close();

header('Content-Type: application/json');
echo json_encode($seguros);
