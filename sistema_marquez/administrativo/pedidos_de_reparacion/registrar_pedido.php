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

    // Obtener arrays de dispositivos y técnicos
    $id_dispositivos = $_POST['id_dispositivos'];  // Es un array
    $id_tecnicos = $_POST['id_tecnicos'];  // Es un array de técnicos asignados a los dispositivos

    // Insertar el nuevo pedido en la tabla pedidos_de_reparacion
    $query_pedido = "INSERT INTO pedidos_de_reparacion 
                     (id_pedidos_de_reparacion, fecha_de_pedido, estado_reparacion, numero_orden, observacion, id_clientes)
                     VALUES ('$id_pedidos_de_reparacion', '$fecha_pedido', '$estado_reparacion', '$numero_orden', '$observacion', '$id_clientes')";

    if (mysqli_query($conn, $query_pedido)) {
        echo "Pedido registrado exitosamente.<br>";

        // Iterar sobre los dispositivos y técnicos para registrar en detalle_reparaciones
        for ($i = 0; $i < count($id_dispositivos); $i++) {
            $id_dispositivo = trim($id_dispositivos[$i]);
            $id_tecnico = trim($id_tecnicos[$i]);

            // Valores predeterminados para detalle_reparaciones
            $fecha_finalizada = '0000-00-00';  // Valor predeterminado
            $descripcion = '------';  // Valor predeterminado
            $estado_dispositivo = '0';  // Valor predeterminado
            $id_servicios = 1;  // Servicio predeterminado

            // Insertar los detalles de reparación correspondientes a cada dispositivo
            $query_detalle = "INSERT INTO detalle_reparaciones 
                              (fecha_finalizada, descripcion, estado_dispositivo, id_pedidos_de_reparacion, id_servicios, id_dispositivos, id_tecnicos)
                              VALUES ('$fecha_finalizada', '$descripcion', '$estado_dispositivo', '$id_pedidos_de_reparacion', '$id_servicios', '$id_dispositivo', '$id_tecnico')";

            if (mysqli_query($conn, $query_detalle)) {
                echo "Detalle de reparación para el dispositivo $id_dispositivo registrado correctamente.<br>";
            } else {
                echo "Error al registrar el detalle de reparación: " . mysqli_error($conn) . "<br>";
            }
        }
    } else {
        echo "Error al registrar el pedido: " . mysqli_error($conn);
    }
}
?>
