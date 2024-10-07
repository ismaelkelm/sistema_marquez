<?php
// Conexión a la base de datos
include '../../base_datos/db.php'; 

$numero_serie = $_POST['numero_serie'];

$query = "SELECT * FROM dispositivos WHERE numero_de_serie = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $numero_serie);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $dispositivo = $result->fetch_assoc();
    echo json_encode([
        'status' => 'exists',
        'dispositivo' => $dispositivo
    ]);
} else {
    echo json_encode(['status' => 'not_found']);
}

$stmt->close();
$conn->close();
?>
