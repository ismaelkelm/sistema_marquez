<?php
require_once '../base_datos/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = trim(mysqli_real_escape_string($conn, $_POST['nombre']));
    $apellido = trim(mysqli_real_escape_string($conn, $_POST['apellido']));
    $telefono = trim(mysqli_real_escape_string($conn, $_POST['telefono']));
    $correo = trim(mysqli_real_escape_string($conn, $_POST['correo']));
    $direccion = trim(mysqli_real_escape_string($conn, $_POST['direccion']));
    $dni =trim(mysqli_real_escape_string($conn, $_POST['dni']));

    // Insertar el cliente en la base de datos
    $query = "INSERT INTO clientes (nombre, apellido, telefono, correo_electronico, direccion, dni)
              VALUES ('$nombre', '$apellido', '$telefono', '$correo', '$direccion', '$dni')";

    if (mysqli_query($conn, $query)) {
        echo json_encode(['status' => 'success']);
    } else {
        $error_message = mysqli_error($conn);
        echo json_encode(['status' => 'error', 'message' => $error_message]);
    }
}

mysqli_close($conn);
