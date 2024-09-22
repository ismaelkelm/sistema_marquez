<?php
header('Content-Type: application/json');

// Conectar a la base de datos
$pdo = new PDO('mysql:host=localhost;dbname=pruebas_marquez2', 'root', ' ');

// Obtener datos POST
$id_permisos = isset($_POST['id_permisos']) ? (int)$_POST['id_permisos'] : 0;
$rolId = isset($_POST['rolId']) ? (int)$_POST['rolId'] : 0;
$estado = isset($_POST['estado']) ? (int)$_POST['estado'] : 0;

$response = ['success' => false, 'message' => ''];

if ($id_permisos && $rolId) {
    // Preparar y ejecutar la consulta
    $stmt = $pdo->prepare("UPDATE permisos_en_roles SET estado = :estado WHERE id_permisos = :id_permisos AND id_roles = :rolId");
    $result = $stmt->execute([
        ':estado' => $estado,
        ':id_permisos' => $id_permisos,
        ':rolId' => $rolId
    ]);

    if ($result) {
        $response['success'] = true;
        $response['message'] = 'Estado actualizado correctamente.';
    } else {
        $response['message'] = 'Error al actualizar el estado.';
    }
} else {
    $response['message'] = 'Datos incompletos.';
}

echo json_encode($response);
?>
