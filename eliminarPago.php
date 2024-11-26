<?php
require 'conection.php';  // Asegúrate de que este archivo contiene la conexión a tu base de datos

// Verificar si se ha recibido el id_pago desde la URL
if (isset($_GET['id_pago'])) {
    $id_pago = $_GET['id_pago'];

    // Consultar los datos del pago para confirmar que existe antes de eliminar
    $sql = "SELECT * FROM pagos WHERE id_pago = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param('i', $id_pago); // 'i' indica que el parámetro es un entero
        $stmt->execute();
        $stmt->store_result();

        // Verificar si el pago existe
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id_pago, $id_mascota, $id_seguro, $monto_bsf, $monto_usd, $fecha_pago, $tipo_pago, $referencia, $creado_en);
            $stmt->fetch();
        } else {
            echo "Pago no encontrado.";
            exit;
        }
        $stmt->close();
    } else {
        echo "Error al ejecutar la consulta.";
        exit;
    }

    // Si se ha confirmado la eliminación
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Eliminar el pago de la base de datos
        $sql = "DELETE FROM pagos WHERE id_pago = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param('i', $id_pago); // 'i' indica que el parámetro es un entero
            if ($stmt->execute()) {
                echo "<script>alert('Pago eliminado exitosamente'); window.location.href='dashboardAdmin.php';</script>";
            } else {
                echo "Error al eliminar el pago.";
            }
            $stmt->close();
        }
    }

} else {
    echo "No se ha proporcionado el id_pago.";
    exit;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Pago</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .form-container {
            background: #ffffff;
            padding: 20px 30px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
        }

        .form-container h2 {
            color: #d9534f;
            margin-bottom: 20px;
            text-align: center;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #333;
            font-weight: bold;
        }

        .form-group input, .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        .form-group input[type="submit"] {
            background: #d9534f;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
        }

        .form-group input[type="submit"]:hover {
            background: #c9302c;
        }

        .alert {
            background-color: #f2dede;
            color: #a94442;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        @media (max-width: 500px) {
            .form-container {
                padding: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Eliminar Pago</h2>
        <div class="alert">
            <strong>Advertencia!</strong> Estás a punto de eliminar un pago. Esta acción no se puede deshacer.
        </div>
        <form action="eliminarPago.php?id_pago=<?php echo $id_pago; ?>" method="POST">
            <div class="form-group">
                <label><strong>ID Pago:</strong> <?php echo $id_pago; ?></label>
                <label><strong>Mascota ID:</strong> <?php echo $id_mascota; ?></label>
                <label><strong>Seguro ID:</strong> <?php echo $id_seguro; ?></label>
                <label><strong>Monto en USD:</strong> <?php echo $monto_usd; ?> USD</label>
                <label><strong>Monto en Bs.:</strong> <?php echo $monto_bsf; ?> Bs.</label>
                <label><strong>Fecha de Pago:</strong> <?php echo $fecha_pago; ?></label>
                <label><strong>Tipo de Pago:</strong> <?php echo $tipo_pago; ?></label>
                <label><strong>Referencia:</strong> <?php echo $referencia; ?></label>
            </div>
            <div class="form-group">
                <input type="submit" value="Eliminar Pago">
            </div>
        </form>
    </div>
</body>
</html>
