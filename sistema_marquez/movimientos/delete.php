<?php
// Incluir el archivo de conexión a la base de datos
include '../../base_datos/db.php'; // Ajusta la ruta según la ubicación del archivo

// Obtener el ID del movimiento desde la URL
$id_movimiento = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Preparar la consulta SQL para eliminar el movimiento
$query = "DELETE FROM movimientos WHERE id_movimiento = $id_movimiento";

// Ejecutar la consulta y verificar si fue exitosa
if (mysqli_query($conn, $query)) {
    header("Location: index.php"); // Redirigir a la página principal de la lista
    exit();
} else {
    echo "Error: " . mysqli_error($conn); // Mostrar mensaje de error
}
?>
