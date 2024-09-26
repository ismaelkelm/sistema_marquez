<?php
header('Content-Type: application/json'); // Indica que la respuesta será en formato JSON

include('db.php'); // Incluye el archivo de conexión a la base de datos

// Verifica si se han pasado los parámetros 'idPermiso', 'rolId' y 'estado'
if (isset($_POST['idPermiso']) && isset($_POST['rolId']) && isset($_POST['estado'])) {
    $idPermiso = $_POST['idPermiso'];
    $rolId = $_POST['rolId'];
    $estado = $_POST['estado'];

    // Prepara la consulta SQL para actualizar el estado solo del rol seleccionado
    $stmt = $conn->prepare("UPDATE permisos_en_roles SET estado = ? WHERE Permisos_idPermisos = ? AND roles_id_roles = ?");
    $stmt->bind_param("iii", $estado, $idPermiso, $rolId); // Vincula los parámetros

    $stmt->execute(); // Ejecuta la consulta

    if ($stmt->affected_rows > 0) {
        // Si se actualiza correctamente, devuelve un mensaje de éxito
        echo json_encode(['success' => true]);
    } else {
        // Verifica si la consulta afectó alguna fila, si no, hay un problema con los datos
        if ($stmt->error) {
            // Muestra el error SQL si hay un problema con la consulta
            echo json_encode(['success' => false, 'message' => 'Error en la consulta: ' . $stmt->error]);
        } else {
            // Si no se afecta ninguna fila, posiblemente porque el estado ya estaba actualizado
            echo json_encode(['success' => false, 'message' => 'No se encontró el rol o permiso para actualizar.']);
        }
    }

    $stmt->close(); // Cierra la consulta preparada
} else {
    // Si faltan parámetros, devuelve un mensaje de error
    echo json_encode(['success' => false, 'message' => 'Faltan parámetros.']);
}

$conn->close(); // Cierra la conexión a la base de datos
?>
