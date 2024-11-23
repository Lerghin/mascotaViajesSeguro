<?php
// Conexión a la base de datos
require 'conection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recoger los datos del formulario
    $id_mascota = $_POST['id_mascota'];
    $fecha_inicio = $_POST['fecha_inicio'];
    $fecha_fin = $_POST['fecha_fin'];

    // Validar que los campos no estén vacíos
    if (empty($id_mascota) || empty($fecha_inicio) || empty($fecha_fin)) {
        echo "Todos los campos son requeridos.";
        exit;
    }

    // Insertar el seguro en la tabla `seguros`
    $sql = "INSERT INTO seguros (id_mascota, fecha_inicio, fecha_fin) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('iss', $id_mascota, $fecha_inicio, $fecha_fin);

    if ($stmt->execute()) {
        // Obtener el id_seguro generado automáticamente
        $id_seguro = $conn->insert_id;
        
        // Devolver el id_seguro como respuesta JSON
        echo json_encode(['id_seguro' => $id_seguro, 'message' => 'Seguro registrado exitosamente.']);
    } else {
        echo json_encode(['message' => 'Error al registrar el seguro: ' . $stmt->error]);
    }

    // Cerrar la conexión
    $stmt->close();
    $conn->close();
} else {
    echo "Método no permitido.";
}
?>
