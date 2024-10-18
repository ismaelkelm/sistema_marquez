
<?php
require_once '../base_datos/db.php'; // Asegúrate de que db.php define y exporta $conn

function obtenerPermisos($user_id) {
    global $conn;
    $query = "
        SELECT p.descripcion, pr.estado
        FROM permisos_en_roles pr
        JOIN permisos p ON pr.id_permisos = p.id_permisos
        WHERE pr.id_roles = (
            SELECT id_roles FROM usuario WHERE id_usuario = ?
        )
    ";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $permisos = [];
    while ($row = $result->fetch_assoc()) {
        $permisos[$row['descripcion']] = $row['estado'];
    }
    $stmt->close();
    return $permisos;
}

function obtenerIconos($user_id) {
    global $conn;
    $permisos = obtenerPermisos($user_id);

    $iconos_y_rutas = [
        'Administrador' => [
            'accesorios_y_componentes' => ['icono' => 'fa-tools', 'ruta' => '../administrador/accesorios_componentes/index.php'],
            'area_tecnico' => ['icono' => 'fa-cogs', 'ruta' => '../administrador/area_tecnico/index.php'],
            'cliente_con_usuario' => ['icono' => 'fa-user', 'ruta' => '../administrador/cliente_con_usuario/index.php'],
            'clientes' => ['icono' => 'fa-user', 'ruta' => '../administrador/clientes/index.php'],
            'comprobante_proveedores' => ['icono' => 'fa-box', 'ruta' => '../administrador/comprobante_proveedores/index.php'],
            'detalle_factura' => ['icono' => 'fa-wrench', 'ruta' => '../administrador/detalle_factura/index.php'],
            'detalle_reparaciones' => ['icono' => 'fa-wrench', 'ruta' => '../administrador/detalle_reparaciones/index.php'],
            'detalle_servicios' => ['icono' => 'fa-shopping-cart', 'ruta' => '../administrador/detalle_servicios/index.php'],
            'dispositivos' => ['icono' => 'fa-laptop', 'ruta' => '../administrador/dispositivos/index.php'],
            'cabecera_factura' => ['icono' => 'fa-file-invoice', 'ruta' => '../administrador/cabecera_factura/index.php'],
            'historial_cambios_contrasena' => ['icono' => 'fa-history', 'ruta' => '../administrador/historial_cambios_contrasena/index.php'],
            'notificaciones' => ['icono' => 'fa-bell', 'ruta' => '../administrador/notificaciones/index.php'],
            'operacion' => ['icono' => 'fa-credit-card', 'ruta' => '../administrador/operacion/index.php'],
            'pedidos_de_reparacion' => ['icono' => 'fa-repair', 'ruta' => '../administrador/pedidos_de_reparacion/index.php'],
            'permisos' => ['icono' => 'fa-shield-alt', 'ruta' => '../administrador/permisos/index.php'],
            'permisos_en_roles' => ['icono' => 'fa-user-shield', 'ruta' => '../administrador/permisos_en_roles/index.php'],
            'proveedores' => ['icono' => 'fa-truck', 'ruta' => '../administrador/proveedores/index.php'],
            'roles' => ['icono' => 'fa-users-cog', 'ruta' => '../administrador/roles/index.php'],
            'servicios' => ['icono' => 'fa-users-cog', 'ruta' => '../administrador/servicios/index.php'],
            'tecnicos' => ['icono' => 'fa-tools', 'ruta' => '../administrador/tecnicos/index.php'],
            'tipo_comprobante' => ['icono' => 'fa-users-cog', 'ruta' => '../administrador/tipo_comprobante/index.php'],
            'tipo_de_pago' => ['icono' => 'fa-money-bill-wave', 'ruta' => '../administrador/tipo_de_pago/index.php'],
            'usuario' => ['icono' => 'fa-user-cog', 'ruta' => '../administrador/usuario/index.php']
        ],
        'Administrativo' => [
            'accesorios_y_componentes' => ['icono' => 'fa-tools', 'ruta' => '../administrativo/accesorios_componentes/index.php'],
            'cliente_con_usuario' => ['icono' => 'fa-user', 'ruta' => '../administrativo/cliente_con_usuario/index.php'],
            'clientes' => ['icono' => 'fa-user', 'ruta' => '../administrativo/clientes/index.php'],
            'comprobante_proveedores' => ['icono' => 'fa-box', 'ruta' => '../administrativo/comprobante_proveedores/index.php'],
            'detalle_factura' => ['icono' => 'fa-wrench', 'ruta' => '../administrativo/detalle_factura/index.php'],
            'detalle_reparaciones' => ['icono' => 'fa-wrench', 'ruta' => '../administrativo/detalle_reparaciones/index.php'],
            'detalle_servicios' => ['icono' => 'fa-shopping-cart', 'ruta' => '../administrativo/detalle_servicios/index.php'],
            'dispositivos' => ['icono' => 'fa-laptop', 'ruta' => '../administrativo/dispositivos/index.php'],
            'cabecera_factura' => ['icono' => 'fa-file-invoice', 'ruta' => '../administrativo/cabecera_factura/index.php'],
            'notificaciones' => ['icono' => 'fa-bell', 'ruta' => '../administrativo/notificaciones/index.php'],
            'operacion' => ['icono' => 'fa-credit-card', 'ruta' => '../administrativo/operacion/index.php'],
            'pedidos_de_reparacion' => ['icono' => 'fa-repair', 'ruta' => '../administrativo/pedidos_de_reparacion/index.php'],
            'proveedores' => ['icono' => 'fa-truck', 'ruta' => '../administrativo/proveedores/index.php'],
            'roles' => ['icono' => 'fa-users-cog', 'ruta' => '../administrativo/roles/index.php'],
            'servicios' => ['icono' => 'fa-users-cog', 'ruta' => '../administrativo/roles/index.php'],
            'tecnicos' => ['icono' => 'fa-tools', 'ruta' => '../administrativo/tecnicos/index.php'],
            'tipo_comprobante' => ['icono' => 'fa-users-cog', 'ruta' => '../administrativo/tipo_comprobante/index.php'],
            'tipo_de_pago' => ['icono' => 'fa-money-bill-wave', 'ruta' => '../administrativo/tipo_de_pago/index.php'],
            'usuario' => ['icono' => 'fa-user-cog', 'ruta' => '../administrativo/usuario/index.php']
        ],
        'Tecnico' => [
            'accesorios_y_componentes' => ['icono' => 'fa-tools', 'ruta' => '../tecnico/accesorios_componentes/index.php'],
            'area_tecnico' => ['icono' => 'fa-cogs', 'ruta' => '../tecnico/area_tecnico/index.php'],
            'cliente_con_usuario' => ['icono' => 'fa-user', 'ruta' => '../tecnico/cliente_con_usuario/index.php'],
            'clientes' => ['icono' => 'fa-user', 'ruta' => '../tecnico/clientes/index.php'],
            'comprobante_proveedores' => ['icono' => 'fa-box', 'ruta' => '../tecnico/comprobante_proveedores/index.php'],
            'detalle_factura' => ['icono' => 'fa-wrench', 'ruta' => '../tecnico/detalle_factura/index.php'],
            'detalle_reparaciones' => ['icono' => 'fa-wrench', 'ruta' => '../tecnico/detalle_reparaciones/index.php'],
            'detalle_servicios' => ['icono' => 'fa-shopping-cart', 'ruta' => '../tecnico/detalle_servicios/index.php'],
            'dispositivos' => ['icono' => 'fa-laptop', 'ruta' => '../tecnico/dispositivos/index.php'],
            'cabecera_factura' => ['icono' => 'fa-file-invoice', 'ruta' => '../tecnico/cabecera_factura/index.php'],
            'historial_cambios_contrasena' => ['icono' => 'fa-history', 'ruta' => '../tecnico/historial_cambios_contrasena/index.php'],
            'notificaciones' => ['icono' => 'fa-bell', 'ruta' => '../tecnico/notificaciones/index.php'],
            'operacion' => ['icono' => 'fa-credit-card', 'ruta' => '../tecnico/operacion/index.php'],
            'pedidos_de_reparacion' => ['icono' => 'fa-repair', 'ruta' => '../tecnico/pedidos_de_reparacion/index.php'],
            'permisos' => ['icono' => 'fa-shield-alt', 'ruta' => '../tecnico/permisos/index.php'],
            'permisos_en_roles' => ['icono' => 'fa-user-shield', 'ruta' => '../tecnico/permisos_en_roles/index.php'],
            'proveedores' => ['icono' => 'fa-truck', 'ruta' => '../tecnico/proveedores/index.php'],
            'roles' => ['icono' => 'fa-users-cog', 'ruta' => '../tecnico/roles/index.php'],
            'servicios' => ['icono' => 'fa-users-cog', 'ruta' => '../tecnico/roles/index.php'],
            'tecnicos' => ['icono' => 'fa-tools', 'ruta' => '../tecnico/tecnicos/index.php'],
            'tipo_comprobante' => ['icono' => 'fa-users-cog', 'ruta' => '../tecnico/tipo_comprobante/index.php'],
            'tipo_de_pago' => ['icono' => 'fa-money-bill-wave', 'ruta' => '../tecnico/tipo_de_pago/index.php'],
            'usuario' => ['icono' => 'fa-user-cog', 'ruta' => '../tecnico/usuario/index.php']
        ],
        'Cliente' => [
            'accesorios_y_componentes' => ['icono' => 'fa-tools', 'ruta' => '../cliente/accesorios_componentes/index.php'],
            'area_tecnico' => ['icono' => 'fa-cogs', 'ruta' => '../cliente/area_tecnico/index.php'],
            'cliente_con_usuario' => ['icono' => 'fa-user', 'ruta' => '../cliente/cliente_con_usuario/index.php'],
            'clientes' => ['icono' => 'fa-user', 'ruta' => '../cliente/clientes/index.php'],
            'comprobante_proveedores' => ['icono' => 'fa-box', 'ruta' => '../cliente/comprobante_proveedores/index.php'],
            'detalle_factura' => ['icono' => 'fa-wrench', 'ruta' => '../cliente/detalle_factura/index.php'],
            'detalle_reparaciones' => ['icono' => 'fa-wrench', 'ruta' => '../cliente/detalle_reparaciones/index.php'],
            'detalle_servicios' => ['icono' => 'fa-shopping-cart', 'ruta' => '../cliente/detalle_servicios/index.php'],
            'dispositivos' => ['icono' => 'fa-laptop', 'ruta' => '../cliente/dispositivos/index.php'],
            'cabecera_factura' => ['icono' => 'fa-file-invoice', 'ruta' => '../cliente/cabecera_factura/index.php'],
            'historial_cambios_contrasena' => ['icono' => 'fa-history', 'ruta' => '../cliente/historial_cambios_contrasena/index.php'],
            'notificaciones' => ['icono' => 'fa-bell', 'ruta' => '../cliente/notificaciones/index.php'],
            'operacion' => ['icono' => 'fa-credit-card', 'ruta' => '../cliente/operacion/index.php'],
            'pedidos_de_reparacion' => ['icono' => 'fa-repair', 'ruta' => '../cliente/pedidos_de_reparacion/index.php'],
            'permisos' => ['icono' => 'fa-shield-alt', 'ruta' => '../cliente/permisos/index.php'],
            'permisos_en_roles' => ['icono' => 'fa-user-shield', 'ruta' => '../cliente/permisos_en_roles/index.php'],
            'proveedores' => ['icono' => 'fa-truck', 'ruta' => '../cliente/proveedores/index.php'],
            'roles' => ['icono' => 'fa-users-cog', 'ruta' => '../cliente/roles/index.php'],
            'servicios' => ['icono' => 'fa-users-cog', 'ruta' => '../cliente/roles/index.php'],
            'tecnicos' => ['icono' => 'fa-tools', 'ruta' => '../cliente/tecnicos/index.php'],
            'tipo_comprobante' => ['icono' => 'fa-users-cog', 'ruta' => '../cliente/tipo_comprobante/index.php'],
            'tipo_de_pago' => ['icono' => 'fa-money-bill-wave', 'ruta' => '../cliente/tipo_de_pago/index.php'],
            'usuario' => ['icono' => 'fa-user-cog', 'ruta' => '../cliente/usuario/index.php']
        ],
        'Empleado' => [

            'accesorios_y_componentes' => ['icono' => 'fa-tools', 'ruta' => '../empleados/accesorios_componentes/index.php'],
            'area_tecnico' => ['icono' => 'fa-cogs', 'ruta' => '../empleados/area_tecnico/index.php'],
            'cliente_con_usuario' => ['icono' => 'fa-user', 'ruta' => '../cliente/cliente_con_usuario/index.php'],
            'clientes' => ['icono' => 'fa-user', 'ruta' => '../empleados/clientes/index.php'],
            'comprobante_proveedores' => ['icono' => 'fa-box', 'ruta' => '../empleados/comprobante_proveedores/index.php'],
            'detalle_factura' => ['icono' => 'fa-wrench', 'ruta' => '../empleados/detalle_factura/index.php'],
            'detalle_reparaciones' => ['icono' => 'fa-wrench', 'ruta' => '../cempleados/detalle_reparaciones/index.php'],
            'detalle_servicios' => ['icono' => 'fa-shopping-cart', 'ruta' => '../empleados/detalle_servicios/index.php'],
            'dispositivos' => ['icono' => 'fa-laptop', 'ruta' => '../empleados/dispositivos/index.php'],
            'cabecera_factura' => ['icono' => 'fa-file-invoice', 'ruta' => '../cliente/cabecera_factura/index.php'],
            'historial_cambios_contrasena' => ['icono' => 'fa-history', 'ruta' => '../cliente/historial_cambios_contrasena/index.php'],
            'notificaciones' => ['icono' => 'fa-bell', 'ruta' => '../cliente/notificaciones/index.php'],
            'operacion' => ['icono' => 'fa-credit-card', 'ruta' => '../cliente/operacion/index.php'],
            'pedidos_de_reparacion' => ['icono' => 'fa-repair', 'ruta' => '../cliente/pedidos_de_reparacion/index.php'],
            'permisos' => ['icono' => 'fa-shield-alt', 'ruta' => '../cliente/permisos/index.php'],
            'permisos_en_roles' => ['icono' => 'fa-user-shield', 'ruta' => '../cliente/permisos_en_roles/index.php'],
            'proveedores' => ['icono' => 'fa-truck', 'ruta' => '../cliente/proveedores/index.php'],
            'roles' => ['icono' => 'fa-users-cog', 'ruta' => '../cliente/roles/index.php'],
            'servicios' => ['icono' => 'fa-users-cog', 'ruta' => '../cliente/roles/index.php'],
            'tecnicos' => ['icono' => 'fa-tools', 'ruta' => '../cliente/tecnicos/index.php'],
            'tipo_comprobante' => ['icono' => 'fa-users-cog', 'ruta' => '../cliente/tipo_comprobante/index.php'],
            'tipo_de_pago' => ['icono' => 'fa-money-bill-wave', 'ruta' => '../cliente/tipo_de_pago/index.php'],
            'usuario' => ['icono' => 'fa-user-cog', 'ruta' => '../cliente/usuario/index.php']
        ],
    ];

    $query_rol = "SELECT nombre FROM roles WHERE id_roles = (SELECT id_roles FROM usuario WHERE id_usuario = ?)";
    $stmt_rol = $conn->prepare($query_rol);
    $stmt_rol->bind_param("i", $user_id);
    $stmt_rol->execute();
    $result_rol = $stmt_rol->get_result();
    $rol_row = $result_rol->fetch_assoc();
    $rol_nombre = $rol_row['nombre'];
    $stmt_rol->close();

    $iconos_visibles = [];
    if (isset($iconos_y_rutas[$rol_nombre])) {
        foreach ($iconos_y_rutas[$rol_nombre] as $tabla => $icono_y_ruta) {
            if (isset($permisos[$tabla]) && $permisos[$tabla] == 1) {
                $iconos_visibles[$tabla] = $icono_y_ruta;
            }
        }
    }

    return $iconos_visibles;
}

if (!isset($_SESSION['user_id'])) {
    die('Usuario no autenticado.');
}
$user_id = $_SESSION['user_id'];

$iconos_visibles = obtenerIconos($user_id);
?>
