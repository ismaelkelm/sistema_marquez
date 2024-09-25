<?php
include '../../base_datos/db.php'; // Incluye el archivo de conexión

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $detalle_servicios = $_POST['detalle_servicios'];
    $id_servicios = $_POST['id_servicios'];

    $query = "INSERT INTO detalle_servicios (detalle_servicios, id_servicios) VALUES (?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'si', $detalle_servicios, $id_servicios);

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
    <h1>Agregar Detalle de Servicios</h1>
    <form method="POST">
        <div class="form-group">
            <label>Detalle de Servicios</label>
            <input type="text" name="detalle_servicios" class="form-control" required>
        </div>
        <div class="form-group">
            <label>ID Servicios</label>
            <input type="number" name="id_servicios" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">Guardar</button>
        <a href="index.php" class="btn btn-secondary">Volver Atrás</a>
    </form>
</div>

<?php include('../../includes/footer.php'); ?>
