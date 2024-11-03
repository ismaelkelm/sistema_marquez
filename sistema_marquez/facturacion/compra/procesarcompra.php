<?php
include '../../base_datos/db.php'; // Conexión a la base de datos

// Validación de que los datos requeridos estén presentes
if (
    isset($_POST['id_proveedores'], $_POST['id_tipo_pago'], $_POST['tipo_comprobante'], $_POST['id_accesorios_y_componentes'], 
    $_POST['fecha_de_compra'], $_POST['cantidad_comprada'], $_POST['num_de_comprobante'], $_POST['precio_por_unidad'], $_POST['total_pagado'])
) {
    // Escapar los datos para evitar inyección SQL
    $id_proveedores = $conn->real_escape_string($_POST['id_proveedores']);
    $id_tipo_pago = $conn->real_escape_string($_POST['id_tipo_pago']);
    $tipo_comprobante = $conn->real_escape_string($_POST['tipo_comprobante']);
    $id_accesorios_y_componentes = $conn->real_escape_string($_POST['id_accesorios_y_componentes']);
    $fecha_de_compra = $conn->real_escape_string($_POST['fecha_de_compra']);
    $cantidad_comprada = $conn->real_escape_string($_POST['cantidad_comprada']);
    $num_de_comprobante = $conn->real_escape_string($_POST['num_de_comprobante']);
    $precio_por_unidad = $conn->real_escape_string($_POST['precio_por_unidad']);
    $total_pagado = $conn->real_escape_string($_POST['total_pagado']);
    
    // Calcular el precio de reventa
    $precio_reventa = $precio_por_unidad * 1.5;

    // Actualizar el stock y el precio en accesorios_y_componentes
    $update_stock_sql = "UPDATE accesorios_y_componentes 
                         SET stock = stock + ?, precio = ? 
                         WHERE id_accesorios_y_componentes = ?";
    $stmt = $conn->prepare($update_stock_sql);
    $stmt->bind_param("idi", $cantidad_comprada, $precio_reventa, $id_accesorios_y_componentes);
    $stmt->execute();

    // Verificar si la actualización de stock fue exitosa
    if ($stmt->affected_rows > 0) {
        // Preparar la consulta de inserción para comprobante_proveedores
        $sql = "INSERT INTO comprobante_proveedores (id_proveedores, id_tipo_pago, id_tipo_comprobante, id_accesorios_y_componentes, 
                fecha_de_compra, cantidad_comprada, num_de_comprobante, precio_por_unidad, total_pagado)
                VALUES ('$id_proveedores', '$id_tipo_pago', '$tipo_comprobante', '$id_accesorios_y_componentes', 
                        '$fecha_de_compra', '$cantidad_comprada', '$num_de_comprobante', '$precio_por_unidad', '$total_pagado')";

        // Ejecutar la consulta de inserción
        if ($conn->query($sql) === TRUE) {
            echo "Compra registrada exitosamente.";
        } else {
            echo "Error al registrar la compra: " . $conn->error;
        }
    } else {
        echo "Error al actualizar el stock o el precio de reventa.";
    }

    $stmt->close();
} else {
    echo "Por favor, complete todos los campos.";
}

$conn->close();
?>
