<?php
header('Content-Type: application/json');

// Incluir la conexión a la base de datos
include('db.php');

// Verificar si el parámetro 'descripcion' está presente
if (isset($_GET['descripcion'])) {
    // Agregar un comodín "%" a la descripción
    $descripcion = $_GET['descripcion'] . '%';

    // Preparar la consulta SQL
    $stmt = $conn->prepare("SELECT id_permisos, descripcion FROM permisos WHERE descripcion LIKE ?");
    if ($stmt === false) {
        echo json_encode(['success' => false, 'message' => 'Error en la preparación de la consulta.']);
        exit();
    }

    // Enlazar el parámetro a la consulta
    $stmt->bind_param("s", $descripcion);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result === false) {
        echo json_encode(['success' => false, 'message' => 'Error al obtener los resultados.']);
        exit();
    }

    // Recoger todos los permisos encontrados
    $permisos = [];
    while ($row = $result->fetch_assoc()) {
        $permisos[] = $row;
    }

    // Devolver los resultados o un mensaje si no se encontraron
    echo json_encode(['success' => true, 'data' => $permisos]);

    $stmt->close();
} else {
    // Si el parámetro 'descripcion' no está presente
    echo json_encode(['success' => false, 'message' => 'Parámetro de descripción faltante.']);
}

// Cerrar la conexión a la base de datos
$conn->close();
?>
