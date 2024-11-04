<?php
include '../../base_datos/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fecha_desde = $_POST['fecha_desde'];
    $fecha_hasta = $_POST['fecha_hasta'];

    // Consulta para obtener el total de dinero en compras de accesorios (DEBE)
    $query_accesorios = "
        SELECT SUM(total_pagado) AS total_accesorios
        FROM comprobante_proveedores
        WHERE fecha_de_compra BETWEEN ? AND ?
    ";
    $stmt_accesorios = $conn->prepare($query_accesorios);
    $stmt_accesorios->bind_param("ss", $fecha_desde, $fecha_hasta);
    $stmt_accesorios->execute();
    $result_accesorios = $stmt_accesorios->get_result();
    $total_accesorios = $result_accesorios->fetch_assoc()['total_accesorios'];

    // Consulta para obtener el total de dinero en facturas (HABER)
    $query_facturas = "
        SELECT SUM(total_factura) AS total_facturas
        FROM cabecera_factura
        WHERE fecha_factura BETWEEN ? AND ?
    ";
    $stmt_facturas = $conn->prepare($query_facturas);
    $stmt_facturas->bind_param("ss", $fecha_desde, $fecha_hasta);
    $stmt_facturas->execute();
    $result_facturas = $stmt_facturas->get_result();
    $total_facturas = $result_facturas->fetch_assoc()['total_facturas'];

    // Calcular la diferencia entre HABER y DEBE
    $diferencia = $total_facturas - $total_accesorios;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Movimiento de Caja</title>
    <link rel="stylesheet" href="caja.css">
</head>
<body>
<?php echo '<button onclick="location.href=\'compra.html\'" class="mt-3 button button-back">Volver</button>';?>
    <h2>Movimiento de caja en el periodo</h2>
    
    <form action="" method="post">
        <label for="fecha_desde">Desde:</label>
        <input type="date" id="fecha_desde" name="fecha_desde" required>
        
        <label for="fecha_hasta">Hasta:</label>
        <input type="date" id="fecha_hasta" name="fecha_hasta" required>
        
        <button type="submit">Consultar</button>
    </form>

    <?php if (isset($total_accesorios) && isset($total_facturas)): ?>
        <div class="reporte">
            <table>
                <tr>
                    <th>DEBE</th>
                    <th>HABER</th>
                </tr>
                <tr>
                    <td>$<?= number_format($total_accesorios, 2) ?></td>
                    <td>$<?= number_format($total_facturas, 2) ?></td>
                </tr>
                <tr>
                    <td colspan="2" class="diferencia">Diferencia: $<?= number_format($diferencia, 2) ?></td>
                </tr>
            </table>
        </div>
    <?php endif; ?>
</body>
</html>
