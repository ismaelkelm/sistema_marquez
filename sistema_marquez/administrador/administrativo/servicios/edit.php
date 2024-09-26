<?php
include '../../base_datos/db.php';

$id = $_GET['id_servicios'];

$query = "SELECT * FROM servicios WHERE id_servicios = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $descripcion = $_POST['descripcion'];
    $precio_servicio = $_POST['precio_servicio'];
    $tipo_servicio = $_POST['tipo_servicio'];

    $query = "UPDATE servicios SET descripcion = ?, precio_servicio = ?, tipo_servicio = ? WHERE id_servicios = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'sdsi', $descripcion, $precio_servicio, $tipo_servicio, $id);

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
    <h1>Editar Servicio</h1>
    <form method="POST">
        <div class="form-group">
            <label>Descripción</label>
            <input type="text" name="descripcion" class="form-control" value="<?php echo htmlspecialchars($row['descripcion']); ?>" required>
        </div>
        <div class="form-group">
            <label>Precio</label>
            <input type="number" step="0.01" name="precio_servicio" class="form-control" value="<?php echo htmlspecialchars($row['precio_servicio']); ?>" required>
        </div>
        <div class="form-group">
            <label>Tipo de Servicio</label>
            <input type="text" name="tipo_servicio" class="form-control" value="<?php echo htmlspecialchars($row['tipo_servicio']); ?>" required>
        </div>
        <button type="submit" class="btn btn-success mt-3">Actualizar</button>
        <a href="index.php" class="btn btn-secondary mt-3">Volver</a>
    </form>
</div>

<?php include('../../includes/footer.php'); ?>
