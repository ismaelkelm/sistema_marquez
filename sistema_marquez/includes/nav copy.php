<?php
// Verificar si la sesión está iniciada y el rol del usuario está definido
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    header("Location: ../login/login.php");
    exit;
}

$usuario_rol = $_SESSION['role'];
$inicio_url = '';

switch ($usuario_rol) {
    case 'administrador':
        $inicio_url = '../administrador/administrador.php';
        break;
    case 'administrativo':
        $inicio_url = '../administrativo/administrativo.php';
        break;
    case 'tecnico':
        $inicio_url = '../tecnico/tecnico.php';
        break;
    case 'cliente':
        $inicio_url = '../cliente/cliente.php';
        break;
    case 'empleado':
        $inicio_url = '../empleados/empleado.php';
        break;
    default:
        $inicio_url = '../login/login.php'; // URL predeterminada
        break;
}
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="<?php echo htmlspecialchars($inicio_url); ?>">
        Inicio
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav mr-auto">
            <?php if ($usuario_rol === 1): ?>
                
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="adminDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Administrador
                    </a>
                    <div class="dropdown-menu" aria-labelledby="adminDropdown">
                        <a class="dropdown-item" href="../base_datos/gestionar_permisos.php">
                            Permisos
                        </a>
                        <a class="dropdown-item" href="../administrador/enviar_notificacion.php">
                            Enviar Notificación
                        </a>
                        <a class="dropdown-item" href="../administrador/gestionar_tareas.php">
                            Gestionar Tareas
                        </a>
                        <a class="dropdown-item" href="../administrador/listar_reparaciones.php">
                            Listar Reparaciones
                        </a>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="repairDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Permisos
                    </a>
                    <div class="dropdown-menu" aria-labelledby="repairDropdown">
                        <a class="dropdown-item" href="../administrador/enviar_notificacion.php">
                            Buscar permisos
                        </a>
                    </div>
                </li>
            <?php elseif ($usuario_rol === 2): ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="adminDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Administrativo
                    </a>
                    <div class="dropdown-menu" aria-labelledby="adminDropdown">
                        <a class="dropdown-item" href="../administrativo/gestionar_tareas.php">
                            Gestionar Tareas
                        </a>
                        <a class="dropdown-item" href="../administrativo/listar_reparaciones.php">
                            Lista de Reparaciones
                        </a>
                        <a class="dropdown-item" href="../administrativo/enviar_notificacion.php">
                            Enviar Notificación
                        </a>
                    </div>
                </li>
            <?php elseif ($usuario_rol === 3): ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="tecnicoDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Reparaciones
                    </a>
                    <div class="dropdown-menu" aria-labelledby="tecnicoDropdown">
                        <a class="dropdown-item" href="../tecnico/listar_reparaciones.php">
                            Listar Reparaciones
                        </a>
                        <a class="dropdown-item" href="../tecnico/gestionar_tareas.php">
                            Gestionar Tareas
                        </a>
                        <a class="dropdown-item" href="../tecnico/notificar_completado.php">
                            Notificar Completado
                        </a>
                        <a class="dropdown-item" href="../tecnico/ver_notificacion.php">
                            Ver Notificaciones
                        </a>
                    </div>
                </li>
            <?php elseif ($usuario_rol === 4): ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="clienteDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Cliente
                    </a>
                    <div class="dropdown-menu" aria-labelledby="clienteDropdown">
                        <a class="dropdown-item" href="../cliente/perfil.php">
                            Mi Perfil
                        </a>
                        <a class="dropdown-item" href="../cliente/reparaciones.php">
                            Mis Reparaciones
                        </a>
                        <a class="dropdown-item" href="../cliente/notificaciones.php">
                            Notificaciones
                        </a>
                    </div>
                </li>
                <?php elseif ($usuario_rol === 5): ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="clienteDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Empleado
                    </a>
                    <div class="dropdown-menu" aria-labelledby="clienteDropdown">
                        <a class="dropdown-item" href="../../mi_sistema/empleados/empleado.php">
                            Mi Perfil
                        </a>
                        <a class="dropdown-item" href="../cliente/reparaciones.php">
                            Mis Reparaciones
                        </a>
                        <a class="dropdown-item" href="../cliente/notificaciones.php">
                            Notificaciones
                        </a>
                    </div>
                </li>
            <?php endif; ?>
        </ul>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a href="../login/forgot_change.html" class="btn btn-outline-light">
                    Cambiar Contraseña
                </a>
                <a href="../login/logout.php" class="btn btn-outline-light">
                    Cerrar Sesión
                </a>
            </li>
        </ul>
    </div>
</nav>

<!-- Scripts de Bootstrap y jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<style>
    /* Estilo personalizado para el navbar */
    .navbar {
        padding: 0.5rem 1rem;
    }
    .navbar-nav .nav-link {
        padding: 0.5rem 1rem;
        transition: background-color 0.3s ease, color 0.3s ease;
    }
    .navbar-nav .nav-link:hover,
    .navbar-nav .nav-item.active .nav-link {
        background-color: #343a40;
        color: #f8f9fa;
    }
    .dropdown-menu .dropdown-item {
        transition: background-color 0.3s ease, color 0.3s ease;
    }
    .dropdown-menu .dropdown-item:hover {
        background-color: #007bff;
        color: #ffffff;
    }
    .btn-outline-light {
        border-color: #f8f9fa;
        color: #f8f9fa;
    }
    .btn-outline-light:hover {
        background-color: #f8f9fa;
        color: orangered;
    }
</style>
