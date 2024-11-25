<?php
require 'conection.php';
session_start();

if (!$conn) {
    die("Error de conexión: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cedula = $_POST['cedula'];
    $contrasena = $_POST['contrasena'];

    // Validar que los campos no estén vacíos
    if (empty($cedula) || empty($contrasena)) {
        echo "<script>
            alert('Por favor, complete todos los campos.');
            window.history.back();
        </script>";
        exit;
    }

    // Preparar la consulta para buscar al usuario por cédula
    $stmt = $conn->prepare("SELECT id_dueno, nombre, contrasena FROM dueños WHERE cedula = ?");
    if ($stmt === false) {
        die("Ocurrió un error inesperado. Por favor, inténtelo más tarde.");
    }

    // Vincular el parámetro
    $stmt->bind_param("s", $cedula);

    // Ejecutar la consulta
    $stmt->execute();
    $resultado = $stmt->get_result();

    // Verificar si el usuario existe
    if ($resultado->num_rows === 1) {
        $usuario = $resultado->fetch_assoc();

        // Verificar la contraseña
        if (password_verify($contrasena, $usuario['contrasena'])) {
            // Guardar datos en la sesión
            $_SESSION['usuario_id'] = $usuario['id_dueno'];
            $_SESSION['nombre'] = $usuario['nombre'];

            echo "<script>
                alert('Inicio de sesión exitoso.');
                window.location.href = 'dashboardCliente.php';
            </script>";
        } else {
            echo "<script>
                alert('Contraseña incorrecta.');
                window.history.back();
            </script>";
        }
    } else {
        echo "<script>
            alert('La cédula no está registrada.');
            window.history.back();
        </script>";
    }

    // Cerrar la declaración
    $stmt->close();
}

// Cerrar la conexión
$conn->close();
?>
