<?php
include '../../base_datos/db.php';
// Verificar si el usuario ha iniciado sesión y obtener el id desde la sesión
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || !in_array($_SESSION['role'], [1, 2])) {
    header("Location: ../login/login.php");
    exit;
}
$subtotal = 0;
$factura = 0;
// Obtener el id_usuario desde la sesión
$id_usuario = $_SESSION['user_id'];

$query_tipo_pago = "SELECT id_tipo_de_pago, descripcion_de_pago FROM tipo_de_pago";
$result_tipo_pago = mysqli_query($conn, $query_tipo_pago);

$query_tipo_comprobante = "SELECT id_tipo_comprobante, tipo_comprobante FROM tipo_comprobante";
$result_tipo_comprobante = mysqli_query($conn, $query_tipo_comprobante);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {


    // Obtener el número de orden ingresado por el usuario
    $numero_orden = $conn->real_escape_string($_POST['numero_orden']);

    // Consultar para obtener el ID del pedido de reparación
    $sql_pedido = "SELECT id_pedidos_de_reparacion 
                   FROM pedidos_de_reparacion 
                   WHERE numero_orden = '$numero_orden' LIMIT 1";
    $resultado_pedido = $conn->query($sql_pedido);

    if ($resultado_pedido->num_rows > 0) {
        // Obtener el ID del pedido de reparación
        $pedido = $resultado_pedido->fetch_assoc();
        $id_pedido_reparacion = $pedido['id_pedidos_de_reparacion'];

        // Consultar la factura que corresponde al pedido y que tiene tipo_comprobante = 5
        $sql_factura = "SELECT * FROM cabecera_factura 
                        WHERE id_pedido_reparacion = '$id_pedido_reparacion' 
                        AND id_tipo_comprobante = 5 LIMIT 1";
        $resultado_factura = $conn->query($sql_factura);

        if ($resultado_factura->num_rows > 0) {
            // Mostrar los datos de la factura
            $factura = $resultado_factura->fetch_assoc();
            echo "<h3>Factura</h3>";
            echo "Fecha de Factura: " . $factura['fecha_factura'] . "<br>";
            // echo "Subtotal: " . $factura['subtotal_factura'] . "<br>";
            // echo "Impuestos: " . $factura['impuestos'] . "<br>";
            // echo "Total: " . $factura['total_factura'] . "<br>";

            // Inicializamos el subtotal acumulado
            // Consultar los detalles de la factura
            $id_cabecera_factura = $factura['id_cabecera_factura'];
            $sql_detalle = "SELECT * FROM detalle_factura 
                            WHERE id_cabecera_factura = '$id_cabecera_factura'";
            $resultado_detalle = $conn->query($sql_detalle);

            if ($resultado_detalle->num_rows > 0) {
                echo "<h3>Detalles de la Factura</h3>";
                while ($detalle = $resultado_detalle->fetch_assoc()) {
                    $cantidad = $detalle['cantidad_venta'];
                    $precio_unitario = $detalle['precio_unitario_V'];
                    $total_detalle = $cantidad * $precio_unitario; // Calcular el total de cada detalle

                    // Acumular el total en el subtotal
                    $subtotal += $total_detalle;

                    // Mostrar detalles
                    echo "Cantidad Vendida: " . $cantidad . "<br>";
                    echo "Precio Unitario: " . $precio_unitario . "<br>";
                    echo "ID de Accesorio/Componente: " . $detalle['id_accesorios_y_componentes'] . "<br>";
                    echo "ID de Servicio: " . $detalle['id_servicio'] . "<br><hr>";
                }
                // Mostrar el subtotal acumulado
                echo "<h3>Subtotal: $" . $subtotal . "</h3>";
            } else {
                echo "No hay detalles para esta factura.";
            }
        } else {
            echo "No se encontró ninguna factura para este pedido con tipo de comprobante 5.";
        }
    } else {
        echo "No se encontró ningún pedido con ese número de orden.";
    }

    // Cerrar la conexión
    $conn->close();
}
?>
<?php
// El código PHP que tienes ya está correcto, agregaremos el campo oculto para el subtotal
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FACTURAR REPARACION</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Asegúrate de tener jQuery cargado -->
    <script src="reparacion.js"></script> <!-- Aquí va el archivo JS donde harás el cálculo del IVA -->
</head>
<body>

<form method="POST" action="">
    <label for="numero_orden">Número de Orden:</label>
    <input type="text" name="numero_orden" id="numero_orden" required>
    <input type="submit" value="Buscar Factura">
</form>
<form method="POST" action="../modificar_Factura.php">
<!-- Secciones para el tipo de comprobante y pago -->
<div class="form-group">
    <label for="id_tipo_comprobante">Tipo de Comprobante:</label>
    <select class="form-control" id="id_tipo_comprobante" name="id_tipo_comprobante" required>
        <?php while ($row_comprobante = mysqli_fetch_assoc($result_tipo_comprobante)) { ?>
            <option value="<?php echo $row_comprobante['id_tipo_comprobante']; ?>">
                <?php echo $row_comprobante['tipo_comprobante']; ?>
            </option>
        <?php } ?>
    </select>
</div>

<div class="form-group">
    <label for="id_tipo_de_pago">Tipo de Pago:</label>
    <select class="form-control" id="id_tipo_de_pago" name="id_tipo_de_pago" required>
        <?php while ($row_pago = mysqli_fetch_assoc($result_tipo_pago)) { ?>
            <option value="<?php echo $row_pago['id_tipo_de_pago']; ?>">
                <?php echo $row_pago['descripcion_de_pago']; ?>
            </option>
        <?php } ?>
    </select>
</div>

<!-- Campos ocultos para los cálculos -->
<input type="hidden" id="subtotal" name="subtotal" value="<?php echo $subtotal; ?>">
<input type="hidden" id="id_usuario" name="id_usuario" value="<?php echo $id_usuario; ?>">

<input type="hidden" id="id_cabecera_factura" name="id_cabecera_factura" value="<?php echo $factura['id_cabecera_factura']; ?>">

<!-- Resultado del IVA -->
<div class="form-group" style="display: none;" id="iva_resultados">
    <label for="iva_resultado" id="iva_label">IVA (21%):</label>
    <input type="text" id="iva_resultado" name="iva_resultado" value="" readonly>
</div>

<!-- Resultado del total -->
<div class="form-group" style="display: none;" id="total_resultados">
    <label for="total" id="total_label">Total:</label>
    <input type="text" id="total" name="total" value="" readonly>
</div>

<!-- Botón Modificar -->
<input type="submit" name="modificar" value="Modificar">
</form>
<script>
    $(document).ready(function () {
        // Escuchar cambios en el select del tipo de comprobante
        $('#id_tipo_comprobante').change(function () {
            var tipoComprobante = $(this).val();
            var subtotal = parseFloat($('#subtotal').val());
            var iva = 0;
            var total = subtotal;

            if (tipoComprobante == 2) { // Si es "Factura A"
                iva = subtotal * 0.21;
                total = subtotal + iva;

                $('#iva_resultados').show();
                $('#iva_resultado').val(iva.toFixed(2));
                $('#total_resultados').show();
                $('#total').val(total.toFixed(2));
                $('#resultado').html("");
            } else {
                $('#iva_resultados').hide();
                $('#total_resultados').hide();
                $('#resultado').html("<p>Este tipo de comprobante no requiere IVA.</p>");
            }
        });
    });
</script>

</body>
</html>