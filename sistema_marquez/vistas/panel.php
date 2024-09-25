<?php
session_start();
require_once('db.php');
require_once('roles.php');

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['loggedin'])) {
    header("Location: login/login.php");
    exit;
}

// Obtener el rol del usuario desde la sesión
$user_role = $_SESSION['user_role'];

// Verificar si el rol del usuario existe en la configuración
if (!isset($roles[$user_role])) {
    header("Location: login/login.php");
    exit;
}

// Obtener permisos del rol
$permissions = $roles[$user_role]['permissions'];

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Usuario - Mi Empresa</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="estilos/estilo.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <?php include 'includes/nav.php'; ?>

    <div class="container my-4">
        <h2>Panel de Usuario</h2>
        <p>Bienvenido, <?php echo htmlspecialchars($roles[$user_role]['name']); ?>. Aquí están tus opciones:</p>
        
        <div class="row">
            <?php
            // Definir los íconos y las rutas para todas las opciones
            $all_options = [
                'reparaciones' => ['icon' => 'fa-tools', 'label' => 'Reparaciones', 'path' => 'reparaciones/index.php'],
                'pedidos_de_reparacion' => ['icon' => 'fa-box-open', 'label' => 'Pedidos de Reparación', 'path' => 'pedidos_de_reparacion/index.php'],
                'piezas_y_componentes' => ['icon' => 'fa-cogs', 'label' => 'Piezas y Componentes', 'path' => 'piezas_y_componentes/index.php'],
                'clientes' => ['icon' => 'fa-users', 'label' => 'Clientes', 'path' => 'clientes/index.php'],
                'facturas' => ['icon' => 'fa-file-invoice', 'label' => 'Facturas', 'path' => 'facturas/index.php'],
                'recibos' => ['icon' => 'fa-receipt', 'label' => 'Recibos', 'path' => 'recibos/index.php'],
                'notificaciones' => ['icon' => 'fa-bell', 'label' => 'Notificaciones', 'path' => 'notificaciones/index.php'],
                'empleados' => ['icon' => 'fa-id-card', 'label' => 'Empleados', 'path' => 'empleados/index.php'],
                'usuarios' => ['icon' => 'fa-user-cog', 'label' => 'Usuarios', 'path' => 'usuarios/index.php'],
                'accesorios' => ['icon' => 'fa-plug', 'label' => 'Accesorios', 'path' => 'accesorios/index.php'],
                'detalle_facturas' => ['icon' => 'fa-file-alt', 'label' => 'Detalle Facturas', 'path' => 'detalle_facturas/index.php'],
                'detalle_reparaciones' => ['icon' => 'fa-wrench', 'label' => 'Detalle Reparaciones', 'path' => 'detalle_reparaciones/index.php'],
                'perfil' => ['icon' => 'fa-user', 'label' => 'Perfil', 'path' => 'perfil/index.php'],
                'historial' => ['icon' => 'fa-history', 'label' => 'Historial', 'path' => 'historial/index.php'],
                'tickets' => ['icon' => 'fa-ticket-alt', 'label' => 'Tickets', 'path' => 'tickets/index.php']
            ];

            // Mostrar las opciones permitidas para el rol del usuario
            foreach ($permissions as $permission) {
                if (isset($all_options[$permission])) {
                    $option = $all_options[$permission];
                    echo '<div class="col-md-3 text-center mb-4">';
                    echo '<a href="' . htmlspecialchars($option['path']) . '" class="btn btn-light p-3 d-block">';
                    echo '<i class="fas ' . htmlspecialchars($option['icon']) . ' fa-2x"></i>';
                    echo '<p class="mt-2">' . htmlspecialchars($option['label']) . '</p>';
                    echo '</a>';
                    echo '</div>';
                }
            }
            ?>
        </div>
    </div>
    
    <?php include 'includes/footer.php'; ?>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="scripts/script.js"></script>
</body>
</html>
