<?php
include '../../base_datos/db.php';

$id = $_GET['id'];

$query = "DELETE FROM comprobantes_proveedores WHERE id_comprobante_proveedores = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'i', $id);

if (mysqli_stmt_execute($stmt)) {
    header('Location: index.php');
    exit;
} else {
    echo "Error: " . mysqli_error($conn);
}
?>
