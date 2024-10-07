<?php
// Conexión a la base de datos
include '../../base_datos/db.php';

// Establecer la zona horaria
date_default_timezone_set('America/Argentina/Buenos_Aires');

header('Content-Type: application/json');

$marca = trim($_POST['marca']);
$modelo = trim($_POST['modelo']);
$numero_serie = trim($_POST['numero_serie']);
$fecha_actual = date('Y-m-d H:i:s');

// Verificar si el número de serie ya existe
$query_verificar = "SELECT id_dispositivos FROM dispositivos WHERE numero_de_serie = ?";
$stmt_verificar = $conn->prepare($query_verificar);
if ($stmt_verificar === false) {
    echo json_encode(['status' => 'error', 'message' => 'Error en la preparación de la consulta de verificación: ' . $conn->error]);
    exit;
}

$stmt_verificar->bind_param("s", $numero_serie);
$stmt_verificar->execute();
$stmt_verificar->store_result();

if ($stmt_verificar->num_rows > 0) {
    $stmt_verificar->close();
    $conn->close();
    echo json_encode(['status' => 'error', 'message' => 'El número de serie ya está registrado.']);
    exit;
}

$stmt_verificar->close();

// Insertar el nuevo dispositivo si el número de serie no existe
$query_insertar = "INSERT INTO dispositivos (marca, modelo, numero_de_serie, fecha_de_carga) VALUES (?, ?, ?, ?)";
$stmt_insertar = $conn->prepare($query_insertar);

if ($stmt_insertar === false) {
    echo json_encode(['status' => 'error', 'message' => 'Error en la preparación de la consulta: ' . $conn->error]);
    exit;
}

// Comprobamos si hay errores en la preparación del statement
if ($stmt_insertar->error) {
    echo json_encode(['status' => 'error', 'message' => 'Error en la preparación del statement: ' . $stmt_insertar->error]);
    exit;
}

$stmt_insertar->bind_param("ssss", $marca, $modelo, $numero_serie, $fecha_actual);

if ($stmt_insertar->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Dispositivo registrado con éxito.']);
} else {
    // Capturamos el error de ejecución
    echo json_encode(['status' => 'error', 'message' => 'Error al registrar el dispositivo: ' . $stmt_insertar->error]);
}

// Cerramos el statement
$stmt_insertar->close();
$conn->close();
?>
