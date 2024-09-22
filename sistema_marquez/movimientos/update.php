<?php
// Incluir el archivo de conexión a la base de datos
include '../../base_datos/db.php'; // Ajusta la ruta según la ubicación del archivo

// Procesar el formulario cuando se envía
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario y proteger contra inyecciones SQL
    $id_movimiento = mysqli_real_escape_string($conn, $_POST['id_movimiento']);
    $tipo_movimiento = mysqli_real_escape_string($conn, $_POST['tipo_movimiento']);
    $monto = mysqli_real_escape_string($conn, $_POST['monto']);
    $fecha = mysqli_real_escape_string($conn, $_POST['fecha']);
    $descripcion = mysqli_real_escape_string($conn, $_POST['descripcion']);
    $id_recibo = mysqli_real_escape_string($conn, $_POST['id_recibo']);
    $id_ticket = mysqli_real_escape_string($conn, $_POST['id_ticket']);

    // Preparar la consulta SQL para actualizar el movimiento
    $query = "UPDATE movimientos 
              SET tipo_movimiento = '$tipo_movimiento', monto = '$monto', fecha = '$fecha', 
                  descripcion = '$descripcion', id_recibo = '$id_recibo', id_ticket = '$id_ticket'
              WHERE id_movimiento = '$id_movimiento'";

    // Ejecutar la consulta y verificar si fue exitosa
    if (mysqli_query($conn, $query)) {
        header("Location: index.php"); // Redirigir a la página principal de la lista
        exit();
    } else {
        echo "Error: " . mysqli_error($conn); // Mostrar mensaje de error
    }
}
?>
