<?php
include '../../base_datos/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_usuario = $_POST['id_usuario'];
    $fecha_cambio = $_POST['fecha_cambio'];
    $motivo = $_POST['motivo'];

    $query = "INSERT INTO historial_cambios_contrasena (id_usuario, fecha_cambio, motivo) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'iss', $id_usuario, $fecha_cambio, $motivo);

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
    <h1>Agregar Cambio de Contraseña</h1>
    <form method="POST">
        <div class="form-group">
            <label>ID Usuario</label>
            <input type="number" name="id_usuario" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Fecha de Cambio</label>
            <input type="date" name="fecha_cambio" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Motivo</label>
            <textarea name="motivo" class="form-control" required></textarea>
        </div>
        <button type="submit" class="btn btn-success mt-3">Guardar</button>
        <a href="index.php" class="btn btn-secondary mb-3">Volver</a>
    </form>
</div>

<?php include('../../includes/footer.php'); ?>
