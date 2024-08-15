<?php
require('fpdf/fpdf.php'); // Ajusta la ruta a tu archivo fpdf.php
require('fpdf/NumeroALetras.php'); // Ajusta la ruta a tu archivo NumeroALetras.php

class PDF extends FPDF
{
    function Header()
    {
        // Imagen en la parte superior
        $this->Image('images/banner3.png', 15, 5, 50); // Ajusta la ruta y dimensiones de la imagen
        $this->Ln(20); // Espacio después de la imagen
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(0, 10, 'Ticket de Venta', 0, 1, 'C');
        $this->Ln(2);
    }

    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Gracias por su compra!', 0, 0, 'C');
    }
}

// Crear instancia de PDF con tamaño personalizado
$pdf = new PDF('P', 'mm', array(80, 250)); // Ancho: 90 mm, Alto: 250 mm
$pdf->AddPage();
$pdf->SetFont('Arial', '', 8); // Tamaño de fuente reducido para ajustar mejor el contenido

// Datos del ticket
$fecha = date('d/m/Y');
$hora = date('H:i:s');
$cliente = 'Juan Pérez';
$nota_venta = '00123';

// Datos de los artículos
$articulos = [
    ['Articulo 1', 2, 150.00],
    ['Articulo 2', 1, 300.00],
    ['Articulo 3', 3, 75.00],
];

// Cálculo de totales
$subtotal = 0;
foreach ($articulos as $articulo) {
    $subtotal += $articulo[1] * $articulo[2];
}
$iva = $subtotal * 0.16; // 16% de IVA
$total = $subtotal + $iva;

// Imprimir información del ticket
$pdf->Cell(0, 8, "Fecha: $fecha");
$pdf->Ln();
$pdf->Cell(0, 8, "Hora: $hora");
$pdf->Ln();
$pdf->Cell(0, 8, "Cliente: $cliente");
$pdf->Ln();
$pdf->Cell(0, 8, "Nota de Venta: $nota_venta");
$pdf->Ln();
$pdf->Cell(0, 8, '-------------------------------------------------------------');
$pdf->Ln();

$pdf->SetFont('Arial', 'B', 8); // Fuente en negrita para los encabezados de las columnas
$pdf->Cell(15, 8, 'Articulo', 0, 0, 'L');
$pdf->Cell(15, 8, 'Cant', 0, 0, 'C');
$pdf->Cell(15, 8, 'Precio', 0, 0, 'C');
$pdf->Cell(15, 8, 'Total', 0, 1, 'C');
$pdf->Cell(0, 8, '------------------------------------------------------------');
$pdf->Ln();

// Volver a configurar la fuente para el contenido
$pdf->SetFont('Arial', '', 8); 

foreach ($articulos as $articulo) {
    $nombre = $articulo[0];
    $cantidad = $articulo[1];
    $precio_unitario = number_format($articulo[2], 2);
    $total_articulo = number_format($cantidad * $articulo[2], 2);
    
    // Ajustar nombre del artículo para que se ajuste en una sola línea
    $pdf->Cell(15, 8, $nombre, 0, 0, 'L');
    $pdf->Cell(15, 8, $cantidad, 0, 0, 'C');
    $pdf->Cell(15, 8, "$".$precio_unitario, 0, 0, 'C');
    $pdf->Cell(15, 8, "$".$total_articulo, 0, 1, 'C');
}

$pdf->Cell(0, 8, '-------------------------------------------------------------');
$pdf->Ln();
$pdf->Cell(45, 8, 'Subtotal', 0, 0, 'L');
$pdf->Cell(15, 8, '', 0, 0, 'C');
$pdf->Cell(15, 8, '', 0, 0, 'C');
$pdf->Cell(20, 8, "$".number_format($subtotal, 2), 0, 1, 'C');
$pdf->Cell(45, 8, 'IVA (16%)', 0, 0, 'L');
$pdf->Cell(15, 8, '', 0, 0, 'C');
$pdf->Cell(15, 8, '', 0, 0, 'C');
$pdf->Cell(20, 8, "$".number_format($iva, 2), 0, 1, 'C');
$pdf->Cell(0, 8, '-------------------------------------------------------------');
$pdf->Ln();
$pdf->Cell(45, 8, 'Total', 0, 0, 'L');
$pdf->Cell(15, 8, '', 0, 0, 'C');
$pdf->Cell(15, 8, '', 0, 0, 'C');
$pdf->Cell(20, 8, "$".number_format($total, 2), 0, 1, 'C');

// Convertir total a letras
$numeroALetras = new NumeroALetras();
$totalLetras = $numeroALetras->convertir(number_format($total, 2)); // Formatear antes de convertir

$pdf->Ln(10);
$pdf->MultiCell(0, 8, "Total en letras: ".$totalLetras);
$pdf->Output();
?>
