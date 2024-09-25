<?php
include '../../base_datos/db.php';

$id = $_GET['id_tecnicos'];

$query = "DELETE FROM tecnicos WHERE id_tecnicos = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'i', $id);

if (mysqli_stmt_execute($stmt)) {
    header('Location: index.php');
    exit;
} else {
    echo "Error al eliminar: " . mysqli_error($conn);
}
?>
