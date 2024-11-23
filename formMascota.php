<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Registro de Mascotas</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f7f6;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .form-container {
            background: #fff;
            border-radius: 10px;
            margin-top:200px;
            padding: 20px;
            max-width: 500px;
            width: 100%;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
            color: #555;
        }

        input, select {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border-radius: 5px;
            border: 1px solid #ccc;
            margin-top: 5px;
            outline: none;
        }

        input:focus {
            border-color: #3f51b5;
        }

        .form-group input[type="submit"] {
            background-color: #3f51b5;
            color: white;
            cursor: pointer;
            border: none;
            margin-top: 20px;
        }

        .form-group input[type="submit"]:hover {
            background-color: #303f9f;
        }
        .btn-back{
          background-color: #495057 !important;
        }
        button {
            width: 100%;
            padding: 10px;
            margin-top:1rem;
            background-color: #507dbc;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        @media (max-width: 768px) {
            .form-container {
                padding: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Registro de Mascotas</h2>
        <form action="porcesarMascota.php" method="POST">
            <!-- Campo para buscar al dueño por cédula -->
            <div class="form-group">
                <label for="cedula">Cédula del Dueño</label>
                <input type="text" id="cedula" name="cedula" required placeholder="Ingrese la cédula del dueño">
            </div>

            <!-- Nombre de la mascota -->
            <div class="form-group">
                <label for="nombre_mascota">Nombre de la Mascota</label>
                <input type="text" id="nombre_mascota" name="nombre_mascota" required placeholder="Ingrese el nombre">
            </div>

            <!-- Especie -->
            <div class="form-group">
                <label for="especie">Especie</label>
                <select id="especie" name="especie" required>
                    <option value="">Seleccione la especie</option>
                    <option value="Perro">Perro</option>
                    <option value="Gato">Gato</option>
                    <option value="Ave">Ave</option>
                    <option value="Otro">Otro</option>
                </select>
            </div>

            <!-- Raza -->
            <div class="form-group">
                <label for="raza">Raza</label>
                <input type="text" id="raza" name="raza" placeholder="Ingrese la raza (opcional)">
            </div>

            <!-- Edad -->
            <div class="form-group">
                <label for="edad">Edad</label>
                <input type="number" id="edad" name="edad" min="0" placeholder="Ingrese la edad en años">
            </div>

            <!-- Peso -->
            <div class="form-group">
                <label for="peso">Peso (kg)</label>
                <input type="number" id="peso" name="peso" step="0.01" min="0" placeholder="Ingrese el peso" required>
            </div>

            <!-- Esterilizado -->
            <div class="form-group">
                <label for="esterilizado">¿Está esterilizado?</label>
                <select id="esterilizado" name="esterilizado">
                    <option value="">Seleccione una opción</option>
                    <option value="1">Sí</option>
                    <option value="0">No</option>
                </select>
            </div>

            <!-- Botón de envío -->
            <div class="form-group">
            <button class=" btn-back" onclick="window.history.back();">Atras</button>
                <input type="submit" value="Siguiente">
            </div>
        </form>
    </div>
</body>
</html>
