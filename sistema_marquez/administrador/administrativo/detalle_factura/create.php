<?php
include '../../base_datos/db.php'; // Incluye el archivo de conexión

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cantidad_venta = $_POST['cantidad_venta'];
    $precio_unitario_V = $_POST['precio_unitario_V'];
    $id_accesorios_y_componentes = $_POST['id_accesorios_y_componentes'];
    $id_cabecera_factura = $_POST['id_cabecera_factura'];

    $query = "INSERT INTO detalle_factura (cantidad_venta, precio_unitario_V, id_accesorios_y_componentes, id_cabecera_factura) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'idis', $cantidad_venta, $precio_unitario_V, $id_accesorios_y_componentes, $id_cabecera_factura);

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
    <h1>Agregar Detalle de Factura</h1>
    <form method="POST">
        <div class="form-group">
            <label>Cantidad Vendida</label>
            <input type="number" name="cantidad_venta" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Precio Unitario</label>
            <input type="number" step="0.01" name="precio_unitario_V" class="form-control" required>
        </div>
        <div class="form-group">
            <label>ID Accesorio/Componente</label>
            <input type="number" name="id_accesorios_y_componentes" class="form-control" required>
        </div>
        <div class="form-group">
            <label>ID Cabecera de Factura</label>
            <input type="number" name="id_cabecera_factura" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">Guardar</button>
        <a href="index.php" class="btn btn-secondary">Volver Atrás</a>
    </form>
</div>

<?php include('../../includes/footer.php'); ?>
