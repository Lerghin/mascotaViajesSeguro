<?php
header('Content-Type: application/json'); // Especificar que la respuesta es JSON

require 'conection.php'; // Archivo de conexión a la base de datos

$response = []; // Respuesta para JSON

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id_seguro'])) {
    // Verificar que el parámetro id_seguro esté presente
    $id_seguro = $_GET['id_seguro'];
    error_log("id_seguro recibido: " . $id_seguro);
    // Obtener los datos del seguro de la base de datos
    $sql = "SELECT id_seguro, fecha_inicio, fecha_fin FROM seguros WHERE id_seguro = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id_seguro);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $seguro = $result->fetch_assoc();
        $response['success'] = true;
        $response['seguro'] = $seguro;
    } else {
        $response['success'] = false;
        $response['message'] = 'No se encontró el seguro.';
    }

    echo json_encode($response); // Respuesta en formato JSON
    $stmt->close();
    $conn->close();
} else {
    $response['success'] = false;
    $response['message'] = 'Método no permitido o falta el id_seguro.';
    echo json_encode($response);
}
?>
