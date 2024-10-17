<?php
// Conexión a la base de datos
include '../../base_datos/db.php'; 

$id_dispositivo = $_GET['id'];

// Consulta para obtener los detalles del dispositivo
$sql = "SELECT marca, modelo FROM dispositivos WHERE id_dispositivos = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_dispositivo);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $dispositivo = $result->fetch_assoc();
    // Devolver los detalles como JSON
    echo json_encode($dispositivo);
} else {
    echo json_encode(['error' => 'Dispositivo no encontrado']);
}

$stmt->close();
$conexion->close();
?>