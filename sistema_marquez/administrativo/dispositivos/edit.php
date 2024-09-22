<?php
include '../../base_datos/db.php'; // Incluye el archivo de conexión

$id = $_GET['id'];

$query = "SELECT * FROM dispositivos WHERE id_dispositivos = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $marca = $_POST['marca'];
    $modelo = $_POST['modelo'];
    $numero_de_serie = $_POST['numero_de_serie'];

    $query = "UPDATE dispositivos SET marca = ?, modelo = ?, numero_de_serie = ? WHERE id_dispositivos = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'sssi', $marca, $modelo, $numero_de_serie, $id);

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
    <h1>Editar Dispositivo</h1>
    <form method="POST">
        <div class="form-group">
            <label>Marca</label>
            <input type="text" name="marca" class="form-control" value="<?php echo htmlspecialchars($row['marca']); ?>" required>
        </div>
        <div class="form-group">
            <label>Modelo</label>
            <input type="text" name="modelo" class="form-control" value="<?php echo htmlspecialchars($row['modelo']); ?>" required>
        </div>
        <div class="form-group">
            <label>Número de Serie</label>
            <input type="text" name="numero_de_serie" class="form-control" value="<?php echo htmlspecialchars($row['numero_de_serie']); ?>" required>
        </div>
        <button type="submit" class="btn btn-success">Guardar Cambios</button>
        <a href="index.php" class="btn btn-secondary">Volver Atrás</a>
    </form>
</div>

<?php include('../../includes/footer.php'); ?>
