<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Administrador</title>
    <style>
        /* Estilos Generales */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f1f1f1;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: #fff;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        h2 {
            text-align: center;
            color: #4A90E2;
            margin-bottom: 20px;
        }

        label {
            font-size: 14px;
            color: #333;
            margin-bottom: 5px;
            display: block;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 2px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
            background-color: #f9f9f9;
            font-size: 14px;
            transition: border-color 0.3s ease;
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="password"]:focus {
            border-color: #4A90E2;
            outline: none;
        }

        input[type="submit"] {
            background-color: #4A90E2;
            color: #fff;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            width: 100%;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #357ABD;
        }

        .error {
            color: #ff4d4d;
            font-size: 14px;
            margin-top: 10px;
            text-align: center;
        }

        .success {
            color: #4CAF50;
            font-size: 14px;
            margin-top: 10px;
            text-align: center;
        }

        /* Responsivo */
        @media screen and (max-width: 480px) {
            .container {
                width: 90%;
                padding: 20px;
            }

            h2 {
                font-size: 18px;
            }

            input[type="text"],
            input[type="email"],
            input[type="password"] {
                font-size: 16px;
            }

            input[type="submit"] {
                font-size: 18px;
            }
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Registro de Administrador</h2>
        <form action="registro_admin.php" method="POST">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" required><br>

            <label for="apellido">Apellido:</label>
            <input type="text" id="apellido" name="apellido" required><br>

            <label for="email">Correo Electrónico:</label>
            <input type="email" id="email" name="email" required><br>

            <label for="contrasena">Contraseña:</label>
            <input type="password" id="contrasena" name="contrasena" required><br>

            <label for="confirmar_contrasena">Confirmar Contraseña:</label>
            <input type="password" id="confirmar_contrasena" name="confirmar_contrasena" required><br>

            <input type="submit" value="Registrar">
        </form>

        <?php
        // Mensajes de error o éxito (si los hay)
        if (isset($error)) {
            echo "<div class='error'>$error</div>";
        }
        if (isset($success)) {
            echo "<div class='success'>$success</div>";
        }
        ?>
    </div>

</body>
</html>
