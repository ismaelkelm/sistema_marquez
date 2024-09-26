<?php
// Incluir el archivo de conexión a la base de datos
include '../../base_datos/db.php'; // Ajusta la ruta según la ubicación del archivo

// Obtener el ID del movimiento desde la URL
$id_movimiento = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Consultar el movimiento a editar
$query = "SELECT * FROM movimientos WHERE id_movimiento = $id_movimiento";
$result = mysqli_query($conn, $query);
$movimiento = mysqli_fetch_assoc($result);

if (!$movimiento) {
    die("Movimiento no encontrado.");
}

// Consultar recibos y tickets para los select
$recibos_query = "SELECT id_recibo FROM recibos"; // Ajusta el nombre de la tabla si es diferente
$recibos_result = mysqli_query($conn, $recibos_query);

$tickets_query = "SELECT id_ticket FROM tickets"; // Ajusta el nombre de la tabla si es diferente
$tickets_result = mysqli_query($conn, $tickets_query);
?>

<?php include('../../includes/header.php'); ?>

<div class="container mt-5">
    <a href="index.php" class="btn btn-secondary mb-3">Volver</a>
    <h1 class="mb-4">Editar Movimiento</h1>
    <form action="update.php" method="post">
        <input type="hidden" name="id_movimiento" value="<?php echo htmlspecialchars($movimiento['id_movimiento']); ?>">
        
        <div class="form-group">
            <label for="tipo_movimiento">Tipo de Movimiento</label>
            <select class="form-control" id="tipo_movimiento" name="tipo_movimiento" required>
                <option value="Ingreso" <?php echo ($movimiento['tipo_movimiento'] == 'Ingreso') ? 'selected' : ''; ?>>Ingreso</option>
                <option value="Egreso" <?php echo ($movimiento['tipo_movimiento'] == 'Egreso') ? 'selected' : ''; ?>>Egreso</option>
            </select>
        </div>
        <div class="form-group">
            <label for="monto">Monto</label>
            <input type="number" step="0.01" class="form-control" id="monto" name="monto" 
                   value="<?php echo htmlspecialchars($movimiento['monto']); ?>" required>
        </div>
        <div class="form-group">
            <label for="fecha">Fecha</label>
            <input type="date" class="form-control" id="fecha" name="fecha" 
                   value="<?php echo htmlspecialchars($movimiento['fecha']); ?>" required>
        </div>
        <div class="form-group">
            <label for="descripcion">Descripción</label>
            <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required><?php echo htmlspecialchars($movimiento['descripcion']); ?></textarea>
        </div>
        <div class="form-group">
            <label for="id_recibo">ID Recibo (opcional)</label>
            <select class="form-control" id="id_recibo" name="id_recibo">
                <option value="">Seleccione un recibo</option>
                <?php while ($recibo = mysqli_fetch_assoc($recibos_result)) { ?>
                    <option value="<?php echo htmlspecialchars($recibo['id_recibo']); ?>" 
                        <?php echo ($movimiento['id_recibo'] == $recibo['id_recibo']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($recibo['id_recibo']); ?>
                    </option>
                <?php } ?>
            </select>
        </div>
        <div class="form-group">
            <label for="id_ticket">ID Ticket (opcional)</label>
            <select class="form-control" id="id_ticket" name="id_ticket">
                <option value="">Seleccione un ticket</option>
                <?php while ($ticket = mysqli_fetch_assoc($tickets_result)) { ?>
                    <option value="<?php echo htmlspecialchars($ticket['id_ticket']); ?>" 
                        <?php echo ($movimiento['id_ticket'] == $ticket['id_ticket']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($ticket['id_ticket']); ?>
                    </option>
                <?php } ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Actualizar</button>
    </form>
</div>

<?php include('../../includes/footer.php'); ?>

<?php
// Cerrar la conexión a la base de datos
mysqli_close($conn);
?>
