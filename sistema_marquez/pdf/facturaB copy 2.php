<?php
require('./fpdf.php');

require_once('../base_datos/db.php');


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
    
    // Método para dibujar los cuadros de verificación
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
                $this->SetFont('Arial', 'B', 8); // Establecer fuente en negrita para el título
                $this->Cell(0, 5, $rowTitles[floor($i / 5)], 0, 1, 'L'); // Escribir el título de la fila
                $this->SetFont('Arial', '', 10); // Volver a la fuente normal
            }

            // Dibujar el cuadro
            $this->Rect($currentX, $currentY, $checkboxSize, $checkboxSize); // Crear el cuadro
            $this->SetXY($currentX + 5, $currentY); // Ajustar la posición del texto a la derecha del cuadro
            $this->Cell(30, 5, $checkboxTexts[$i], 0, 0, 'L'); // Escribir el texto específico al lado del cuadro
        }
    }

    // Function to add products to the table
    function AddProduct($cantidad, $descripcion, $precio_unitario)
    {
        $this->SetFont('Arial', '', 10);
        $iva = $precio_unitario * 0.21; // Calculate IVA
        $total = $precio_unitario + $iva; // Calculate total

        // Cells with product details
        $this->Cell(30, 8, $cantidad, 1, 0, 'C'); // Cantidad
        $this->Cell(70, 8, utf8_decode($descripcion), 1); // Descripción
        $this->Cell(30, 8, '$' . number_format($precio_unitario, 2), 1, 0, 'C'); // Precio unitario
        $this->Cell(30, 8, '$' . number_format($iva, 2), 1, 0, 'C'); // IVA
        $this->Cell(30, 8, '$' . number_format($total, 2), 1, 1, 'C'); // Total

        return $total; // Return the total for the overall calculation
    }

    // New function to fill empty rows until reaching 247
    function FillEmptyRows($currentY)
    {
        // Calculate how many rows are needed to fill the space up to 247
        $maxY = 247; // Desired Y position
        $rowHeight = 5; // Height of each row
        $emptyRows = floor(($maxY - $currentY) / $rowHeight); // Calculate empty rows needed

        // Fill empty rows
        for ($i = 0; $i < $emptyRows; $i++) {
            $this->Cell(30, $rowHeight, '', 1, 0, 'C'); // Empty cell for Cantidad
            $this->Cell(70, $rowHeight, '', 1); // Empty cell for Descripción
            $this->Cell(30, $rowHeight, '', 1, 0, 'C'); // Empty cell for Precio unitario
            $this->Cell(30, $rowHeight, '', 1, 0, 'C'); // Empty cell for IVA
            $this->Cell(30, $rowHeight, '', 1, 1, 'C'); // Empty cell for Total
        }
    }

    // Function to calculate the final total of the invoice
    function TotalInvoice($total)
    {
        $this->SetFont('Arial', 'B', 10);
        
        // Adjust the cell widths for alignment
        $this->Cell(30, 10, '', 0, 0); // Empty cell for spacing
        $this->Cell(70, 10, '', 0, 0); // Empty cell for spacing
        $this->Cell(30, 10, '', 0, 0); // Empty cell for spacing
        $this->Cell(30, 10, 'Total Factura:', 1, 0, 'C'); // Total label
        $this->Cell(30, 10, '$' . number_format($total, 2), 1, 1, 'C'); // Total amount
    }

    // Método para generar la factura
    function GenerateInvoice($productos)
    {
        $total_factura = 0;

        foreach ($productos as $producto) {
            $total_factura += $this->AddProduct($producto['cantidad'], $producto['descripcion'], $producto['precio_unitario']);
        }

        // En el flujo de trabajo donde agregas productos
        $currentY = $this->GetY(); // Obtén la posición Y actual después de agregar productos
        $this->FillEmptyRows($currentY); // Llama a la función para llenar las filas vacías
        $this->SetY(247 - 5); // Ajusta la posición Y antes de agregar el total
        $this->TotalInvoice($total_factura); // Llama a la función TotalInvoice

        // Llama a la función para dibujar los cuadros de verificación
        $this->DrawCheckboxes(); // Añadir la implementación de DrawCheckboxes aquí
    }

    // function AddInvoiceSection($products, $clientData)
    // {
    //     // Define starting Y position
    //     $startY = $this->GetY();
        
    //     // Outer border for the invoice section
    //     $this->Rect(10, $startY - 5, 190, 247); // El recuadro permanece en su lugar
        
    //     // Dibujar la línea vertical en el centro del recuadro
    //     $middleX = 10 + 190 / 2; // Calcular la coordenada X para el centro del recuadro
    //     $this->Line($middleX, $startY + 15, $middleX, $startY + 38); // Línea desde arriba hasta abajo
        
    //     $this->SetY($startY); // Establecer Y de vuelta al punto de partida
    //     $this->SetFont('Arial', 'B', 12); // Establecer fuente en negrita para los títulos
        
    //     // Detalles de la empresa, retirando 1 mm del margen izquierdo
    //     $this->Ln(5);
    //     $this->Cell(110, 6, 'Empresa: Marquez Comunicaciones', 0, 0, 'L'); // Este texto comienza en X=11
    //     $this->Cell(90, 6, 'Fecha: 09/10/2024', 0, 1, 'L');
    //     $this->Cell(110, 6, 'CUIT: 30-12345678-9', 0, 0, 'L');
    //     $this->Cell(90, 6, utf8_decode('Factura N°: 0001-00001234'), 0, 1, 'L');
    //     $this->Cell(110, 6, 'Ing. Brutos: 123456789', 0, 0, 'L');
    //     $this->Cell(90, 6, 'Punto de Venta: 0001', 0, 1, 'L');
    //     $this->Cell(110, 6, utf8_decode('Condición IVA: Responsable Inscripto'), 0, 0, 'L');
    //     $this->Cell(90, 6, 'Tel: 011-1234-5678', 0, 1, 'L');
    //     $this->Cell(110, 6, utf8_decode('Dirección: Calle Falsa 123, CABA'), 0, 1, 'L');
    //     $this->Ln(3);
        
    //     // Línea divisoria horizontal
    //     $this->Line(10, $this->GetY(), 200, $this->GetY()); // La línea permanece en su lugar
    //     $this->Ln(3);

    //     // Información del cliente
    //     $this->SetFont('Arial', 'B', 12); // Mantener en negrita para el título "Cliente"
    //     $this->Cell(0, 5, 'Cliente', 0, 1, 'L');
    //     $this->Ln(1);
        
    //     $this->SetFont('Arial', '', 10); // Cambiar a fuente normal para el resto del texto
    //     $this->Cell(110, 6, utf8_decode('Nombre: ' . $clientData['nombre'] . ' ' . $clientData['apellido']), 0, 0, 'L'); // Nombre del cliente
    //     $this->Cell(90, 6, 'CUIT: ' . $clientData['cuit'], 0, 1, 'L'); // CUIT del cliente
    //     $this->Cell(110, 6, utf8_decode('Dirección: ' . $clientData['direccion']), 0, 0, 'L'); // Dirección del cliente
    //     // Suponiendo que quieres mostrar la condición IVA del cliente
    //     $this->Cell(90, 6, utf8_decode('Condición IVA: Consumidor Final ')    , 0, 1, 'L'); // Asegúrate de que la condición IVA esté disponible
        
    //     $this->Line(10, $this->GetY(), 200, $this->GetY()); // La línea permanece en su lugar
    //     $this->Ln(8);
        
    //     // Añadir cuadros de verificación
    //     $this->DrawCheckboxes();
    //     $this->Ln(8);
    //     // Encabezado de tabla de productos
    //     $this->SetFont('Arial', 'B', 10);
    //     $this->SetFillColor(200, 220, 255); // Color de fondo del encabezado
    //     $this->Cell(30, 10, 'Cantidad', 1, 0, 'C', true);
    //     $this->Cell(70, 10, utf8_decode('Descripción'), 1, 0, 'C', true);
    //     $this->Cell(30, 10, 'Precio Unitario', 1, 0, 'C', true);
    //     $this->Cell(30, 10, 'IVA (21%)', 1, 0, 'C', true);
    //     $this->Cell(30, 10, 'Total', 1, 1, 'C', true);

    //     // Agregar productos
    //     $total_factura = 0;
    //     foreach ($products as $producto) {
    //         $cantidad = $producto[0];
    //         $descripcion = $producto[1];
    //         $precio_unitario = $producto[2];
    //         $total_factura += $this->AddProduct($cantidad, $descripcion, $precio_unitario);
    //     }

    //     $this->SetY(267 - 10); // Ajustar la posición Y antes de agregar el total
    //     $this->TotalInvoice($total_factura);
    // }

    function AddInvoiceSection($products, $clientData) {

        $startY = $this->GetY();

        $this->Rect(10, $startY - 5, 190, 247); // El recuadro permanece en su lugar
        
        // Dibujar la línea vertical en el centro del recuadro
        $middleX = 10 + 190 / 2; // Calcular la coordenada X para el centro del recuadro
        $this->Line($middleX, $startY + 15, $middleX, $startY + 38); // Línea desde arriba hasta abajo
        
        $this->SetY($startY); // Establecer Y de vuelta al punto de partida
        $this->SetFont('Arial', 'B', 12); // Establecer fuente en negrita para los títulos
        
        // Detalles de la empresa, retirando 1 mm del margen izquierdo
        $this->Ln(5);
        $this->Cell(110, 6, 'Empresa: Marquez Comunicaciones', 0, 0, 'L'); // Este texto comienza en X=11
        $this->Cell(90, 6, 'Fecha: 09/10/2024', 0, 1, 'L');
        $this->Cell(110, 6, 'CUIT: 30-12345678-9', 0, 0, 'L');
        $this->Cell(90, 6, utf8_decode('Factura N°: 0001-00001234'), 0, 1, 'L');
        $this->Cell(110, 6, 'Ing. Brutos: 123456789', 0, 0, 'L');
        $this->Cell(90, 6, 'Punto de Venta: 0001', 0, 1, 'L');
        $this->Cell(110, 6, utf8_decode('Condición IVA: Responsable Inscripto'), 0, 0, 'L');
        $this->Cell(90, 6, 'Tel: 011-1234-5678', 0, 1, 'L');
        $this->Cell(110, 6, utf8_decode('Dirección: Calle Falsa 123, CABA'), 0, 1, 'L');
        $this->Ln(3);
        
        // Línea divisoria horizontal
        $this->Line(10, $this->GetY(), 200, $this->GetY()); // La línea permanece en su lugar
        $this->Ln(3);

        // Información del cliente
        $this->SetFont('Arial', 'B', 12); // Mantener en negrita para el título "Cliente"
        $this->Cell(0, 5, 'Cliente', 0, 1, 'L');
        $this->Ln(1);
        
        $this->SetFont('Arial', '', 10); // Cambiar a fuente normal para el resto del texto
        $this->Cell(110, 6, utf8_decode('Nombre: ' . $clientData['nombre'] . ' ' . $clientData['apellido']), 0, 0, 'L'); // Nombre del cliente
        $this->Cell(90, 6, 'CUIT: ' . $clientData['cuit'], 0, 1, 'L'); // CUIT del cliente
        $this->Cell(110, 6, utf8_decode('Dirección: ' . $clientData['direccion']), 0, 0, 'L'); // Dirección del cliente
        // Suponiendo que quieres mostrar la condición IVA del cliente
        $this->Cell(90, 6, utf8_decode('Condición IVA: Consumidor Final ')    , 0, 1, 'L'); // Asegúrate de que la condición IVA esté disponible
        
        $this->Line(10, $this->GetY(), 200, $this->GetY()); // La línea permanece en su lugar
        $this->Ln(8);
        
        // Añadir cuadros de verificación
        $this->DrawCheckboxes();
        $this->Ln(8);
        // Encabezado de tabla de productos
        $this->SetFont('Arial', 'B', 10);
        $this->SetFillColor(200, 220, 255); // Color de fondo del encabezado
        $this->Cell(30, 10, 'Cantidad', 1, 0, 'C', true);
        $this->Cell(70, 10, utf8_decode('Descripción'), 1, 0, 'C', true);
        $this->Cell(30, 10, 'Precio Unitario', 1, 0, 'C', true);
        $this->Cell(30, 10, 'IVA (21%)', 1, 0, 'C', true);
        $this->Cell(30, 10, 'Total', 1, 1, 'C', true);
        
        // Add products
        $subTotal = 0;
        $totalIVA = 0;
        $totalFactura = 0;
        foreach ($products as $producto) {
            $cantidad = $producto[0];
            $descripcion = $producto[1];
            $precio_unitario = $producto[2];
            $total = $cantidad * $precio_unitario;
            $iva = $total * 0.21; // IVA is 21%
            
            // Add to totals
            $subTotal += $total;
            $totalIVA += $iva;
            $totalFactura += $total + $iva;
            
            // Display product row
            $this->Cell(30, 8, $cantidad, 1);
            $this->Cell(70, 8, $descripcion, 1);
            $this->Cell(30, 8, '$' . number_format($precio_unitario, 2), 1);
            $this->Cell(30, 8, '$' . number_format($iva, 2), 1); // IVA
            $this->Cell(30, 8, '$' . number_format($total + $iva, 2), 1); // Total with IVA
            $this->Ln();
        }
        
        // Fill remaining rows with empty cells if necessary
        while ($this->GetY() < ($startY + 247) && count($products) < 14) {
            $this->Cell(30, 8, '', 1);
            $this->Cell(70, 8,utf8_decode('') , 1);
            $this->Cell(30, 8, '', 1);
            $this->Cell(30, 8, '', 1);
            $this->Cell(30, 8, '', 1);
            $this->Ln();
            $products[] = ['cantidad' => '',utf8_decode('descripcion')  => utf8_decode(''), 'precio_unitario' => ''];
        }
        
        // Display total at the bottom
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(30, 6, '', 1); // Empty cell for "Cantidad"
        $this->Cell(70, 6,utf8_decode('') , 1); // Empty cell for "Descripción"
        $this->Cell(30, 6, 'Sub-Total: $' . number_format($subTotal, 2), 1);
        $this->Cell(30, 6, 'Total IVA: $' . number_format($totalIVA, 2), 1);
        $this->Cell(30, 6, 'Total: $' . number_format($totalFactura, 2), 1);
    }
    

}
    
// Crear una instancia del PDF
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();

// Consultar clientes de la base de datos
$query_clientes = "SELECT id_clientes, nombre, apellido, cuit, direccion FROM clientes"; // Incluyendo CUIT y dirección
$result_clientes = mysqli_query($conn, $query_clientes);

$query_detalle_factura = "SELECT id_detalle_factura, cantidad_venta, precio_unitario_V, id_accesorios_y_componentes FROM detalle_factura";
$result_detalle_factura = mysqli_query($conn, $query_detalle_factura);

$query = "
SELECT 
    df.cantidad_venta, 
    ac.nombre AS nombre_accesorio_componente, 
    df.precio_unitario_V 
FROM 
    detalle_factura df
JOIN 
    accesorios_y_componentes ac
ON 
    df.id_accesorios_y_componentes = ac.id_accesorios_y_componentes
";

$result = mysqli_query($conn, $query);

// Verifica si la consulta se realizó correctamente y si hay resultados
$productos = []; // Inicializamos un array vacío para productos
if ($result && mysqli_num_rows($result) > 0) {
    // Recorrer los resultados y construir la matriz de productos
    while ($row = mysqli_fetch_assoc($result)) {
        $productos[] = [
            $row['cantidad_venta'],             // Cantidad
            $row[utf8_decode('nombre_accesorio_componente')], // Descripción (nombre del accesorio/componente)
            $row['precio_unitario_V']            // Precio unitario
        ];
    }
} else {
    echo "No se encontraron productos.";
}

$query_clientes = "SELECT id_clientes, nombre, apellido, cuit, direccion FROM clientes";
$result_clientes = mysqli_query($conn, $query_clientes);

// Verificar si hay datos de clientes
if ($result_clientes && mysqli_num_rows($result_clientes) > 0) {
    // Obtener el primer cliente como ejemplo
    $clientData = mysqli_fetch_assoc($result_clientes);

    // Usar los productos obtenidos de la consulta SQL en lugar de los productos de ejemplo
    $pdf->AddInvoiceSection($productos, $clientData);
} else {
    echo "No se encontraron clientes.";
}


// Salida del PDF
$pdf->Output('I', 'factura.pdf'); // Cambia 'I' a 'D' si deseas forzar la descarga

// Cerrar la conexión a la base de datos
mysqli_close($conn);
?>


