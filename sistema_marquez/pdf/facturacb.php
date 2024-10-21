
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


    function AddEmpresaSection($titulocomprobante, $ultimoId) 
    {
        $numeroFactura = sprintf('%04d', $ultimoId); // Formato a 4 dígitos
        // Guardar la posición inicial
        $xStart = 10;
        $yStart = $this->GetY();

        // Dibujar el recuadro de 190x50 para la sección de la empresa
        $yStart = $this->GetY() - 5;
        $this->Rect($xStart, $yStart, 190, 40);

        // Contenido de la sección de la empresa
        $this->SetFont('Arial', 'B', 12); // Establecer fuente en negrita
        $this->Ln(-2);

        // Información de la empresa y comprobante
        $this->Cell(110, 6, 'Empresa: Marquez Comunicaciones', 0, 0, 'L');
        $this->Cell(90, 6, $titulocomprobante['tipo_comprobante'], 0, 1, 'L');
        $this->Cell(110, 6, 'CUIT: 30-12345678-9', 0, 0, 'L');
        $this->Cell(90, 6, 'Fecha: ' . date('d/m/Y'), 0, 1, 'L');
        $this->Cell(110, 6, utf8_decode('Factura N°: 0001-0000' . $numeroFactura), 0, 0, 'L');
        $this->Cell(90, 6, 'Ing. Brutos: 01-23456789', 0, 1, 'L');
        $this->Cell(110, 6, 'Punto de Venta: 0001', 0, 0, 'L');
        $this->Cell(90, 6, utf8_decode('Condición IVA: Responsable Inscripto'), 0, 1, 'L');
        $this->Cell(110, 6, 'Tel: 3764-436974', 0, 0, 'L');
        $this->Cell(90, 6, 'Tel: 3764-281526', 0, 1, 'L');
        $this->Cell(110, 6, utf8_decode('Dirección: Calle Sarmiento 1994 - Posadas'), 0, 0, 'L');
        $this->Cell(90, 6, 'Fecha inicio actividades: 02/02/2000', 0, 1, 'L');

        // Agregar un salto de línea
        $this->Ln(12);
    }



    function AddTotalsSection($subTotal, $totalIVA, $totalFactura)
    {
        // Agregar línea para el subtotal

        $this->Ln(100);
        $this->Cell(130, 5, '', 0, 0);
        $this->Cell(25, 5, 'Subtotal', 0, 0);
        $this->Cell(34, 5, '$' . number_format($subTotal, 2, '.', ','), 0, 1, 'R'); // Formatear el subtotal con dos decimales

        // Agregar línea para el IVA (21%)
        $this->Cell(130, 5, '', 0, 0);
        $this->Cell(25, 5, 'IVA (21%)', 0, 0);
        $this->Cell(34, 5, '$' . number_format($totalIVA, 2, '.', ','), 0, 1, 'R'); // Formatear el IVA

        // Agregar línea para el total de la factura
        $this->Cell(130, 5, '', 0, 0);
        $this->Cell(25, 5, 'Total', 0, 0);
        $this->Cell(34, 5, '$' . number_format($totalFactura, 2, '.', ','), 0, 1, 'R'); // Formatear el total
        

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


    // function AddInvoiceSection($servicios, $dispositivos)
    // {
    //     // Posición actual
    //     $currentY = $this->GetY();
    //     $this->SetY($currentY + 42); // Ajusta el valor según tu diseño

    //     // Encabezado
    //     $this->SetFont('Arial', '', 10);
    //     $this->SetFillColor(200, 220, 255);
    //     $this->Cell(30, 10, 'Cantidad', 1, 0, 'C', true);
    //     $this->Cell(70, 10, utf8_decode('Descripción'), 1, 0, 'C', true);
    //     $this->Cell(30, 10, 'Precio Unitario', 1, 0, 'C', true);
    //     $this->Cell(30, 10, 'IVA (21%)', 1, 0, 'C', true);
    //     $this->Cell(30, 10, 'Total', 1, 1, 'C', true);

    //     // Variables para totales
    //     $subTotal = 0;
    //     $totalIVA = 0;
    //     $totalFactura = 0;
    //     $lineHeight = 6; // Altura de la celda

    //     // Agregar servicios
    //     if (!empty($servicios)) {
    //         foreach ($servicios as $servicio) {
    //             $this->Cell(30, $lineHeight, '1', 0, 0, 'C');
    //             $this->Cell(70, $lineHeight, utf8_decode($servicio['descripcion']), 0, 0, 'L');
    //             $this->Cell(30, $lineHeight, '$  ' . number_format($servicio['precio'], 2), 0, 0, 'L');
    //             $ivaServicio = $servicio['precio'] * 0.21;
    //             $this->Cell(30, $lineHeight, '$  ' . number_format($ivaServicio, 2), 0, 0, 'L');
    //             $this->Cell(30, $lineHeight, '$  ' . number_format($servicio['precio'] + $ivaServicio, 2), 0, 1, 'L');
    //             $subTotal += $servicio['precio'];
    //             $totalIVA += $ivaServicio;
    //             $totalFactura += $servicio['precio'] + $ivaServicio;
    //         }
    //     }

    //     // Agregar dispositivos
    //     if (!empty($dispositivos)) {
    //         foreach ($dispositivos as $dispositivo) {
    //             // Asegúrate de que el dispositivo tenga marca y modelo
    //             if (!empty($dispositivo['marca']) && !empty($dispositivo['modelo'])) {
    //                 $this->Cell(30, $lineHeight, '1', 0, 0, 'C');
    //                 $this->Cell(70, $lineHeight, utf8_decode($dispositivo['marca'] . ' ' . $dispositivo['modelo']), 0, 0, 'L');
    //                 $this->Cell(30, $lineHeight, 'N/A', 0, 0, 'L');
    //                 $this->Cell(30, $lineHeight, 'N/A', 0, 0, 'L');
    //                 $this->Cell(30, $lineHeight, 'N/A', 0, 1, 'L'); // No hay precio ni IVA para dispositivos
    //             }
    //         }
    //     }

    //     // Posición para los totales
    //     $fixedY = 145;
    //     $this->SetY($fixedY);

    //     // Mostrar totales
    //     $this->AddTotalsSection($subTotal, $totalIVA, $totalFactura);
    // }


    function AddDevices($dispositivos, $lineHeight)
    {
        if (!empty($dispositivos)) {
            foreach ($dispositivos as $dispositivo) {
                // Solo imprime el dispositivo si tiene marca y modelo
                if (!empty($dispositivo['marca']) && !empty($dispositivo['modelo'])) {
                    $this->Cell(30, $lineHeight, '1', 0, 0, 'C'); // Cantidad fija como 1 para dispositivos
                    $this->Cell(70, $lineHeight, utf8_decode($dispositivo['marca'] . ' ' . $dispositivo['modelo']), 0, 0, 'L');
                    $this->Cell(30, $lineHeight, 'N/A', 0, 0, 'L'); // Precio no aplicable
                    $this->Cell(30, $lineHeight, 'N/A', 0, 0, 'L'); // IVA no aplicable
                    $this->Cell(30, $lineHeight, 'N/A', 0, 1, 'L'); // Total no aplicable
                }
            }
        }
    }
    
    function AddInvoiceSection($servicios, $dispositivos, $productos = [])
    {
        // Posición actual
        $currentY = $this->GetY();
        $this->SetY($currentY + 42); // Ajusta el valor según tu diseño

        // Encabezado
        $this->SetFont('Arial', '', 10);
        $this->SetFillColor(200, 220, 255);
        $this->Cell(30, 10, 'Cantidad', 1, 0, 'C', true);
        $this->Cell(70, 10, utf8_decode('Descripción'), 1, 0, 'C', true);
        $this->Cell(30, 10, 'Precio Unitario', 1, 0, 'C', true);
        $this->Cell(30, 10, 'IVA (21%)', 1, 0, 'C', true);
        $this->Cell(30, 10, 'Total', 1, 1, 'C', true);

        // Variables para totales
        $subTotal = 0;
        $totalIVA = 0;
        $totalFactura = 0;
        $lineHeight = 6; // Altura de la celda

        // Agregar servicios
        if (!empty($servicios)) {
            foreach ($servicios as $servicio) {
                $this->Cell(30, $lineHeight, '1', 0, 0, 'C');
                $this->Cell(70, $lineHeight, utf8_decode($servicio['descripcion']), 0, 0, 'L');
                $this->Cell(30, $lineHeight, '$  ' . number_format($servicio['precio'], 2), 0, 0, 'L');
                $ivaServicio = $servicio['precio'] * 0.21;
                $this->Cell(30, $lineHeight, '$  ' . number_format($ivaServicio, 2), 0, 0, 'L');
                $totalServicio = $servicio['precio'] + $ivaServicio;
                $this->Cell(30, $lineHeight, '$  ' . number_format($totalServicio, 2), 0, 1, 'L');
                $subTotal += $servicio['precio'];
                $totalIVA += $ivaServicio;
                $totalFactura += $totalServicio;
            }
        }

        

        // Imprimir dispositivos utilizando la función AddDevices
        $this->AddDevices($dispositivos, $lineHeight);

        // Agregar productos
        if (!empty($productos)) {
            foreach ($productos as $producto) {
                $cantidad = $producto[0]; // Cantidad
                $descripcion = $producto[1]; // Descripción
                $precio_unitario = $producto[2]; // Precio unitario
                $total = $cantidad * $precio_unitario; // Total
                $iva = $total * 0.21; // IVA

                // Mostrar cada producto en una fila
                $this->Cell(30, $lineHeight, $cantidad, 0, 0, 'C');
                $this->Cell(70, $lineHeight, utf8_decode($descripcion), 0, 0, 'L');
                $this->Cell(30, $lineHeight, '$  ' . number_format($precio_unitario, 2), 0, 0, 'L');
                $this->Cell(30, $lineHeight, '$  ' . number_format($iva, 2), 0, 0, 'L');
                $this->Cell(30, $lineHeight, '$  ' . number_format($total + $iva, 2), 0, 1, 'L');

                // Sumar los totales
                $subTotal += $total;
                $totalIVA += $iva;
                $totalFactura += $total + $iva;
            }
        }

        // Posición para los totales
        $fixedY = 145; // Ajusta esto según tu diseño
        $this->SetY($fixedY);

        // Mostrar totales
        $this->AddTotalsSection($subTotal, $totalIVA, $totalFactura);
    }

    

    function Addproducts($productos)
    {
        $productosAlmacenados = []; // Array para almacenar los productos
    
        foreach ($productos as $producto) {
            if (count($producto) === 3) { // Asegúrate de que el producto tenga 3 elementos
                $cantidad = $producto[0];
                $descripcion = $producto[1];
                $precio_unitario = $producto[2];
    
                // Almacena los datos del producto en un array
                $productosAlmacenados[] = [
                    'cantidad' => $cantidad,
                    'descripcion' => $descripcion,
                    'precio_unitario' => $precio_unitario
                ];
            }
        }
    
        return $productosAlmacenados; // Devuelve el array de productos almacenados
    }
    

    function AddServices($servicios)
    {

        foreach ($servicios as $servicio) {
            // $this->Cell(80, 10, $servicio['descripcion'], 1);
            // $this->Cell(30, 10, '$' . number_format($servicio['precio'], 2, '.', ','), 1);
        }

    }

    // function AddDevices($dispositivos)
    // {
    //     foreach ($dispositivos as $dispositivo) {
    //         // $this->Cell(80, 10, utf8_decode($dispositivo['marca'] . ' ' . $dispositivo['modelo']), 1);
    //     }
    // }

    function AddClienteSection($cliente)
    {
        // Guardar la posición inicial y restar para subir el recuadro
        $xStart = 10;
        $yStart = $this->GetY() - 200; // Restar para subir el recuadro más arriba. Ajusta según necesidad

        // Dibujar el recuadro de 190x23
        $this->Rect($xStart, $yStart, 190, 23);

        // Ajustar la posición para el título "Cliente"
        $this->SetFont('Arial', 'B', 12); // Negrita para el título
        $this->SetXY($xStart, $yStart + 2); // Posición para el texto "Cliente"
        $this->Cell(0, 5, 'Cliente', 0, 1, 'L');

        // Campo DNI al lado derecho en la misma altura del título "Cliente"
        $this->SetFont('Arial', '', 10); // Cambiar a fuente normal para el resto del texto
        $this->SetXY($xStart + 140, $yStart + 2); // Ajustar posición del DNI
        $this->Cell(-10.8, 6, 'DNI: ' . $cliente['dni'], 0, 1, 'R'); // Posicionar el DNI a la derecha

        // Posicionar el resto de los campos más abajo
        $this->SetXY($xStart, $yStart + 10); // Ajustar posición para el resto del contenido
        $this->Cell(110, 6, utf8_decode('Nombre: ' . $cliente['nombre'] . ' ' . $cliente['apellido']), 0, 0, 'L'); // Nombre del cliente
        $this->Cell(35, 6, 'CUIT: ' . $cliente['cuit'], 0, 1, 'R'); // CUIT del cliente
        $this->Cell(110, 6, utf8_decode('Dirección: ' . $cliente['direccion']), 0, 0, 'L'); // Dirección del cliente
        $this->Cell(53, 6, utf8_decode('Condición IVA: Consumidor Final'), 0, 1, 'R'); // Condición IVA del cliente

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
$query_tipo_comprobante = "SELECT id_tipo_comprobante, tipo_comprobante FROM tipo_comprobante";
$result_tipo_comprobante = mysqli_query($conn, $query_tipo_comprobante);

if ($result_tipo_comprobante && mysqli_num_rows($result_tipo_comprobante) > 0) {
    // Obtener el primer tipo de comprobante como ejemplo
    $titulocomprobante = mysqli_fetch_assoc($result_tipo_comprobante);
} else {
    echo "No se encontraron tipos de comprobante.";
    exit;
}

// Consultas generales
$querys = [
    'tipo_pago' => "SELECT id_tipo_de_pago, descripcion_de_pago FROM tipo_de_pago",
    'tipo_comprobante' => "SELECT id_tipo_comprobante, tipo_comprobante FROM tipo_comprobante",
    'clientes' => "SELECT id_clientes, nombre, apellido, dni, cuit, direccion FROM clientes",
    'pedidos_de_reparacion' => "SELECT id_pedidos_de_reparacion, numero_orden, id_clientes FROM pedidos_de_reparacion",
    'detalle_reparaciones' => "SELECT id_pedidos_de_reparacion, id_servicios, id_dispositivos, id_accesorio, cantidad_usada FROM detalle_reparaciones",
    'servicios' => "SELECT id_servicios, descripcion, precio_servicio FROM servicios",
    'dispositivos' => "SELECT id_dispositivos, marca, modelo FROM dispositivos"
];

// Ejecutar consultas y almacenar resultados
foreach ($querys as $key => $query) {
    $$key = mysqli_query($conn, $query);
    if (!$$key) {
        echo "Error en la consulta de $key: " . mysqli_error($conn);
        exit;
    }
}

// Verificar resultados de tipo de comprobante
if (isset($tipo_comprobante) && mysqli_num_rows($tipo_comprobante) > 0) {
    $titulocomprobante = mysqli_fetch_assoc($tipo_comprobante);
} else {
    echo "No se encontraron tipos de comprobante.";
    exit;
}

// Ingresar manualmente el ID de cabecera de factura
$id_cabecera_factura = 13; // Cambia este valor por el ID que deseas usar

// Validar ID de cabecera de factura
$id_cabecera_factura = intval($id_cabecera_factura);

// Consultar si el id_cabecera_factura existe
$query = "SELECT * FROM cabecera_factura WHERE id_cabecera_factura = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'i', $id_cabecera_factura);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($result && mysqli_num_rows($result) > 0) {
    // ID válido, proceder a la consulta de detalles
    $queryDetalles = "
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

    $resultDetalles = mysqli_query($conn, $queryDetalles);

    if ($resultDetalles && mysqli_num_rows($resultDetalles) > 0) {
        // Almacenar productos y datos del cliente
        $productos = [];
        $cliente = [];

        while ($row = mysqli_fetch_assoc($resultDetalles)) {
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
        exit; // Salir si no hay productos
    }
} else {
    echo "No se encontró ninguna cabecera de factura con el ID ingresado.";
    exit; // Salir si el ID no es válido
}

// Consulta para obtener los detalles del último detalle de reparación
$ultimoIdDetalle = 20; // Cambia este valor según sea necesario
$query = "
SELECT
    pr.numero_orden,
    c.nombre AS nombre_cliente,
    c.apellido AS apellido_cliente,
    c.dni,
    c.cuit,
    c.direccion,
    s.descripcion AS descripcion_servicio,
    s.precio_servicio,
    d.marca,
    d.modelo,
    dr.cantidad_usada
FROM
    detalle_reparaciones dr
JOIN
    pedidos_de_reparacion pr ON dr.id_pedidos_de_reparacion = pr.id_pedidos_de_reparacion
JOIN
    clientes c ON pr.id_clientes = c.id_clientes
JOIN
    servicios s ON dr.id_servicios = s.id_servicios
JOIN
    dispositivos d ON dr.id_dispositivos = d.id_dispositivos
WHERE
    dr.id_detalle_reparaciones = $ultimoIdDetalle";

$result = mysqli_query($conn, $query);

if (!$result) {
    echo "Error en la consulta: " . mysqli_error($conn);
    exit;
}

if (mysqli_num_rows($result) > 0) {
    // Almacenar los datos
    $cliente = [];
    $dispositivo = [];
    $servicios = [];

    while ($row = mysqli_fetch_assoc($result)) {
        // Almacenar los datos del cliente
        if (empty($cliente)) {
            $cliente = [
                'nombre' => $row['nombre_cliente'],
                'apellido' => $row['apellido_cliente'],
                'dni' => $row['dni'],
                'cuit' => $row['cuit'],
                'direccion' => $row['direccion']
            ];
        }

        // Guardar los datos del dispositivo (una sola vez)
        if (empty($dispositivo)) {
            $dispositivo = [
                'marca' => $row['marca'],
                'modelo' => $row['modelo']
            ];
        }

        // Guardar los servicios
        if (!empty($row['descripcion_servicio'])) {
            $servicios[] = [
                'descripcion' => $row['descripcion_servicio'],
                'precio' => $row['precio_servicio']
            ];
        }
    }

    ob_start(); // Iniciar el buffer de salida

    // Generar el PDF
    $pdf = new PDF('Original');
    $pdf->AddPage();

// Consultas a la base de datos
$query_tipo_comprobante = "SELECT id_tipo_comprobante, tipo_comprobante FROM tipo_comprobante";
$result_tipo_comprobante = mysqli_query($conn, $query_tipo_comprobante);

if ($result_tipo_comprobante && mysqli_num_rows($result_tipo_comprobante) > 0) {
    // Obtener el primer tipo de comprobante como ejemplo
    $titulocomprobante = mysqli_fetch_assoc($result_tipo_comprobante);
} else {
    echo "No se encontraron tipos de comprobante.";
    exit;
}

// Ingresar manualmente el ID de cabecera de factura
$id_cabecera_factura = 3; // Cambia este valor por el ID que deseas usar

// Verificar si se ingresó un ID
if ($id_cabecera_factura > 0) {
    // Consultar si el id_cabecera_factura existe
    $query = "SELECT * FROM cabecera_factura WHERE id_cabecera_factura = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'i', $id_cabecera_factura);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) > 0) {
        // ID válido, proceder a la consulta de detalles
        $rowCabecera = mysqli_fetch_assoc($result);
        $ultimoId = $rowCabecera['id_cabecera_factura']; // Asignar el ID

        // Aquí puedes proceder a consultar los detalles de la factura
        // (agrega tu lógica para obtener detalles de la factura aquí)
    } else {
        echo "No se encontró ninguna cabecera de factura con el ID ingresado.";
        exit; // Salir si el ID no es válido
    }
} else {
    echo "Por favor, ingrese un ID de cabecera de factura válido.";
    exit; // Salir si el ID es 0 o no se ingresó
}

// Ahora llamamos a la función AddInvoiceSection
// Verificamos que $titulocomprobante y $ultimoId estén disponibles
if (isset($titulocomprobante) && isset($ultimoId)) {
    $pdf->AddEmpresaSection($titulocomprobante, $ultimoId); // Asegúrate de acceder correctamente al tipo de comprobante
} else {
    echo "Error: No se pudo obtener el título del comprobante o el último ID.";
    exit;
}


    if (!empty($servicios) || !empty($dispositivo)) {
        $pdf->AddInvoiceSection($servicios, $dispositivo, $productos);
        $pdf->AddClienteSection($cliente);
    }

    // Ruta para guardar los archivos PDF
    $directorio_guardado = '../pdf/facturapedido'; // Carpeta donde se guardarán las facturas
    if (!file_exists($directorio_guardado)) {
        mkdir($directorio_guardado, 0777, true); // Crea el directorio si no existe
    }

    // Formato de la fecha
    $fechaActual = date('Y-m-d'); // Fecha actual en formato YYYY-MM-DD
    $nombreCliente = strtolower($cliente['nombre']); // Convertir a minúsculas
    $apellidoCliente = strtolower($cliente['apellido']); // Convertir a minúsculas

    // Guardar el PDF como original
    $archivoOriginal = $directorio_guardado . '/' . $nombreCliente . '_' . $apellidoCliente . '_factura_original_' . $fechaActual . '.pdf'; // Nombre del archivo
    $pdf->Output('F', $archivoOriginal); // Guarda como original

    // Mostrar el PDF "Original" en el navegador
    $pdf->Output('I', 'factura_original.pdf'); // 'I' muestra el PDF en el navegador

    // Generar el PDF duplicado
    $pdfDuplicado = new PDF('Duplicado');
    $pdfDuplicado->AddPage();
    $pdfDuplicado->AddEmpresaSection($titulocomprobante, $ultimoId); // Asegúrate de acceder correctamente al tipo de comprobante
    $pdfDuplicado->AddInvoiceSection($servicios, $dispositivo, $productos);
    $pdfDuplicado->AddClienteSection($cliente);
    $archivoDuplicado = $directorio_guardado . '/' . $nombreCliente . '_' . $apellidoCliente . '_factura_duplicado_' . $fechaActual . '.pdf'; // Nombre del archivo
    $pdfDuplicado->Output('F', $archivoDuplicado); // Guarda como duplicado
} else {
    echo "No se encontraron detalles de reparación.";
    exit;
}
mysqli_close($conn);
?>



