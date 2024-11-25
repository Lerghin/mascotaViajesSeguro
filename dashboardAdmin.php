<?php
// Conexión a la base de datos
require 'conection.php';

// Verificar si el administrador está autenticado
session_start();
if (!isset($_SESSION['admin_id'])) {
    echo "<script>
        alert('Debe iniciar sesión como administrador.');
        window.location.href = 'loginAdmin.php';
    </script>";
    exit();
}

// Obtener los datos del administrador
$id_admin = $_SESSION['admin_id'];
$queryAdmin = $conn->prepare("SELECT nombre, apellido FROM administradores WHERE id_admin = ?");
$queryAdmin->bind_param("i", $id_admin);
$queryAdmin->execute();
$resultAdmin = $queryAdmin->get_result();
$admin = $resultAdmin->fetch_assoc();

$nombreAdmin = $admin['nombre'];
$apellidoAdmin = $admin['apellido'];

// Consultar todos los registros de mascotas, pagos y seguros
$queryMascotas = "SELECT * FROM mascotas";
$queryPagos = "SELECT * FROM pagos";
$querySeguros = "SELECT s.id_seguro, s.id_mascota, s.fecha_inicio, s.fecha_fin, c.nombre_cobertura 
                 FROM seguros s
                 JOIN seguros_coberturas sc ON s.id_seguro = sc.id_seguro
                 JOIN coberturas c ON sc.id_cobertura = c.id_cobertura";

$resultMascotas = $conn->query($queryMascotas);
$resultPagos = $conn->query($queryPagos);
$resultSeguros = $conn->query($querySeguros);

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css" />
    <link href="css/style.css" rel="stylesheet" />
    <title>Panel de Administrador</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            color: #333;
            padding: 0;
            margin: 0;
        }
        .dashboard {
            max-width: 1200px;
            margin: 20px auto;
            background: #ffffff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        h1, h2 {
            text-align: center;
            color: #0056b3;
        }
        table {
            width: 100%;
            margin: 20px 0;
            border-collapse: collapse;
        }
        table th, table td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
        }
        table th {
            background-color: #007bff;
            color: white;
        }
        .btn {
            padding: 8px 15px;
            margin: 5px;
            border-radius: 5px;
            color: white;
            text-decoration: none;
        }
        .btn-edit {
            background-color: #28a745;
        }
        .btn-delete {
            background-color: #dc3545;
        }
        .btn-add {
            background-color: #007bff;
            display: block;
            width: 150px;
            margin: 0 auto 20px auto;
            text-align: center;
        }
    </style>
</head>
<body>
<div class="dashboard">
    <h1>👋 Bienvenido, <?php echo $nombreAdmin . ' ' . $apellidoAdmin; ?></h1>
    
    <header class="header_section">
      <div class="container-fluid">
        <nav class="navbar navbar-expand-lg custom_nav-container ">
          <a class="navbar-brand" href="index.html">
            <img src="images/logo.png" alt="">
            <span>
              Pet Travel
            </span>
          </a>
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>

          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <div class="d-flex mx-auto flex-column flex-lg-row align-items-center">
              <ul class="navbar-nav  ">

                <li class="nav-item active">
                  <a class="nav-link" href="index.html"> Inicio <span class="sr-only">(current)</span></a>
                </li>

                <li class="nav-item">
                  <a class="nav-link" href="dog.html"> Perros</a>
                </li>

                <li class="nav-item">
                  <a class="nav-link" href="cat.html"> Gatos</a>
                </li>

                <li class="nav-item">
                  <a class="nav-link" href="registrarAdmin.php"> Registrar Admin </a>
                </li>

                <li class="nav-item">
                <li class="nav-item"><a class="nav-link" href="logout.php">Cerrar Sesión</a></li>
                </li>
   
              <form class="form-inline my-2 my-lg-0 ml-0 ml-lg-4 mb-3 mb-lg-0">

              </form>
            </div>
            <div class="quote_btn-container  d-flex justify-content-center">
              
            </div>
          </div>
        </nav>
      </div>
    </header>

    <!-- Sección de Mascotas -->
    <div class="section">
        <h2>Mascotas</h2>
        <a href="formMascota.php" class="btn btn-add">Agregar Mascota</a>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Especie</th>
                    <th>Raza</th>
                    <th>Edad</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($mascota = $resultMascotas->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $mascota['id_mascota']; ?></td>
                        <td><?php echo $mascota['nombre_mascota']; ?></td>
                        <td><?php echo $mascota['especie']; ?></td>
                        <td><?php echo $mascota['raza']; ?></td>
                        <td><?php echo $mascota['edad']; ?></td>
                        <td>
                            <a href="editarMascota.php?id_mascota=<?php echo $mascota['id_mascota']; ?>" class="btn btn-edit">Editar</a>
                            <a href="eliminarMascota.php?id_mascota=<?php echo $mascota['id_mascota']; ?>" class="btn btn-delete">Eliminar</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Sección de Seguros -->
    <div class="section">
        <h2>Seguros</h2>
        <a href="formSeguros.php" class="btn btn-add">Agregar Seguro</a>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>ID Mascota</th>
                    <th>Cobertura</th>
                    <th>Fecha Inicio</th>
                    <th>Fecha Fin</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($seguro = $resultSeguros->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $seguro['id_seguro']; ?></td>
                        <td><?php echo $seguro['id_mascota']; ?></td>
                        <td><?php echo $seguro['nombre_cobertura']; ?></td>
                        <td><?php echo $seguro['fecha_inicio']; ?></td>
                        <td><?php echo $seguro['fecha_fin']; ?></td>
                        <td>
                            <a href="editarSeguro.php?id_seguro=<?php echo $seguro['id_seguro']; ?>" class="btn btn-edit">Editar</a>
                            <a href="eliminarSeguro.php?id_seguro=<?php echo $seguro['id_seguro']; ?>" class="btn btn-delete">Eliminar</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Sección de Pagos -->
    <div class="section">
        <h2>Pagos</h2>
        <a href="formPago.php" class="btn btn-add">Agregar Pago</a>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>ID Mascota</th>
                    <th>Monto (USD)</th>
                    <th>Monto (Bs)</th>
                    <th>Fecha</th>
                    <th>Referencia</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($pago = $resultPagos->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $pago['id_pago']; ?></td>
                        <td><?php echo $pago['id_mascota']; ?></td>
                        <td><?php echo $pago['monto_usd']; ?></td>
                        <td><?php echo $pago['monto_bsf']; ?></td>
                        <td><?php echo $pago['fecha_pago']; ?></td>
                        <td><?php echo $pago['referencia']; ?></td>
                        <td>
                            <a href="editarPago.php?id_pago=<?php echo $pago['id_pago']; ?>" class="btn btn-edit">Editar</a>
                            <a href="eliminarPago.php?id_pago=<?php echo $pago['id_pago']; ?>" class="btn btn-delete">Eliminar</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
