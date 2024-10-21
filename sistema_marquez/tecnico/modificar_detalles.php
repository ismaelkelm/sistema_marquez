<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>wepp</title>
</head>
<body>
<style>
        body {
            display: flex;
            justify-content: center; /* Centra horizontalmente */
            align-items: center; /* Centra verticalmente */
            height: 100vh; /* Altura completa de la ventana */
            margin: 0; /* Elimina márgenes */
            flex-direction: column; /* Apila elementos en columna */
        }
        .mensaje-exito {
            color: green;
            font-weight: bold;
            font-size: 20px; /* Tamaño de letra más grande */
            border: 2px solid green;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            text-align: center; /* Centra el texto */
        }
        .mensaje-error {
            color: red;
            font-weight: bold;
            font-size: 20px; /* Tamaño de letra más grande */
            border: 2px solid red;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            text-align: center; /* Centra el texto */
        }
    </style>

</body>
</html>
<?php
// Incluir el archivo de conexión a la base de datos
include '../base_datos/db.php';
function procesar_factura($conn, $id_clientes, $fecha_factura, $subtotal_factura, $impuestos, $total_factura, $id_usuario, $id_tipo_comprobante, $id_tipo_de_pago, $id_pedido_reparacion, $cantidad_venta, $precio_unitario_V, $id_accesorios_y_componentes, $servicios, $id_operacion) {
    
    // Primero, obtenemos el último número de factura
    $query_numero_factura = "SELECT MAX(id_cabecera_factura) as ultimo_numero FROM cabecera_factura";
    $result = mysqli_query($conn, $query_numero_factura);
    $row = mysqli_fetch_assoc($result);
    
    // Si no hay facturas previas, el número de factura será 1, si no, le sumamos 1 al último número
    $numero_factura = isset($row['ultimo_numero']) ? $row['ultimo_numero'] + 1 : 1;
    
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
            $id_servicios = $servicios[$key];
            $query_detalle = "INSERT INTO detalle_factura (cantidad_venta, precio_unitario_V, id_accesorios_y_componentes, id_cabecera_factura, id_servicio)
                              VALUES (?, ?, ?, ?, ?)";
            $stmt_detalle = mysqli_prepare($conn, $query_detalle);
            // Aquí se pasa el `$id_servicios` como valor único
            mysqli_stmt_bind_param($stmt_detalle, 'idiii', $cantidad, $precio_unitario, $id_accesorio, $id_cabecera_factura, $id_servicios);
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
        echo "<div class='mensaje-exito'>Factura procesada exitosamente!</div>";
    } catch (Exception $e) {
        // Si hay algún error, deshacer la transacción
        mysqli_rollback($conn);
        echo "<div class='mensaje-error'>Error al procesar la factura: " . $e->getMessage() . "</div>";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_detalle_reparaciones = $_POST['id_detalle_reparaciones'];
    $estado_dispositivo = $_POST['estado_dispositivo'];
    $id_servicio = $_POST['id_servicios'];
    $descripcion = $_POST['descripcion']; // Se asume que puede ser un array
    $id_accesorios_y_componentes = $_POST['id_accesorios_y_componentes'];
    $cantidad_usada = $_POST['cantidad_usada'];
    $id_usuario = $_POST['id_usuario'];
    $id_pedidos_de_reparacion = $_POST['id_pedidos_de_reparacion'];
    $fecha_seguimiento = date('Y-m-d H:i:s'); // Nueva variable para la fecha de seguimiento
    
    // Obtener el ID del dispositivo a partir del ID de detalle de reparaciones
    $query = "SELECT id_dispositivos FROM detalle_reparaciones WHERE id_detalle_reparaciones = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id_detalle_reparaciones);
    $stmt->execute();
    $stmt->bind_result($id_dispositivos);
    $stmt->fetch();
    $stmt->close();

    // Inicializar la variable id_clientes
    $id_clientes = null;
    

    // Procesar la inserción de detalles de reparación
    foreach ($id_accesorios_y_componentes as $index => $id_accesorio) {
        $cantidad = $cantidad_usada[$index];

        // Inserta cada detalle en la tabla detalle_reparaciones
        $insert_query = "INSERT INTO detalle_reparaciones (fecha_seguimiento, descripcion, estado_dispositivo, id_pedidos_de_reparacion, id_servicios, id_dispositivos, id_tecnico, id_accesorio, cantidad_usada)
                         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $insert_stmt = $conn->prepare($insert_query);

        // Asegúrate de que la descripción sea una cadena
        $descripcion_actual = is_array($descripcion) ? implode(', ', $descripcion) : $descripcion;

        $insert_stmt->bind_param("ssiiiiiis", $fecha_seguimiento, $descripcion_actual, $estado_dispositivo, $id_pedidos_de_reparacion, $id_servicio, $id_dispositivos, $id_usuario, $id_accesorio, $cantidad);
        $insert_stmt->execute();
        $insert_stmt->close();

        // Actualizar el stock en la tabla accesorios_y_componentes
        $update_query = "UPDATE accesorios_y_componentes SET stock = stock - ? WHERE id_accesorios_y_componentes = ?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param("ii", $cantidad, $id_accesorio);
        $update_stmt->execute();
        $update_stmt->close();
    }

    // Mostrar un mensaje de éxito o redirigir

    echo "<div class='mensaje-exito'>Detalles procesados exitosamente y stock actualizado.</div>";
    // Verificar si el estado del dispositivo es igual a 1

    if ($estado_dispositivo == 1) {
        // Obtener el id_cliente de la tabla pedidos_de_reparacion usando una subconsulta
        $query_cliente = "SELECT id_clientes FROM pedidos_de_reparacion WHERE id_pedidos_de_reparacion = ?";
        $stmt_cliente = $conn->prepare($query_cliente);
        $stmt_cliente->bind_param("i", $id_pedidos_de_reparacion);
        $stmt_cliente->execute();
        $stmt_cliente->bind_result($id_clientes);
        $stmt_cliente->fetch();
        $stmt_cliente->close();
    
        // Obtener los accesorios y cantidades utilizados para el dispositivo desde la tabla detalle_reparaciones
        // Solo se traerán aquellos donde la cantidad_usada sea distinta de 0
        $query_detalle = "SELECT id_accesorio, cantidad_usada FROM detalle_reparaciones WHERE id_dispositivos = ? AND cantidad_usada != 0";
        $stmt_detalle = $conn->prepare($query_detalle);
        $stmt_detalle->bind_param("i", $id_dispositivos);
        $stmt_detalle->execute();
        $stmt_detalle->bind_result($id_accesorio, $cantidad);
    
        // Inicializar arrays
        $id_accesorios_y_componentes = [];
        $cantidad_usada = [];
    
        // Obtener todos los accesorios y cantidades asociados al dispositivo
        while ($stmt_detalle->fetch()) {
            $id_accesorios_y_componentes[] = $id_accesorio; // Almacenar id_accesorio en el array
            $cantidad_usada[] = $cantidad; // Almacenar cantidad en el array
        }
    
        $stmt_detalle->close();
    
        // Obtener el precio de cada accesorio y almacenarlo en un array
        $precios_accesorios = [];
        foreach ($id_accesorios_y_componentes as $index => $id_accesorio) {
            $query_accesorio = "SELECT precio FROM accesorios_y_componentes WHERE id_accesorios_y_componentes = ?";
            $stmt_accesorio = $conn->prepare($query_accesorio);
            $stmt_accesorio->bind_param("i", $id_accesorio);
            $stmt_accesorio->execute();
            $stmt_accesorio->bind_result($precio_accesorio);
            $stmt_accesorio->fetch();
            $precios_accesorios[] = $precio_accesorio; // Guardar los precios en un array
            $stmt_accesorio->close();
        }
    
        // Crear un array para almacenar los servicios asociados al dispositivo
        $servicios = [];
    
        // Obtener los id_servicios relacionados al dispositivo desde la tabla detalle_reparaciones
        // Solo se traen aquellos donde el id_dispositivos es correcto y también existe un id_accesorio asociado
        $query_servicios = "
        SELECT id_servicios 
        FROM detalle_reparaciones 
        WHERE id_dispositivos = ? 
        AND id_accesorio IS NOT NULL 
        AND cantidad_usada > 0"; // Nos aseguramos que haya un accesorio y que la cantidad usada sea mayor que cero

        $stmt_servicios = $conn->prepare($query_servicios);
        $stmt_servicios->bind_param("i", $id_dispositivos);
        $stmt_servicios->execute();
        $stmt_servicios->bind_result($id_servicios);

        // Almacenar los id_servicios en el array $servicios
        $servicios = [];
        while ($stmt_servicios->fetch()) {
        $servicios[] = $id_servicios;
        }

        $stmt_servicios->close();
        // Procesar la factura usando arrays para cantidad, precios, accesorios y servicios
        $fecha_factura = date('Y-m-d');
        $subtotal_factura = 0; // Según tus cálculos
        $impuestos = 0; // Según tus cálculos
        $total_factura = 0; // Según tus cálculos
        $id_tipo_comprobante = 5; // Por defecto, 5
        $id_tipo_de_pago = 1; // Por defecto, 1
        $id_operacion = 2; // Según corresponda
        $precio_unitario_V = $precios_accesorios; // Array de precios
    
        // Llamar a la función para procesar la factura con arrays
        procesar_factura(
            $conn, 
            $id_clientes, 
            $fecha_factura, 
            $subtotal_factura, 
            $impuestos, 
            $total_factura, 
            $id_usuario, 
            $id_tipo_comprobante, 
            $id_tipo_de_pago, 
            $id_pedidos_de_reparacion, 
            $cantidad_usada, 
            $precio_unitario_V, 
            $id_accesorios_y_componentes, 
            $servicios, // Pasar el array de servicios
            $id_operacion
        );
    }
    
    echo "<script>
            setTimeout(function() {
                window.location.href = 'tareas_pendientes.php';
            }, 1500); 
        </script>";
    exit();
}

?>
