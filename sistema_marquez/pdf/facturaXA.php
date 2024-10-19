<?php
require('../pdf/fpdf.php');

// Conexión a la base de datos
$host = 'localhost';
$user = 'root';
$password = '543210';
$db_name = 'pruebas_marquez2';
$conn = new mysqli($host, $user, $password, $db_name);


// Verificar si el formulario fue enviado
if (isset($_POST['cliente_id'])) {
    // Obtener el id del cliente seleccionado
    $cliente_id = intval($_POST['cliente_id']);

    // Conexión a la base de datos
    $conn = mysqli_connect("localhost", "usuario", "contraseña", "base_de_datos");

    // Consulta para obtener los datos del cliente seleccionado
    $query_cliente = "SELECT * FROM clientes WHERE id_cliente = $cliente_id";
    $result_cliente = mysqli_query($conn, $query_cliente);

    if ($cliente = mysqli_fetch_assoc($result_cliente)) {
        // Obtener los detalles de la factura del cliente
        $query_detalle_factura = "SELECT * FROM detalle_factura WHERE id_cliente = $cliente_id";
        $result_detalle_factura = mysqli_query($conn, $query_detalle_factura);

        // Aquí comienza el proceso de generación del PDF, tal como lo tienes en tu código

        $pdf = new PDF(); // Asegúrate de que esta línea esté dentro del flujo principal del script
        $pdf->AliasNbPages();
        $pdf->AddPage();

        // Obtener detalles de la factura
        $productos = [];
        while ($detalle = mysqli_fetch_assoc($result_detalle_factura)) {
            // Consulta para obtener información de los productos
            $query_producto = "SELECT nombre, precio FROM accesorios_y_componentes WHERE id_accesorios_y_componentes = " . $detalle['id_accesorios_y_componentes'];
            $resultado_producto = mysqli_query($conn, $query_producto);
            
            if ($producto = mysqli_fetch_assoc($resultado_producto)) {
                // Añadiendo productos al array
                $productos[] = [
                    'cantidad' => $detalle['cantidad_venta'],
                    'descripcion' => utf8_decode($producto['nombre']),
                    'precio_unitario' => $detalle['precio_unitario_V']
                ];
            } else {
                // En caso de que el producto no exista
                $productos[] = [
                    'cantidad' => $detalle['cantidad_venta'],
                    'descripcion' => 'Producto no encontrado',
                    'precio_unitario' => $detalle['precio_unitario_V']
                ];
            }
        }

        // Añadir filas vacías si es necesario
        while (count($productos) < 17) {
            $productos[] = [
                'cantidad' => '',
                'descripcion' => '',
                'precio_unitario' => ''
            ];
        }

        // Llamada a la función que genera la sección de la factura
        $pdf->AddInvoiceSection($productos, $cliente, $numeroOrden);

        // Generar el PDF
        $pdf->Output('I', 'Factura.pdf');
    } else {
        echo "Cliente no encontrado";
    }
} else {
    echo "No se seleccionó ningún cliente.";
}
?>

