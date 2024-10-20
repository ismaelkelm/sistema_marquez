<?php
include '../base_datos/db.php';

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
        echo "Factura modificada con éxito.";
    } else {
        echo "Error al modificar la factura: " . $conn->error;
    }

    // Cerrar la conexión
    $conn->close();
}
?>
