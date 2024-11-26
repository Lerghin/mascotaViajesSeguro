<?php
header('Content-Type: application/json'); // Especificar que la respuesta es JSON

require 'conection.php'; // Archivo de conexión a la base de datos

$response = []; // Respuesta para JSON

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $id_seguro = $_POST['id_seguro'];
    $fecha_inicio = $_POST['fecha_inicio'];
    $fecha_fin = $_POST['fecha_fin'];

    // Validar que los campos no estén vacíos
    if (empty($id_seguro) || empty($fecha_inicio) || empty($fecha_fin)) {
        $response['success'] = false;
        $response['message'] = 'Todos los campos son obligatorios.';
        echo json_encode($response);
        exit();
    }

    // Actualizar el seguro en la base de datos
    $sql = "UPDATE seguros SET fecha_inicio = ?, fecha_fin = ? WHERE id_seguro = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssi', $fecha_inicio, $fecha_fin, $id_seguro);

    if ($stmt->execute()) {
        // Si la actualización fue exitosa
        $response['success'] = true;
        $response['message'] = 'Seguro actualizado correctamente.';
    } else {
        // Si hubo error al ejecutar la consulta
        $response['success'] = false;
        $response['message'] = 'Error al actualizar el seguro: ' . $stmt->error;
    }

    echo json_encode($response); // Respuesta en formato JSON
    $stmt->close();
    $conn->close();
} else {
    $response['success'] = false;
    $response['message'] = 'Método no permitido.';
    echo json_encode($response);
}
?>
