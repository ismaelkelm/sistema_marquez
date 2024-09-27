<?php
require_once '../../base_datos/db.php';
include('../../includes/header.php');

// Verificar si el usuario ha iniciado sesión y obtener el id desde la sesión
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || !in_array($_SESSION['role'], [1, 2])) {
    header("Location: ../login/login.php");
    exit;
}

// Obtener el id_usuario desde la sesión
$id_usuario = $_SESSION['user_id'];
$id_operacion=1;
echo "usuario: ", $id_usuario;
// Obtener los datos para los selectores
$query_tipo_pago = "SELECT id_tipo_de_pago, descripcion_de_pago FROM tipo_de_pago";
$result_tipo_pago = mysqli_query($conn, $query_tipo_pago);

$query_tipo_comprobante = "SELECT id_tipo_comprobante, tipo_comprobante FROM tipo_comprobante";
$result_tipo_comprobante = mysqli_query($conn, $query_tipo_comprobante);

$query_accesorios_componentes = "SELECT id_accesorios_y_componentes, nombre, precio FROM accesorios_y_componentes";
$result_accesorios_componentes = mysqli_query($conn, $query_accesorios_componentes);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Factura</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="venta.js"></script>
</head>
<body>
    <h2>Cabecera de Factura</h2>
    <form id="form-factura" action="../cargaFactura.php" method="POST">
        <!-- Datos del cliente -->
        <label for="dni_cliente">DNI del Cliente:</label>
        <input type="number" id="dni_cliente" name="dni_cliente" required><br><br>

        <label for="nombre_cliente">Nombre del Cliente:</label>
        <input type="text" id="nombre_cliente" name="nombre_cliente" readonly><br><br>
        <!-- ID Cliente (oculto) -->
        <input type="hidden" id="id_clientes" name="id_clientes">
        <div id="mensaje_cliente" style="color:red;"></div>
         <!-- Formulario para levantar cliente si no existe -->
         <div id="form-levantar-cliente" style="display:none;">
            <h3>Nuevo Cliente</h3>
            <label for="nuevo_nombre">Nombre:</label>
            <input type="text" id="nuevo_nombre" name="nuevo_nombre"><br><br>

            <label for="nuevo_apellido">Apellido:</label>
            <input type="text" id="nuevo_apellido" name="nuevo_apellido"><br><br>

            <label for="nuevo_telefono">Teléfono:</label>
            <input type="text" id="nuevo_telefono" name="nuevo_telefono"><br><br>

            <label for="nuevo_correo">Correo Electrónico:</label>
            <input type="email" id="nuevo_correo" name="nuevo_correo"><br><br>

            <label for="nueva_direccion">Dirección:</label>
            <input type="text" id="nueva_direccion" name="nueva_direccion"><br><br>

            <button type="button" id="registrar_cliente">Registrar Cliente</button>
        </div>
          <!-- Fecha se genera automáticamente -->
          <label for="fecha_factura">Fecha de Factura:</label>
        <input type="text" id="fecha_factura" name="fecha_factura" value="<?php echo date('Y-m-d'); ?>" readonly><br><br>

        <!-- Seleccionar Tipo de Pago -->
        <label for="id_tipo_de_pago">Tipo de Pago:</label>
        <select id="id_tipo_de_pago" name="id_tipo_de_pago" required>
            <?php while ($row_pago = mysqli_fetch_assoc($result_tipo_pago)) { ?>
                <option value="<?php echo $row_pago['id_tipo_de_pago']; ?>">
                    <?php echo $row_pago['descripcion_de_pago']; ?>
                </option>
            <?php } ?>
        </select><br><br>
        <!-- ID Usuario tomado de la sesión -->
        <input type="hidden" id="id_usuario" name="id_usuario" value="<?php echo $id_usuario; ?>">
        <input type="hidden" id="id_operacion" name="id_operacion" value="<?php echo $id_operacion; ?>">

        <h2>Detalle de Factura</h2>
        <div id="detalles-accesorios">
            <div class="detalle-accesorio">
                <label for="cantidad_venta[]">Cantidad Vendida:</label>
                <input type="number" name="cantidad_venta[]" required><br><br>

                <label for="precio_unitario_V[]">Precio Unitario:</label>
                <input type="number" step="0.01" name="precio_unitario_V[]" required readonly><br><br>

                <label for="subtotal[]">Subtotal:</label>
                <input type="number" step="0.01" name="subtotal[]" readonly><br><br>

                <label for="id_accesorios_y_componentes[]">Accesorios o Componentes:</label>
                <select name="id_accesorios_y_componentes[]" required>
                    <?php while ($row_accesorio = mysqli_fetch_assoc($result_accesorios_componentes)) { ?>
                        <option value="<?php echo $row_accesorio['id_accesorios_y_componentes']; ?>">
                            <?php echo $row_accesorio['nombre']; ?>
                        </option>
                    <?php } ?>
                </select><br><br>
            </div>
        </div>
        <button type="button" id="add-accesorio">Agregar otro accesorio</button><br><br>
        <button type="submit" id="submit_button" disabled>Registrar Factura</button>
        <form id="ivaForm">
            <label for="subtotal_factura">Subtotal:</label>
            <input type="number" step="0.01" id="subtotal_factura" name="subtotal_factura" required readonly><br><br>
                    <!-- Seleccionar Tipo de Comprobante -->
                    <label for="id_tipo_comprobante">Tipo de Comprobante:</label>
            <select id="id_tipo_comprobante" name="id_tipo_comprobante" required>
                <?php while ($row_comprobante = mysqli_fetch_assoc($result_tipo_comprobante)) { ?>
                    <option value="<?php echo $row_comprobante['id_tipo_comprobante']; ?>">
                        <?php echo $row_comprobante['tipo_comprobante']; ?>
                    </option>
                <?php } ?>
            </select><br><br>
                    
            <div id="resultado" style="margin-top: 20px;"></div>

            <label for="iva_resultado" style="display: none;" id="iva_label">IVA (21%):</label>
            <input type="text" id="iva_resultado" name="iva_resultado" value="" readonly style="display: none;"><br><br>

            <label for="total" style="display: none;" id="total_label">Total: </label>
            <input type="text" id="total" name="total" value="" readonly style="display: none;"><br><br>
            
        </form>

    </form>
</body>
</html>

<?php
// Cerrar la conexión a la base de datos
mysqli_close($conn);
?>
