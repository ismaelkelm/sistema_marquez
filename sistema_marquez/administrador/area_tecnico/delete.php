<?php
// Incluir el archivo de conexión a la base de datos
include '../../base_datos/db.php';

// Verificar si se ha recibido el ID para eliminar
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Eliminar el área técnica de la base de datos
    $query = "DELETE FROM area_tecnico WHERE id_area_tecnico = $id";
    $result = mysqli_query($conn, $query);

    if ($result) {
        header("Location: index.php"); // Redirigir a la página principal después de eliminar
    } else {
        echo "Error al eliminar el registro: " . mysqli_error($conn);
    }
}
?>
