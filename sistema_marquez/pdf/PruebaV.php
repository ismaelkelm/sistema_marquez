<?php
require('../pdf/fpdf.php');
require_once('../base_datos/db.php');

// Consultas a la base de datos
$query_tipo_pago = "SELECT id_tipo_de_pago, descripcion_de_pago FROM tipo_de_pago";
$result_tipo_pago = mysqli_query($conn, $query_tipo_pago);

$query_tipo_comprobante = "SELECT id_tipo_comprobante, tipo_comprobante FROM tipo_comprobante";
$result_tipo_comprobante = mysqli_query($conn, $query_tipo_comprobante);

$query_accesorios_componentes = "SELECT id_accesorios_y_componentes, nombre, precio FROM accesorios_y_componentes";
$result_accesorios_componentes = mysqli_query($conn, $query_accesorios_componentes);

$query_clientes = "SELECT id_clientes, nombre, apellido, cuit, direccion FROM clientes"; // Incluyendo CUIT y dirección
$result_clientes = mysqli_query($conn, $query_clientes);

$query_pedidos_de_reparacion = "SELECT id_pedidos_de_reparacion, fecha_de_pedido, observacion FROM pedidos_de_reparacion";
$result_pedidos_de_reparacion = mysqli_query($conn, $query_pedidos_de_reparacion);

$query_detalle_factura = "SELECT id_detalle_factura, cantidad_venta, precio_unitario_V, id_accesorios_y_componentes FROM detalle_factura";
$result_detalle_factura = mysqli_query($conn, $query_detalle_factura);

$query_cabecera_factura = "
    SELECT 
        id_cabecera_factura, 
        fecha_factura, 
        subtotal_factura, 
        impuestos, 
        total_factura, 
        id_clientes, 
        id_usuario, 
        id_operacion, 
        id_tipo_comprobante, 
        id_tipo_de_pago, 
        id_pedido_reparacion 
    FROM 
        cabecera_factura
";

$result_cabecera_factura = mysqli_query($conn, $query_cabecera_factura);

// Clase PDF
class PDF extends FPDF
{
    // Constructor
    function __construct()
    {
        parent::__construct();
        $this->SetMargins(10, 10, 10); // Set page margins
        $this->SetAutoPageBreak(true, 10); // Enable auto page breaks
    }

    // Header of the invoice
    function Header()
    {
        // Título "Original"
        $this->SetFont('Arial', 'I', 10);
        $this->SetXY(10, 10); // Posicionar el texto "Original" en la parte superior del primer recuadro
        $this->Cell(190, 10, 'Original', 0, 0, 'C'); // Alinear en el centro del recuadro
        $this->Ln(5);
        
        // Crear recuadro para "Original"
        $this->Rect(10, 10, 190, 10);  // X, Y, Ancho, Alto
        
        // Posición Y para el siguiente recuadro
        $y = 20; // Cambiar esto si es necesario para otros elementos

        // Título "A"
        $this->SetFont('Arial', 'B', 16);
        $titulo = 'A';
        $subtitulo = '001'; // Nuevo texto a agregar
        $tituloWidth = $this->GetStringWidth($titulo); // Obtener el ancho del texto
        $subtituloWidth = $this->GetStringWidth($subtitulo); // Obtener el ancho del nuevo texto

        // Aumentar el ancho total del recuadro
        $totalWidth = max($tituloWidth, $subtituloWidth) + 5; // Agregamos un margen adicional de 10
        
        $x = 10 + (190 - $totalWidth) / 2; // Calcular la posición X para centrar el recuadro

        // Crear recuadro centrado alrededor del título
        $this->Rect($x, $y, $totalWidth, 20); // Altura aumentada para dos líneas de texto

        // Posicionar el texto "A" en el centro del recuadro
        $this->SetXY($x, $y); // Cambia 'x' por la variable $x calculada para centrar
        $this->Cell($totalWidth, 12, $titulo, 0, 0, 'C'); // Alinear en el centro del recuadro

        // Posicionar el texto "001" en el centro del recuadro debajo de "A"
        $this->SetXY($x, $y + 12); // Mover hacia abajo
        $this->Cell($totalWidth, 5, $subtitulo, 0, 0, 'C'); // Alinear en el centro del recuadro

        $this->Ln(-7); // Espacio después del recuadro
    }

    function Footer()
    {
        // Set position at 1.5 cm from the bottom
        $this->SetY(-30); // Adjust the Y position to leave space for two rows
        $this->SetFont('Arial', 'I', 8);
    
        // Draw outer border for the footer
        $this->Rect(10, $this->GetY(), 190,25); // X, Y, Width, Height
    
        // First Row of Footer Information
        $this->SetY(-26); // Position for the first row
        $this->Cell(90, 6, 'CAE: 12345678901234', 0, 0, 'L'); // Left column
        $this->Cell(90, 6, 'Fecha Vto. CAE: 19/10/2024', 0, 1, 'R'); // Right column
        $this->Cell(90, 6, 'Comprobante Autorizado', 0, 0, 'L'); // Left column
        $this->Cell(90, 6, 'CAI: 701705642879', 0, 1, 'R'); // Right column
    
        // Second Row of Footer Information
        $this->Cell(90, 6, 'Fecha de Vto. del CAI: 24/10/2024', 0, 0, 'L'); // Left column
        $this->Cell(90, 6, '', 0, 1, 'R'); // Empty right cell to balance the row
    
        // Centered Page Number
        $this->SetY(-28); // Set Y position for the page number
        $this->Cell(187, 6, utf8_decode('Página ') . $this->PageNo() . '/{nb}', 0, 0, 'C'); // Centered page number
    
        // Centered QR code below the page number
        $this->SetY(-22); // Adjust this Y position for the QR code
        $this->Image('../presentacion/qr.png', 95, $this->GetY(), 13, 13, 'PNG'); // Center the QR code (X, Y, Width, Height)
    }

    function AddInvoiceSection($products, $cliente)
    {
        // Define starting Y position
        $startY = $this->GetY();
        
        // Outer border for the invoice section
        $this->Rect(10, $startY - 5, 190, 247); // El recuadro permanece en su lugar
        
        // Dibujar la línea vertical en el centro del recuadro
        $middleX = 10 + 190 / 2; // Calcular la coordenada X para el centro del recuadro
        $this->Line($middleX, $startY + 15, $middleX, $startY + 38); // Línea desde arriba hasta abajo
        
        $this->SetY($startY); // Establecer Y de vuelta al punto de partida
        $this->SetFont('Arial', 'B', 12); // Establecer fuente en negrita para los títulos
        
        // Detalles de la empresa
        $this->Ln(5);
        $this->Cell(110, 6, 'Empresa: Marquez Comunicaciones', 0, 0, 'L'); // Este texto comienza en X=11
        $this->Cell(90, 6, 'Fecha: ' . date('d/m/Y'), 0, 1, 'L'); // Fecha actual
        $this->Cell(110, 6, 'CUIT: 30-12345678-9', 0, 0, 'L');
        $this->Cell(90, 6, utf8_decode('Factura N°: 0001-00001234'), 0, 1, 'L');
        $this->Cell(110, 6, 'Ing. Brutos: 123456789', 0, 0, 'L');
        $this->Cell(90, 6, 'Punto de Venta: 0001', 0, 1, 'L');
        $this->Cell(110, 6, utf8_decode('Condición IVA: Responsable Inscripto'), 0, 0, 'L');
        $this->Cell(90, 6, 'Tel: 011-1234-5678', 0, 1, 'L');
        $this->Cell(110, 6, utf8_decode('Dirección: Calle Falsa 123, CABA'), 0, 1, 'L');
        $this->Ln(3);
        $this->Line(10, $this->GetY(), 200, $this->GetY()); // La línea permanece en su lugar
        $this->Ln(3);
        
        $this->SetFont('Arial', 'B', 12); // Mantener en negrita para el título "Cliente"

        // Definir la celda para "Cliente" alineada a la izquierda y "IVA" alineada a la derecha
        $this->Cell(130, 10, 'Cliente', 0, 0, 'L'); // Alineado a la izquierda
        $this->Cell(-10, 10, 'IVA', 0, 1, 'R'); // Alineado a la derecha

        $this->Ln(1); // Espaciado después de los títulos

        // Cambiar a fuente normal para el resto del texto
        $this->SetFont('Arial', '', 10); 

        // Celda para el nombre alineada a la izquierda y CUIT alineado a la derecha
        $this->Cell(110, 6, utf8_decode('Nombre: Juan Pérez'), 0, 0, 'L'); // Alineado a la izquierda
        $this->Cell(32, 6, 'C.U.I.T: 20-87654321-9', 0, 1, 'R'); // Alineado a la derecha

        // Celda para la dirección alineada a la izquierda y Condición IVA alineada a la derecha
        $this->Cell(110, 6, utf8_decode('Dirección: Calle Verdadera 456, CABA'), 0, 0, 'L'); // Alineado a la izquierda
        $this->Cell(47, 6, utf8_decode('Condición IVA: Consumidor Final'), 0, 1, 'R'); // Alineado a la derecha

        // Línea debajo
        $this->Line(10, $this->GetY(), 200, $this->GetY()); // La línea permanece en su lugar

        $this->Ln(7); // Espacio extra
        $this->DrawCheckboxes();
        
        $this->Ln(8);
        
        // Encabezado de tabla de productos
        $this->SetFont('Arial', 'B', 10); // Establece la fuente en negrita
        $this->SetFillColor(200, 220, 255); // Color de fondo del encabezado

        // Dibuja las celdas del encabezado, indicando que deben estar rellenas
        $this->Cell(30, 10, 'Cantidad', 1, 0, 'C', true);
        $this->Cell(70, 10, utf8_decode('Descripción'), 1, 0, 'C', true);
        $this->Cell(30, 10, 'Precio Unitario', 1, 0, 'C', true);
        $this->Cell(30, 10, 'IVA (21%)', 1, 0, 'C', true); // Coloca el IVA después del Precio Unitario
        $this->Cell(30, 10, 'Total', 1, 1, 'C', true); // Coloca un '1' para que el fondo se aplique

        $this->SetFont('Arial', '', 10); // Cambia a fuente normal para el resto del texto


        // Agregar productos
        $totalFactura = 0; // Variable para el total de la factura
        foreach ($products as $producto) {
            $total = $producto['cantidad'] * $producto['precio_unitario'];
            $this->Cell(30, 10, $producto['cantidad'], 1);
            $this->Cell(70, 10, $producto['descripcion'], 1);
            $this->Cell(30, 10, '$' . number_format($producto['precio_unitario'], 2), 1);
            $this->Cell(30, 10, '$' . number_format($total * 0.21, 2), 1); // Calcular el IVA
            $this->Cell(30, 10, '$' . number_format($total + ($total * 0.21), 2), 1); // Total con IVA

            $this->Ln();
            $totalFactura += $total + ($total * 0.21); // Sumar el total con IVA a la factura
        }

        // Total general
        $this->SetFont('Arial', 'B', 10);
        $this->Ln(22); // Espacio entre el total y el footer
        $this->Cell(50, 10, 'Subt-Total :', 1);
        $this->Cell(55, 10, 'Total IVA :', 1);
        $this->Cell(55, 10, 'Total :', 1);
        $this->Cell(30, 10, '$' . number_format($totalFactura, 2), 1);
        
    }

    function DrawCheckboxes()
    {
        // Definir la posición inicial para los cuadros de verificación
        $startX = 13; // El margen izquierdo
        $startY = $this->GetY(); // La posición Y actual
        $checkboxSize = 3; // Tamaño de cada cuadro de verificación
        $spacing = 8; // Espacio entre filas
        $horizontalSpacing = 33; // Espacio horizontal entre cuadros
        $totalCheckboxes = 8; // Total de cuadros

        // Array con textos personalizados para cada cuadro
        $checkboxTexts = [
            'excento',
            'Resp.Inscripto',
            'Cons.Final',
            'Resp.Monotributo',
            'No Responsable',
            'Contado',
            'Cta.Cte.',
            'Remito N°:',
        ];

        // Títulos para cada fila
        $rowTitles = [
            utf8_decode('Condición de IVA:'),
            utf8_decode('Condición de Venta:'),
        ];

        // Generar los cuadros en dos líneas
        for ($i = 0; $i < $totalCheckboxes; $i++) {
            // Calcular la posición X e Y de cada cuadro
            $currentX = $startX + ($i % 5) * ($checkboxSize + $horizontalSpacing); // Cada fila tendrá 5 cuadros
            $currentY = $startY + floor($i / 5) * ($checkboxSize + $spacing); // Dos filas

            // Verificar si es el inicio de una nueva fila para agregar un título
            if ($i % 5 === 0) {
                // Dibujar el título de la fila
                $this->SetXY($startX, $currentY - 6); // Ajustar la posición para el título
                $this->SetFont('Arial', 'B', 9); // Establecer fuente en negrita para el título
                $this->Cell(0, 5, $rowTitles[floor($i / 5)], 0, 1, 'L'); // Escribir el título de la fila
                $this->SetFont('Arial', '', 10); // Volver a la fuente normal
            }

            // Dibujar el cuadro
            $this->Rect($currentX, $currentY, $checkboxSize, $checkboxSize); // Crear el cuadro
            $this->SetXY($currentX + 5, $currentY); // Ajustar la posición del texto a la derecha del cuadro
            $this->Cell(30, 5, $checkboxTexts[$i], 0, 0, 'L'); // Escribir el texto específico al lado del cuadro
        }
    }
}

// Crear instancia de PDF
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();

// Obtener datos del cliente (modificar según el cliente que desees)
$cliente = mysqli_fetch_assoc($result_clientes); // Solo un cliente por simplicidad

// Obtener detalles de la factura
$productos = [];
while ($detalle = mysqli_fetch_assoc($result_detalle_factura)) {
    // Suponiendo que id_accesorios_y_componentes se puede usar para obtener el nombre y precio
    $query_producto = "SELECT nombre, precio FROM accesorios_y_componentes WHERE id_accesorios_y_componentes = " . $detalle['id_accesorios_y_componentes'];
    $resultado_producto = mysqli_query($conn, $query_producto);
    $producto = mysqli_fetch_assoc($resultado_producto);
    
    // Añadiendo información del producto al array
    $productos[] = [
        'cantidad' => $detalle['cantidad_venta'],
        utf8_decode('descripcion') => $producto['nombre'], // Obtener nombre del producto
        'precio_unitario' => $detalle['precio_unitario_V'] // Precio unitario
    ];
}

$pdf->AddInvoiceSection($productos, $cliente);
$pdf->Output('I', 'Factura.pdf');
?>
