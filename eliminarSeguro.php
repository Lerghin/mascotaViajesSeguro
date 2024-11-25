<?php
require 'conection.php';

if (isset($_GET['id_seguro'])) {
    $id_seguro = $_GET['id_seguro'];

    // Actualizamos los pagos para que no apunten al seguro
    $updatePagosQuery = $conn->prepare("UPDATE pagos SET id_seguro = NULL WHERE id_seguro = ?");
    $updatePagosQuery->bind_param("i", $id_seguro);

    if ($updatePagosQuery->execute()) {
        // Ahora eliminamos el seguro
        $deleteSeguroQuery = $conn->prepare("DELETE FROM seguros WHERE id_seguro = ?");
        $deleteSeguroQuery->bind_param("i", $id_seguro);

        if ($deleteSeguroQuery->execute()) {
            // Redirigir con mensaje de éxito
            echo "<script>
            alert('Seguro actualizado y eliminado correctamente');
            window.history.back();
            </script>";
            exit;
        } else {
            // Si ocurre un error al eliminar el seguro
            echo "<script>
            alert('Error al eliminar el seguro');
            window.history.back();
            </script>";
            exit;
        }
    } else {
        // Si ocurre un error al actualizar los pagos
        echo "<script>
        alert('Error al actualizar los pagos');
        window.history.back();
        </script>";
        exit;
    }

    $updatePagosQuery->close();
    $deleteSeguroQuery->close();
}

$conn->close();
?>
