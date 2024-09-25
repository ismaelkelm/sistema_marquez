<?php
include '../../base_datos/db.php'; // Incluye el archivo de conexión

// Verificar si el ID está en la URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die('ID de permiso no especificado.');
}

$id = $_GET['id'];

// Eliminar el permiso
$query = "DELETE FROM permisos WHERE id_permisos = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: index.php"); // Redirigir a la lista de permisos
    exit();
} else {
    die("Error al eliminar: " . $stmt->error);
}
?>
