<?php
include '../../base_datos/db.php';

$id = $_GET['id'];

$query = "SELECT * FROM notificaciones WHERE id_notificaciones = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $mensaje = $_POST['mensaje'];
    $fecha_de_envío = $_POST['fecha_de_envío'];
    $estado = $_POST['estado'];
    $numero_orden = $_POST['numero_orden'];
    $id_pedidos_de_reparacion = $_POST['id_pedidos_de_reparacion'];

    $query = "UPDATE notificaciones SET mensaje = ?, fecha_de_envío = ?, estado = ?, numero_orden = ?, id_pedidos_de_reparacion = ? WHERE id_notificaciones = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'ssssii', $mensaje, $fecha_de_envío, $estado, $numero_orden, $id_pedidos_de_reparacion, $id);

    if (mysqli_stmt_execute($stmt)) {
        header('Location: index.php');
        exit;
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<?php include('../../includes/header.php'); ?>

<div class="container mt-5">
    <h1>Editar Notificación</h1>
    <form method="POST">
        <div class="form-group">
            <label>Mensaje</label>
            <input type="text" name="mensaje" class="form-control" value="<?php echo htmlspecialchars($row['mensaje']); ?>" required>
        </div>
        <div class="form-group">
            <label>Fecha de Envío</label>
            <input type="datetime-local" name="fecha_de_envío" class="form-control" value="<?php echo htmlspecialchars(date('Y-m-d\TH:i', strtotime($row['fecha_de_envío']))); ?>" required>
        </div>
        <div class="form-group">
            <label>Estado</label>
            <input type="text" name="estado" class="form-control" value="<?php echo htmlspecialchars($row['estado']); ?>" required>
        </div>
        <div class="form-group">
            <label>Número de Orden</label>
            <input type="text" name="numero_orden" class="form-control" value="<?php echo htmlspecialchars($row['numero_orden']); ?>" required>
        </div>
        <div class="form-group">
            <label>ID Pedido de Reparación</label>
            <input type="number" name="id_pedidos_de_reparacion" class="form-control" value="<?php echo htmlspecialchars($row['id_pedidos_de_reparacion']); ?>" required>
        </div>
        <button type="submit" class="btn btn-success mt-3">Guardar Cambios</button>
        <a href="index.php" class="btn btn-secondary mt-3">Volver</a>
    </form>
</div>

<?php include('../../includes/footer.php'); ?>
