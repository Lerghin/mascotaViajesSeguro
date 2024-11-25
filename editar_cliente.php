<?php
require 'conection.php';

if (!$conn) {
    die("Error de conexión: " . $conn->connect_error);
}

// Obtener el ID del dueño desde la URL
$id_dueno = $_GET['id_dueno'];

// Consultar los datos del dueño
$stmt = $conn->prepare("SELECT * FROM dueños WHERE id_dueno = ?");
$stmt->bind_param("i", $id_dueno);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $dueno = $result->fetch_assoc();
} else {
    echo "<script>
        alert('Dueño no encontrado.');
        window.history.back();
    </script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Dueño</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7f6;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .form-container {
            background: #fff;
            border-radius: 10px;
            padding: 20px;
            max-width: 500px;
            width: 100%;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #333;
        }

        label {
            font-weight: bold;
            display: block;
            margin-top: 10px;
        }

        input, textarea {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
            outline: none;
        }

        input[type="submit"], button {
            margin-top: 15px;
            padding: 10px;
            background-color: #507dbc;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        button {
            background-color: #6c757d;
        }

        button:hover {
            background-color: #5a6268;
        }

        input[type="submit"]:hover {
            background-color: #4066a1;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Editar Dueño</h2>
        <form action="procesarEditarDueno.php" method="POST">
            <input type="hidden" name="id_dueno" value="<?php echo $dueno['id_dueno']; ?>">

            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" value="<?php echo $dueno['nombre']; ?>" required>

            <label for="apellido">Apellido:</label>
            <input type="text" id="apellido" name="apellido" value="<?php echo $dueno['apellido']; ?>" required>

            <label for="cedula">Cédula:</label>
            <input type="text" id="cedula" name="cedula" value="<?php echo $dueno['cedula']; ?>" required  readonly>

            <label for="telefono">Teléfono:</label>
            <input type="text" id="telefono" name="telefono" value="<?php echo $dueno['telefono']; ?>">

            <label for="email">Correo Electrónico:</label>
            <input type="email" id="email" name="email" value="<?php echo $dueno['email']; ?>">

            <label for="direccion">Dirección:</label>
            <textarea id="direccion" name="direccion"><?php echo $dueno['direccion']; ?></textarea>

            <input type="submit" value="Guardar Cambios">
            <button type="button" onclick="window.history.back();">Atrás</button>
        </form>
    </div>
</body>
</html>
