<?php
include '../../base_datos/db.php'; // Incluye el archivo de conexión

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

    $query = "INSERT INTO cabecera_factura (fecha_factura, subtotal_factura, impuestos, total_factura, id_clientes, id_usuario, id_operacion, id_tipo_comprobante, id_tipo_de_pago, id_pedido_reparacion) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'sdddiiiiii', $fecha_factura, $subtotal_factura, $impuestos, $total_factura, $id_clientes, $id_usuario, $id_operacion, $id_tipo_comprobante, $id_tipo_de_pago, $id_pedido_reparacion);

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
    <h1>Agregar Factura</h1>
    <form method="POST">
        <div class="form-group">
            <label>Fecha</label>
            <input type="date" name="fecha_factura" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Subtotal</label>
            <input type="number" name="subtotal_factura" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Impuestos</label>
            <input type="number" name="impuestos" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Total</label>
            <input type="number" name="total_factura" class="form-control" required>
        </div>
        <div class="form-group">
            <label>ID Cliente</label>
            <input type="number" name="id_clientes" class="form-control" required>
        </div>
        <div class="form-group">
            <label>ID Usuario</label>
            <input type="number" name="id_usuario" class="form-control" required>
        </div>
        <div class="form-group">
            <label>ID Operación</label>
            <input type="number" name="id_operacion" class="form-control" required>
        </div>
        <div class="form-group">
            <label>ID Tipo Comprobante</label>
            <input type="number" name="id_tipo_comprobante" class="form-control" required>
        </div>
        <div class="form-group">
            <label>ID Tipo de Pago</label>
            <input type="number" name="id_tipo_de_pago" class="form-control" required>
        </div>
        <div class="form-group">
            <label>ID Pedido de Reparación</label>
            <input type="number" name="id_pedido_reparacion" class="form-control">
        </div>
        <button type="submit" class="btn btn-success">Guardar</button>
        <a href="index.php" class="btn btn-secondary">Volver Atrás</a>
    </form>
</div>

<?php include('../../includes/footer.php'); ?>
