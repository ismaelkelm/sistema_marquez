<?php
// Iniciar sesión si no se ha iniciado ya
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Incluir el archivo de conexión
require_once '../base_datos/db.php'; // Usar require_once para evitar inclusiones múltiples

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/login.php");
    exit;
}

// Supongamos que el ID del usuario está almacenado en $_SESSION['user_id']
$user_id = $_SESSION['user_id'];

// Consultar el id_roles del usuario
$query = "SELECT id_roles FROM usuarios WHERE id_usuarios = ?";
$stmt = $conn->prepare($query);
if ($stmt === false) {
    die("Error en la consulta: " . htmlspecialchars($conn->error));
}
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if (!$row) {
    die("Error: Usuario no encontrado.");
}

$id_roles = $row['id_roles'];

// Consultar el nombre del rol directamente desde la base de datos
$query = "SELECT nombre FROM roles WHERE id_roles = ?";
$stmt = $conn->prepare($query);
if ($stmt === false) {
    die("Error en la consulta: " . htmlspecialchars($conn->error));
}
$stmt->bind_param("i", $id_roles);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if (!$row) {
    die("Error: Rol no encontrado.");
}

$role_name = $row['nombre'];

// Verificar si el usuario tiene el rol 'Cliente'
if ($role_name !== 'cliente') {
    header("Location: ../login/login.php");
    exit;
}

// Incluir los archivos comunes
$pageTitle = "Panel de Control - Cliente"; // Establecer el título específico para esta página
include('../includes/header.php'); // Asegúrate de que header.php no incluya nav.php nuevamente
include('../base_datos/icons.php'); // Incluir los iconos
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"> <!-- Font Awesome para iconos -->
    <style>
        .card-icon {
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            text-align: center;
            background-color: #f9f9f9;
        }
        .card-icon:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }
        .card-icon i {
            color: #007bff;
            font-size: 2rem; /* Ajusta el tamaño del icono aquí */
            transition: color 0.3s ease;
        }
        .card-icon:hover i {
            color: #dc3545; /* Cambia el color al pasar el ratón */
        }
        .card-icon .card-body {
            padding: 1.5rem;
        }
        .card-title {
            margin-top: 1rem;
        }
        .btn-custom {
            margin-bottom: 1rem;
            text-align: center;
        }
        .btn-custom a {
            display: block;
            margin: 0.5rem 0;
            padding: 1rem;
            border-radius: 5px;
            color: #fff;
            font-weight: bold;
            text-decoration: none;
        }
        .btn-custom a:hover {
            background-color: #dc3545; /* Cambia el color al pasar el ratón */
            color: #fff;
        }
    </style>
</head>
<body>
    <!-- Incluye el menú de navegación aquí solo una vez -->
    <?php include('../includes/nav.php'); ?>

    <div class="container my-4">
        <h2 class="text-center mb-4">Panel de Control - Cliente</h2>
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card card-icon text-center">
                    <div class="card-body">
                        <i class="fas fa-user-circle"></i>
                        <h5 class="card-title mt-3">Mi Perfil</h5>
                        <a href="../cliente/perfil.php" class="btn btn-primary">Ver Perfil</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card card-icon text-center">
                    <div class="card-body">
                        <i class="fas fa-repair"></i>
                        <h5 class="card-title mt-3">Mis Reparaciones</h5>
                        <a href="../cliente/reparaciones.php" class="btn btn-primary">Ver Reparaciones</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card card-icon text-center">
                    <div class="card-body">
                        <i class="fas fa-bell"></i>
                        <h5 class="card-title mt-3">Notificaciones</h5>
                        <a href="../cliente/notificaciones.php" class="btn btn-primary">Ver Notificaciones</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card card-icon text-center">
                    <div class="card-body">
                        <i class="fas fa-box"></i>
                        <h5 class="card-title mt-3">Estado de Reparación</h5>
                        <a href="../cliente/check_status.php" class="btn btn-primary">Consultar Estado</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include('../includes/footer.php'); ?>
</body>
</html>
<?php
// Cerrar la conexión a la base de datos
mysqli_close($conn);
?>
          