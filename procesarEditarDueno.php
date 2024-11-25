<?php
require 'conection.php';

if (!$conn) {
    die("Error de conexión: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_dueno = $_POST['id_dueno'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $cedula = $_POST['cedula'];
    $telefono = $_POST['telefono'];
    $email = $_POST['email'];
    $direccion = $_POST['direccion'];

    // Validar que los campos obligatorios no estén vacíos
    if (empty($nombre) || empty($apellido) || empty($cedula)) {
        echo "<script>
            alert('Los campos nombre, apellido y cédula son obligatorios.');
            window.history.back();
        </script>";
        exit;
    }

    // Validar formato de correo electrónico
    if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>
            alert('El correo electrónico no es válido.');
            window.history.back();
        </script>";
        exit;
    }

    // Preparar la consulta SQL para actualizar los datos
    $stmt = $conn->prepare("UPDATE dueños SET nombre = ?, apellido = ?, cedula = ?, telefono = ?, email = ?, direccion = ? WHERE id_dueno = ?");
    $stmt->bind_param("ssssssi", $nombre, $apellido, $cedula, $telefono, $email, $direccion, $id_dueno);

    if ($stmt->execute()) {
        echo "<script>
            alert('Datos actualizados correctamente.');
            window.location.href = 'dashboardCliente.php';
        </script>";
    } else {
        echo "<p>Error al actualizar los datos: " . $stmt->error . "</p>";
    }

    $stmt->close();
}

$conn->close();
?>
