<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Seguro</title>
    <style>
        /* Estilos básicos */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7f6;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .form-container {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }
        label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }
        input, select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        input[type="submit"] {
            background-color: #3f51b5;
            color: white;
            border: none;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #303f9f;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Editar Seguro</h2>
        <form id="editSeguroForm">
            <label for="id_mascota">Mascota</label>
            <select id="id_mascota" name="id_mascota" required>
                <option value="">Seleccione una mascota</option>
                <!-- Opciones dinámicas -->
            </select>

            <label for="fecha_inicio">Fecha de Inicio</label>
            <input type="date" id="fecha_inicio" name="fecha_inicio" required>

            <label for="fecha_fin">Fecha de Fin</label>
            <input type="date" id="fecha_fin" name="fecha_fin" required>

            <input type="submit" value="Guardar Cambios">
        </form>
    </div>

    <script>
        // Cargar mascotas dinámicamente
        fetch('obtener_mascotas.php')
            .then(response => response.json())
            .then(data => {
                const selectMascota = document.getElementById('id_mascota');
                data.forEach(mascota => {
                    const option = document.createElement('option');
                    option.value = mascota.id_mascota;
                    option.textContent = mascota.nombre;
                    selectMascota.appendChild(option);
                });
            })
            .catch(error => console.error('Error al cargar mascotas:', error));

        // Guardar cambios del seguro
        document.getElementById('editSeguroForm').addEventListener('submit', function (e) {
            e.preventDefault();

            const formData = new FormData(this);
            formData.append('id_seguro', new URLSearchParams(window.location.search).get('id_seguro'));

            fetch('editarSeguro.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    window.location.href = 'editarCobertura.php?id_seguro=' + formData.get('id_seguro');
                } else {
                    alert(data.message);
                }
            })
            .catch(error => console.error('Error al guardar el seguro:', error));
        });
    </script>
</body>
</html>
