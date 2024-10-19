<?php
// Incluir el archivo de conexión a la base de datos
include '../base_datos/db.php';

// Verificar si se recibieron los datos necesarios
if (isset($_POST['id_tecnico']) && isset($_POST['id_detalle_reparacion'])) {
    
    // Obtener los valores enviados desde el formularioa
    $id_tecnico = $_POST['id_tecnico'];
    $id_detalle_reparacion = $_POST['id_detalle_reparacion'];
    
    // Preparar la consulta para actualizar el id_tecnico
    $query_update = "UPDATE detalle_reparaciones SET id_tecnico = ? WHERE id_detalle_reparaciones = ?";
    $stmt_update = $conn->prepare($query_update);
    
    // Verificar si la consulta se preparó correctamente
    if ($stmt_update) {
        // Vincular los parámetros a la consulta
        $stmt_update->bind_param("ii", $id_tecnico, $id_detalle_reparacion);
        
        // Ejecutar la consulta
        if ($stmt_update->execute()) {
            echo "Tarea asignada correctamente.";
        } else {
            echo "Error al asignar la tarea: " . $stmt_update->error;
        }

        // Cerrar la consulta
        $stmt_update->close();
    } else {
        echo "Error al preparar la consulta: " . $conn->error;
    }
} else {
    echo "Datos no recibidos correctamente.";
    if (!isset($_POST['id_tecnico'])) {
        echo "El ID técnico no fue recibido.";
    }
    if (!isset($_POST['id_detalle_reparacion'])) {
        echo "El ID de detalle de reparación no fue recibido.";
    }
}

// Cerrar la conexión
$conn->close();
?>