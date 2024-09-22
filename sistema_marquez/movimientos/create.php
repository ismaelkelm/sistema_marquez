<?php
// Incluir el archivo de conexión a la base de datos
include '../../base_datos/db.php'; // Ajusta la ruta según la ubicación del archivo

// Consultar recibos y tickets para los select
$recibos_query = "SELECT id_recibo FROM recibos"; // Ajusta el nombre de la tabla si es diferente
$recibos_result = mysqli_query($conn, $recibos_query);

$tickets_query = "SELECT id_ticket FROM tickets"; // Ajusta el nombre de la tabla si es diferente
$tickets_result = mysqli_query($conn, $tickets_query);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario y proteger contra inyecciones SQL
    $tipo_movimiento = mysqli_real_escape_string($conn, $_POST['tipo_movimiento']);
    $monto = mysqli_real_escape_string($conn, $_POST['monto']);
    $fecha = mysqli_real_escape_string($conn, $_POST['fecha']);
    $descripcion = mysqli_real_escape_string($conn, $_POST['descripcion']);
    $id_recibo = mysqli_real_escape_string($conn, $_POST['id_recibo']);
    $id_ticket = mysqli_real_escape_string($conn, $_POST['id_ticket']);

    // Preparar la consulta SQL para insertar un nuevo movimiento
    $query = "INSERT INTO movimientos (tipo_movimiento, monto, fecha, descripcion, id_recibo, id_ticket)
              VALUES ('$tipo_movimiento', '$monto', '$fecha', '$descripcion', '$id_recibo', '$id_ticket')";

    // Ejecutar la consulta y verificar si fue exitosa
    if (mysqli_query($conn, $query)) {
        header("Location: index.php"); // Redirigir a la página principal de la lista
        exit();
    } else {
        echo "Error: " . mysqli_error($conn); // Mostrar mensaje de error
    }
}
?>

<?php include('../../includes/header.php'); ?>

<div class="container mt-5">
    <a href="index.php" class="btn btn-secondary mb-3">Volver</a>
    <h1 class="mb-4">Agregar Movimiento</h1>
    <form action="create.php" method="post">
        <div class="form-group">
            <label for="tipo_movimiento">Tipo de Movimiento</label>
            <select class="form-control" id="tipo_movimiento" name="tipo_movimiento" required>
                <option value="Ingreso">Ingreso</option>
                <option value="Egreso">Egreso</option>
            </select>
        </div>
        <div class="form-group">
            <label for="monto">Monto</label>
            <input type="number" step="0.01" class="form-control" id="monto" name="monto" required>
        </div>
        <div class="form-group">
            <label for="fecha">Fecha</label>
            <input type="date" class="form-control" id="fecha" name="fecha" required>
        </div>
        <div class="form-group">
            <label for="descripcion">Descripción</label>
            <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required></textarea>
        </div>
        <div class="form-group">
            <label for="id_recibo">ID Recibo (opcional)</label>
            <select class="form-control" id="id_recibo" name="id_recibo">
                <option value="">Seleccione un recibo</option>
                <?php while ($recibo = mysqli_fetch_assoc($recibos_result)) { ?>
                    <option value="<?php echo htmlspecialchars($recibo['id_recibo']); ?>">
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
                    <option value="<?php echo htmlspecialchars($ticket['id_ticket']); ?>">
                        <?php echo htmlspecialchars($ticket['id_ticket']); ?>
                    </option>
                <?php } ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Agregar</button>
    </form>
</div>

<?php include('../../includes/footer.php'); ?>

<?php
// Cerrar la conexión a la base de datos
mysqli_close($conn);
?>
