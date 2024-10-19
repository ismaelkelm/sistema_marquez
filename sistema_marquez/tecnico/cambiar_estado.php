<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include '../base_datos/db.php';

    $numero_orden = $_POST['numero_orden'];
    $estado = $_POST['estado'];

    // Consulta para actualizar el estado de la reparación
    $sql = "UPDATE pedidos_de_reparacion 
            SET estado_reparacion = ? 
            WHERE numero_orden = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $estado, $numero_orden);

    if ($stmt->execute()) {
        echo "Estado actualizado";
    } else {
        echo "Error al actualizar el estado";
    }

    $stmt->close();
    $conn->close();
}
?>