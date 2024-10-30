<?php
include '../../base_datos/db.php'; // Conexión a la base de datos

// Consultas para obtener las opciones de proveedores, tipo de pago, tipo de comprobante, y accesorios
$proveedores = $conn->query("SELECT id_proveedores, nombre FROM proveedores");
$tipos_pago = $conn->query("SELECT id_tipo_de_pago, descripcion_de_pago FROM tipo_de_pago");
$tipos_comprobante = $conn->query("SELECT id_tipo_comprobante, tipo_comprobante FROM tipo_comprobante");
$accesorios = $conn->query("SELECT id_accesorios_y_componentes, nombre, stock FROM accesorios_y_componentes");

?>

<h2>Registrar Compra</h2>
<form action="../../facturacion/compra/procesarcompra.php" method="POST">
    <label for="proveedor">Proveedor:</label>
    <select name="proveedor" required>
        <?php while ($row = $proveedores->fetch_assoc()) {
            echo "<option value='{$row['id_proveedores']}'>{$row['nombre']}</option>";
        } ?>
    </select><br>

    <label for="tipo_pago">Tipo de Pago:</label>
    <select name="tipo_pago" required>
        <?php while ($row = $tipos_pago->fetch_assoc()) {
            echo "<option value='{$row['id_tipo_de_pago']}'>{$row['descripcion_de_pago']}</option>";
        } ?>
    </select><br>

    <label for="tipo_comprobante">Tipo de Comprobante:</label>
    <select name="tipo_comprobante" required>
        <?php while ($row = $tipos_comprobante->fetch_assoc()) {
            echo "<option value='{$row['id_tipo_comprobante']}'>{$row['tipo_comprobante']}</option>";
        } ?>
    </select><br>

    <label for="producto">Producto:</label>
    <select name="producto" required>
        <?php while ($row = $accesorios->fetch_assoc()) {
            echo "<option value='{$row['id_accesorios_y_componentes']}'>{$row['nombre']} (Stock actual: {$row['stock']})</option>";
        } ?>
    </select><br>

    <label for="cantidad">Cantidad Comprada:</label>
    <input type="number" name="cantidad" min="1" required><br>

    <label for="num_comprobante">Número de Comprobante:</label>
    <input type="text" name="num_comprobante" required><br>

    <button type="submit">Registrar Compra</button>
</form>

<?php $conn->close(); ?>
