<?php
require_once '../../sistema_marquez/base_datos/db.php';

// Capturar el ID del rol desde el formulario
if (!isset($_POST['rol_id'])) {
    die('ID de rol no proporcionado.');
}

$role_id = $_POST['rol_id'];

// Obtener el nombre del rol y los permisos en una sola consulta
$query = "
    SELECT r.nombre AS nombre_rol, p.descripcion AS permiso, pr.estado, p.id_permisos
    FROM permisos_en_roles pr
    JOIN permisos p ON pr.id_permisos = p.id_permisos
    JOIN roles r ON pr.id_roles = r.id_roles
    WHERE pr.id_roles = ?
";
$stmt = $conn->prepare($query);

if ($stmt === false) {
    die('Error en la preparación de la consulta: ' . $conn->error);
}

$stmt->bind_param("i", $role_id);
$stmt->execute();
$result = $stmt->get_result();

// Variable para almacenar el nombre del rol y los permisos
$permisos_del_rol = array();
$role_name = null;

while ($row = $result->fetch_assoc()) {
    if ($role_name === null) {
        $role_name = $row['nombre_rol']; // Asignar el nombre del rol
    }
    $permisos_del_rol[] = array(
        'descripcion' => $row['permiso'],
        'estado' => $row['estado'],
        'idPermisos' => $row['id_permisos']
    );
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Permisos del Rol</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
        function cambiarEstado(rolId, permisoId, estado) {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "./actualizar_permisos.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function () {
                if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                    location.reload();
                }
            };
            xhr.send("roles_id_roles=" + rolId + "&Permisos_idPermisos=" + permisoId + "&estado=" + estado);
        }
    </script>
    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 800px;
            background-color: #ffffff;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1.5rem;
        }
        th, td {
            padding: 1rem;
            text-align: center;
            border-bottom: 1px solid #dee2e6;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .btn-custom {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            font-size: 1rem;
            border-radius: 0.5rem;
            transition: background-color 0.3s ease;
        }
        .btn-custom i {
            font-size: 1.2rem;
        }
        .btn-update {
            background-color: #007bff;
            color: white;
        }
        .btn-update:hover {
            background-color: #0056b3;
        }
        .btn-back {
            background-color: #6c757d;
            color: white;
        }
        .btn-back:hover {
            background-color: #5a6268;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="d-flex justify-content-between mb-4">
            <button class="btn btn-custom btn-update" onclick="location.reload();">
                <i class="fas fa-sync-alt"></i> Actualizar Permisos
            </button>
            <a href="../base_datos/gestionar_permisos.php" class="btn btn-custom btn-back">
                <i class="fas fa-arrow-left"></i> Volver Atrás
            </a>
        </div>
        <h2 class="text-center mb-4">Permisos del Rol: <strong><?php echo htmlspecialchars($role_name); ?></strong></h2>
        <table>
            <thead>
                <tr>
                    <th>Permiso</th>
                    <th>Estado</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($permisos_del_rol as $permiso): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($permiso['descripcion']); ?></td>
                        <td>
                            <span class="badge bg-<?php echo $permiso['estado'] ? 'success' : 'danger'; ?>">
                                <?php echo $permiso['estado'] ? 'Permitido' : 'No permitido'; ?>
                            </span>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-<?php echo $permiso['estado'] ? 'danger' : 'success'; ?>" 
                                    onclick="cambiarEstado(<?php echo $role_id; ?>, <?php echo $permiso['idPermisos']; ?>, <?php echo $permiso['estado'] ? 0 : 1; ?>)">
                                <?php echo $permiso['estado'] ? 'Desactivar' : 'Activar'; ?>
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</body>
</html>
