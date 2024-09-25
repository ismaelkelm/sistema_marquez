<?php
include '../../base_datos/db.php'; // Incluye el archivo de conexión

// Verificar si los IDs están en la URL
if (!isset($_GET['id_roles']) || !isset($_GET['id_permisos'])) {
    die('ID de rol o permiso no especificado.');
}

$id_roles = $_GET['id_roles'];
$id_permisos = $_GET['id_permisos'];

// Eliminar el permiso en rol
$query = "DELETE FROM permisos_en_roles WHERE id_roles = ? AND id_permisos = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $id_roles, $id_permisos);

if ($stmt->execute()) {
    header("Location: index.php"); // Redirigir a la lista de permisos en roles
    exit();
} else {
    die("Error al eliminar: " . $stmt->error);
}
?>
