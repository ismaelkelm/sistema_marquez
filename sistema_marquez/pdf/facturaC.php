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

    function Header()
    {
        // Título "Original"
        $this->SetFont('Arial', '', 10);
        $this->SetXY(10, 10); // Posicionar el texto "Original" en la parte superior del primer recuadro
        $this->Cell(190, 10, 'Original', 0, 0, 'C'); // Alinear en el centro del recuadro

        
        // Crear recuadro para "Original"
        $this->Rect(10, 10, 190, 10);  // X, Y, Ancho, Alto
    
        
        // Posición Y para el siguiente recuadro
        $y = 20; // Cambiar esto si es necesario para otros elementos

        // Título "A" con tamaño grande
        $this->SetFont('Arial', 'B', 22);
        $titulo = 'C';
        $tituloWidth = $this->GetStringWidth($titulo); // Obtener el ancho del texto del título
        
        // Subtítulo "cod 001" con tamaño más pequeño
        $this->SetFont('Arial', '', 10);
        $subtitulo = 'cod 001'; // Nuevo texto a agregar
        $subtituloWidth = $this->GetStringWidth($subtitulo); // Obtener el ancho del subtítulo

        // Aumentar el ancho total del recuadro para el más ancho de los dos textos
        $totalWidth = max($tituloWidth, $subtituloWidth) + 4; // Agregamos un margen adicional de 10
        
        // Calcular la posición X para centrar el recuadro
        $x = 10 + (190 - $totalWidth) / 2;

        // Crear recuadro centrado alrededor del título y subtítulo
        $this->Rect($x, $y, $totalWidth, 20 ); // Altura aumentada para dos líneas de texto

        // Posicionar el texto "A" en el centro del recuadro
        $this->SetXY($x, $y + 2); // Mover un poco hacia abajo para que quede mejor centrado
        $this->SetFont('Arial', 'B', 16); // Tamaño grande para el título
        $this->Cell($totalWidth, 8, $titulo, 0, 0, 'C'); // Alinear el título en el centro

        // Posicionar el texto "001" en el centro del recuadro debajo de "A"
        $this->SetXY($x, $y + 12); // Mover hacia abajo para el subtítulo
        $this->SetFont('Arial', 'B', 10); // Tamaño más pequeño para el subtítulo
        $this->Cell($totalWidth, 5, $subtitulo, 0, 0, 'C'); // Alinear el subtítulo en el centro

        $this->Ln(-7); // Espacio después del recuadro
        
    }

    function Footer()
    {
        // Set position at 1.5 cm from the bottom
        $this->SetY(-35 ); // Adjust the Y position to leave space for two rows
        $this->SetFont('Arial', 'I', 8);
    
        // Draw outer border for the footer
        $this->Rect(10, $this->GetY(), 190,28); // X, Y, Width, Height
    
        // First Row of Footer Information
        $this->SetY(-30); // Position for the first row
        $this->Cell(90, 6, 'CAE: 12345678901234', 0, 0, 'L'); // Left column
        $this->Cell(90, 6, 'Fecha Vto. CAE: 19/10/2024', 0, 1, 'R'); // Right column
        $this->Cell(90, 6, 'Comprobante Autorizado', 0, 0, 'L'); // Left column
        $this->Cell(90, 6, 'CAI: 701705642879', 0, 1, 'R'); // Right column
    
        // Second Row of Footer Information
        $this->Cell(90, 6, 'Fecha de Vto. del CAI: 24/10/2024', 0, 0, 'L'); // Left column
        $this->Cell(90, 6, '', 0, 1, 'R'); // Empty right cell to balance the row
    
        // Centered Page Number
        $this->SetY(-31); // Set Y position for the page number
        $this->Cell(187, 6, utf8_decode('Página ') . $this->PageNo() . '/{nb}', 0, 0, 'C'); // Centered page number
    
        // Centered QR code below the page number
        $this->SetY(-25); // Adjust this Y position for the QR code
        $this->Image('../presentacion/qr.png', 95, $this->GetY(), 13, 13, 'PNG'); // Center the QR code (X, Y, Width, Height)
    }

    function AddClienteSection($clientData)
    {
        // Guardar la posición inicial y restar para subir el recuadro
        $xStart = 10;
        $yStart = $this->GetY() - 11; // Restar 10 para subir el recuadro más arriba. Cambia este valor según necesites
        
        // Dibujar el recuadro de 190x30 (21 de altura en este caso)
        $this->Rect($xStart, $yStart, 190, 23);

        // Ajustar la posición del contenido del recuadro
        $this->SetFont('Arial', 'B', 12); // Mantener en negrita para el título "Cliente"
        $this->SetXY($xStart, $yStart + 2); // Ajustar posición para el texto "Cliente"
        $this->Cell(0, 5, 'Cliente', 0, 1, 'L');
        $this->Ln(1);
        
        $this->SetFont('Arial', '', 10); // Cambiar a fuente normal para el resto del texto
        $this->SetXY($xStart, $this->GetY()); // Ajustar la posición Y para el resto del contenido
        $this->Cell(110, 6, utf8_decode('Nombre: ' . $clientData['nombre'] . ' ' . $clientData['apellido']), 0, 0, 'L'); // Nombre del cliente
        $this->Cell(90, 6, 'CUIT: ' . $clientData['cuit'], 0, 1, 'L'); // CUIT del cliente
        $this->Cell(110, 6, utf8_decode('Dirección: ' . $clientData['direccion']), 0, 0, 'L'); // Dirección del cliente
        $this->Cell(90, 6, utf8_decode('Condición IVA: Consumidor Final'), 0, 1, 'L'); // Condición IVA del cliente
        $this->Ln(3);
        
        // Línea divisoria horizontal
        $this->Line(10, $this->GetY(), 200, $this->GetY());
        $this->Ln(8);
        
        // Añadir cuadros de verificación
        $this->DrawCheckboxes();
        $this->Ln();
    }

    function DrawCheckboxes()
    {
        // Guardar la posición inicial
        $xStart = 10;
        $yStart = $this->GetY();

        // Dibujar el recuadro de 190x30
        $yStart = $this->GetY() - 8; // Mover hacia arriba 10mm
        $this->Rect($xStart, $yStart, 190, 30);

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
            utf8_decode('Remito N°:'),
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
            $this->Ln(3);

        }
    }

    function AddEmpresaSection($titulocomprobante)
    {
        // Guardar la posición inicial
        $xStart = 10;
        $yStart = $this->GetY();

        // Dibujar el recuadro de 190x30 para la sección de la empresa
        $yStart = $this->GetY() - 5;
        $this->Rect($xStart, $yStart, 190, 43);

        // Contenido de la sección de la empresa
        $this->SetFont('Arial', 'B', 12); // Establecer fuente en negrita para los títulos
        $this->Ln(5);

        $this->Cell(110, 6, 'Empresa: Marquez Comunicaciones', 0, 0, 'L'); // Nombre de la empresa
        $this->Cell(110, 6, 'FACTURA'. $titulocomprobante['tipo_comprobante'], 0, 0, 'L'); // Nombre de la empresa
        // $this->Cell(90, 6, 'CUIT: ' . $clientData['cuit'], 0, 1, 'L'); // CUIT del cliente
        $this->Cell(90, 6, 'Fecha: ' . date('d/m/Y'), 0, 1, 'L'); // Fecha actual
        $this->Cell(110, 6, 'CUIT: 30-12345678-9', 0, 0, 'L'); // CUIT de la empresa
        $this->Cell(90, 6, utf8_decode('Factura N°: 0001-00001234'), 0, 1, 'L'); // Número de factura
        $this->Cell(110, 6, 'Ing. Brutos: 123456789', 0, 0, 'L'); // Ingresos brutos
        $this->Cell(90, 6, 'Punto de Venta: 0001', 0, 1, 'L'); // Punto de venta
        $this->Cell(110, 6, utf8_decode('Condición IVA: Responsable Inscripto'), 0, 0, 'L'); // Condición IVA
        
        // Teléfono alineado a la izquierda
        $this->Cell(90, 6, 'Tel: 011-1234-5678', 0, 1, 'L'); // Teléfono de la empresa
        
        // Dirección de la empresa
        $this->Cell(110, 6, utf8_decode('Dirección: Calle Falsa 123, CABA'), 0, 0, 'L'); 
        
        // Fecha de inicio de actividades alineada a la derecha, debajo del teléfono
        $this->SetXY(140, $this->GetY()); // Posicionar en la parte derecha de la misma línea
        $this->Cell(54.5, 5, utf8_decode('Fecha inicio actividades: 02/02/2000'), 0, 1, 'R'); // Fecha de inicio alineada a la derecha

        $this->Ln(15);
    }


    function AddTotalsSection($subTotal = 0, $totalIVA = 0, $totalFactura = 0)
    {
        // Obtener la posición actual en el eje Y
        $currentY = $this->GetY();
        
        // Asegurarse de que los totales estén exactamente a 120 mm del encabezado, ajustando si es necesario
        if ($currentY < 120) {
            // Si estamos por encima de 120 mm, movemos la posición a 120 mm
            $this->SetY(120);
        } else {
            // Si ya estamos más abajo, solo añadimos un espacio adecuado
            $this->Ln(110);
        }

        // Configurar fuente para los totales
        $this->SetFont('Arial', 'B', 10);

        // Ajustar la posición X para 10 mm de margen desde la izquierda
        $this->SetX(15); // Mueve 10 mm del margen predeterminado de 10 mm a 15 mm

        // Mostrar el Sub-Total
        $this->Cell(160, 6, utf8_decode('Sub-Total:'), 0, 0, 'L');
        $this->Cell(30, 6, '$' . number_format($subTotal, 2), 0, 1, 'L');

        // Ajustar la posición X nuevamente para el Total IVA
        $this->SetX(15); // Mueve 10 mm desde el margen predeterminado

        // Mostrar el Total IVA
        $this->Cell(160, 6, utf8_decode('Total IVA:'), 0, 0, 'L');
        $this->Cell(30, 6, '$' . number_format($totalIVA, 2), 0, 1, 'L');

        // Ajustar la posición X nuevamente para el Total general
        $this->SetX(15); // Mueve 10 mm desde el margen predeterminado

        // Mostrar el Total general
        $this->Cell(160, 6, utf8_decode('Total:'), 0, 0, 'L');
        $this->Cell(30, 6, '$' . number_format($totalFactura, 2), 0, 1, 'L');

        // Espacio adicional después de los totales para separación visual
        $this->Ln(10); // Esto es opcional para agregar espacio después de la sección de totales
    }

    function AddInvoiceSection($products)
    {
        // Establecer la fuente y los encabezados de la tabla
        $this->SetFont('Arial', '', 10);
        $this->SetFillColor(200, 220, 255);
        $this->Cell(30, 10, 'Cantidad', 1, 0, 'C', true);
        $this->Cell(70, 10, utf8_decode('Descripción'), 1, 0, 'C', true);
        $this->Cell(30, 10, 'Precio Unitario', 1, 0, 'C', true);
        $this->Cell(30, 10, 'IVA (21%)', 1, 0, 'C', true);
        $this->Cell(30, 10, 'Total', 1, 1, 'C', true);

        // Variables para los totales
        $subTotal = 0;
        $totalIVA = 0;
        $totalFactura = 0;

        // Máximo de productos a mostrar
        $maxProducts = 19;
        $lineHeight = 6; // Altura de cada línea de producto
        $spaceForProducts = $lineHeight * $maxProducts; // Altura total disponible para productos

        // Limitar el array de productos a 14 elementos
        $productsToShow = array_slice($products, 0, $maxProducts);

        // Posición inicial de la tabla de productos
        $startY = $this->GetY();

        // Mostrar productos y calcular totales
        foreach ($productsToShow as $producto) {
            $cantidad = $producto[0];
            $descripcion = $producto[1];
            $precio_unitario = $producto[2];
            $total = $cantidad * $precio_unitario;
            $iva = $total * 0.21;

            // Acumular totales
            $subTotal += $total;
            $totalIVA += $iva;
            $totalFactura += $total + $iva;

            // Mostrar fila de producto
            $this->Cell(30, $lineHeight, $cantidad, 0, 0, 'C');
            $this->Cell(73, $lineHeight, utf8_decode($descripcion), 0, 0, 'L');
            $this->Cell(30, $lineHeight, '$  ' . number_format($precio_unitario, 2), 0, 0, 'L');
            $this->Cell(30, $lineHeight, '$  ' . number_format($iva, 2), 0, 0, 'L');
            $this->Cell(30, $lineHeight, '$  ' . number_format($total + $iva, 2), 0, 1, 'L');
        }

        // Obtener la posición después de listar productos
        $currentY = $this->GetY();
        $productsHeight = $currentY - $startY;

        // Asegurarse de que los totales estén exactamente a 120 mm del encabezado
        $totalsPositionY = 133;

        // Si la posición actual está por encima de 120 mm, mover el cursor a esa posición
        if ($productsHeight < $spaceForProducts) {
            // Si no hemos llenado todo el espacio destinado a productos, llenar con líneas vacías
            $emptyLines = ($maxProducts - count($productsToShow)) * $lineHeight;
            $this->Ln($emptyLines);
        }

        // Ahora establecer la posición fija para los totales
        $this->SetY($totalsPositionY);

        // Llamar a la función que muestra los totales
        $this->AddTotalsSection($subTotal, $totalIVA, $totalFactura);
    }



}


$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();


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

$query_detalle_factura = "SELECT id_detalle_factura, cantidad_venta, precio_unitario_V, id_accesorios_y_componentes FROM detalle_factura";
$result_detalle_factura = mysqli_query($conn, $query_detalle_factura);

$query_cabecera_factura = "SELECT id_cabecera_factura, id_clientes, id_usuario FROM cabecera_factura";
$result_cabecera_factura = mysqli_query($conn, $query_detalle_factura);


$query_pedidos_de_reparacion = "SELECT id_pedidos_de_reparacion, observacion,id_clientes FROM pedidos_de_reparacion";
$result_pedidos_de_reparacion = mysqli_query($conn, $query_pedidos_de_reparacion);

$query_detalle_reparaciones = "SELECT id_detalle_reparaciones, descripcion, id_pedidos_de_reparacion, id_servicios, id_dispositivos FROM detalle_reparaciones";
$result_detalle_reparaciones = mysqli_query($conn, $query_detalle_reparaciones);

$query_dispositivos = "SELECT id_dispositivos, marca, modelo FROM dispositivos";
$result_dispositivos = mysqli_query($conn, $query_dispositivos);

$query_servicios = "SELECT id_servicios, descripcion, precio_servicio FROM servicios";
$result_servicios = mysqli_query($conn, $query_servicios);

$query_proveedores = "SELECT id_proveedores, nombre, contacto, telefono,direccion FROM proveedores";
$result_proveed = mysqli_query($conn, $query_detalle_factura);



// Verificar si hay datos de clientes
if ($result_clientes && mysqli_num_rows($result_clientes) > 0) {
    // Obtener el primer cliente como ejemplo
    $clientData = mysqli_fetch_assoc($result_clientes);
} else {
    echo "No se encontraron clientes.";
    exit;
}


$query_accesorios_componentes = "SELECT id_accesorios_y_componentes, nombre, precio FROM accesorios_y_componentes";
$result_accesorios_componentes = mysqli_query($conn, $query_accesorios_componentes);

if ($result_tipo_comprobante && mysqli_num_rows($result_tipo_comprobante) > 1) {
    // Obtener el primer cliente como ejemplo
    $titulocomprobante = mysqli_fetch_assoc($result_tipo_comprobante);
} else {
    echo "No se encontraron clientes.";
    exit;
}


$id_cabecera_factura = 3; // Puedes cambiarlo manualmente o ingresar por otro método (como un formulario)

// **Paso 2**: Comprobar si $id_cabecera_factura es 0
if ($id_cabecera_factura == 0) {
    // Si id_cabecera_factura es 0, no buscar productos y pasar un array vacío
    $productos = []; // No cargamos productos
} else {
    // **Consulta de productos si el id_cabecera_factura no es 0**
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
        WHERE 
            df.id_cabecera_factura = $id_cabecera_factura
    ";

    $result = mysqli_query($conn, $query);

    // Inicializamos un array vacío para productos
    $productos = [];
    if ($result && mysqli_num_rows($result) > 0) {
        // Recorrer los resultados y construir la matriz de productos
        while ($row = mysqli_fetch_assoc($result)) {
            $productos[] = [
                $row['cantidad_venta'],             // Cantidad
                $row['nombre_accesorio_componente'], // Descripción (nombre del accesorio/componente)
                $row['precio_unitario_V']            // Precio unitario
            ];
        }
    } else {
        // Si no se encuentran productos, el array de productos seguirá vacío
        echo "No se encontraron productos para el id_cabecera_factura = " . $id_cabecera_factura;
        exit;
    }
}

// Generar la sección de la factura con los datos del cliente y productos
$pdf->AddEmpresaSection($titulocomprobante);
$pdf->AddClienteSection($clientData);
$pdf->AddInvoiceSection($productos); // Pasar los productos o array vacío

// Salida del PDF
$pdf->Output('I', 'factura.pdf'); // Cambia 'I' a 'D' si deseas forzar la descarga

// Cerrar la conexión a la base de datos
mysqli_close($conn);

?>