<?php
require_once '../base_datos/db.php';

if (isset($_POST['dni'])) {
    $dni = $_POST['dni'];

    // Consulta para obteneer los datos del cliente basados en el DNI
    $query = "SELECT id_clientes, nombre, apellido FROM clientes WHERE dni = '$dni'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $cliente = mysqli_fetch_assoc($result);
        echo json_encode(['status' => 'exists', 'cliente' => $cliente]);
    } else {
        echo json_encode(['status' => 'not_found']);
    }
}
?>
