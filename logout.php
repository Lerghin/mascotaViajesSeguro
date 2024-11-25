<?php
// Inicia la sesión
session_start();

// Destruye todas las variables de sesión
session_unset();

// Destruye la sesión
session_destroy();

// Redirige al usuario al formulario de inicio de sesión o a otra página deseada
header("Location: index.html");
exit();
?>