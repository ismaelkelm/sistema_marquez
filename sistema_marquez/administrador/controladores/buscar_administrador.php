<?php
require_once '../base_datos/db.php'; // Asegúrate de que este archivo defina y exporte $conn

$q = $_GET['q'] ?? '';

if ($q !== '') {
    $stmt = $conn->prepare("SELECT nombre, ruta FROM items WHERE nombre LIKE ?");
    $busqueda = '%' . $q . '%';
    $stmt->bind_param('s', $busqueda);
    $stmt->execute();

    $result = $stmt->get_result();
    $resultados = [];

    while ($row = $result->fetch_assoc()) {
        $resultados[] = $row;
    }

    echo json_encode($resultados);
}

$stmt->close();
$conn->close();
?>
