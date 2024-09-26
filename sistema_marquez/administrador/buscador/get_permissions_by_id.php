<?php
header('Content-Type: application/json');

// Incluir la conexión a la base de datos
include('db.php');

// Verificar si el parámetro 'id' está presente
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Validar que el ID es un número
    if (!is_numeric($id)) {
        echo json_encode(['success' => false, 'message' => 'ID inválido']);
        $conn->close();
        exit();
    }

    // Preparar la consulta SQL
    $stmt = $conn->prepare("
        SELECT per.id_permisos, per.descripcion AS permiso_descripcion, per_en_roles.estado, rol.nombre AS rol_nombre
        FROM permisos per
        LEFT JOIN permisos_en_roles per_en_roles ON per.id_permisos = per_en_roles.id_permisos
        LEFT JOIN roles rol ON per_en_roles.id_roles = rol.id_roles
        WHERE per.id_permisos = ?");
    
    if ($stmt === false) {
        echo json_encode(['success' => false, 'message' => 'Error en la preparación de la consulta.']);
        $conn->close();
        exit();
    }

    // Enlazar el parámetro a la consulta
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result === false) {
        echo json_encode(['success' => false, 'message' => 'Error al obtener los resultados.']);
        $stmt->close();
        $conn->close();
        exit();
    }

    // Recoger los permisos y roles encontrados
    $permissions = $result->fetch_all(MYSQLI_ASSOC);

    // Devolver los resultados o un mensaje si no se encontraron
    echo json_encode(['success' => true, 'data' => $permissions]);

    $stmt->close();
} else {
    // Si el parámetro 'id' no está presente
    echo json_encode(['success' => false, 'message' => 'Parámetro ID faltante.']);
}

// Cerrar la conexión a la base de datos
$conn->close();
?>
