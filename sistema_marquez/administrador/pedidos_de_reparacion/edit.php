<?php
include '../../base_datos/db.php';

$id = $_GET['id'];

$query = "SELECT * FROM pedidos_de_reparacion WHERE id_pedidos_de_reparacion = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fecha_de_pedido = $_POST['fecha_de_pedido'];
    $estado_reparacion = $_POST['estado_reparacion'];
    $numero_orden = $_POST['numero_orden'];
    $observacion = $_POST['observacion'];
    $id_dispositivos = $_POST['id_dispositivos'];
    $id_tecnicos = $_POST['id_tecnicos'];
    $id_clientes = $_POST['id_clientes'];

    $query = "UPDATE pedidos_de_reparacion SET fecha_de_pedido = ?, estado_reparacion = ?, numero_orden = ?, observacion = ?, id_dispositivos = ?, id_tecnicos = ?, id_clientes = ? WHERE id_pedidos_de_reparacion = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'sssssiii', $fecha_de_pedido, $estado_reparacion, $numero_orden, $observacion, $id_dispositivos, $id_tecnicos, $id_clientes, $id);

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
    <h1>Editar Pedido de Reparación</h1>
    <form method="POST">
        <div class="form-group">
            <label>Fecha de Pedido</label>
            <input type="date" name="fecha_de_pedido" class="form-control" value="<?php echo htmlspecialchars($row['fecha_de_pedido']); ?>" required>
        </div>
        <div class="form-group">
            <label>Estado</label>
            <input type="text" name="estado_reparacion" class="form-control" value="<?php echo htmlspecialchars($row['estado_reparacion']); ?>" required>
        </div>
        <div class="form-group">
            <label>Número de Orden</label>
            <input type="text" name="numero_orden" class="form-control" value="<?php echo htmlspecialchars($row['numero_orden']); ?>" required>
        </div>
        <div class="form-group">
            <label>Observación</label>
            <textarea name="observacion" class="form-control" required><?php echo htmlspecialchars($row['observacion']); ?></textarea>
        </div>
        <div class="form-group">
            <label>ID Dispositivos</label>
            <input type="number" name="id_dispositivos" class="form-control" value="<?php echo htmlspecialchars($row['id_dispositivos']); ?>" required>
        </div>
        <div class="form-group">
            <label>ID Técnicos</label>
            <input type="number" name="id_tecnicos" class="form-control" value="<?php echo htmlspecialchars($row['id_tecnicos']); ?>" required>
        </div>
        <div class="form-group">
            <label>ID Clientes</label>
            <input type="number" name="id_clientes" class="form-control" value="<?php echo htmlspecialchars($row['id_clientes']); ?>" required>
        </div>
        <button type="submit" class="btn btn-success mt-3">Actualizar</button>
        <a href="index.php" class="btn btn-secondary mt-3">Volver</a>
    </form>
</div>

<?php include('../../includes/footer.php'); ?>
