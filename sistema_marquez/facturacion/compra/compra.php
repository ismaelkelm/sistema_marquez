<?php
include '../../base_datos/db.php'; // Conexión a la base de datos

// Consultas para obtener las opciones de proveedores, tipo de pago, tipo de comprobante, y accesorios
$proveedores = $conn->query("SELECT id_proveedores, nombre FROM proveedores");
$tipos_pago = $conn->query("SELECT id_tipo_de_pago, descripcion_de_pago FROM tipo_de_pago");
$tipos_comprobante = $conn->query("SELECT id_tipo_comprobante, tipo_comprobante FROM tipo_comprobante");
$accesorios = $conn->query("SELECT id_accesorios_y_componentes, nombre, stock FROM accesorios_y_componentes");
?>

<h2>Registrar Compra</h2>
<a href="../../administrativo/accesorios_componentes/index.php" class="btn btn-secondary">Cargar nuevo accesorio??</a>

<form action="../../facturacion/compra/procesarcompra.php" method="POST">
    <label for="proveedor">Proveedor:</label>
    <select name="id_proveedores" required>
        <?php while ($row = $proveedores->fetch_assoc()) {
            echo "<option value='{$row['id_proveedores']}'>{$row['nombre']}</option>";
        } ?>
    </select><br>

    <label for="tipo_pago">Tipo de Pago:</label>
    <select name="id_tipo_pago" required>
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
    <select name="id_accesorios_y_componentes" required>
        <?php while ($row = $accesorios->fetch_assoc()) {
            echo "<option value='{$row['id_accesorios_y_componentes']}'>{$row['nombre']} (Stock actual: {$row['stock']})</option>";
        } ?>
    </select><br>

    <label for="fecha_de_compra">Fecha de Compra:</label>
    <input type="date" name="fecha_de_compra" required><br>

    <label for="cantidad_comprada">Cantidad Comprada:</label>
    <input type="number" name="cantidad_comprada" min="1" required><br>

    <label for="num_de_comprobante">Número de Comprobante:</label>
    <input type="text" name="num_de_comprobante" required><br>

    <label for="precio_por_unidad">Precio por Unidad:</label>
    <input type="number" name="precio_por_unidad" step="0.01" min="0" required><br>

    <label for="total_pagado">Total Pagado:</label>
    <input type="number" name="total_pagado" step="0.01" min="0" required><br>

    <button type="submit">Registrar Compra</button>
</form>

<?php $conn->close(); ?>
