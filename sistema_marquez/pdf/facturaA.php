
<?php
require('./fpdf.php');

require_once('../base_datos/db.php');


class PDF extends FPDF
{
    // Constructor
    private $tituloFactura; // Variable para el título de la factura (Original o Duplicado)

    // Constructor original, pero ahora con soporte para el título de la factura
    public function __construct($tituloFactura = 'Original') // Por defecto será 'Original'
    {
        parent::__construct(); // Llamar al constructor de la clase FPDF
        $this->SetMargins(10, 10, 10); // Establecer márgenes de la página
        $this->SetAutoPageBreak(true, 10); // Habilitar saltos de página automáticos
        $this->tituloFactura = $tituloFactura; // Asignar el título de la factura
    }

    // function Header()
    // {
    //     // Título "Original"
    //     $this->SetFont('Arial', '', 10);
    //     $this->SetXY(10, 10); // Posicionar el texto "Original" en la parte superior del primer recuadro
    //     $this->Cell(190, 10, 'Original', 0, 0, 'C'); // Alinear en el centro del recuadro

        
    //     // Crear recuadro para "Original"
    //     $this->Rect(10, 10, 190, 10);  // X, Y, Ancho, Alto
    
        
    //     // Posición Y para el siguiente recuadro
    //     $y = 20; // Cambiar esto si es necesario para otros elementos

    //     // Título "A" con tamaño grande
    //     $this->SetFont('Arial', 'B', 22);
    //     $titulo = 'B';
    //     $tituloWidth = $this->GetStringWidth($titulo); // Obtener el ancho del texto del título
        
    //     // Subtítulo "cod 001" con tamaño más pequeño
    //     $this->SetFont('Arial', '', 10);
    //     $subtitulo = 'cod 001'; // Nuevo texto a agregar
    //     $subtituloWidth = $this->GetStringWidth($subtitulo); // Obtener el ancho del subtítulo

    //     // Aumentar el ancho total del recuadro para el más ancho de los dos textos
    //     $totalWidth = max($tituloWidth, $subtituloWidth) + 4; // Agregamos un margen adicional de 10
        
    //     // Calcular la posición X para centrar el recuadro
    //     $x = 10 + (190 - $totalWidth) / 2;

    //     // Crear recuadro centrado alrededor del título y subtítulo
    //     $this->Rect($x, $y, $totalWidth, 20 ); // Altura aumentada para dos líneas de texto

    //     // Posicionar el texto "A" en el centro del recuadro
    //     $this->SetXY($x, $y + 2); // Mover un poco hacia abajo para que quede mejor centrado
    //     $this->SetFont('Arial', 'B', 16); // Tamaño grande para el título
    //     $this->Cell($totalWidth, 8, $titulo, 0, 0, 'C'); // Alinear el título en el centro

    //     // Posicionar el texto "001" en el centro del recuadro debajo de "A"
    //     $this->SetXY($x, $y + 12); // Mover hacia abajo para el subtítulo
    //     $this->SetFont('Arial', 'B', 10); // Tamaño más pequeño para el subtítulo
    //     $this->Cell($totalWidth, 5, $subtitulo, 0, 0, 'C'); // Alinear el subtítulo en el centro

    //     $this->Ln(-7); // Espacio después del recuadro
        
    // }

    function Header()
    {
        // Título (Original o Duplicado)
        $this->SetFont('Arial', '', 10);
        $this->SetXY(10, 10); // Posicionar el texto en la parte superior del primer recuadro
        $this->Cell(190, 10, $this->tituloFactura, 0, 0, 'C'); // Alinear en el centro del recuadro

        // Crear recuadro para el título
        $this->Rect(10, 10, 190, 10);  // X, Y, Ancho, Alto
        
        // Posición Y para el siguiente recuadro
        $y = 20; // Cambiar esto si es necesario para otros elementos

        // Título "A" con tamaño grande
        $this->SetFont('Arial', 'B', 22);
        $titulo = 'B';
        $tituloWidth = $this->GetStringWidth($titulo); // Obtener el ancho del texto del título

        // Subtítulo "cod 001" con tamaño más pequeño
        $this->SetFont('Arial', '', 10);
        $subtitulo = 'cod 001'; // Nuevo texto a agregar
        $subtituloWidth = $this->GetStringWidth($subtitulo); // Obtener el ancho del subtítulo

        // Aumentar el ancho total del recuadro para el más ancho de los dos textos
        $totalWidth = max($tituloWidth, $subtituloWidth) + 4; // Agregar un margen adicional de 10

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



    function AddEmpresaSection($titulocomprobante, $ultimoId)
    {

        $numeroFactura = sprintf('%04d', $ultimoId); // Formato a 4 dígitos (rellena con ceros a la izquierda si es necesario)
        // Guardar la posición inicial
        $xStart = 10;
        $yStart = $this->GetY();

        // Dibujar el recuadro de 190x50 para la sección de la empresa
        $yStart = $this->GetY() - 5;
        $this->Rect($xStart, $yStart, 190, 40);

        // Contenido de la sección de la empresa
        $this->SetFont('Arial', 'B', 12); // Establecer fuente en negrita para los títulos
        $this->Ln(-2);

        // Información de la empresa y comprobante
        $this->Cell(110, 6, 'Empresa: Marquez Comunicaciones', 0, 0, 'L'); // Nombre de la empresa
        $this->Cell(90, 6, $titulocomprobante['tipo_comprobante'], 0, 1, 'L'); // Tipo de comprobante
        $this->Cell(110, 6, 'CUIT: 30-12345678-9', 0, 0, 'L'); // CUIT de la empresa
        $this->Cell(90, 6, 'Fecha: ' . date('d/m/Y'), 0, 1, 'L'); // Fecha actual
        $this->Cell(110, 6, utf8_decode('Factura N°: 0001-0000' . $numeroFactura), 0, 0, 'L'); // Número de factura
        // $this->Cell(110, 6, utf8_decode('Factura N°: 0001-00001234'), 0, 0, 'L'); // Número de factura
        $this->Cell(90, 6, 'Ing. Brutos: 01-23456789', 0, 1, 'L'); // Ingresos brutos
        $this->Cell(110, 6, 'Punto de Venta: 0001', 0, 0, 'L'); // Punto de venta
        $this->Cell(90, 6, utf8_decode('Condición IVA: Responsable Inscripto'), 0, 1, 'L'); // Condición IVA

        // Teléfonos alineados
        $this->Cell(110, 6, 'Tel: 3764-436974', 0, 0, 'L'); // Teléfono a la izquierda
        $this->Cell(90, 6, 'Tel: 3764-281526', 0, 1, 'L'); // Teléfono a la derecha

        // Dirección de la empresa
        $this->Cell(110, 6, utf8_decode('Dirección: Calle Sarmiento 1994 - Posadas'), 0, 0, 'L'); 

        // Fecha de inicio de actividades alineada a la derecha
        $this->Cell(90, 6, 'Fecha inicio actividades: 02/02/2000', 0, 1, 'L'); // Fecha de inicio alineada a la derecha

        // Agregar un salto de línea
        $this->Ln(12);
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

    function AddClienteSection($clientData)
    {
        // Guardar la posición inicial y restar para subir el recuadro
        $xStart = 10;
        $yStart = $this->GetY() - 11; // Restar para subir el recuadro más arriba. Ajusta según necesidad
        
        // Dibujar el recuadro de 190x23
        $this->Rect($xStart, $yStart, 190, 23);
        
        // Ajustar la posición para el título "Cliente"
        $this->SetFont('Arial', 'B', 12); // Negrita para el título
        $this->SetXY($xStart, $yStart + 2); // Posición para el texto "Cliente"
        $this->Cell(0, 5, 'Cliente', 0, 1, 'L');
        
        // Campo DNI al lado derecho en la misma altura del título "Cliente"
        $this->SetFont('Arial', '', 10); // Cambiar a fuente normal para el resto del texto
        $this->SetXY($xStart + 140, $yStart + 2); // Ajustar posición del DNI
        $this->Cell(-4.8, 6, 'DNI: ' . $clientData['dni'], 0, 1, 'R'); // Posicionar el DNI a la derecha

        // Posicionar el resto de los campos más abajo
        $this->SetXY($xStart, $yStart + 10); // Ajustar posición para el resto del contenido
        $this->Cell(110, 6, utf8_decode('Nombre: ' . $clientData['nombre'] . ' ' . $clientData['apellido']), 0, 0, 'L'); // Nombre del cliente
        $this->Cell(35.5, 6, 'CUIT: ' . $clientData['cuit'], 0, 1, 'R'); // CUIT del cliente
        $this->Cell(110, 6, utf8_decode('Dirección: ' . $clientData['direccion']), 0, 0, 'L'); // Dirección del cliente
        $this->Cell(53.5, 6, utf8_decode('Condición IVA: Consumidor Final'), 0, 1, 'R'); // Condición IVA del cliente
        
        // Espaciado adicional
        $this->Ln(1);
        
        // Línea divisoria horizontal
        $this->Line(10, $this->GetY(), 200, $this->GetY());
        $this->Ln(8);
        
        // Añadir cuadros de verificación
        $this->DrawCheckboxes();
        $this->Ln(2);
    }


}



// Consultas a la base de datos
$query_tipo_pago = "SELECT id_tipo_de_pago, descripcion_de_pago FROM tipo_de_pago";
$result_tipo_pago = mysqli_query($conn, $query_tipo_pago);

$query_tipo_comprobante = "SELECT id_tipo_comprobante, tipo_comprobante FROM tipo_comprobante";
$result_tipo_comprobante = mysqli_query($conn, $query_tipo_comprobante);

$query_accesorios_componentes = "SELECT id_accesorios_y_componentes, nombre, precio FROM accesorios_y_componentes";
$result_accesorios_componentes = mysqli_query($conn, $query_accesorios_componentes);

$query_clientes = "SELECT id_clientes, nombre, apellido, dni, cuit, direccion FROM clientes";
$result_clientes = mysqli_query($conn, $query_clientes);


if ($result_tipo_comprobante && mysqli_num_rows($result_tipo_comprobante) > 1) {
    // Obtener el primer cliente como ejemplo
    $titulocomprobante = mysqli_fetch_assoc($result_tipo_comprobante);
} else {
    echo "No se encontraron clientes.";
    exit;
}

$query = "SELECT id_cabecera_factura FROM cabecera_factura ORDER BY id_cabecera_factura DESC LIMIT 1";
$result = mysqli_query($conn, $query);

// Manejo de resultados
if ($result) {
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $ultimoId = $row['id_cabecera_factura'];
        echo "Último ID de cabecera de factura: " . $ultimoId; // Muestra el último ID
    } else {
        echo "No se encontró ningún registro de cabecera de factura.";
    }
} else {
    echo "Error en la consulta: " . mysqli_error($conn);
}


// Obtener el id_cabecera_factura de la URL
$id_cabecera_factura = $ultimoId;

if ($id_cabecera_factura == 0) {
    $productos = []; // No cargamos productos si no hay cabecera de factura
} else {
    $query = "
        SELECT 
            df.cantidad_venta, 
            ac.nombre AS nombre_accesorio_componente, 
            df.precio_unitario_V,
            c.nombre AS nombre_cliente, 
            c.apellido AS apellido_cliente,
            c.dni AS dni_cliente, 
            c.cuit AS cuit_cliente, 
            c.direccion AS direccion_cliente
        FROM 
            detalle_factura df
        JOIN 
            accesorios_y_componentes ac 
            ON df.id_accesorios_y_componentes = ac.id_accesorios_y_componentes
        JOIN 
            cabecera_factura cf 
            ON df.id_cabecera_factura = cf.id_cabecera_factura
        JOIN 
            clientes c 
            ON cf.id_clientes = c.id_clientes
        WHERE 
            df.id_cabecera_factura = $id_cabecera_factura
    ";

    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        // Almacenar productos y datos del cliente
        $productos = [];
        $cliente = [];
    
        while ($row = mysqli_fetch_assoc($result)) {
            // Almacenar productos
            $productos[] = [
                $row['cantidad_venta'], 
                $row['nombre_accesorio_componente'], 
                $row['precio_unitario_V']
            ];
    
            // Almacenar datos del cliente si aún no están
            if (empty($cliente)) {
                $cliente = [
                    'nombre' => $row['nombre_cliente'],
                    'apellido' => $row['apellido_cliente'],
                    'dni' => $row['dni_cliente'],
                    'cuit' => $row['cuit_cliente'],
                    'direccion' => $row['direccion_cliente']
                ];
            }
        }
    } else {
        echo "No se encontraron productos ni cliente para la factura.";
        exit;
    }
}

$directorio_guardado = '../pdf/facturas'; // Carpeta donde se guardarán las facturas
if (!file_exists($directorio_guardado)) {
    mkdir($directorio_guardado, 0777, true); // Crea el directorio si no existe
}

// Formato de la fecha
$fechaActual = date('Y-m-d'); // Fecha actual en formato YYYY-MM-DD
$nombreCliente = strtolower($cliente['nombre']); // Convertir a minúsculas
$apellidoCliente = strtolower($cliente['apellido']); // Convertir a minúsculas

// Sección de generación del PDF
$clientData = $cliente;
ob_start(); // Inicia la captura de la salida

// Título dinámico: Original
$tituloFactura = 'Original';
$pdfOriginal = new PDF($tituloFactura);
$pdfOriginal->AliasNbPages();
$pdfOriginal->AddPage();

// Verifica que las funciones AddEmpresaSection, AddClienteSection y AddInvoiceSection estén definidas
if (method_exists($pdfOriginal, 'AddEmpresaSection') && method_exists($pdfOriginal, 'AddClienteSection') && method_exists($pdfOriginal, 'AddInvoiceSection')) {
    // Añadir contenido aquí (empresa, cliente, productos, etc.)
    $pdfOriginal->AddEmpresaSection($titulocomprobante, $ultimoId);
    $pdfOriginal->AddClienteSection($clientData);
    $pdfOriginal->AddInvoiceSection($productos);
} else {
    echo "Las funciones AddEmpresaSection, AddClienteSection o AddInvoiceSection no están definidas.";
    exit;
}

// Guardar el PDF con título "Original" en el servidor
$archivoOriginal = $directorio_guardado . '/' . $nombreCliente . '_' . $apellidoCliente . '_factura_original_' . $fechaActual . '.pdf'; // Nombre del archivo
$pdfOriginal->Output('F', $archivoOriginal); // Guarda como "nombre_apellido_factura_original_{fecha}.pdf"

// Mostrar el PDF "Original" en el navegador
$pdfOriginal->Output('I', 'factura_original.pdf'); // 'I' muestra el PDF en el navegador

// Ahora repetimos el proceso para el duplicado

// Título dinámico: Duplicado
$tituloFactura = 'Duplicado';
$pdfDuplicado = new PDF($tituloFactura);
$pdfDuplicado->AliasNbPages();
$pdfDuplicado->AddPage();

// Verifica nuevamente para el PDF duplicado
if (method_exists($pdfDuplicado, 'AddEmpresaSection') && method_exists($pdfDuplicado, 'AddClienteSection') && method_exists($pdfDuplicado, 'AddInvoiceSection')) {
    // Añadir el mismo contenido para el duplicado
    $pdfDuplicado->AddEmpresaSection($titulocomprobante, $ultimoId);
    $pdfDuplicado->AddClienteSection($clientData);
    $pdfDuplicado->AddInvoiceSection($productos);
} else {
    echo "Las funciones AddEmpresaSection, AddClienteSection o AddInvoiceSection no están definidas.";
    exit;
}

// Guardar el PDF con título "Duplicado" en el servidor
$archivoDuplicado = $directorio_guardado . '/' . $nombreCliente . '_' . $apellidoCliente . '_factura_duplicado_' . $fechaActual . '.pdf'; // Nombre del archivo
$pdfDuplicado->Output('F', $archivoDuplicado); // Guarda como "nombre_apellido_factura_duplicado_{fecha}.pdf"

// Mostrar el PDF "Duplicado" en el navegador
$pdfDuplicado->Output('I', 'factura_duplicado.pdf'); // 'I' muestra el PDF en el navegador

// Cerrar la conexión a la base de datos
mysqli_close($conn);
?>
