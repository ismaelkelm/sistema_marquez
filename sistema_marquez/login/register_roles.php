<?php
// Incluir conexión a la base de datos
require_once '../../mi_sistema/base_datos/db.php';

// Obtener todos los roles
$query = "SELECT id_roles, nombre, habilitado FROM roles";
$result = $conn->query($query);

if ($result === false) {
    die('Error en la consulta: ' . $conn->error);
}

$roles = [];
while ($row = $result->fetch_assoc()) {
    $roles[] = $row;
}

// Cerrar la conexión
$conn->close();
?>

<!-- HTML para mostrar y cambiar el estado de los roles -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrar Roles</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container my-4">
        <h2>Administrar Roles</h2>
        <a href="javascript:window.history.back();" class="btn btn-secondary mb-3">Volver</a>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Estado</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($roles as $rol): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($rol['id_roles']); ?></td>
                        <td><?php echo htmlspecialchars($rol['nombre']); ?></td>
                        <td><?php echo $rol['habilitado'] ? 'Habilitado' : 'Deshabilitado'; ?></td>
                        <td>
                            <!-- Formulario para cambiar el estado del rol -->
                            <form method="POST" action="cambiar_estado_rol.php" style="display: inline;">
                                <input type="hidden" name="id_roles" value="<?php echo htmlspecialchars($rol['id_roles']); ?>">
                                <input type="hidden" name="nuevo_estado" value="<?php echo $rol['habilitado'] ? 0 : 1; ?>">
                                <button type="submit" class="btn btn-<?php echo $rol['habilitado'] ? 'danger' : 'success'; ?>">
                                    <?php echo $rol['habilitado'] ? 'Deshabilitar' : 'Habilitar'; ?>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
