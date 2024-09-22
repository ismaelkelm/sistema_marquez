<?php
include '../../base_datos/db.php'; // Incluye el archivo de conexión

$id = $_GET['id'];

$query = "SELECT * FROM cabecera_factura WHERE id_cabecera_factura = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fecha_factura = $_POST['fecha_factura'];
    $subtotal_factura = $_POST['subtotal_factura'];
    $impuestos = $_POST['impuestos'];
    $total_factura = $_POST['total_factura'];
    $id_clientes = $_POST['id_clientes'];
    $id_usuario = $_POST['id_usuario'];
    $id_operacion = $_POST['id_operacion'];
    $id_tipo_comprobante = $_POST['id_tipo_comprobante'];
    $id_tipo_de_pago = $_POST['id_tipo_de_pago'];
    $id_pedido_reparacion = $_POST['id_pedido_reparacion'];

    $query = "UPDATE cabecera_factura SET fecha_factura = ?, subtotal_factura = ?, impuestos = ?, total_factura = ?, id_clientes = ?, id_usuario = ?, id_operacion = ?, id_tipo_comprobante = ?, id_tipo_de_pago = ?, id_pedido_reparacion = ? WHERE id_cabecera_factura = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'sdddiiiiiii', $fecha_factura, $subtotal_factura, $impuestos, $total_factura, $id_clientes, $id_usuario, $id_operacion, $id_tipo_comprobante, $id_tipo_de_pago, $id_pedido_reparacion, $id);

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
    <h1>Editar Factura</h1>
    <form method="POST">
        <div class="form-group">
            <label>Fecha</label>
            <input type="date" name="fecha_factura" class="form-control" value="<?php echo htmlspecialchars($row['fecha_factura']); ?>" required>
        </div>
        <div class="form-group">
            <label>Subtotal</label>
            <input type="number" name="subtotal_factura" class="form-control" value="<?php echo htmlspecialchars($row['subtotal_factura']); ?>" required>
        </div>
        <div class="form-group">
            <label>Impuestos</label>
            <input type="number" name="impuestos" class="form-control" value="<?php echo htmlspecialchars($row['impuestos']); ?>" required>
        </div>
        <div class="form-group">
            <label>Total</label>
            <input type="number" name="total_factura" class="form-control" value="<?php echo htmlspecialchars($row['total_factura']); ?>" required>
        </div>
        <div class="form-group">
            <label>ID Cliente</label>
            <input type="number" name="id_clientes" class="form-control" value="<?php echo htmlspecialchars($row['id_clientes']); ?>" required>
        </div>
        <div class="form-group">
            <label>ID Usuario</label>
            <input type="number" name="id_usuario" class="form-control" value="<?php echo htmlspecialchars($row['id_usuario']); ?>" required>
        </div>
        <div class="form-group">
            <label>ID Operación</label>
            <input type="number" name="id_operacion" class="form-control" value="<?php echo htmlspecialchars($row['id_operacion']); ?>" required>
        </div>
        <div class="form-group">
            <label>ID Tipo Comprobante</label>
            <input type="number" name="id_tipo_comprobante" class="form-control" value="<?php echo htmlspecialchars($row['id_tipo_comprobante']); ?>" required>
        </div>
        <div class="form-group">
            <label>ID Tipo de Pago</label>
            <input type="number" name="id_tipo_de_pago" class="form-control" value="<?php echo htmlspecialchars($row['id_tipo_de_pago']); ?>" required>
        </div>
        <div class="form-group">
            <label>ID Pedido de Reparación</label>
            <input type="number" name="id_pedido_reparacion" class="form-control" value="<?php echo htmlspecialchars($row['id_pedido_reparacion']); ?>">
        </div>
        <button type="submit" class="btn btn-success">Guardar Cambios</button>
        <a href="index.php" class="btn btn-secondary">Volver Atrás</a>
    </form>
</div>

<?php include('../../includes/footer.php'); ?>

<?php
// Cerrar la conexión a la base de datos
mysqli_close($conn);
?>
