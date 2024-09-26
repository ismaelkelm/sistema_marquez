<?php
include '../../base_datos/db.php';

$id = $_GET['id'];

$query = "SELECT * FROM operacion WHERE id_operacion = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tipo = $_POST['tipo'];

    $query = "UPDATE operacion SET tipo = ? WHERE id_operacion = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'si', $tipo, $id);

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
    <h1>Editar Operación</h1>
    <form method="POST">
        <div class="form-group">
            <label>Tipo</label>
            <input type="text" name="tipo" class="form-control" value="<?php echo htmlspecialchars($row['tipo']); ?>" required>
        </div>
        <button type="submit" class="btn btn-success mt-3">Guardar Cambios</button>
        <a href="index.php" class="btn btn-secondary mt-3">Volver</a>
    </form>
</div>

<?php include('../../includes/footer.php'); ?>
