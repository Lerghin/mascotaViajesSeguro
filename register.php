<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Dueños</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f7f6;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
           
        }

        .form-container {
            background: #fff;
            margin-top:400px;
            border-radius: 10px;
            padding: 20px;
            max-width: 500px;
            width: 100%;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
            color: #555;
        }

        input, select, textarea {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border-radius: 5px;
            border: 1px solid #ccc;
            margin-top: 5px;
            outline: none;
        }

        input:focus {
            border-color: #3f51b5;
        }

        textarea {
            resize: vertical;
        }

        .form-group input[type="submit"] {
            background-color: #3f51b5;
            color: white;
            cursor: pointer;
            border: none;
            margin-top: 20px;
        }

        .form-group input[type="submit"]:hover {
            background-color: #303f9f;
        }
        .btn-back{
          background-color: #495057 !important;
        }
        button {
            width: 100%;
            padding: 10px;
            margin-top:1rem;
            background-color: #507dbc;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        /* Responsividad */
        @media (max-width: 768px) {
            .form-container {
                padding: 15px;
            }

            input, select, textarea {
                padding: 8px;
            }
        }
    </style>
</head>
<body>

    <div class="form-container">
        <h2>Formulario de Registro de Dueños</h2>
        <form action="procesarFormulario.php" method="POST">
            <!-- Campo nombre -->
            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" id="nombre" name="nombre" required placeholder="Ingrese el nombre">
            </div>

            <!-- Campo apellido -->
            <div class="form-group">
                <label for="apellido">Apellido</label>
                <input type="text" id="apellido" name="apellido" required placeholder="Ingrese el apellido">
            </div>

            <!-- Campo cédula -->
            <div class="form-group">
                <label for="cedula">Cédula</label>
                <input type="text" id="cedula" name="cedula" required placeholder="Ingrese la cédula">
            </div>

            <!-- Campo teléfono -->
            <div class="form-group">
                <label for="telefono">Teléfono</label>
                <input type="text" id="telefono" name="telefono" placeholder="Ingrese el teléfono">
            </div>

            <!-- Campo email -->
            <div class="form-group">
                <label for="email">Correo Electrónico</label>
                <input type="email" id="email" name="email" placeholder="Ingrese el correo electrónico">
            </div>

            <!-- Campo dirección -->
            <div class="form-group">
                <label for="direccion">Dirección</label>
                <textarea id="direccion" name="direccion" required placeholder="Ingrese su dirección"></textarea>
            </div>
             <!-- Campo contraseña -->
             <div class="form-group">
                <label for="contrasena">Contraseña</label>
                <input type="password" id="contrasena" name="contrasena" required placeholder="Ingrese una contraseña">
            </div>

            <!-- Confirmación de contraseña -->
            <div class="form-group">
                <label for="confirmar_contrasena">Confirmar Contraseña</label>
                <input type="password" id="confirmar_contrasena" name="confirmar_contrasena" required placeholder="Confirme la contraseña">
            </div>

            

            <!-- Botón de envío -->
            <div class="form-group">
                <input type="submit" value="Registrar">
                <button class=" btn-back" onclick="window.history.back();">Atras</button>
            </div>
        </form>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
