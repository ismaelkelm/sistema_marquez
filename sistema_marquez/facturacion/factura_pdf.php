<?php
require_once '../../base_datos/db.php';

$query_tipo_pago = "SELECT id_tipo_de_pago, descripcion_de_pago FROM tipo_de_pago";
$result_tipo_pago = mysqli_query($conn, $query_tipo_pago);

$query_tipo_comprobante = "SELECT id_tipo_comprobante, tipo_comprobante FROM tipo_comprobante";
$result_tipo_comprobante = mysqli_query($conn, $query_tipo_comprobante);

$query_accesorios_componentes = "SELECT id_accesorios_y_componentes, nombre, precio FROM accesorios_y_componentes";
$result_accesorios_componentes = mysqli_query($conn, $query_accesorios_componentes);

$query_clientes = "SELECT id_clientes, nombre, apellido FROM clientes";
$result_clientes = mysqli_query($conn, $query_clientes);

$query_pedidos_de_reparacion = "SELECT id_pedidos_de_reparacion, fecha_de_pedido, observacion FROM pedidos_de_repracion";
$result_pedidos_de_reparacion = mysqli_query($conn, $query_clientes);

$query_detalle_factura = "SELECT id_detalle_factura, cantidad_venta, precio_unitario_V FROM detalle_factura";
$result_detalle_factura = mysqli_query($conn, $query_clientes);

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

?>