<?php
// Conexión a la base de datos
require 'conection.php';

// Verificar si el cliente está autenticado
session_start();
if (!isset($_SESSION['usuario_id'])) {
    echo "<script>
        alert('Debe iniciar sesión primero.');
        window.location.href = 'login.php';
    </script>";
    exit();
}

// Obtener el ID del dueño desde la sesión
$id_dueno = $_SESSION['usuario_id'];

// Obtener los datos del cliente
$queryCliente = $conn->prepare("SELECT nombre, apellido FROM dueños WHERE id_dueno = ?");
$queryCliente->bind_param("i", $id_dueno);
$queryCliente->execute();
$resultCliente = $queryCliente->get_result();
$cliente = $resultCliente->fetch_assoc();

$nombreCliente = $cliente['nombre'];
$apellidoCliente = $cliente['apellido'];

// Consultar todas las mascotas asociadas al dueño
$queryMascotas = $conn->prepare("SELECT id_mascota, nombre_mascota, raza, especie, edad, peso FROM mascotas WHERE id_dueno = ?");
$queryMascotas->bind_param("i", $id_dueno);
$queryMascotas->execute();
$resultMascotas = $queryMascotas->get_result();

// Verificar si se seleccionó una mascota específica
if (isset($_GET['id_mascota'])) {
    $id_mascota = $_GET['id_mascota'];

    // Consultar los pagos relacionados con la mascota
    $queryPagos = $conn->prepare("SELECT id_pago, monto_usd, monto_bsf, fecha_pago, tipo_pago, referencia 
                                  FROM pagos WHERE id_mascota = ?");
    $queryPagos->bind_param("i", $id_mascota);
    $queryPagos->execute();
    $resultPagos = $queryPagos->get_result();

   
     // Consultar los seguros asociados a la mascota
     $querySeguros = $conn->prepare("SELECT s.id_seguro, s.fecha_inicio, s.fecha_fin, c.nombre_cobertura 
     FROM seguros s
     JOIN seguros_coberturas sc ON s.id_seguro = sc.id_seguro
     JOIN coberturas c ON sc.id_cobertura = c.id_cobertura
     WHERE s.id_mascota = ?");
         $querySeguros->bind_param("i", $id_mascota);
     $querySeguros->execute();
     $resultSeguros = $querySeguros->get_result();
 
} else {
    $id_mascota = null;
    $resultPagos = null;
    $resultSeguros = null;
}

// Cerrar conexiones
$queryMascotas->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css" />
    <link href="css/style.css" rel="stylesheet" />
    <link href="css/responsive.css" rel="stylesheet" />
    <title>Dashboard Cliente</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #fefefe;
            color: #444;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .dashboard {
            max-width: 1200px;
            margin: 20px auto;
            background: #ffffff;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1, h2 {
            text-align: center;
            color: #507dbc;
        }

        .section {
            margin-top: 30px;
        }

        .section h2 {
            font-size: 20px;
            margin-bottom: 15px;
            border-bottom: 2px solid #f3d4d4;
            display: inline-block;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }

        table th, table td {
            text-align: center;
            padding: 10px;
            border: 1px solid #f3d4d4;
        }

        table th {
            background-color: #fad4d4;
            color: #444;
        }

        table tr:nth-child(even) {
            background-color: #f9f6f6;
        }

        p {
            font-size: 16px;
            line-height: 1.5;
        }

        .details p {
            margin: 5px 0;
        }

      

        .btn:hover {
            background-color: #b5e1a5;
            color: #333;
        }
        .btn-1 {
            padding: 5px 10px;
            margin: 5px;
            color: #fff;
            background-color: #48c9b0 ;
            text-decoration: none;
            border-radius: 4px;
        }
        .btn:hover {
            background-color: #6aa1e2;
        }
        .btn-danger {
            background-color: #d9534f;
        }
        .btn-danger:hover {
            background-color: #e57373;
        }
       
    </style>
</head>
<body>
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
                  <a class="nav-link" href="contacto.html"> Contacto </a>
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

<div class="dashboard">
<h1>👋 Bienvenido, <?php echo $nombreCliente . ' ' . $apellidoCliente; ?></h1>
<a href="editar_cliente.php?id_dueno=<?php echo $id_dueno; ?>" class="btn-1 text-center">Editar Datos</a>
    
    <!-- Sección de Mascotas -->
    <div class="section">
        <h2>Mascotas</h2>
        <h6> <a href="formMascota.php" class="btn btn-success">Agregar Mascota/seguro/Pago</a></h6>
        <table>
            <thead>
                <tr>
                    <th>Cod. Mascota</th>
                    <th>Nombre</th>
                    <th>Raza</th>
                    <th>Edad(años)</th>
                    <th>Especie</th>
                    <th>Peso (KG)</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($mascota = $resultMascotas->fetch_assoc()): ?>
                    <tr>
                        <td  ><?php echo $mascota['id_mascota']; ?></td>
                        <td><?php echo $mascota['nombre_mascota']; ?></td>
                        <td><?php echo $mascota['raza']; ?></td>
                        <td><?php echo $mascota['edad']; ?></td>
                        <td><?php echo $mascota['especie']; ?></td>
                        <td><?php echo $mascota['peso']; ?></td>
                        <td>
                            <a href="?id_mascota=<?php echo $mascota['id_mascota']; ?>" class="btn-1">Ver Detalles de Procesos</a>
                            <a href="editarMascota.php?id_mascota=<?php echo $mascota['id_mascota']; ?>" class="btn-1">Editar Mascotas</a>
                          
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Sección de Seguros -->
    <?php if ($id_mascota && $resultSeguros): ?>
        <div class="section">
            <h2 id="mascotaIdHeader">Seguros para Mascota Nro: <?php echo $id_mascota; ?> </h2>
            <h6>   <a href="formSeguros.php" class="btn btn-success">+ Agregar Seguro</a></h6>
            <table>
                <thead>
                    <tr>
                        <th>Cod. Seguro</th>
                        <th>Cobertura</th>
                        <th>Fecha Inicio</th>
                        <th>Fecha Fin</th>
                       
                    </tr>
                </thead>
                <tbody>
                    <?php while ($seguro = $resultSeguros->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $seguro['id_seguro']; ?></td>
                            <td><?php echo $seguro['nombre_cobertura']; ?></td>
                            <td><?php echo $seguro['fecha_inicio']; ?></td>
                            <td><?php echo $seguro['fecha_fin']; ?></td>
                          
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

    <!-- Sección de Pagos -->
    <?php if ($id_mascota && $resultPagos): ?>
        <div class="section">
            <h2>Pagos para Mascota Nro: <?php echo $id_mascota; ?></h2>
            <h6> <a href="formPago2.php"class="btn btn-success">+ Agregar Pago</a></h6>
            <table>
                <thead>
                    <tr>
                        <th>Nro. Pago</th>
                        <th>Monto USD</th>
                        <th>Monto Bs.</th>
                        <th>Fecha</th>
                        <th>Tipo</th>
                        <th>Referencia</th>
                      
                    </tr>
                </thead>
                <tbody>
                    <?php while ($pago = $resultPagos->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $pago['id_pago']; ?></td>
                            <td><?php echo $pago['monto_usd']; ?></td>
                            <td><?php echo $pago['monto_bsf']; ?></td>
                            <td><?php echo $pago['fecha_pago']; ?></td>
                            <td><?php echo $pago['tipo_pago']; ?></td>
                            <td><?php echo $pago['referencia']; ?></td>
                          
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
<script>
    // Obtener el ID de la mascota desde el encabezado h2 y guardarlo en localStorage
    window.onload = function() {
        var mascotaIdHeader = document.getElementById("mascotaIdHeader");
        var idMascota = mascotaIdHeader ? mascotaIdHeader.textContent.match(/\d+/)[0] : null;
        
        if (idMascota) {
            localStorage.setItem('id_mascota', idMascota);
        }
    }
</script>
</body>
</html>
