<?php
include '../../base_datos/db.php';

$id = $_GET['id_roles'];

$query = "DELETE FROM roles WHERE id_roles = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'i', $id);

if (mysqli_stmt_execute($stmt)) {
    header('Location: index.php');
    exit;
} else {
    echo "Error al eliminar: " . mysqli_error($conn);
}
?>
