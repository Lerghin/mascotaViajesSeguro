<?php
require 'conection.php'; // Conexión a la base de datos

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $id_mascota = $_POST['id_mascota'];
    $id_seguro = $_POST['id_seguro'];
    $monto_usd = $_POST['monto_usd'];
    $monto_bsf = $_POST['monto_bsf'];
    $fecha_pago = $_POST['fecha_pago'];
    $tipo_pago = $_POST['tipo_pago'];
    $referencia = $_POST['referencia'];

    // Validar que los campos no estén vacíos
    if (empty($id_mascota) || empty($id_seguro) || empty($monto_usd) || empty($monto_bsf) || empty($fecha_pago) || empty($tipo_pago) || empty($referencia)) {
        echo "<script>alert('Todos los campos son obligatorios.'); window.history.back();</script>";
        exit();
    }

    // Preparar la consulta para insertar el pago en la base de datos
    $stmt = $conn->prepare("INSERT INTO pagos (id_mascota, id_seguro, monto_usd, monto_bsf, fecha_pago, tipo_pago, referencia) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iiddsis", $id_mascota, $id_seguro, $monto_usd, $monto_bsf, $fecha_pago, $tipo_pago, $referencia);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        // Obtener el ID del pago recién registrado
        $id_pago = $stmt->insert_id;

        // Si el pago se registró correctamente, redirigir a la página de confirmación
        echo "<script>
            alert('Pago registrado correctamente.');
            window.location.href = 'indexCliente.php?id_mascota=$id_mascota'; // Página de confirmación de pago
        </script>";
    } else {
        // Si ocurre un error al insertar el pago
        echo "Error al registrar el pago: " . $stmt->error;
    }

    // Cerrar la conexión
    $stmt->close();
    $conn->close();
}
?>
