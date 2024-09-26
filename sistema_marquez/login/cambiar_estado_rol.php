<?php
// Incluir conexión a la base de datos
require_once '../../sistema_marquez/base_datos/db.php';

if (isset($_POST['id_roles'], $_POST['nuevo_estado'])) {
    $id_roles = intval($_POST['id_roles']);
    $nuevo_estado = intval($_POST['nuevo_estado']);

    // Actualizar el estado del rol
    $query = "UPDATE roles SET habilitado = ? WHERE id_roles = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ii', $nuevo_estado, $id_roles);

    if ($stmt->execute()) {
        // Redirigir de nuevo a la página de administración de roles
        header('Location: register_roles.php');
    } else {
        echo "Error al actualizar el estado: " . $conn->error;
    }

    // Cerrar la declaración
    $stmt->close();
}

// Cerrar la conexión
$conn->close();
?>
