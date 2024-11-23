<?php
header('Content-Type: application/json'); // Especificar que la respuesta es JSON

require 'conection.php'; // Archivo de conexión a la base de datos

$response = []; // Respuesta para JSON

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $id_seguro = $_POST['id_seguro'];
    $id_cobertura = $_POST['id_cobertura'];

    // Validar que los campos no estén vacíos
    if (empty($id_seguro) || empty($id_cobertura)) {
        $response['success'] = false;
        $response['message'] = 'Todos los campos son obligatorios.';
        echo json_encode($response);
        exit();
    }

    // Insertar la relación entre seguro y cobertura
    $sql = "INSERT INTO seguros_coberturas (id_seguro, id_cobertura) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $id_seguro, $id_cobertura);

    if ($stmt->execute()) {
        // Si la inserción fue exitosa
        $response['success'] = true;
        $response['message'] = 'Cobertura registrada correctamente.';
        $response['redirect'] = 'formPago.php'; // URL para redirigir
    } else {
        // Si hubo error al ejecutar la consulta
        $response['success'] = false;
        $response['message'] = 'Error al asociar la cobertura: ' . $stmt->error;
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
