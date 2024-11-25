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
        echo json_encode(['success' => false, 'message' => 'Todos los campos son requeridos.']);
        exit;
    }

    // Actualizar el seguro en la tabla `seguros`
    $sql = "UPDATE seguros SET id_mascota = ?, fecha_inicio = ?, fecha_fin = ? WHERE id_seguro = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('issi', $id_mascota, $fecha_inicio, $fecha_fin, $_GET['id_seguro']);  // Asumiendo que el ID del seguro se pasa por GET

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Seguro actualizado correctamente.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al actualizar el seguro: ' . $stmt->error]);
    }

    // Cerrar la conexión
    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
}
?>
