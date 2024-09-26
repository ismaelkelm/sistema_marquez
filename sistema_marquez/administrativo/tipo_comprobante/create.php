<?php
include '../../base_datos/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tipo_comprobante = $_POST['tipo_comprobante'];

    $query = "INSERT INTO tipo_comprobante (tipo_comprobante) VALUES (?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 's', $tipo_comprobante);

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
    <h1>Agregar Tipo de Comprobante</h1>
    <form method="POST">
        <div class="form-group">
            <label>Descripción</label>
            <input type="text" name="tipo_comprobante" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success mt-3">Guardar</button>
        <a href="index.php" class="btn btn-secondary mt-3">Volver</a>
    </form>
</div>

<?php include('../../includes/footer.php'); ?>
