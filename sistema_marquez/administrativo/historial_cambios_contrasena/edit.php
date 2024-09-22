<?php
include '../../base_datos/db.php';

$id = $_GET['id'];

$query = "SELECT * FROM historial_cambios_contrasena WHERE id_cambio = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_usuario = $_POST['id_usuario'];
    $fecha_cambio = $_POST['fecha_cambio'];
    $motivo = $_POST['motivo'];

    $query = "UPDATE historial_cambios_contrasena SET id_usuario = ?, fecha_cambio = ?, motivo = ? WHERE id_cambio = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'issi', $id_usuario, $fecha_cambio, $motivo, $id);

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
    <h1>Editar Cambio de Contraseña</h1>
    <form method="POST">
        <div class="form-group">
            <label>ID Usuario</label>
            <input type="number" name="id_usuario" class="form-control" value="<?php echo htmlspecialchars($row['id_usuario']); ?>" required>
        </div>
        <div class="form-group">
            <label>Fecha de Cambio</label>
            <input type="date" name="fecha_cambio" class="form-control" value="<?php echo htmlspecialchars($row['fecha_cambio']); ?>" required>
        </div>
        <div class="form-group">
            <label>Motivo</label>
            <textarea name="motivo" class="form-control" required><?php echo htmlspecialchars($row['motivo']); ?></textarea>
        </div>
        <button type="submit" class="btn btn-success mt-3">Guardar Cambios</button>
        <a href="index.php" class="btn btn-secondary mt-3">Volver</a>
    </form>
</div>

<?php include('../../includes/footer.php'); ?>
