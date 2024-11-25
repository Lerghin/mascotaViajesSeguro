<?php
require 'conection.php';

if (isset($_GET['id_mascota'])) {
    $id_mascota = $_GET['id_mascota'];

    $query = $conn->prepare("DELETE FROM mascotas WHERE id_mascota = ?");
    $query->bind_param("i", $id_mascota);

    if ($query->execute()) {
        // Redirigir de vuelta a la misma página con un mensaje de éxito
        echo "<script>
        alert('Se ha borrado correctamente');
        window.history.back();
    </script>";
        exit;
    } else {
        // Redirigir de vuelta con un mensaje de error
        header("Location: " . $_SERVER['PHP_SELF'] . "?error=Error al eliminar la mascota");
        exit;
    }

    $query->close();
}
$conn->close();
?>
