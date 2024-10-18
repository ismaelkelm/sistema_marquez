<?php
require_once '../base_datos/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_cabecera_factura = intval($_POST['id']);

    // Obtener el precio del accesorio
    $query = "SELECT id_cabecera_factura FROM cabecera_factura WHERE id_cabecera_factura = $id_cabecera_factura";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        echo json_encode(['status' => 'success', 'precio' => $row['precio']]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Accesorio no encontrado']);
    }
}

mysqli_close($conn);
?>