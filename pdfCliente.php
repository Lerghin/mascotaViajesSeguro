<?php
require 'conection.php';
require('libs/fpdf/fpdf.php'); // Asegúrate de tener FPDF incluido

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

// Crear el PDF
// Crear un nuevo objeto FPDF
$pdf = new FPDF();
$pdf->AddPage();

// Establecer fuente
$pdf->SetFont('Arial', 'B', 14);

// Título del documento
$pdf->Cell(0, 10, 'Datos del Cliente', 0, 1, 'C');

// Espacio
$pdf->Ln(10);

// Datos del dueño
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, 'Nombre: ' . $dueno['nombre'] . ' ' . $dueno['apellido'], 0, 1);
$pdf->Cell(0, 10, 'Telefono: ' . $dueno['telefono'], 0, 1);
$pdf->Cell(0, 10, 'Email: ' . $dueno['email'], 0, 1);

// Espacio
$pdf->Ln(10);

// Título de la tabla de pagos
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, 'Pagos Realizados', 0, 1, 'C');

// Establecer el color de fondo para las celdas de la tabla (colores pastel)
$pdf->SetFillColor(250, 216, 216);  // Color pastel para encabezado de la tabla

// Encabezados de la tabla
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(30, 10, 'ID Pago', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Monto USD', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Monto Bs.', 1, 0, 'C', true);
$pdf->Cell(40, 10, 'Fecha de Pago', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Tipo de Pago', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Referencia', 1, 1, 'C', true);

// Volver al color blanco para las celdas de datos
$pdf->SetFillColor(255, 255, 255);

// Mostrar los pagos
$pdf->SetFont('Arial', '', 10);
while ($pago = $resultPagos->fetch_assoc()) {
    $pdf->Cell(30, 10, $pago['id_pago'], 1, 0, 'C', true);
    $pdf->Cell(30, 10, $pago['monto_usd'], 1, 0, 'C', true);
    $pdf->Cell(30, 10, $pago['monto_bsf'], 1, 0, 'C', true);
    $pdf->Cell(40, 10, $pago['fecha_pago'], 1, 0, 'C', true);
    $pdf->Cell(30, 10, $pago['tipo_pago'], 1, 0, 'C', true);
    $pdf->Cell(30, 10, $pago['referencia'], 1, 1, 'C', true);
}

// Espacio
$pdf->Ln(10);

// Título de la tabla de seguros
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, 'Seguros Registrados', 0, 1, 'C');

// Establecer el color de fondo para las celdas de la tabla de seguros
$pdf->SetFillColor(250, 216, 216);  // Color pastel para encabezado de la tabla

// Encabezados de la tabla de seguros
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(40, 10, 'Numero de Seguro', 1, 0, 'C', true);
$pdf->Cell(50, 10, 'Cobertura', 1, 0, 'C', true);
$pdf->Cell(40, 10, 'Fecha de Inicio', 1, 0, 'C', true);
$pdf->Cell(40, 10, 'Fecha de Fin', 1, 1, 'C', true);

// Volver al color blanco para las celdas de datos
$pdf->SetFillColor(255, 255, 255);

// Mostrar los seguros
$pdf->SetFont('Arial', '', 10);
while ($seguro = $resultSeguros->fetch_assoc()) {
    $pdf->Cell(40, 10, $seguro['id_seguro'], 1, 0, 'C', true);
    $pdf->Cell(50, 10, $seguro['nombre_cobertura'], 1, 0, 'C', true);
    $pdf->Cell(40, 10, $seguro['fecha_inicio'], 1, 0, 'C', true);
    $pdf->Cell(40, 10, $seguro['fecha_fin'], 1, 1, 'C', true);
}

// Salida del PDF
$pdf->Output();
?>
