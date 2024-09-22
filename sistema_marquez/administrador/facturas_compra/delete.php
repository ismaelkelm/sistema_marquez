<?php
include '../../base_datos/db.php'; // Incluye el archivo de conexión

// Verificar si el ID está en la URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die('ID de factura de compra no especificado.');
}

$id = $_GET['id'];

// Eliminar factura de compra
$query = "DELETE FROM facturas_compra WHERE id_facturas_compra = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: index.php"); // Redirigir a la lista de facturas de compra
    exit();
} else {
    die("Error al eliminar: " . $stmt->error);
}
?>
