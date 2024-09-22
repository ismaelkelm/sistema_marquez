<?php
include '../../base_datos/db.php';

$id = $_GET['id_tipo_de_pago'];

$query = "DELETE FROM tipo_de_pago WHERE id_tipo_de_pago = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'i', $id);

if (mysqli_stmt_execute($stmt)) {
    header('Location: index.php');
    exit;
} else {
    echo "Error al eliminar: " . mysqli_error($conn);
}
?>
