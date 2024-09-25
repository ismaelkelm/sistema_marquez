<?php
include '../../base_datos/db.php';

$id = $_GET['id_tipo_de_pago'];

$query = "SELECT * FROM tipo_de_pago WHERE id_tipo_de_pago = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $descripcion_de_pago = $_POST['descripcion_de_pago'];

    $query = "UPDATE tipo_de_pago SET descripcion_de_pago = ? WHERE id_tipo_de_pago = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'si', $descripcion_de_pago, $id);

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
    <h1>Editar Tipo de Pago</h1>
    <form method="POST">
        <div class="form-group">
            <label>Descripción</label>
            <input type="text" name="descripcion_de_pago" class="form-control" value="<?php echo htmlspecialchars($row['descripcion_de_pago']); ?>" required>
        </div>
        <button type="submit" class="btn btn-success mt-3">Actualizar</button>
        <a href="index.php" class="btn btn-secondary mt-3">Volver</a>
    </form>
</div>

<?php include('../../includes/footer.php'); ?>
