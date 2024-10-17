<?php
// Incluir el archivo de conexión a la base de datos
include '../../base_datos/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_detalle_reparaciones = $_POST['id_detalle_reparaciones'];
    $estado_dispositivo = $_POST['estado_dispositivo'];
    $id_servicios = $_POST['id_servicios'];
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

    // Procesar la inserción de detalles de reparación
    foreach ($id_accesorios_y_componentes as $index => $id_accesorio) {
        $cantidad = $cantidad_usada[$index];

        // Inserta cada detalle en la tabla detalle_reparaciones
        $insert_query = "INSERT INTO detalle_reparaciones (fecha_seguimiento, descripcion, estado_dispositivo, id_pedidos_de_reparacion, id_servicios, id_dispositivos, id_tecnico, id_accesorio, cantidad_usada)
                         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $insert_stmt = $conn->prepare($insert_query);

        // Asegúrate de que la descripción sea una cadena
        $descripcion_actual = is_array($descripcion) ? implode(', ', $descripcion) : $descripcion;

        $insert_stmt->bind_param("ssiiiiiis", $fecha_seguimiento, $descripcion_actual, $estado_dispositivo, $id_pedidos_de_reparacion, $id_servicios, $id_dispositivos, $id_usuario, $id_accesorio, $cantidad);
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
    echo "<h2>Detalles procesados exitosamente y stock actualizado.</h2>";
    exit();
}
?>
