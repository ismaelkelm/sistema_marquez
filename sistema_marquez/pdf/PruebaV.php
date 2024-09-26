<?php
// Inicia el buffer de salida para evitar problemas con la salida
ob_start();

// Conectar a la base de datos
require_once '../base_datos/db.php';

require('./fpdf.php');

class PDF extends FPDF
{
    // Constructor
    function __construct()
    {
        parent::__construct();
        // Configurar márgenes: 10 mm a la izquierda, arriba y derecha; 10 mm abajo
        $this->SetMargins(10, 10, 10);
        $this->SetAutoPageBreak(true, 10);
    }

    // Cabecera de página
    function Header()
    {
        // Logo de la empresa
        $this->Image('logo.png', 10, 8, 20); // Ajusta la posición y tamaño según sea necesario

        // Título
        $this->SetFont('Arial', 'B', 16);
        $this->Cell(0, 15, utf8_decode('Marquez Comunicaciones'), 0, 1, 'C');

        // Información de contacto
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(0, 10, utf8_decode("Ubicación: calle Falsa 123"), 0, 1);
        $this->Cell(0, 10, utf8_decode("Teléfono: 123-456-789"), 0, 1);
        $this->Cell(0, 10, utf8_decode("Correo: contacto@marquez.com"), 0, 1);
        $this->Cell(0, 10, utf8_decode("Sucursal: Principal"), 0, 1);
        $this->Ln(10);

        // Título de la tabla
        $this->SetTextColor(228, 100, 0);
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(0, 10, utf8_decode("REPORTE DE CLIENTES"), 0, 1, 'C');
        $this->Ln(7);

        // Encabezados de la tabla
        $this->SetFillColor(228, 100, 0);
        $this->SetTextColor(255, 255, 255);
        $this->SetDrawColor(163, 163, 163);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(20, 10, utf8_decode('ID'), 1, 0, 'C', 1);
        $this->Cell(30, 10, utf8_decode('Nombre'), 1, 0, 'C', 1);
        $this->Cell(30, 10, utf8_decode('Apellido'), 1, 0, 'C', 1);
        $this->Cell(30, 10, utf8_decode('Teléfono'), 1, 0, 'C', 1);
        $this->Cell(50, 10, utf8_decode('Correo Electrónico'), 1, 0, 'C', 1);
        $this->Cell(40, 10, utf8_decode('Dirección'), 1, 1, 'C', 1); // Reducido en 10 mm
    }

    // Pie de página
    function Footer()
    {
        // Posicionar a 1,5 cm del final
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        // Número de página
        $this->Cell(0, 10, utf8_decode('Página ') . $this->PageNo(), 0, 0, 'C');
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

// Consultar los datos de la base de datos
$sql = "SELECT * FROM clientes";
$result = $conn->query($sql);

// Mostrar los datos en el PDF
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $pdf->Cell(20, 10, utf8_decode($row['id_clientes']), 1);
        $pdf->Cell(30, 10, utf8_decode($row['nombre']), 1);
        $pdf->Cell(30, 10, utf8_decode($row['apellido']), 1);
        $pdf->Cell(30, 10, utf8_decode($row['telefono']), 1);
        $pdf->Cell(50, 10, utf8_decode($row['correo_electronico']), 1);
        $pdf->Cell(40, 10, utf8_decode($row['direccion']), 1);
        $pdf->Ln();
    }
} else {
    $pdf->Cell(0, 10, 'No hay datos disponibles', 0, 1, 'C');
}

// Cerrar la conexión
$conn->close();

// Termina el buffer de salida y genera el PDF
ob_end_clean();
$pdf->Output('I', 'ReporteClientes.pdf');
?>
