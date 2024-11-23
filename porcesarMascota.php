<?php
require 'conection.php'; // Archivo de conexión a la base de datos

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Obtener los datos del formulario
    $cedula = $_POST['cedula'];
    $nombre_mascota = $_POST['nombre_mascota'];
    $especie = $_POST['especie'];
    $raza = $_POST['raza'];
    $edad = $_POST['edad'];

    // Convertir el peso a un formato adecuado, reemplazando la coma por punto si es necesario
    $peso = str_replace(",", ".", $_POST['peso']); // Reemplazar coma por punto

    // Validar que el peso sea un número flotante válido
    if (!is_numeric($peso)) {
        echo "<script>alert('El peso debe ser un número válido.'); window.history.back();</script>";
        exit(); // Detener la ejecución si el peso no es válido
    }

    // Obtener el valor de esterilizado
    $esterilizado = $_POST['esterilizado'];

    // Verificar que el dueño existe en la base de datos
    $query = $conn->prepare("SELECT id_dueno FROM dueños WHERE cedula = ?");
    $query->bind_param("s", $cedula);
    $query->execute();
    $query->bind_result($id_dueno);

    if ($query->fetch()) { // Si se encuentra el dueño
        // Cerrar el resultado de la consulta para evitar conflictos con la siguiente consulta
        $query->free_result();

        // Insertar la mascota en la base de datos
        $stmt = $conn->prepare("INSERT INTO mascotas (nombre_mascota, especie, raza, edad, peso, esterilizado, id_dueno) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssidsi", $nombre_mascota, $especie, $raza, $edad, $peso, $esterilizado, $id_dueno);

        

        if ($stmt->execute()) {
            // Obtener el ID de la mascota recién registrada
            $id_mascota = $stmt->insert_id;

            // Guardar el ID de la mascota en localStorage y redirigir
            echo "<script>
                localStorage.setItem('id_mascota', '$id_mascota');
                alert('Mascota registrada correctamente.');
                window.location.href = 'formSeguros.php'; // O la página que necesites
            </script>";
        } else {
            // Si ocurre un error al registrar la mascota
            echo "Error al registrar la mascota: " . $stmt->error;
        }
        $stmt->close();
    } else {
        // Si el dueño no se encuentra
        echo "<script>alert('Dueño no encontrado. Verifique la cédula.'); window.history.back();</script>";
    }

    $query->close();
    $conn->close();
}
?>
