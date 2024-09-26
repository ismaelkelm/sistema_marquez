<?php
require_once '../../sistema_marquez/base_datos/db.php';

if (!isset($_POST['roles_id_roles']) || !isset($_POST['Permisos_idPermisos']) || !isset($_POST['estado'])) {
    die('Datos incompletos.');
}

$roles_id_roles = $_POST['roles_id_roles'];
$Permisos_idPermisos = $_POST['Permisos_idPermisos'];
$estado = $_POST['estado'];

// Corregir nombres de columnas en la consulta SQL
$query = "UPDATE permisos_en_roles SET estado = ? WHERE id_roles = ? AND id_permisos = ?";
$stmt = $conn->prepare($query);

if ($stmt === false) {
    die('Error en la preparación de la consulta: ' . $conn->error);
}

$stmt->bind_param("iii", $estado, $roles_id_roles, $Permisos_idPermisos);
$stmt->execute();

if ($stmt->affected_rows === 0) {
    echo 'No se actualizó ningún registro.';
} else {
    echo 'Estado actualizado correctamente.';
}

$stmt->close();
$conn->close();
?>
