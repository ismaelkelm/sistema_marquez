<?php
include '../../base_datos/db.php';

$id = $_GET['id_tecnicos'];

$query = "SELECT * FROM tecnicos WHERE id_tecnicos = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_usuario = $_POST['id_usuario'];

    $query = "UPDATE tecnicos SET id_usuario = ? WHERE id_tecnicos = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'ii', $id_usuario, $id);

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
    <h1>Editar Técnico</h1>
    <form method="POST">
        <div class="form-group">
            <label>ID Usuario</label>
            <input type="number" name="id_usuario" class="form-control" value="<?php echo htmlspecialchars($row['id_usuario']); ?>" required>
        </div>
        <button type="submit" class="btn btn-success mt-3">Actualizar</button>
        <a href="index.php" class="btn btn-secondary mt-3">Volver</a>
    </form>
</div>

<?php include('../../includes/footer.php'); ?>
