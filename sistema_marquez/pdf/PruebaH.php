<?php

require('./fpdf.php');

class PDF extends FPDF
{
    // Constructor
    function __construct()
    {
        parent::__construct();
        // Configurar márgenes
        $this->SetMargins(10, 10, 10); // Margen izquierdo, superior y derecho (en mm)
        $this->SetAutoPageBreak(true, 10); // Margen inferior (en mm)
    }

    // Cabecera de página
    function Header()
    {
        // Logo de la empresa
        $this->Image('logo.png', 10, 8, 20); // Ajusta la posición y tamaño según sea necesario
        $this->SetFont('Arial', 'B', 16);
        $this->Cell(0, 15, utf8_decode('Marquez Comunicaciones'), 0, 1, 'C');
        $this->SetFont('Arial', 'B', 10);

        // Información de contacto
        $this->Cell(0, 10, utf8_decode("Ubicación:calle "), 0, 1);
        $this->Cell(0, 10, utf8_decode("Teléfono: "), 0, 1);
        $this->Cell(0, 10, utf8_decode("Correo: "), 0, 1);
        $this->Cell(0, 10, utf8_decode("Sucursal: "), 0, 1);
        $this->Ln(10);

        // Titulo de la tabla
        $this->SetTextColor(228, 100, 0);
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(0, 10, utf8_decode("REPORTE DE HABITACIONES"), 0, 1, 'C');
        $this->Ln(7);

        // Campos de la tabla
        $this->SetFillColor(228, 100, 0);
        $this->SetTextColor(255, 255, 255);
        $this->SetDrawColor(163, 163, 163);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(20, 10, utf8_decode('N°'), 1, 0, 'C', 1);
        $this->Cell(30, 10, utf8_decode('NÚMERO'), 1, 0, 'C', 1);
        $this->Cell(30, 10, utf8_decode('TIPO'), 1, 0, 'C', 1);
        $this->Cell(30, 10, utf8_decode('PRECIO'), 1, 0, 'C', 1);
        $this->Cell(60, 10, utf8_decode('CARACTERÍSTICAS'), 1, 0, 'C', 1);
        $this->Cell(20, 10, utf8_decode('ESTADO'), 1, 1, 'C', 1); // Reducido en 10 mm
    }

    // Pie de página
    function Footer()
    {
        // Posicionar a 1,5 cm del final
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        // Número de página
        $this->Cell(0, 10, utf8_decode('Página ') . $this->PageNo() . '/{nb}', 0, 0, 'C');
        // Fecha actual
        $this->SetY(-10);
        $hoy = date('d/m/Y');
        $this->Cell(0, 10, utf8_decode($hoy), 0, 0, 'C');
    }
}

// Crear instancia del PDF
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage(); // Agrega una página en orientación Portrait y tamaño A4

// Establecer fuente y color para el contenido
$pdf->SetFont('Arial', '', 10);
$pdf->SetDrawColor(163, 163, 163);

// Ejemplo de contenido
$i = 1; // Puedes ajustar esto según tus datos
$pdf->Cell(20, 10, utf8_decode($i), 1, 0, 'C');
$pdf->Cell(30, 10, utf8_decode("numero"), 1, 0, 'C');
$pdf->Cell(30, 10, utf8_decode("nombre"), 1, 0, 'C');
$pdf->Cell(30, 10, utf8_decode("precio"), 1, 0, 'C');
$pdf->Cell(60, 10, utf8_decode("info"), 1, 0, 'C');
$pdf->Cell(20, 10, utf8_decode("total"), 1, 1, 'C'); // Reducido en 10 mm
$pdf->Cell(20, 10, utf8_decode($i), 1, 0, 'C');
$pdf->Cell(30, 10, utf8_decode("numero"), 1, 0, 'C');
$pdf->Cell(30, 10, utf8_decode("nombre"), 1, 0, 'C');
$pdf->Cell(30, 10, utf8_decode("precio"), 1, 0, 'C');
$pdf->Cell(60, 10, utf8_decode("info"), 1, 0, 'C');
$pdf->Cell(20, 10, utf8_decode("total"), 1, 1, 'C'); // Reducido en 10 mm
$pdf->Cell(20, 10, utf8_decode($i), 1, 0, 'C');
$pdf->Cell(30, 10, utf8_decode("numero"), 1, 0, 'C');
$pdf->Cell(30, 10, utf8_decode("nombre"), 1, 0, 'C');
$pdf->Cell(30, 10, utf8_decode("precio"), 1, 0, 'C');
$pdf->Cell(60, 10, utf8_decode("info"), 1, 0, 'C');
$pdf->Cell(20, 10, utf8_decode("total"), 1, 1, 'C'); // Reducido en 10 mm

// Salida del PDF
$pdf->Output('I', 'PruebaV.pdf');
?>
