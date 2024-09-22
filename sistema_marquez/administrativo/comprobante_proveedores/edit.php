<?php
include '../../base_datos/db.php';

$id = $_GET['id'];

$query = "SELECT * FROM comprobante_proveedores WHERE id_comprobante_proveedores = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fecha_de_compra = $_POST['fecha_de_compra'];
    $cantidad_comprada = $_POST['cantidad_comprada'];
    $num_de_comprobante = $_POST['num_de_comprobante'];
    $id_accesorios_y_componentes = $_POST['id_accesorios_y_componentes'];
    $id_proveedores = $_POST['id_proveedores'];

    $query = "UPDATE comprobantes_proveedores SET fecha_de_compra = ?, cantidad_comprada = ?, num_de_comprobante = ?, id_accesorios_y_componentes = ?, id_proveedores = ? WHERE id_comprobante_proveedores = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'sisisi', $fecha_de_compra, $cantidad_comprada, $num_de_comprobante, $id_accesorios_y_componentes, $id_proveedores, $id);

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
    <h1>Editar Comprobante de Proveedor</h1>
    <form method="POST">
        <div class="form-group">
            <label>Fecha de Compra</label>
            <input type="date" name="fecha_de_compra" class="form-control" value="<?php echo htmlspecialchars($row['fecha_de_compra']); ?>" required>
        </div>
        <div class="form-group">
            <label>Cantidad Comprada</label>
            <input type="number" name="cantidad_comprada" class="form-control" value="<?php echo htmlspecialchars($row['cantidad_comprada']); ?>" required>
        </div>
        <div class="form-group">
            <label>Número de Comprobante</label>
            <input type="text" name="num_de_comprobante" class="form-control" value="<?php echo htmlspecialchars($row['num_de_comprobante']); ?>" required>
        </div>
        <div class="form-group">
            <label>ID Accesorio/Componente</label>
            <input type="number" name="id_accesorios_y_componentes" class="form-control" value="<?php echo htmlspecialchars($row['id_accesorios_y_componentes']); ?>" required>
        </div>
        <div class="form-group">
            <label>ID Proveedor</label>
            <input type="number" name="id_proveedores" class="form-control" value="<?php echo htmlspecialchars($row['id_proveedores']); ?>" required>
        </div>
        <button type="submit" class="btn btn-success">Guardar Cambios</button>
        <a href="index.php" class="btn btn-secondary">Volver Atrás</a>
    </form>
</div>

<?php include('../../includes/footer.php'); ?>
