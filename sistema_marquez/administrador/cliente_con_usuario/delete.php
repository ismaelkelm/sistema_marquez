<?php
include '../../base_datos/db.php'; // Incluye el archivo de conexión

// Obtener el ID del registro que se va a eliminar
$id = $_GET['id'];

// Preparar la consulta para eliminar el registro
$query = "DELETE FROM cabecera_factura WHERE id_cabecera_factura = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'i', $id);

// Ejecutar la consulta
if (mysqli_stmt_execute($stmt)) {
    // Redirigir de vuelta al índice después de la eliminación
    header('Location: index.php');
    exit;
} else {
    echo "Error al eliminar el registro: " . mysqli_error($conn);
}

// Cerrar la conexión a la base de datos
mysqli_close($conn);
?>
