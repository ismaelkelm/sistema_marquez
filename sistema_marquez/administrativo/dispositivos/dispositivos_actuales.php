<?php
// Incluir el archivo de conexión a la base de datos
include '../../base_datos/db.php';
// Establecer la zona horaria
date_default_timezone_set('America/Argentina/Buenos_Aires'); // Cambia esto a tu zona horaria

// Obtener la fecha y hora actual
$fecha_actual = date('Y-m-d H:i:s');

// Consulta para obtener los dispositivos registrados en los últimos 10 minutos
$query = "SELECT * FROM dispositivos 
          WHERE fecha_de_carga >= DATE_SUB('$fecha_actual', INTERVAL 10 MINUTE) 
          AND fecha_de_carga <= '$fecha_actual'
          ORDER BY id_dispositivos DESC 
          LIMIT 10"; // Cambia el límite según necesites

$resultado = mysqli_query($conn, $query);

// Verifica si hubo error en la consulta
if (!$resultado) {
    die('Error en la consulta: ' . mysqli_error($conn));
}

$dispositivos = [];
if (mysqli_num_rows($resultado) > 0) {
    while ($fila = mysqli_fetch_assoc($resultado)) {
        $dispositivos[] = $fila;
    }
}

// Devolver la respuesta en formato JSON
header('Content-Type: application/json');
echo json_encode($dispositivos);
?>
