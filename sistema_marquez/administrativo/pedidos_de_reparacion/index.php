<?php
// Incluir el archivo de conexión a la base de datos
include '../../base_datos/db.php'; 

// Obtener el siguiente ID de cliente
$query_client_id = "SELECT MAX(id_clientes) AS max_client_id FROM clientes";
$result_client_id = mysqli_query($conn, $query_client_id);
$next_client_id = ($result_client_id && $row_client_id = mysqli_fetch_assoc($result_client_id)) ? (int) ($row_client_id['max_client_id'] ?? 0) + 1 : 1;

// Obtener el siguiente ID del dispositivo
$query_device_id = "SELECT MAX(id_dispositivos) AS max_device_id FROM dispositivos";
$result_device_id = mysqli_query($conn, $query_device_id);
$next_device_id = ($result_device_id && $row_device_id = mysqli_fetch_assoc($result_device_id)) ? (int) ($row_device_id['max_device_id'] ?? 0) + 1 : 1;

// Obtener todos los dispositivos para la lista desplegable
$query_devices = "SELECT id_dispositivos, marca, modelo FROM dispositivos";
$result_devices = mysqli_query($conn, $query_devices);

// Obtener el siguiente ID de pedido de reparación
$query_order_id = "SELECT MAX(id_pedidos_de_reparacion) AS max_order_id FROM pedidos_de_reparacion";
$result_order_id = mysqli_query($conn, $query_order_id);
$next_order_id = ($result_order_id && $row_order_id = mysqli_fetch_assoc($result_order_id)) ? (int) ($row_order_id['max_order_id'] ?? 0) + 1 : 1;

// Obtener el último número de orden y calcular el siguiente
$query_max_order = "SELECT numero_orden FROM pedidos_de_reparacion ORDER BY id_pedidos_de_reparacion DESC LIMIT 1";
$result_max_order = mysqli_query($conn, $query_max_order);
$last_order_row = mysqli_fetch_assoc($result_max_order);
$last_order_number = $last_order_row['numero_orden'] ?? 'ORD0000';
$next_order = (int) substr($last_order_number, 3) + 1; // Extraer el número y aumentar
$formatted_order_number = 'ORD' . str_pad($next_order, 4, '0', STR_PAD_LEFT); // Ej: ORD0009

// Obtener el último ID registrado de técnicos
$query_last_technician_id = "SELECT MAX(id_tecnicos) AS max_technician_id FROM tecnicos";
$result_last_technician_id = mysqli_query($conn, $query_last_technician_id);
$last_technician_id = ($result_last_technician_id && $row_last_technician_id = mysqli_fetch_assoc($result_last_technician_id)) ? (int) $row_last_technician_id['max_technician_id'] : 1;

if (isset($_POST['registrar_pedido'])) {
    $id_dispositivo = $_POST['id_dispositivos'];

    // Asegúrate de que el dispositivo esté seleccionado
    if (!$id_dispositivo) {
        echo "Por favor, seleccione un dispositivo.";
    } else {
        $estado_reparacion = $_POST['estado_reparacion'];
        $observacion = $_POST['observacion'];

        // Inserción en la base de datos
        $query_insert_order = "INSERT INTO pedidos_de_reparacion (id_pedidos_de_reparacion, fecha_de_pedido, numero_orden, observacion, estado, id_dispositivos, id_tecnicos, id_clientes) VALUES ($next_order_id, NOW(), '$formatted_order_number', '$observacion', '$estado_reparacion', $id_dispositivo, 1, $next_client_id)";
        
        // Ejecutar la consulta
        if (mysqli_query($conn, $query_insert_order)) {
            header('Location: '.$_SERVER['PHP_SELF']);
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
}
?>

<?php include('../../includes/header.php'); ?>

<div class="container mt-5">
    <a href="../administrativo.php" class="btn btn-secondary mb-3">Volver</a>

    <!-- Formulario para registrar un nuevo cliente -->
    <h1 class="mb-4">Registrar Nuevo Cliente</h1>
    <form method="post" class="row g-3">
        <div class="col-md-6 form-floating">
            <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre" required>
            <label for="nombre">Nombre</label>
        </div>
        <div class="col-md-6 form-floating">
            <input type="text" class="form-control" id="apellido" name="apellido" placeholder="Apellido" required>
            <label for="apellido">Apellido</label>
        </div>
        <div class="col-md-6 form-floating">
            <input type="text" class="form-control" id="telefono" name="telefono" placeholder="Teléfono" required>
            <label for="telefono">Teléfono</label>
        </div>
        <div class="col-md-6 form-floating">
            <input type="email" class="form-control" id="correo_electronico" name="correo_electronico" placeholder="Correo Electrónico" required>
            <label for="correo_electronico">Correo Electrónico</label>
        </div>
        <div class="col-md-6 form-floating">
            <input type="text" class="form-control" id="direccion" name="direccion" placeholder="Dirección" required>
            <label for="direccion">Dirección</label>
        </div>
        <div class="col-md-6 form-floating">
            <input type="text" class="form-control" id="dni" name="dni" placeholder="DNI" required>
            <label for="dni">DNI</label>
        </div>
        <div class="col-md-12">
            <button type="submit" name="registrar_cliente" class="btn btn-primary">Registrar Cliente</button>
        </div>
        <div class="col-md-12 mt-2">
            <label>ID del Cliente Siguiente: <?php echo $next_client_id; ?></label>
        </div>
    </form>

    <!-- Formulario para registrar un nuevo dispositivo -->
    <h1 class="mb-4 mt-5">Registrar Nuevo Dispositivo</h1>
    <form method="post" class="row g-3">
        <div class="col-md-4 form-floating">
            <input type="text" class="form-control" id="marca" name="marca" placeholder="Marca" required>
            <label for="marca">Marca</label>
        </div>
        <div class="col-md-4 form-floating">
            <input type="text" class="form-control" id="modelo" name="modelo" placeholder="Modelo" required>
            <label for="modelo">Modelo</label>
        </div>
        <div class="col-md-4 form-floating">
            <input type="text" class="form-control" id="numero_de_serie" name="numero_de_serie" placeholder="Número de Serie" required>
            <label for="numero_de_serie">Número de Serie</label>
        </div>
        <div class="col-md-12">
            <button type="submit" name="registrar_dispositivo" class="btn btn-primary">Registrar Dispositivo</button>
        </div>
        <div class="col-md-12 mt-2">
            <label>ID del Dispositivo Siguiente: <?php echo $next_device_id; ?></label>
        </div>
    </form>

    <!-- Seleccionar dispositivo registrado -->
    <h2 class="mt-5">Seleccionar Dispositivo Registrado</h2>
    <form method="post">
        <div class="col-md-12">
            <select class="form-select" name="id_dispositivo" required>
                <option value="">Seleccione un Dispositivo</option>
                <?php while ($device = mysqli_fetch_assoc($result_devices)) : ?>
                    <option value="<?php echo $device['id_dispositivos']; ?>">
                        <?php echo $device['marca'] . ' - ' . $device['modelo']; ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
    </form>

    <!-- Formulario para registrar un nuevo pedido de reparación -->
    <h1 class="mb-4 mt-5">Registrar Nuevo Pedido de Reparación</h1>
    <form method="post" class="row g-3">
        <div class="col-md-6 form-floating">
            <input type="text" class="form-control" id="id_pedidos_de_reparacion" value="<?php echo $next_order_id; ?>" readonly>
            <label for="id_pedidos_de_reparacion">ID del Pedido</label>
        </div>
        <div class="col-md-6 form-floating">
            <input type="text" class="form-control" id="fecha_pedido" value="<?php echo date('Y-m-d H:i:s'); ?>" readonly>
            <label for="fecha_pedido">Fecha de Pedido</label>
        </div>
        <div class="col-md-6 form-floating">
            <select class="form-select" id="id_dispositivo" name="id_dispositivo" required>
                <option value="">Seleccione un Dispositivo</option>
                <?php while ($device = mysqli_fetch_assoc($result_devices)) : ?>
                    <option value="<?php echo $device['id_dispositivos']; ?>">
                        <?php echo $device['marca'] . ' - ' . $device['modelo']; ?>
                    </option>
                <?php endwhile; ?>
            </select>
            <label for="id_dispositivo">Dispositivo</label>
        </div>
        <div class="col-md-6 form-floating">
            <select class="form-select" id="estado_reparacion" name="estado_reparacion" required>
                <option value="Pendiente">Pendiente</option>
                <option value="Completado">Completado</option>
                <option value="En proceso">En proceso</option>
                <option value="Cancelado">Cancelado</option>
                <option value="Entregado">Entregado</option>
            </select>
            <label for="estado_reparacion">Estado de Reparación</label>
        </div>
        <div class="col-md-6 form-floating">
            <input type="text" class="form-control" id="numero_orden" value="<?php echo $formatted_order_number; ?>" readonly>
            <label for="numero_orden">Número de Orden</label>
        </div>
        <div class="col-md-6 form-floating">
            <textarea class="form-control" id="observacion" name="observacion" placeholder="Observación" required></textarea>
            <label for="observacion">Observación</label>
        </div>
        <div class="col-md-12">
            <button type="submit" name="registrar_pedido" class="btn btn-primary">Registrar Pedido</button>
        </div>
        <div class="col-md-12 mt-2">
            <label>ID del Pedido Siguiente: <?php echo $next_order_id; ?></label>
        </div>
        <div class="col-md-12 mt-2">
            <label>Número de Orden Siguiente: <?php echo $formatted_order_number; ?></label>
        </div>
    </form>
</div>

<?php include('../../includes/footer.php'); ?>
