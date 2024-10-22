<?php
include '../base_datos/db.php';

// Inicializar variable de mensaje
$mensaje = '';
$tipo_mensaje = ''; // 'success' o 'error'

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener los datos del formulario
    $id_cabecera_factura = $_POST['id_cabecera_factura'];
    $id_tipo_comprobante = $_POST['id_tipo_comprobante'];
    $id_tipo_de_pago = $_POST['id_tipo_de_pago'];
    $id_usuario = $_POST['id_usuario'];
    $subtotal = $_POST['subtotal'];
    $iva = isset($_POST['iva_resultado']) ? $_POST['iva_resultado'] : 0;
    $total = isset($_POST['total']) ? $_POST['total'] : $subtotal;
    $fecha_actual = date('Y-m-d'); // Fecha actual

    // Consulta para actualizar la cabecera de la factura
    $sql_update = "UPDATE cabecera_factura 
                   SET fecha_factura = '$fecha_actual', 
                       subtotal_factura = '$subtotal', 
                       impuestos = '$iva', 
                       total_factura = '$total', 
                       id_usuario = '$id_usuario',
                       id_tipo_comprobante = '$id_tipo_comprobante', 
                       id_tipo_de_pago = '$id_tipo_de_pago'
                   WHERE id_cabecera_factura = '$id_cabecera_factura'";

    if ($conn->query($sql_update) === TRUE) {
        $mensaje = "Factura modificada con éxito.";
        $tipo_mensaje = 'success'; // Mensaje de éxito
    } else {
        $mensaje = "Error al modificar la factura: " . $conn->error;
        $tipo_mensaje = 'error'; // Mennsaje de error
    }
    echo "<script>
            setTimeout(function() {
                window.location.href = './reparacion/reparacion.php';
            }, 1500); 
        </script>";
    // Cerrar la conexión
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Factura</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .mensaje {
            display: flex;
            align-items: center;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-weight: bold;
        }
        .success {
            background-color: #dff0d8;
            color: #3c763d;
        }
        .error {
            background-color: #f2dede;
            color: #a94442;
        }
        .icon {
            margin-right: 10px;
            font-size: 20px;
        }
    </style>
</head>
<body>

<?php if ($mensaje): ?>
    <div class="mensaje <?php echo $tipo_mensaje; ?>">
        <span class="icon">
            <?php echo $tipo_mensaje === 'success' ? '✔️' : '❌'; ?>
        </span>
        <?php echo $mensaje; ?>
    </div>
<?php endif; ?>

<!-- Aquí puedes agregar el resto de tu contenido o formulario -->

</body>
</html>
