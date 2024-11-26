<?php
// Conexión a la base de datos
require 'conection.php';

// Verificar si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener los datos del formulario
    $id_dueno = $_POST['id_dueno'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $telefono = $_POST['telefono'];
    $email = $_POST['email'];

    // Consultar si el dueño existe (esto ya fue comprobado en editarDueno.php)
    $query = "SELECT * FROM dueños WHERE id_dueno = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id_dueno);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Actualizar los datos del dueño
        $updateQuery = "UPDATE dueños SET nombre = ?, apellido = ?, telefono = ?, email = ? WHERE id_dueno = ?";
        $stmtUpdate = $conn->prepare($updateQuery);
        $stmtUpdate->bind_param("ssssi", $nombre, $apellido, $telefono, $email, $id_dueno);
        $stmtUpdate->execute();

        // Verificar si la actualización fue exitosa
        if ($stmtUpdate->affected_rows > 0) {
            // Redirigir al dashboardAdmin.php si la actualización fue exitosa
            echo "<script>
                alert('Dueño actualizado correctamente.');
                window.location.href = 'dashboardAdmin.php'; // Redirigir al dashboard
            </script>";
        } else {
            echo "<script>
                alert('No se realizaron cambios.');
                window.location.href = 'dashboardAdmin.php'; // Redirigir al dashboard
            </script>";
        }

        $stmtUpdate->close();
    } else {
        echo "El dueño no existe.";
    }

    // Cerrar la conexión
    $stmt->close();
    $conn->close();
} else {
    echo "Acceso no válido.";
    exit();
}
?>
