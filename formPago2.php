<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Pago</title>
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
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Registro de Pago</h2>
        <!-- Tasa de cambio -->
        <div class="exchange-rate">
            Tasa actual: <span id="dolar-rate">Cargando...</span> Bs/USD
        </div>
        <form action="procesarPago.php" method="POST">
            <div class="form-group">
                <label for="id_mascota" style="display:none">ID Mascota</label>
                <input type="number" style="display:none" id="id_mascota" name="id_mascota" readonly required>
            </div>
            <div class="form-group">
                <label for="id_seguro">Seleccione el Seguro</label>
                <select id="id_seguro" name="id_seguro" required>
                    <option value="">Cargando seguros...</option>
                </select>
            </div>
            <div class="form-group">
                <label for="monto_usd">Monto (en USD)</label>
                <input type="number" step="0.01" id="monto_usd" name="monto_usd" placeholder="Ingrese monto en USD" required>
            </div>
            <div class="form-group">
                <label for="monto_bsf">Monto (en Bs.)</label>
                <input type="number" step="0.01" id="monto_bsf" name="monto_bsf" readonly required>
            </div>
            <div class="form-group">
                <label for="fecha_pago">Fecha de Pago</label>
                <input type="date" id="fecha_pago" name="fecha_pago" required>
            </div>
            <div class="form-group">
                <label for="tipo_pago">Tipo de Pago</label>
                <select id="tipo_pago" name="tipo_pago" required>
                    <option value="">Seleccione el tipo de pago</option>
                    <option value="1">Transferencia</option>
                    <option value="2">Efectivo</option>
                    <option value="3">Tarjeta</option>
                </select>
            </div>
            <div class="form-group">
                <label for="referencia">Referencia de Pago</label>
                <input type="text" id="referencia" name="referencia" required>
            </div>
            <div class="form-group">
                <input type="submit" value="Registrar Pago">
            </div>
        </form>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", async function () {
            const idMascota = localStorage.getItem("id_mascota");
            const idMascotaInput = document.getElementById("id_mascota");
            const idSeguroSelect = document.getElementById("id_seguro");
            const dolarRateSpan = document.getElementById("dolar-rate");
            const montoUsdInput = document.getElementById("monto_usd");
            const montoBsfInput = document.getElementById("monto_bsf");

            let tasaCambio = 0;

            if (idMascota) idMascotaInput.value = idMascota;

            try {
                // Obtener la lista de seguros relacionados con la mascota
                const segurosResponse = await fetch(`getSeguros.php?id_mascota=${idMascota}`);
                if (!segurosResponse.ok) throw new Error("Error al obtener los seguros.");

                const seguros = await segurosResponse.json();
                idSeguroSelect.innerHTML = seguros.map(seguro => 
                    `<option value="${seguro.id_seguro}"> CodSeguro: ${seguro.id_seguro} desde el: (${seguro.fecha_inicio} al ${seguro.fecha_fin})</option>`
                ).join('');

            } catch (error) {
                idSeguroSelect.innerHTML = `<option value="">Error al cargar</option>`;
                alert("Error: " + error.message);
            }

            try {
                // Obtener la tasa de cambio
                const apiUrl = "https://v6.exchangerate-api.com/v6/8ee293f7c8b83cfe4baa699c/latest/USD";
                const response = await fetch(apiUrl);
                if (!response.ok) throw new Error("No se pudo obtener la tasa de cambio.");

                const data = await response.json();
                tasaCambio = data.conversion_rates.VES;

                dolarRateSpan.textContent = tasaCambio.toFixed(2);
            } catch (error) {
                dolarRateSpan.textContent = "Error";
                alert("Error al cargar la tasa de cambio: " + error.message);
            }

            // Calcular monto en Bs.
            montoUsdInput.addEventListener("input", function () {
                const montoUsd = parseFloat(montoUsdInput.value);
                montoBsfInput.value = montoUsd > 0 ? (montoUsd * tasaCambio).toFixed(2) : '';
            });
        });
    </script>
</body>
</html>
