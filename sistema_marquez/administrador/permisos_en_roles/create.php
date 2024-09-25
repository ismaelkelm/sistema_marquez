<?php
include '../../base_datos/db.php'; // Incluye el archivo de conexión

// Consultar roles y permisos para el formulario
$roles_query = "SELECT id_roles FROM roles"; // Ajusta la consulta según tu estructura
$roles_result = mysqli_query($conn, $roles_query);

$permisos_query = "SELECT id_permisos FROM permisos";
$permisos_result = mysqli_query($conn, $permisos_query);

// Verificar si el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_roles = $_POST['id_roles'];
    $id_permisos = $_POST['id_permisos'];
    $estado = $_POST['estado'];

    // Insertar nuevo permiso en rol
    $query = "INSERT INTO permisos_en_roles (id_roles, id_permisos, estado) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iis", $id_roles, $id_permisos, $estado);

    if ($stmt->execute()) {
        header("Location: index.php"); // Redirigir a la lista de permisos en roles
        exit();
    } else {
        die("Error al insertar: " . $stmt->error);
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Permiso a Rol</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <a href="index.php" class="btn btn-secondary mb-3">Volver</a>
    <h1 class="mb-4">Agregar Permiso a Rol</h1>
    <form method="post" action="">
        <div class="mb-3">
            <label for="id_roles" class="form-label">ID Rol</label>
            <select class="form-select" id="id_roles" name="id_roles" required>
                <?php while ($row = mysqli_fetch_assoc($roles_result)) { ?>
                    <option value="<?php echo htmlspecialchars($row['id_roles']); ?>"><?php echo htmlspecialchars($row['id_roles']); ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="id_permisos" class="form-label">ID Permiso</label>
            <select class="form-select" id="id_permisos" name="id_permisos" required>
                <?php while ($row = mysqli_fetch_assoc($permisos_result)) { ?>
                    <option value="<?php echo htmlspecialchars($row['id_permisos']); ?>"><?php echo htmlspecialchars($row['id_permisos']); ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="estado" class="form-label">Estado</label>
            <input type="text" class="form-control" id="estado" name="estado" required>
        </div>
        <button type="submit" class="btn btn-primary">Agregar</button>
    </form>
</div>
</body>
</html>
