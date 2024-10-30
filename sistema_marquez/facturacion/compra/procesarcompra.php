<?php
include '../../base_datos/db.php'; // Conexión a la base de datos

// Capturar los datos del formulario
$proveedor_id = $_POST['proveedor'];
$tipo_pago_id = $_POST['tipo_pago'];
$tipo_comprobante_id = $_POST['tipo_comprobante'];
$producto_id = $_POST['producto'];
$cantidad_comprada = $_POST['cantidad'];
$num_comprobante = $_POST['num_comprobante'];
$fecha_de_compra = date('Y-m-d'); // Fecha actual

// Actualizar el stock del producto
$update_stock_sql = "UPDATE accesorios_y_componentes 
                     SET stock = stock + ? 
                     WHERE id_accesorios_y_componentes = ?";
$stmt = $conn->prepare($update_stock_sql);
$stmt->bind_param("ii", $cantidad_comprada, $producto_id);
$stmt->execute();

// Insertar los datos de la compra en la tabla comprobante_proveedores
$insert_comprobante_sql = "INSERT INTO comprobante_proveedores 
                            (fecha_de_compra, cantidad_comprada, num_de_comprobante, id_accesorios_y_componentes, id_proveedores)
                            VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($insert_comprobante_sql);
$stmt->bind_param("sisii", $fecha_de_compra, $cantidad_comprada, $num_comprobante, $producto_id, $proveedor_id);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo "Compra registrada con éxito.";
} else {
    echo "Error al registrar la compra.";
}

$stmt->close();
$conn->close();
?>
