<?php
// Conexión a la base de datos
require 'conection.php';

// Verificar si el id_mascota está presente en la URL
if (isset($_GET['id_mascota'])) {
    $id_mascota = $_GET['id_mascota'];

    // Consultar los datos del dueño de la mascota
    $query = $conn->prepare("SELECT d.id_dueno, d.nombre, d.apellido, d.telefono, d.email FROM dueños d 
                            JOIN mascotas m ON d.id_dueno = m.id_dueno 
                            WHERE m.id_mascota = ?");
    $query->bind_param("i", $id_mascota);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows > 0) {
        $dueno = $result->fetch_assoc();
    } else {
        echo "No se encontró dueño para esta mascota.";
        exit();
    }

    // Consultar los pagos relacionados con la mascota
    $queryPagos = $conn->prepare("SELECT p.id_pago, p.monto_usd, p.monto_bsf, p.fecha_pago, p.tipo_pago, p.referencia 
                                  FROM pagos p 
                                  WHERE p.id_mascota = ?");
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

   

    $query->close();
    $queryPagos->close();
    $querySeguros->close();
    $conn->close();
} else {
    echo "ID de mascota no proporcionado.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css" />

<!-- estilo de fuentes -->
<link href="https://fonts.googleapis.com/css?family=Dosis:400,500|Poppins:400,700&display=swap" rel="stylesheet">
<!-- Custom styles for this template -->
<link href="css/style.css" rel="stylesheet" />
<!-- responsive style -->
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
            text-align: left;
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

        .btn {
    position: absolute;
    right: 300px;  /* Ajusta el valor para poner el botón más a la derecha o más cerca del borde */
    top: 190px;    /* Ajusta la distancia desde el borde superior si es necesario */
    padding: 10px 15px;
    color: white;
    background-color: #507dbc;
    text-decoration: none;
    border-radius: 8px;
    font-size: 16px;
}

        .btn:hover {
            background-color: #b5e1a5;
            color: #333;
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
       
        <div class="div-btn">
        <a href="pdfCliente.php?id_mascota=<?php echo $id_mascota; ?>" class="btn">Generar PDF</a>
        <div>


        <div class="section details">
            <h2>Datos del Dueño</h2>
            <p><strong>Nombre:</strong>
            <a href="dashboardCliente.php?id_dueno=<?php echo $dueno['id_dueno']; ?>">
                 <?php echo $dueno['nombre'] . ' ' . $dueno['apellido']; ?>
             </a>
          </p>
            <p><strong>Teléfono:</strong> <?php echo $dueno['telefono']; ?></p>
            <p><strong>Email:</strong> <?php echo $dueno['email']; ?></p>
        </div>

        <div class="section pagos">
            <h2>Pagos Realizados</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID Pago</th>
                        <th>Monto USD</th>
                        <th>Monto Bs.</th>
                        <th>Fecha de Pago</th>
                        <th>Tipo de Pago</th>
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

        <div class="section seguros">
            <h2>Seguros Registrados</h2>
            <table>
                <thead>
                    <tr>
                        <th>Numero de Seguro</th>
                        <th>Nombre de la Cobertura</th>
                        <th>Fecha de Inicio</th>
                        <th>Fecha de Fin</th>
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
    </div>
</body>
</html>
