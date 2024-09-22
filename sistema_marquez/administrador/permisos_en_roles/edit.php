<?php
include '../../base_datos/db.php'; // Incluye el archivo de conexión

// Verificar si los IDs están en la URL
if (!isset($_GET['id_roles']) || !isset($_GET['id_permisos'])) {
    die('ID de rol o permiso no especificado.');
}

$id_roles = $_GET['id_roles'];
$id_permisos = $_GET['id_permisos'];

// Consultar el permiso en rol
$query = "SELECT * FROM permisos_en_roles WHERE id_roles = ? AND id_permisos = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $id_roles, $id_permisos);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if (!$row) {
    die('Permiso en rol no encontrado.');
}

// Consultar roles y permisos para el formulario
$roles_query = "SELECT id_roles FROM roles"; // Ajusta la consulta según tu estructura
$roles_result = mysqli_query($conn, $roles_query);

$permisos_query = "SELECT id_permisos FROM permisos";
$permisos_result = mysqli_query($conn, $permisos_query);

// Verificar si el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $estado = $_POST['estado'];

    // Actualizar permiso en rol
    $query = "UPDATE permisos_en_roles SET estado = ? WHERE id_roles = ? AND id_permisos = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sii", $estado, $id_roles, $id_permisos);

    if ($stmt->execute()) {
        header("Location: index.php"); // Redirigir a la lista de permisos en roles
        exit();
    } else {
        die("Error al actualizar: " . $stmt->error);
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Permiso a Rol</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <a href="index.php" class="btn btn-secondary mb-3">Volver</a>
    <h1 class="mb-4">Editar Permiso a Rol</h1>
    <form method="post" action="">
        <div class="mb-3">
            <label for="id_roles" class="form-label">ID Rol</label>
            <input type="text" class="form-control" id="id_roles" name="id_roles" value="<?php echo htmlspecialchars($row['id_roles']); ?>" readonly>
        </div>
        <div class="mb-3">
            <label for="id_permisos" class="form-label">ID Permiso</label>
            <input type="text" class="form-control" id="id_permisos" name="id_permisos" value="<?php echo htmlspecialchars($row['id_permisos']); ?>" readonly>
        </div>
        <div class="mb-3">
            <label for="estado" class="form-label">Estado</label>
            <input type="text" class="form-control" id="estado" name="estado" value="<?php echo htmlspecialchars($row['estado']); ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Actualizar</button>
    </form>
</div>
</body>
</html>
