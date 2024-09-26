<?php
include '../../base_datos/db.php';

// Obtener el último número de orden
$numero_orden_query = "SELECT numero_orden FROM pedidos_de_reparacion ORDER BY id_pedidos_de_reparacion DESC LIMIT 1";
$numero_orden_result = mysqli_query($conn, $numero_orden_query);
$ultimo_numero_orden = mysqli_fetch_assoc($numero_orden_result)['numero_orden'];

// Extraer el número y calcular el siguiente
if ($ultimo_numero_orden) {
    $numero_actual = (int)substr($ultimo_numero_orden, 3); // Extraer el número después de "ORD"
    $siguiente_numero_orden = 'ORD' . str_pad($numero_actual + 1, 4, '0', STR_PAD_LEFT);
} else {
    $siguiente_numero_orden = 'ORD0001'; // Primer número de orden
}

// Obtener el siguiente ID de pedido de reparación
$id_pedido_query = "SELECT COALESCE(MAX(id_pedidos_de_reparacion), 0) + 1 AS siguiente_id_pedido FROM pedidos_de_reparacion";
$id_pedido_result = mysqli_query($conn, $id_pedido_query);
$siguiente_id_pedido = mysqli_fetch_assoc($id_pedido_result)['siguiente_id_pedido'];

// Obtener los últimos dos clientes
$clientes_query = "SELECT id_clientes, nombre FROM clientes ORDER BY id_clientes DESC LIMIT 2";
$clientes_result = mysqli_query($conn, $clientes_query);

// Obtener los últimos dos dispositivos
$dispositivos_query = "SELECT id_dispositivos, marca FROM dispositivos ORDER BY id_dispositivos DESC LIMIT 2";
$dispositivos_result = mysqli_query($conn, $dispositivos_query);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fecha_de_pedido = date('Y-m-d'); // Fecha actual
    $estado_reparacion = $_POST['estado_reparacion'];
    $observacion = $_POST['observacion'];
    $id_dispositivos = $_POST['id_dispositivos'];
    $id_clientes = $_POST['id_clientes'];
    $id_tecnicos = 0; // Por defecto

    // Consulta de inserción
    $query = "INSERT INTO pedidos_de_reparacion (fecha_de_pedido, estado_reparacion, numero_orden, observacion, id_dispositivos, id_tecnicos, id_clientes) 
              VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'sssssii', $fecha_de_pedido, $estado_reparacion, $siguiente_numero_orden, $observacion, $id_dispositivos, $id_tecnicos, $id_clientes);

    // Ejecutar la consulta
    if (mysqli_stmt_execute($stmt)) {
        // Mostrar mensaje de éxito y redirigir después de 1 segundo
        echo "<script>
                alert('Pedido de reparación registrado con éxito');
                setTimeout(function() {
                    window.location.href = 'http://sistema.local.com/administrativo/pedidos_de_reparacion/registrar_pedido.php';
                }, 1000);
              </script>";
        exit; // Detener el script después de la redirección
    } else {
        echo "Error: " . mysqli_error($conn); // Mostrar error si hay un fallo
    }
}
?>

<?php include('../../includes/header.php'); ?>

<div class="container mt-5">
    <h1>Agregar Pedido de Reparación</h1>

    <!-- Botones Guardar y Volver al inicio del formulario -->
    <div class="mb-3">
        <button type="submit" form="pedidoForm" class="btn btn-success mr-2">Guardar</button> <!-- Botón "Guardar" -->
        <a href="../../administrativo/administrativo.php" class="btn btn-secondary">Volver</a> <!-- Botón "Volver" -->
    </div>

    <!-- Opciones para agregar clientes y dispositivos -->
    <div class="mb-3">
        <a href="registrar_cliente.php" class="btn btn-primary">Agregar Cliente</a>
        <a href="registrar_dispositivo.php" class="btn btn-primary">Agregar Dispositivo</a>
    </div>

    <form id="pedidoForm" method="POST">
        <div class="form-group">
            <label>Fecha de Pedido</label>
            <input type="date" name="fecha_de_pedido" class="form-control" value="<?php echo date('Y-m-d'); ?>" readonly>
        </div>
        <div class="form-group">
            <label>Número de Orden (Siguiente: <?php echo $siguiente_numero_orden; ?>)</label>
            <input type="text" class="form-control" value="<?php echo $siguiente_numero_orden; ?>" readonly>
        </div>
        <div class="form-group">
            <label>ID Pedido de Reparación (Siguiente: <?php echo $siguiente_id_pedido; ?>)</label>
            <input type="text" class="form-control" value="<?php echo $siguiente_id_pedido; ?>" readonly>
        </div>
        <div class="form-group">
            <label>Estado</label>
            <select name="estado_reparacion" class="form-control" required>
                <option value="en proceso">En Proceso</option>
                <option value="pendiente">Pendiente</option>
                <option value="completado">Completado</option>
            </select>
        </div>
        <div class="form-group">
            <label>Observación</label>
            <textarea name="observacion" class="form-control" required></textarea>
        </div>
        <div class="form-group">
            <label>ID Dispositivos</label>
            <select name="id_dispositivos" class="form-control" required>
                <?php while ($row = mysqli_fetch_assoc($dispositivos_result)): ?>
                    <option value="<?php echo $row['id_dispositivos']; ?>"><?php echo $row['marca']; ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="form-group">
            <label>ID Clientes</label>
            <select name="id_clientes" class="form-control" required>
                <?php while ($row = mysqli_fetch_assoc($clientes_result)): ?>
                    <option value="<?php echo $row['id_clientes']; ?>"><?php echo $row['nombre']; ?></option>
                <?php endwhile; ?>
            </select>
        </div>
    </form>
</div>

<?php include('../../includes/footer.php'); ?>

<?php
// Cerrar la conexión a la base de datos
mysqli_close($conn);
?>
