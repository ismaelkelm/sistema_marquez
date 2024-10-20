<?php 
// Incluir el archivo de conexión a la base de datos
include '../../base_datos/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos generales del pedido
    $id_pedidos_de_reparacion = trim($_POST['id_pedidos_de_reparacion']);
    $fecha_pedido = trim($_POST['fecha_pedido']);
    $estado_reparacion = trim($_POST['estado_reparacion']);
    $numero_orden = trim($_POST['numero_orden']);
    $observacion = trim($_POST['observacion']);
    $id_clientes = trim($_POST['id_clientes']);
    $id_tecnicos = trim($_POST['id_tecnicos']);  // Un solo valor para id_tecnicos
    // Obtener los IDs de dispositivos seleccionados y convertirlos en un array
    $dispositivos_seleccionados = $_POST['dispositivos_seleccionados'];  // Obtiene la cadena de IDs
    $id_dispositivos = explode(',', $dispositivos_seleccionados); // Convierte la cadena en un array

    // Insertar el nuevo pedido en la tabla pedidos_de_reparacion
    $query_pedido = "INSERT INTO pedidos_de_reparacion 
                     (id_pedidos_de_reparacion, fecha_de_pedido, estado_reparacion, numero_orden, observacion, id_clientes)
                     VALUES ('$id_pedidos_de_reparacion', '$fecha_pedido', '$estado_reparacion', '$numero_orden', '$observacion', '$id_clientes')";

    if (mysqli_query($conn, $query_pedido)) {
        // Si el pedido se registró correctamente, iterar sobre los dispositivos
        foreach ($id_dispositivos as $id_dispositivo) {
            $id_dispositivo = trim($id_dispositivo); // Asegúrate de limpiar espacios

            // Valores predeterminados para detalle_reparaciones
            $fecha_seguimiento = date('Y-m-d H:i:s');  // Valor predeterminado
            $descripcion = '------';  // Valor predeterminado
            $estado_dispositivo = '0';  // Valor predeterminado
            $id_servicios = 1;  // Servicio predeterminado
            $id_accesorio = 0;  // Valor predeterminado para id_accesorio
            $cantidad_usada = 0;  // Valor predeterminado para cantidad_usada

            // Insertar los detalles de reparación correspondientes a cada dispositivo
            $query_detalle = "INSERT INTO detalle_reparaciones 
                              (fecha_seguimiento, descripcion, estado_dispositivo, id_pedidos_de_reparacion, id_servicios, id_dispositivos, id_tecnicos, id_accesorio, cantidad_usada)
                              VALUES ('$fecha_seguimiento', '$descripcion', '$estado_dispositivo', '$id_pedidos_de_reparacion', '$id_servicios', '$id_dispositivo', '$id_tecnicos', '$id_accesorio', '$cantidad_usada')";

            if (!mysqli_query($conn, $query_detalle)) {
                echo "Error al registrar el detalle de reparación: " . mysqli_error($conn) . "<br>";
            }
        }
        header("Location: ../../pdf/facturapedido");
        // Redirigir a asignacion_tareas.php si todos los registros fueron exitosos
        header("Location: ../../tecnico/gestionar_tareas.php");
        exit;  // Asegúrate de salir después de la redirección
    } else {
        echo "Error al registrar el pedido: " . mysqli_error($conn);
    }
}

?>
