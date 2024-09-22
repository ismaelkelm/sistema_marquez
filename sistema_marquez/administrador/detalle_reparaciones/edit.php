<?php
include '../../base_datos/db.php'; // Incluye el archivo de conexión

$id = $_GET['id'];

$query = "SELECT * FROM detalle_reparaciones WHERE id_detalle_reparaciones = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fecha_finalizada = $_POST['fecha_finalizada'];
    $descripcion = $_POST['descripcion'];
    $id_pedidos_de_reparacion = $_POST['id_pedidos_de_reparacion'];
    $id_servicios = $_POST['id_servicios'];

    $query = "UPDATE detalle_reparaciones SET fecha_finalizada = ?, descripcion = ?, id_pedidos_de_reparacion = ?, id_servicios = ? WHERE id_detalle_reparaciones = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'ssiii', $fecha_finalizada, $descripcion, $id_pedidos_de_reparacion, $id_servicios, $id);

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
    <h1>Editar Detalle de Reparaciones</h1>
    <form method="POST">
        <div class="form-group">
            <label>Fecha Finalizada</label>
            <input type="date" name="fecha_finalizada" class="form-control" value="<?php echo htmlspecialchars($row['fecha_finalizada']); ?>" required>
        </div>
        <div class="form-group">
            <label>Descripción</label>
            <textarea name="descripcion" class="form-control" required><?php echo htmlspecialchars($row['descripcion']); ?></textarea>
        </div>
        <div class="form-group">
            <label>ID Pedido de Reparación</label>
            <input type="number" name="id_pedidos_de_reparacion" class="form-control" value="<?php echo htmlspecialchars($row['id_pedidos_de_reparacion']); ?>" required>
        </div>
        <div class="form-group">
            <label>ID Servicio</label>
            <input type="number" name="id_servicios" class="form-control" value="<?php echo htmlspecialchars($row['id_servicios']); ?>" required>
        </div>
        <button type="submit" class="btn btn-success">Guardar Cambios</button>
        <a href="index.php" class="btn btn-secondary">Volver Atrás</a>
    </form>
</div>

<?php include('../../includes/footer.php'); ?>
