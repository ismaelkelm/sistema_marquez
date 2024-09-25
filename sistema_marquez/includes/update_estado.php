<?php
header('Content-Type: application/json'); // Indica que la respuesta será en formato JSON

include('db.php'); // Incluye el archivo de conexión a la base de datos

// Verifica si se han pasado los parámetros 'idPermiso' y 'estado'
if (isset($_POST['idPermiso']) && isset($_POST['estado'])) {
    $idPermiso = $_POST['idPermiso'];
    $estado = $_POST['estado'];

    // Prepara la consulta SQL para actualizar el estado
    $stmt = $conn->prepare("UPDATE permisos_en_roles SET estado = ? WHERE Permisos_idPermisos = ?");
    $stmt->bind_param("ii", $estado, $idPermiso); // Vincula los parámetros
    $stmt->execute(); // Ejecuta la consulta

    if ($stmt->affected_rows > 0) {
        // Si se actualiza correctamente, devuelve un mensaje de éxito
        echo json_encode(['success' => true]);
    } else {
        // Si no se actualiza, devuelve un mensaje de error
        echo json_encode(['success' => false, 'message' => 'No se pudo actualizar el estado.']);
    }

    $stmt->close(); // Cierra la consulta preparada
} else {
    // Si faltan parámetros, devuelve un mensaje de error
    echo json_encode(['success' => false, 'message' => 'Faltan parámetros.']);
}

$conn->close(); // Cierra la conexión a la base de datos
?>
