<?php
// Incluir funciones y la conexión a la base de datos
require_once '../../sistema_marquez/base_datos/db.php';

// Verificar si el usuario está logueado
session_start();
if (!isset($_SESSION['user_id'])) {
    die('Usuario no autenticado.');
}

// Obtener el rol del usuario desde la base de datos
$user_id = $_SESSION['user_id'];
$query = "SELECT id_roles FROM usuario WHERE id_usuario = ?";
$stmt = $conn->prepare($query);
if (!$stmt) {
    die('Error en la preparación de la consulta: ' . $conn->error);
}

$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$id_roles = $row['id_roles'];

// Verificar si el usuario es administrador
$query = "SELECT nombre FROM roles WHERE id_roles = ?";
$stmt = $conn->prepare($query);
if (!$stmt) {
    die('Error en la preparación de la consulta: ' . $conn->error);
}

$stmt->bind_param("i", $id_roles);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$role_name = $row['nombre'];

if ($role_name !== 'Administrador') {
    die('Acceso denegado.');
}

// Obtener la lista de roles
$query = "SELECT id_roles, nombre FROM roles";
$result = $conn->query($query);
if (!$result) {
    die('Error en la consulta: ' . $conn->error);
}

$roles = [];
while ($row = $result->fetch_assoc()) {
    $roles[] = $row;
}

// Cerrar la conexión
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Control - Roles</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .card {
            border-radius: 0.75rem;
            box-shadow: 0 0.125rem 0.5rem rgba(0, 0, 0, 0.1);
        }
        .card-title {
            font-size: 1.5rem;
            font-weight: bold;
        }
        .btn-back {
            margin-bottom: 1rem;
        }
        .btn-custom {
            border-radius: 0.5rem;
            transition: background-color 0.3s ease, transform 0.2s ease;
            margin-top: 0.5rem; /* Espacio superior entre botones en general */
        }
        .btn-custom:hover {
            background-color: green;
            transform: scale(1.05);
        }
        .btn-custom i {
            margin-right: 0.5rem;
        }
        .container {
            max-width: 1200px;
        }
        .page-header {
            margin-bottom: 2rem;
        }
        .admin-buttons {
            display: flex;
            justify-content: center;
            gap: 1rem; /* Espacio horizontal entre los botones para el rol Administrativo */
            flex-wrap: wrap;
        }
        .admin-buttons form {
            display: inline-block;
        }
    </style>
</head>
<body>
    <div class="container my-4">
        <a href="../administrador/administrador.php" class="btn btn-secondary btn-back">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
        <div class="page-header text-center">
            <h2>Panel de Control - Roles</h2>
        </div>
        <div class="row g-4">
            <?php foreach ($roles as $rol): ?>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <h5 class="card-title"><?php echo htmlspecialchars($rol['nombre']); ?></h5>

                            <!-- Si el rol es Administrativo, mostrar dos botones -->
                            <?php if ($rol['nombre'] === 'Administrativo'): ?>
                                <div class="admin-buttons">
                                    <form method="POST" action="./permisos.php">
                                        <input type="hidden" name="rol_id" value="<?php echo htmlspecialchars($rol['id_roles']); ?>">
                                        <button type="submit" class="btn btn-custom btn-primary">
                                            <i class="fas fa-lock"></i> Ver Permisos (General)
                                        </button>
                                    </form>
                                    <form method="POST" action="./gestionar_panel.php">
                                        <input type="hidden" name="rol_id" value="<?php echo htmlspecialchars($rol['id_roles']); ?>">
                                        <button type="submit" class="btn btn-custom btn-secondary">
                                            <i class="fas fa-lock"></i> Ver Permisos (Administrativo)
                                        </button>
                                    </form>
                                </div>
                            <?php else: ?>
                                <!-- Para otros roles, un solo botón que redirige a permisos.php -->
                                <form method="POST" action="./permisos.php">
                                    <input type="hidden" name="rol_id" value="<?php echo htmlspecialchars($rol['id_roles']); ?>">
                                    <button type="submit" class="btn btn-custom btn-primary">
                                        <i class="fas fa-lock"></i> Ver Permisos
                                    </button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
