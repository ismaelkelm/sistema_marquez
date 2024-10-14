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

$query_pedidos_de_reparacion = "SELECT id_pedidos_de_reparacion, fecha_de_pedido,numero_orden, observacion FROM pedidos_de_reparacion";
$result_pedidos_de_reparacion = mysqli_query($conn, $query_pedidos_de_reparacion);

$query_detalle_factura = "SELECT id_detalle_factura, cantidad_venta, precio_unitario_V, id_accesorios_y_componentes FROM detalle_factura";
$result_detalle_factura = mysqli_query($conn, $query_detalle_factura);

$query_proveedores = "SELECT id_proveedores, nombre, contacto, telefono,direccion FROM proveedores";
$result_proveed = mysqli_query($conn, $query_detalle_factura);

$query_cabecera_factura = "SELECT id_cabecera_factura, id_clientes, id_usuario FROM cabecera_factura";
$result_cabecera_factura = mysqli_query($conn, $query_detalle_factura);


$query_cabecera_factura = "
    SELECT 
        cf.id_cabecera_factura, 
        cf.fecha_factura, 
        cf.subtotal_factura, 
        cf.impuestos, 
        cf.total_factura, 
        cf.id_clientes, 
        cf.id_usuario, 
        cf.id_operacion, 
        cf.id_tipo_comprobante, 
        cf.id_tipo_de_pago, 
        cf.id_pedido_reparacion, 
        pr.numero_orden -- Asegúrate de incluir el número de orden en la consulta
    FROM 
        cabecera_factura cf
    JOIN 
        pedidos_de_reparacion pr ON cf.id_pedido_reparacion = pr.id_pedidos_de_reparacion -- Realiza un JOIN con la tabla de pedidos
";

$result_cabecera_factura = mysqli_query($conn, $query_cabecera_factura);
$cabecera_factura = mysqli_fetch_assoc($result_cabecera_factura);


// 2. Almacena el número de orden
$numeroOrden = $cabecera_factura['numero_orden'];


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

    // function AddInvoiceSection($products, $cliente, $numeroOrden) {
    //     // Define starting Y position
    //     $startY = $this->GetY();
        
    //     // Outer border for the invoice section
    //     $this->Rect(10, $startY - 5, 190, 247); // El recuadro permanece en su lugar
        
    //     // Draw the vertical line in the center of the box
    //     $middleX = 10 + 190 / 2; // Calculate the X coordinate for the center of the box
    //     $this->Line($middleX, $startY + 15, $middleX, $startY + 38); // Line from top to bottom
        
    //     $this->SetY($startY); // Reset Y to starting point
    //     $this->SetFont('Arial', 'B', 12); // Set bold font for titles
        
    //     // Company details
    //     $this->Ln(5);
    //     $this->Cell(110, 6, 'Empresa: Marquez Comunicaciones', 0, 0, 'L'); // Company Name
    //     $this->Cell(90, 6, 'Fecha: ' . date('d/m/Y'), 0, 1, 'L'); // Current date
    
    //     $this->Cell(110, 6, 'CUIT: 30-12345678-9', 0, 0, 'L');
    //     $this->Cell(90, 6, utf8_decode('Factura N°: 0001-00001234'), 0, 1, 'L');
    //     $this->Cell(110, 6, 'Ing. Brutos: 123456789', 0, 0, 'L');
    //     $this->Cell(90, 6, 'Punto de Venta: 0001', 0, 1, 'L');
    //     $this->Cell(110, 6, utf8_decode('Condición IVA: Responsable Inscripto'), 0, 0, 'L');
    //     $this->Cell(90, 6, 'Tel: 011-1234-5678', 0, 1, 'L');
    
    //     // Here we change the cell for the Order Number
    //     $this->Cell(110, 6, utf8_decode('Dirección: Calle Falsa 123, CABA'), 0, 0, 'L'); // Aligned to the left
    //     $this->Cell(44, 6, utf8_decode('Numero Orden: ' . $numeroOrden), 0, 1, 'R'); // Aligned to the right
    //     $this->Ln(3);
    //     $this->Line(10, $this->GetY(), 200, $this->GetY()); // The line stays in its place
    //     $this->Ln(3);
        
    //     $this->SetFont('Arial', 'B', 12); // Keep bold for the "Cliente" title
    
    //     // Define cell for "Cliente" aligned to the left and "IVA" aligned to the right
    //     $this->Cell(130, 10, 'Cliente', 0, 0, 'L'); // Aligned to the left
    //     $this->Cell(-10, 10, 'IVA', 0, 1, 'R'); // Aligned to the right
    
    //     $this->Ln(1); // Spacing after titles
    
    //     // Change to normal font for the rest of the text
    //     $this->SetFont('Arial', '', 10); 
    
    //     // Cell for name aligned to the left and CUIT aligned to the right
    //     // Access as array for cliente
    //     $nombre = isset($cliente['nombre']) ? $cliente['nombre'] : 'Nombre no disponible';
    //     $cuit = isset($cliente['cuit']) ? $cliente['cuit'] : 'C.U.I.T no disponible';
    
    //     $this->Cell(110, 6, utf8_decode('Nombre: ' . $nombre), 0, 0, 'L'); // Aligned to the left
    //     $this->Cell(32, 6, 'C.U.I.T: ' . $cuit, 0, 1, 'R'); // Aligned to the right
    
    //     // Cell for address aligned to the left and IVA condition aligned to the right
    //     $this->Cell(110, 6, utf8_decode('Dirección: Calle Verdadera 456, CABA'), 0, 0, 'L'); // Aligned to the left
    //     $this->Cell(47, 6, utf8_decode('Condición IVA: Consumidor Final'), 0, 1, 'R'); // Aligned to the right
    
    //     // Line below
    //     $this->Line(10, $this->GetY(), 200, $this->GetY()); // The line stays in its place
    
    //     $this->Ln(7); // Extra space
    //     $this->DrawCheckboxes(); // Call to your method for drawing checkboxes
    
    //     $this->Ln(8);
        
    //     // Header of product table
    //     $this->SetFont('Arial', 'B', 10); // Set the font to bold
    //     $this->SetFillColor(200, 220, 255); // Header background color
    
    //     // Draw header cells, indicating they should be filled
    //     $this->Cell(30, 8, 'Cantidad', 1, 0, 'C', true);
    //     $this->Cell(70, 8, utf8_decode('Descripción'), 1, 0, 'C', true);
    //     $this->Cell(30, 8, 'Precio Unitario', 1, 0, 'C', true);
    //     $this->Cell(30, 8, 'IVA (21%)', 1, 0, 'C', true); // Place IVA after Unit Price
    //     $this->Cell(30, 8, 'Total', 1, 1, 'C', true); // Place a '1' to apply the background
    
    //     $this->SetFont('Arial', '', 10); // Change to normal font for the rest of the text
    
    //     // Add products
    //     $totalFactura = 0; // Variable for the invoice total
    //     foreach ($products as $producto) {
    //         // Ensure quantity and unit price are numbers
    //         $cantidad = is_numeric($producto['cantidad']) ? (float)$producto['cantidad'] : 0;
    //         $precio_unitario = is_numeric($producto['precio_unitario']) ? (float)$producto['precio_unitario'] : 0;
    
    //         $total = $cantidad * $precio_unitario;
    
    //         // If the quantity or price are zero, handle it in different ways
    //         $this->Cell(30, 8, $cantidad, 1);
    //         $this->Cell(70, 8, $producto['descripcion'], 1);
    //         $this->Cell(30, 8, '$' . number_format($precio_unitario, 2), 1);
    //         $this->Cell(30, 8, '$' . number_format($total * 0.21, 2), 1); // Calculate IVA
    //         $this->Cell(30, 8, '$' . number_format($total + ($total * 0.21), 2), 1); // Total with IVA
    
    //         $this->Ln();
    //         $totalFactura += $total + ($total * 0.21); // Sum the total with IVA to the invoice
    //     }
    
    //     // Ensure there are 14 rows
    //     while ($this->GetY() < ($startY + 247) && count($products) < 14) {
    //         $this->Cell(30, 8, '', 1);
    //         $this->Cell(70, 8, '', 1);
    //         $this->Cell(30, 8, '', 1);
    //         $this->Cell(30, 8, '', 1);
    //         $this->Cell(30, 8, '', 1);
    //         $this->Ln();
    //         $products[] = ['cantidad' => '', 'descripcion' => '', 'precio_unitario' => ''];
    //     }
    
    //     // General total
    //     $this->SetFont('Arial', 'B', 10);
    //     $this->Ln(0); // Space between the total and the footer
    //     $this->Cell(50, 8, 'Subt-Total :', 1);
    //     $this->Cell(55, 8, 'Total IVA :', 1);
    //     $this->Cell(55, 8, 'Total :', 1);
    //     $this->Cell(30, 8, '$' . number_format($totalFactura, 2), 1);
    // }


    function AddInvoiceSection($products, $cliente, $numeroOrden) {
        // Define starting Y position
        $startY = $this->GetY();
        
        // Outer border for the invoice section
        $this->Rect(10, $startY - 5, 190, 247); // El recuadro permanece en su lugar
        
        // Draw the vertical line in the center of the box
        $middleX = 10 + 190 / 2; // Calculate the X coordinate for the center of the box
        $this->Line($middleX, $startY + 15, $middleX, $startY + 38); // Line from top to bottom
        
        $this->SetY($startY); // Reset Y to starting point
        $this->SetFont('Arial', 'B', 12); // Set bold font for titles
        
        // Company details
        $this->Ln(5);
        $this->Cell(110, 6, 'Empresa: Marquez Comunicaciones', 0, 0, 'L'); // Company Name
        $this->Cell(90, 6, 'Fecha: ' . date('d/m/Y'), 0, 1, 'L'); // Current date
    
        $this->Cell(110, 6, 'CUIT: 30-12345678-9', 0, 0, 'L');
        $this->Cell(90, 6, utf8_decode('Factura N°: 0001-00001234'), 0, 1, 'L');
        $this->Cell(110, 6, 'Ing. Brutos: 123456789', 0, 0, 'L');
        $this->Cell(90, 6, 'Punto de Venta: 0001', 0, 1, 'L');
        $this->Cell(110, 6, utf8_decode('Condición IVA: Responsable Inscripto'), 0, 0, 'L');
        $this->Cell(90, 6, 'Tel: 011-1234-5678', 0, 1, 'L');
    
        // Here we change the cell for the Order Number
        $this->Cell(110, 6, utf8_decode('Dirección: Calle Falsa 123, CABA'), 0, 0, 'L'); // Aligned to the left
        $this->Cell(53, 6, utf8_decode('Numero Orden: ' . $numeroOrden), 0, 1, 'R'); // Aligned to the right
        $this->Ln(3);
        $this->Line(10, $this->GetY(), 200, $this->GetY()); // The line stays in its place
        $this->Ln(3);
        
        $this->SetFont('Arial', 'B', 12); // Keep bold for the "Cliente" title
    
        // Define cell for "Cliente" aligned to the left and "IVA" aligned to the right
        $this->Cell(130, 10, 'Cliente', 0, 0, 'L'); // Aligned to the left
        $this->Cell(-10, 10, 'IVA', 0, 1, 'R'); // Aligned to the right
    
        $this->Ln(1); // Spacing after titles
    
        // Change to normal font for the rest of the text
        $this->SetFont('Arial', '', 10); 
    
        // Cell for name aligned to the left and CUIT aligned to the right
        // Access as array for cliente
        $nombre = isset($cliente['nombre']) ? $cliente['nombre'] : 'Nombre no disponible';
        $cuit = isset($cliente['cuit']) ? $cliente['cuit'] : 'C.U.I.T no disponible';
    
        $this->Cell(110, 6, utf8_decode('Nombre: ' . $nombre), 0, 0, 'L'); // Aligned to the left
        $this->Cell(32, 6, 'C.U.I.T: ' . $cuit, 0, 1, 'R'); // Aligned to the right
    
        // Cell for address aligned to the left and IVA condition aligned to the right
        $this->Cell(110, 6, utf8_decode('Dirección: Calle Verdadera 456, CABA'), 0, 0, 'L'); // Aligned to the left
        $this->Cell(47, 6, utf8_decode('Condición IVA: Consumidor Final'), 0, 1, 'R'); // Aligned to the right
    
        // Line below
        $this->Line(10, $this->GetY(), 200, $this->GetY()); // The line stays in its place
    
        $this->Ln(7); // Extra space
        $this->DrawCheckboxes(); // Call to your method for drawing checkboxes
    
        $this->Ln(10);
        
        // Header of product table
        $this->SetFont('Arial', 'B', 10); // Set the font to bold
        $this->SetFillColor(200, 220, 255); // Header background color
    
        // Draw header cells, indicating they should be filled
        $this->Cell(30, 8, 'Cantidad', 1, 0, 'C', true);
        $this->Cell(70, 8, utf8_decode('Descripción'), 1, 0, 'C', true);
        $this->Cell(30, 8, 'Precio Unitario', 1, 0, 'C', true);
        $this->Cell(30, 8, 'IVA (21%)', 1, 0, 'C', true); // Place IVA after Unit Price
        $this->Cell(30, 8, 'Total', 1, 1, 'C', true); // Place a '1' to apply the background
    
        $this->SetFont('Arial', '', 10); // Change to normal font for the rest of the text.
        
    
        // Add products
        $subTotal = 0;  // Variable for subtotal
        $totalIVA = 0;  // Variable for total IVA
        $totalFactura = 0; // Variable for the invoice total
        foreach ($products as $producto) {
            // Ensure quantity and unit price are numbers
            $cantidad = is_numeric($producto['cantidad']) ? (float)$producto['cantidad'] : 0;
            $precio_unitario = is_numeric($producto['precio_unitario']) ? (float)$producto['precio_unitario'] : 0;
    
            $total = $cantidad * $precio_unitario;
            $iva = $total * 0.21; // IVA is 21%
    
            // Add to totals
            $subTotal += $total;
            $totalIVA += $iva;
            $totalFactura += $total + $iva;
    
            // Display product row
            $this->Cell(30, 8, $cantidad, 1);
            $this->Cell(70, 8, $producto['descripcion'], 1);
            $this->Cell(30, 8, '$' . number_format($precio_unitario, 2), 1);
            $this->Cell(30, 8, '$' . number_format($iva, 2), 1); // IVA
            $this->Cell(30, 8, '$' . number_format($total + $iva, 2), 1); // Total con IVA
    
            $this->Ln();
        }
    
        // Ensure there are 14 rows
        while ($this->GetY() < ($startY + 247) && count($products) < 14) {
            $this->Cell(30, 8, '', 1);
            $this->Cell(70, 8, '', 1);
            $this->Cell(30, 8, '', 1);
            $this->Cell(30, 8, '', 1);
            $this->Cell(30, 8, '', 1);
            $this->Ln();
            $products[] = ['cantidad' => '', 'descripcion' => '', 'precio_unitario' => ''];
        }
    
        // $this->Ln(1); // Space between the total and the footer
        // General total
        $this->SetFont('Arial', 'B', 8);
        
    
        // Adding the totals in the last row, aligned correctly under their respective columns
        $this->Cell(30, 6, '', 1); // Empty cell for "Cantidad"
        $this->Cell(70, 6, '', 1); // Empty cell for "Descripción"
        $this->Cell(30, 6, 'Sub-Total: $' . number_format($subTotal, 2), 1); // Sub-Total under Price Unitario
        $this->Cell(30, 6, 'Total IVA: $' . number_format($totalIVA, 2), 1); // Total IVA under IVA
        $this->Cell(30, 6, 'Total: $' . number_format($totalFactura, 2), 1); // Total under Total
    
    }
    
}


$pdf = new PDF(); // Asegúrate de que esta línea esté dentro del flujo principal del script
$pdf->AliasNbPages();
$pdf->AddPage();

// Obtener datos del cliente (asegúrate de que la consulta a la base de datos sea correcta)
$cliente = mysqli_fetch_assoc($result_clientes); // Obtener un cliente

// Obtener detalles de la factura
$productos = [];

// Asegúrate de que $result_detalle_factura tenga los resultados necesarios
while ($detalle = mysqli_fetch_assoc($result_detalle_factura)) {
    // Suponiendo que id_accesorios_y_componentes se puede usar para obtener el nombre y precio
    $query_producto = "SELECT nombre, precio FROM accesorios_y_componentes WHERE id_accesorios_y_componentes = " . $detalle['id_accesorios_y_componentes'];
    $resultado_producto = mysqli_query($conn, $query_producto);
    
    // Verifica si se obtuvo un producto válido
    if ($producto = mysqli_fetch_assoc($resultado_producto)) {
        // Añadiendo información del producto al array
        $productos[] = [
            'cantidad' => $detalle['cantidad_venta'],
            'descripcion' => utf8_decode($producto['nombre']), // Obtener nombre del producto
            'precio_unitario' => $detalle['precio_unitario_V'] // Precio unitario
        ];
    } else {
        // Manejar el caso en que no se encuentra el producto
        $productos[] = [
            'cantidad' => $detalle['cantidad_venta'],
            'descripcion' => 'Producto no encontrado', // Mensaje alternativo
            'precio_unitario' => $detalle['precio_unitario_V'] // Precio unitario
        ];
    }
}

// Asegurarse de que haya 17 filas en total (incluyendo productos y filas vacías)
while (count($productos) < 17) {
    $productos[] = [
        'cantidad' => '', // Fila vacía
        'descripcion' => '', // Fila vacía
        'precio_unitario' => '' // Fila vacía
    ];
}

// Llamada a la función que agrega la sección de la factura
$pdf->AddInvoiceSection($productos, $cliente, $numeroOrden);

// Generar el PDF
$pdf->Output('I', 'Factura.pdf');
