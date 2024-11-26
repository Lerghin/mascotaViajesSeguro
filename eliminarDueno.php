<?php
// Conexión a la base de datos
require 'conection.php';

// Verificar si se ha recibido un ID de dueño
if (!isset($_GET['id_dueno'])) {
    echo "No se ha especificado el ID del dueño.";
    exit();
}

$id_dueno = $_GET['id_dueno'];

// Eliminar al dueño
$query = "DELETE FROM dueños WHERE id_dueno = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_dueno);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo "<script>
        alert('Dueño eliminado correctamente.');
        window.location.href = 'dashboard.php';
    </script>";
} else {
    echo "<script>
        alert('Error al eliminar al dueño.');
        window.location.href = 'dashboardAdmin.php';
    </script>";
}

$stmt->close();
$conn->close();
?>
