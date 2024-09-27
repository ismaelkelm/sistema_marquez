<?php
require_once '../base_datos/db.php'; // Conexión a la base de datos

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Primero, obtenemos el último número de factura
    $query_numero_factura = "SELECT MAX(id_cabecera_factura) as ultimo_numero FROM cabecera_factura";
    $result = mysqli_query($conn, $query_numero_factura);
    $row = mysqli_fetch_assoc($result);
    
    // Si no hay facturas previas, el número de factura será 1, si no, le sumamos 1 al último número
    $numero_factura = isset($row['ultimo_numero']) ? $row['ultimo_numero'] + 1 : 1;
    echo "numero de factura ",$numero_factura;
    // Recibir datos del formulario
    $dni_cliente = $_POST['dni_cliente'];
    $id_clientes = $_POST['id_clientes'];
    $fecha_factura = $_POST['fecha_factura'];
    $subtotal_factura = $_POST['subtotal_factura'];
    $impuestos = $_POST['iva_resultado'];
    $total_factura = $_POST['total'];
    $id_usuario = $_POST['id_usuario'];
    $id_tipo_comprobante = $_POST['id_tipo_comprobante'];
    $id_tipo_de_pago = $_POST['id_tipo_de_pago'];
    // Verificar si el campo está presente en el POST y no es nulo o vacío
    if (isset($_POST['id_pedido_reparacion']) && !empty($_POST['id_pedido_reparacion'])) {
        $id_pedido_reparacion = $_POST['id_pedido_reparacion'];
        // Aquí puedes continuar con tu lógica
    } else {
        $id_pedido_reparacion =0;
    }
    // Recibir datos de los detalles de la factura
    $cantidad_venta = $_POST['cantidad_venta'];
    $precio_unitario_V = $_POST['precio_unitario_V'];
    $id_accesorios_y_componentes = $_POST['id_accesorios_y_componentes'];
    $id_operacion = $_POST['id_operacion'];;

    // Empezar la transacción
    mysqli_begin_transaction($conn);

    try {
        // Insertar en la tabla `cabecera_factura`
        $query_factura = "INSERT INTO cabecera_factura (id_cabecera_factura, fecha_factura, subtotal_factura, impuestos, total_factura, id_clientes, id_usuario, id_operacion, id_tipo_comprobante, id_tipo_de_pago, id_pedido_reparacion) 
                          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt_factura = mysqli_prepare($conn, $query_factura);
        mysqli_stmt_bind_param($stmt_factura, 'issddiiiiii', $numero_factura, $fecha_factura, $subtotal_factura, $impuestos, $total_factura, $id_clientes, $id_usuario, $id_operacion, $id_tipo_comprobante, $id_tipo_de_pago, $id_pedido_reparacion);
        mysqli_stmt_execute($stmt_factura);

        // Obtener el id de la cabecera de la factura recién insertada
        $id_cabecera_factura = mysqli_insert_id($conn);

        // Insertar cada detalle de la factura
        foreach ($id_accesorios_y_componentes as $key => $id_accesorio) {
            $cantidad = $cantidad_venta[$key];
            $precio_unitario = $precio_unitario_V[$key];

            $query_detalle = "INSERT INTO detalle_factura (cantidad_venta, precio_unitario_V, id_accesorios_y_componentes, id_cabecera_factura)
                              VALUES (?, ?, ?, ?)";
            $stmt_detalle = mysqli_prepare($conn, $query_detalle);
            mysqli_stmt_bind_param($stmt_detalle, 'idii', $cantidad, $precio_unitario, $id_accesorio, $id_cabecera_factura);
            mysqli_stmt_execute($stmt_detalle);

            // Actualizar el stock de los accesorios
            $query_update_stock = "UPDATE accesorios_y_componentes SET stock = stock - ? WHERE id_accesorios_y_componentes = ?";
            $stmt_stock = mysqli_prepare($conn, $query_update_stock);
            mysqli_stmt_bind_param($stmt_stock, 'ii', $cantidad, $id_accesorio);
            mysqli_stmt_execute($stmt_stock);
        }

        // Commit si todo fue bien
        mysqli_commit($conn);

        // Redireccionar o mostrar mensaje de éxito
        echo "SIII";
        exit;
    } catch (Exception $e) {
        // Si hay algún error, deshacer la transacción
        mysqli_rollback($conn);
        echo "Error al procesar la factura: " . $e->getMessage();
    }
}
?>
