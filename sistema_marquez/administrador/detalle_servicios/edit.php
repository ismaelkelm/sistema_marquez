<?php
include '../../base_datos/db.php'; // Incluye el archivo de conexión

$id = $_GET['id'];

$query = "SELECT * FROM detalle_servicios WHERE id_detalle_servicios = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $detalle_servicios = $_POST['detalle_servicios'];
    $id_servicios = $_POST['id_servicios'];

    $query = "UPDATE detalle_servicios SET detalle_servicios = ?, id_servicios = ? WHERE id_detalle_servicios = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'sii', $detalle_servicios, $id_servicios, $id);

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
    <h1>Editar Detalle de Servicios</h1>
    <form method="POST">
        <div class="form-group">
            <label>Detalle de Servicios</label>
            <input type="text" name="detalle_servicios" class="form-control" value="<?php echo htmlspecialchars($row['detalle_servicios']); ?>" required>
        </div>
        <div class="form-group">
            <label>ID Servicios</label>
            <input type="number" name="id_servicios" class="form-control" value="<?php echo htmlspecialchars($row['id_servicios']); ?>" required>
        </div>
        <button type="submit" class="btn btn-success">Guardar Cambios</button>
        <a href="index.php" class="btn btn-secondary">Volver Atrás</a>
    </form>
</div>

<?php include('../../includes/footer.php'); ?>
