<?php
include '../../base_datos/db.php'; // Incluye el archivo de conexión

$id = $_GET['id'];

$query = "DELETE FROM dispositivos WHERE id_dispositivos = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'i', $id);

if (mysqli_stmt_execute($stmt)) {
    header('Location: index.php');
    exit;
} else {
    echo "Error: " . mysqli_error($conn);
}
?>
