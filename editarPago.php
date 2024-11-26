<?php
require 'conection.php';  // Asegúrate de que este archivo contiene la conexión a tu base de datos

// Verificar si se ha recibido el id_pago desde la URL
if (isset($_GET['id_pago'])) {
    $id_pago = $_GET['id_pago'];

    // Consultar los datos del pago en la base de datos
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
} else {
    echo "No se ha proporcionado el id_pago.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recibir los datos del formulario
    $monto_bsf = $_POST['monto_bsf'];
    $monto_usd = $_POST['monto_usd'];
    $fecha_pago = $_POST['fecha_pago'];
    $tipo_pago = $_POST['tipo_pago'];
    $referencia = $_POST['referencia'];

    // Actualizar los datos del pago en la base de datos
    $sql = "UPDATE pagos SET monto_bsf = ?, monto_usd = ?, fecha_pago = ?, tipo_pago = ?, referencia = ? WHERE id_pago = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param('ddssss', $monto_bsf, $monto_usd, $fecha_pago, $tipo_pago, $referencia, $id_pago);
        if ($stmt->execute()) {
            echo "<script>alert('Pago actualizado exitosamente'); window.location.href='dashboardAdmin.php';</script>";
        } else {
            echo "Error al actualizar el pago.";
        }
        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Pago</title>
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
            color: #0056b3;
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

        .form-group input:focus, .form-group select:focus {
            border-color: #0056b3;
            outline: none;
            box-shadow: 0 0 5px rgba(0, 86, 179, 0.2);
        }

        .form-group input[type="submit"] {
            background: #0056b3;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
        }

        .form-group input[type="submit"]:hover {
            background: #004099;
        }

        .exchange-rate {
            font-size: 14px;
            color: #0056b3;
            margin-bottom: 15px;
            text-align: right;
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
        <h2>Editar Pago</h2>
        <!-- Tasa de cambio -->
        <div class="exchange-rate">
            Tasa actual: <span id="dolar-rate">Cargando...</span> Bs/USD
        </div>
        <form action="editarPago.php?id_pago=<?php echo $id_pago; ?>" method="POST">
            <div class="form-group">
                <label for="id_mascota" style="display:none">ID Mascota</label>
                <input type="number" style="display:none" id="id_mascota" name="id_mascota" readonly value="<?php echo $id_mascota; ?>" required>
            </div>
            <div class="form-group">
                <label for="id_seguro" style="display:none">ID Seguro</label>
                <input type="number" style="display:none" id="id_seguro" name="id_seguro" readonly value="<?php echo $id_seguro; ?>" required>
            </div>
            <div class="form-group">
                <label for="monto_usd">Monto (en USD)</label>
                <input type="number" step="0.01" id="monto_usd" name="monto_usd" placeholder="Ingrese monto en USD" value="<?php echo $monto_usd; ?>" required>
            </div>
            <div class="form-group">
                <label for="monto_bsf">Monto (en Bs.)</label>
                <input type="number" step="0.01" id="monto_bsf" name="monto_bsf" readonly value="<?php echo $monto_bsf; ?>" required>
            </div>
            <div class="form-group">
                <label for="fecha_pago">Fecha de Pago</label>
                <input type="date" id="fecha_pago" name="fecha_pago" value="<?php echo $fecha_pago; ?>" required>
            </div>
            <div class="form-group">
                <label for="tipo_pago">Tipo de Pago</label>
                <select id="tipo_pago" name="tipo_pago" required>
                    <option value="1" <?php echo ($tipo_pago == '1') ? 'selected' : ''; ?>>Transferencia</option>
                    <option value="2" <?php echo ($tipo_pago == '2') ? 'selected' : ''; ?>>Efectivo</option>
                    <option value="3" <?php echo ($tipo_pago == '3') ? 'selected' : ''; ?>>Tarjeta</option>
                </select>
            </div>
            <div class="form-group">
                <label for="referencia">Referencia de Pago</label>
                <input type="text" id="referencia" name="referencia" value="<?php echo $referencia; ?>" required>
            </div>
            <div class="form-group">
                <input type="submit" value="Actualizar Pago">
            </div>
        </form>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", async function () {
            // Referencia al span de la tasa de cambio
            const dolarRateSpan = document.getElementById("dolar-rate");
            const montoUsdInput = document.getElementById("monto_usd");
            const montoBsfInput = document.getElementById("monto_bsf");

            let tasaCambio = 0;

            try {
                // Llamar a la API para obtener la tasa de cambio
                const apiUrl = "https://v6.exchangerate-api.com/v6/8ee293f7c8b83cfe4baa699c/latest/USD";
                const response = await fetch(apiUrl);
                if (!response.ok) throw new Error("No se pudo obtener la tasa de cambio.");

                const data = await response.json();
                tasaCambio = data.conversion_rates.VES;

                // Actualizar el span con la tasa de cambio
                dolarRateSpan.textContent = tasaCambio.toFixed(2);
            } catch (error) {
                dolarRateSpan.textContent = "Error";
                alert("Error al cargar la tasa de cambio: " + error.message);
            }

            // Manejar el cálculo del monto en Bs.
            montoUsdInput.addEventListener("input", function () {
                const montoUsd = parseFloat(montoUsdInput.value);
                if (isNaN(montoUsd) || montoUsd <= 0) {
                    montoBsfInput.value = "";
                    return;
                }
                // Calcular el monto en Bs. usando la tasa actual
                const montoBsf = (montoUsd * tasaCambio).toFixed(2);
                montoBsfInput.value = montoBsf;
            });
        });
    </script>
</body>
</html>
