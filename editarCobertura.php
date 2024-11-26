<?php
require 'conection.php';  // Asegúrate de que este archivo contiene la conexión a tu base de datos

// Inicializar la respuesta
$response = ['success' => false, 'message' => ''];

// Verificar si se ha recibido el id_cobertura, id_seguro y el nuevo nombre de cobertura
if (isset($_POST['id_cobertura']) && isset($_POST['id_seguro'])) {
    // Obtener los valores del formulario
    $id_cobertura = $_POST['id_cobertura'];
  
    $id_seguro = $_POST['id_seguro'];  // Asegurarnos de recibir el id_seguro también

    // Validar que los campos no estén vacíos
    if (empty($id_cobertura)  || empty($id_seguro)) {
        $response['message'] = 'El id de cobertura, nombre de cobertura y id de seguro son obligatorios.';
    } else {
        // Preparar la consulta SQL para actualizar el nombre de la cobertura en la tabla seguros_coberturas
        $sql = "
            UPDATE seguros_coberturas sc
            JOIN coberturas c ON sc.id_cobertura = c.id_cobertura
            SET c.nombre_cobertura = ?
            WHERE sc.id_cobertura = ? AND sc.id_seguro = ?
        ";

        if ($stmt = $conn->prepare($sql)) {
            // Enlazar los parámetros
            $stmt->bind_param('sii', $nombre_cobertura, $id_cobertura, $id_seguro);  // 'sii' significa string, entero, entero

            // Ejecutar la consulta
            if ($stmt->execute()) {
                $response['success'] = true;
                $response['message'] = 'Cobertura actualizada correctamente.';
            } else {
                $response['message'] = 'Error al actualizar la cobertura.';
            }

            // Cerrar la declaración
            $stmt->close();
        } else {
            $response['message'] = 'Error al preparar la consulta.';
        }
    }
} else {
    $response['message'] = 'Faltan parámetros para actualizar la cobertura.';
}

// Cerrar la conexión a la base de datos
$conn->close();

// Devolver la respuesta como JSON
echo json_encode($response);
?>
