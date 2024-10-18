<?php
// Incluir el archivo de conexión a la base de datos
include '../base_datos/db.php';

// Verificar si el formulario fue enviado correctamente
if (isset($_POST['id_detalle_reparaciones'])) {
    
    // Obtener los datos enviados desde el formulario
    $id_detalle_reparaciones = $_POST['id_detalle_reparaciones'];

    // Obtener el detalle actual para conservar ciertos valores
    $query_select = "SELECT id_pedidos_de_reparacion, id_dispositivos FROM detalle_reparaciones WHERE id_detalle_reparaciones = ?";
    $stmt_select = $conn->prepare($query_select);

    // Verificar si la conexión a la base de datos fue exitosa
    if ($stmt_select === false) {
        die('Error al preparar la consulta: ' . $conn->error);
    }

    $stmt_select->bind_param("i", $id_detalle_reparaciones);
    $stmt_select->execute();
    $result_select = $stmt_select->get_result();

    // Verificar si el detalle existe
    if ($result_select->num_rows > 0) {
        $detalle_actual = $result_select->fetch_assoc();

        // Establecer los valores modificados
        $descripcion = '--------';
        $estado_dispositivo = 0;
        $id_servicios = 1;
        $id_tecnico = 0;
        
        // Mantener el id_pedidos_de_reparacion y id_dispositivos del detalle original
        $id_pedidos_de_reparacion = $detalle_actual['id_pedidos_de_reparacion'];
        $id_dispositivos = $detalle_actual['id_dispositivos'];
        
        // Obtener la fecha y hora actual en formato DATETIME
        $fecha_seguimiento = date('Y-m-d H:i:s');
        $id_accesorio = 0;
        $cantidad = 0;
        
        // Insertar el nuevo detalle en la tabla `detalle_reparaciones`
        $query_insert = "
            INSERT INTO detalle_reparaciones 
            (fecha_seguimiento, descripcion, estado_dispositivo, id_pedidos_de_reparacion, id_servicios, id_dispositivos, id_tecnico, id_accesorio, cantidad_usada)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt_insert = $conn->prepare($query_insert);

        // Verificar si la preparación fue exitosa
        if ($stmt_insert === false) {
            die('Error al preparar la consulta de inserción: ' . $conn->error);
        }

        $stmt_insert->bind_param("ssiiiiiii", $fecha_seguimiento, $descripcion, $estado_dispositivo, $id_pedidos_de_reparacion, $id_servicios, $id_dispositivos, $id_tecnico, $id_accesorio, $cantidad);

        // Ejecutar la consulta
        if ($stmt_insert->execute()) {
            // Redireccionar a una página de éxito o mostrar un mensaje
            echo "La tarea ha sido quitada y un nuevo detalle fue insertado correctamente.";
        } else {
            // Mostrar un mensaje de error si ocurre algún problema al insertar
            echo "Error al insertar el nuevo detalle: " . $stmt_insert->error;
        }

        // Cerrar la consulta de inserción
        $stmt_insert->close();
    } else {
        echo "No se encontró el detalle de reparación.";
    }

    // Cerrar la consulta de selección
    $stmt_select->close();
} else {
    echo "Datos no enviados correctamente.";
}

// Cerrar la conexión a la base de datos
$conn->close();
?>
