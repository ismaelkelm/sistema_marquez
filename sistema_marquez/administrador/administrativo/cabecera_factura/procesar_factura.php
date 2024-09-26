<?php
// Conexión a la base de datos
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Datos de la cabecera
    $id_cliente = $_POST['id_cliente'];
    $fecha_factura = $_POST['fecha_factura'];
    $id_tipo_comprobante = $_POST['id_tipo_comprobante'];
    $numero_comprobante = $_POST['numero_comprobante'];

    // Insertar cabecera de la factura
    $sql_cabecera = "INSERT INTO cabecera_factura (id_clientes, fecha_factura, id_tipo_comprobante, numero_comprobante) VALUES ('$id_cliente', '$fecha_factura', '$id_tipo_comprobante', '$numero_comprobante')";
    $db->query($sql_cabecera);
    $id_factura = $db->insert_id;

    // Insertar detalles de la factura
    $productos_servicios = $_POST['id_producto_servicio'];
    $cantidades = $_POST['cantidad'];
    $precios = $_POST['precio'];

    for ($i = 0; $i < count($productos_servicios); $i++) {
        $id_producto_servicio = $productos_servicios[$i];
        $cantidad = $cantidades[$i];
        $precio = $precios[$i];

        $sql_detalle = "INSERT INTO detalle_factura (id_cabecera_factura, id_producto_servicio, cantidad, precio_unitario) 
                        VALUES ('$id_factura', '$id_producto_servicio', '$cantidad', '$precio')";
        $db->query($sql_detalle);
    }

    // Redirigir a la página de éxito
    header('Location: administrativo.php?mensaje=Factura registrada correctamente');
    exit;
}
?>
