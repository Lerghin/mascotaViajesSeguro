<?php
// Conexión a la base de datos
$servername = "localhost";
$username = "root";  // Cambia esto si es necesario
$password = "";      // Cambia esto si es necesario
$dbname = "pet-travel";  // Cambia al nombre de tu base de datos

// Crear la conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoger los datos del formulario
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $email = $_POST['email'];
    $contrasena = $_POST['contrasena'];
    $confirmar_contrasena = $_POST['confirmar_contrasena'];

    // Verificar que las contraseñas coinciden
    if ($contrasena !== $confirmar_contrasena) {
        echo "Las contraseñas no coinciden.";
        exit();
    }

    // Hashear la contraseña
    $hashed_password = password_hash($contrasena, PASSWORD_DEFAULT);

    // Preparar la consulta SQL para insertar el nuevo administrador
    $sql = "INSERT INTO administradores (nombre, apellido, email, contrasena) 
            VALUES ('$nombre', '$apellido', '$email', '$hashed_password')";

    // Ejecutar la consulta y verificar si se ejecutó correctamente
    if ($conn->query($sql) === TRUE) {
        echo "<script>
        alert('Datos registrados correctamente.');
        window.location.href = 'dashboardAdmin.php';
    </script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Cerrar la conexión
    $conn->close();
}
?>
