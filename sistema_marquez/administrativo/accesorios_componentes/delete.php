<?php
include '../../base_datos/db.php';

$id = $_GET['id'];

$query = "DELETE FROM accesorios_y_componentes WHERE id_accesorios_y_componentes = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header('Location: index.php');
    exit();
} else {
    die("Error en la eliminación: " . $conn->error);
}

$stmt->close();
$conn->close();
?>
