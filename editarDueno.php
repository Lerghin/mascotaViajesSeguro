<?php
// Conexión a la base de datos
require 'conection.php';

// Verificar si se ha recibido un ID de dueño
if (!isset($_GET['id_dueno'])) {
    echo "No se ha especificado el ID del dueño.";
    exit();
}

$id_dueno = $_GET['id_dueno'];

// Consultar los datos del dueño
$query = "SELECT * FROM dueños WHERE id_dueno = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_dueno);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "No se encontró el dueño.";
    exit();
}

$dueno = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Dueño</title>
    <link rel="stylesheet" href="css/bootstrap.css" />
    <link href="css/style.css" rel="stylesheet" />
</head>
<body>
    <div class="container">
        <h2>Editar Dueño</h2>
        <form action="actualizarDueno.php" method="POST">
            <input type="hidden" name="id_dueno" value="<?php echo $dueno['id_dueno']; ?>" />
            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo $dueno['nombre']; ?>" required>
            </div>
            <div class="form-group">
                <label for="apellido">Apellido</label>
                <input type="text" class="form-control" id="apellido" name="apellido" value="<?php echo $dueno['apellido']; ?>" required>
            </div>
            <div class="form-group">
                <label for="telefono">Teléfono</label>
                <input type="text" class="form-control" id="telefono" name="telefono" value="<?php echo $dueno['telefono']; ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo $dueno['email']; ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Actualizar</button>
        </form>
    </div>
</body>
</html>
