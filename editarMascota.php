<?php
// Conexión a la base de datos
$conn = new mysqli('localhost', 'root', '', 'pet-travel');
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Obtener datos de la mascota para editar
$id_mascota = $_GET['id_mascota'];
$sql = "SELECT * FROM mascotas WHERE id_mascota = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $id_mascota);
$stmt->execute();
$result = $stmt->get_result();
$mascota = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Guardar cambios de la mascota
    $nombre_mascota = $_POST['nombre_mascota'];
    $especie = $_POST['especie'];
    $raza = $_POST['raza'];
    $edad = $_POST['edad'];
    $peso = $_POST['peso'];
    $esterilizado = isset($_POST['esterilizado']) ? 1 : 0;

    $update_sql = "UPDATE mascotas SET nombre_mascota=?, especie=?, raza=?, edad=?, peso=?, esterilizado=? WHERE id_mascota=?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param('sssidsi', $nombre_mascota, $especie, $raza, $edad, $peso, $esterilizado, $id_mascota);

    if ($update_stmt->execute()) {
        echo "<script>alert('Mascota actualizada exitosamente.'); window.location.href='dashboardCliente.php';</script>";
    } else {
        echo "<script>alert('Error al actualizar la mascota.');</script>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Mascota</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f8fd;
            color: #333;
        }

        .container {
            max-width: 600px;
            margin: 40px auto;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            padding: 20px;
        }

        h1 {
            font-size: 24px;
            color: #5d5c61;
            text-align: center;
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
        }

        input[type="text"], input[type="number"], select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        input[type="checkbox"] {
            margin-right: 10px;
        }

        .btn {
            display: inline-block;
            text-align: center;
            background-color: #a8d0e6;
            color: #333;
            padding: 10px 20px;
            margin: 10px 0;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            text-decoration: none;
        }

        .btn:hover {
            background-color: #91c3d8;
        }

        .btn-secondary {
            background-color: #f76c6c;
            color: #fff;
        }

        .btn-secondary:hover {
            background-color: #f55454;
        }

        .btn-container {
            display: flex;
            justify-content: space-between;
        }

        @media (max-width: 480px) {
            .btn-container {
                flex-direction: column;
                gap: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Editar Mascota</h1>
        <form method="POST">
            <label for="nombre_mascota">Nombre de la Mascota</label>
            <input type="text" id="nombre_mascota" name="nombre_mascota" value="<?= htmlspecialchars($mascota['nombre_mascota']) ?>" required>

            <label for="especie">Especie</label>
            <select id="especie" name="especie" required>
                <option value="Perro" <?= $mascota['especie'] === 'Perro' ? 'selected' : '' ?>>Perro</option>
                <option value="Gato" <?= $mascota['especie'] === 'Gato' ? 'selected' : '' ?>>Gato</option>
            </select>

            <label for="raza">Raza</label>
            <input type="text" id="raza" name="raza" value="<?= htmlspecialchars($mascota['raza']) ?>">

            <label for="edad">Edad (en años)</label>
            <input type="number" id="edad" name="edad" value="<?= htmlspecialchars($mascota['edad']) ?>" required>

            <label for="peso">Peso (en kg)</label>
            <input type="number" step="0.01" id="peso" name="peso" value="<?= htmlspecialchars($mascota['peso']) ?>" required>

            <label>
                <input type="checkbox" id="esterilizado" name="esterilizado" <?= $mascota['esterilizado'] ? 'checked' : '' ?>>
                ¿Está esterilizado?
            </label>

            <div class="btn-container">
                <a href="index.php" class="btn btn-secondary">Atrás</a>
                <button type="submit" class="btn">Guardar Cambios</button>
            </div>
        </form>
    </div>
</body>
</html>
