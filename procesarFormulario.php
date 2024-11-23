<?php
require 'conection.php';

if (!$conn) {
    die("Error de conexión: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $telefono = $_POST['telefono'];
    $cedula = $_POST['cedula'];
    $email = $_POST['email'];
    $direccion = $_POST['direccion'];
    $contrasena = $_POST['contrasena'];
    $confirmar_contrasena = $_POST['confirmar_contrasena'];

    // Validar que todos los campos estén llenos
    if (empty($nombre) || empty($apellido) || empty($telefono) || empty($cedula) || empty($email) || empty($direccion) || empty($contrasena) || empty($confirmar_contrasena)) {
        echo "<script>
            alert('Todos los campos son obligatorios.');
            window.history.back();
        </script>";
        exit;
    }

    // Validar que las contraseñas coincidan
    if ($contrasena !== $confirmar_contrasena) {
        echo "<script>
            alert('Las contraseñas no coinciden.');
            window.history.back();
        </script>";
        exit;
    }

    // Validar formato de correo electrónico
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>
            alert('El correo electrónico no es válido.');
            window.history.back();
        </script>";
        exit;
    }

    // Hash de la contraseña antes de almacenarla
    $hashed_contrasena = password_hash($contrasena, PASSWORD_DEFAULT);

    // Preparar la consulta SQL para insertar los datos
    $stmt = $conn->prepare("INSERT INTO dueños (nombre, apellido, cedula, telefono, email, direccion, contrasena) VALUES (?, ?, ?, ?, ?, ?, ?)");

    if ($stmt === false) {
        die("Ocurrió un error inesperado. Por favor, inténtelo más tarde.");
    }

    // Vincular los parámetros
    $stmt->bind_param("sssssss", $nombre, $apellido, $cedula, $telefono, $email, $direccion, $hashed_contrasena);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        echo "<script>
            alert('Datos registrados correctamente.');
            window.location.href = 'formMascota.php';
        </script>";
    } else {
        echo "<p>Error al registrar los datos: " . $stmt->error . "</p>";
    }

    // Cerrar la declaración
    $stmt->close();
}

// Cerrar la conexión
$conn->close();
?>
