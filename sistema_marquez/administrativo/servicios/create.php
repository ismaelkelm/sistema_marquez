<?php
include '../../base_datos/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $descripcion = $_POST['descripcion'];
    $precio_servicio = $_POST['precio_servicio'];
    $tipo_servicio = $_POST['tipo_servicio'];

    $query = "INSERT INTO servicios (descripcion, precio_servicio, tipo_servicio) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'sds', $descripcion, $precio_servicio, $tipo_servicio);

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
    <h1>Agregar Servicio</h1>
    <form method="POST">
        <div class="form-group">
            <label>Descripción</label>
            <input type="text" name="descripcion" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Precio</label>
            <input type="number" step="0.01" name="precio_servicio" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Tipo de Servicio</label>
            <input type="text" name="tipo_servicio" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success mt-3">Guardar</button>
        <a href="index.php" class="btn btn-secondary mt-3">Volver</a>
    </form>
</div>

<?php include('../../includes/footer.php'); ?>
