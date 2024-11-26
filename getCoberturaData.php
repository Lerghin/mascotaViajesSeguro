<?php
require 'conection.php';

$response = [];

// Verificar que se ha recibido el id_seguro por GET
if (isset($_GET['id_seguro'])) {
    $id_seguro = $_GET['id_seguro'];

    // Preparar la consulta SQL para obtener las coberturas
    $sql = "
        SELECT c.id_cobertura, c.nombre_cobertura
        FROM seguros_coberturas sc
        JOIN coberturas c ON sc.id_cobertura = c.id_cobertura
        WHERE sc.id_seguro = ?";
    
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param('i', $id_seguro);  // Usamos el parámetro id_seguro
        $stmt->execute();
        $stmt->store_result();

        // Verificar si se encontraron coberturas
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id_cobertura, $nombre_cobertura);

            // Guardar los resultados en un arreglo
            $coberturas = [];
            while ($stmt->fetch()) {
                $coberturas[] = [
                    'id_cobertura' => $id_cobertura,
                    'nombre_cobertura' => $nombre_cobertura
                ];
            }

            $response['success'] = true;
            $response['coberturas'] = $coberturas;
        } else {
            $response['success'] = false;
            $response['message'] = 'No se encontraron coberturas para este seguro.';
        }

        $stmt->close();
    } else {
        $response['success'] = false;
        $response['message'] = 'Error al ejecutar la consulta.';
    }

    $conn->close();
} else {
    $response['success'] = false;
    $response['message'] = 'No se ha proporcionado el id_seguro.';
}

echo json_encode($response);
?>
