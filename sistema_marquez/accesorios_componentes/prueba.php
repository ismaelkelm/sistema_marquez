<?php
include '../../base_datos/db.php'; // Conexión a la base de datos

// Validación de que los datos requeridos estén presentes
if (isset($_POST['nombre'], $_POST['descripcion'], $_POST['stock'], $_POST['precio'], $_POST['tipo'], $_POST['stockmin'])) {
    // Escapar los datos para evitar inyección SQL
    $nombre = $conn->real_escape_string($_POST['nombre']);
    $descripcion = $conn->real_escape_string($_POST['descripcion']);
    $stock = (int) $_POST['stock'];
    $precio = (float) $_POST['precio'];
    $tipo = $conn->real_escape_string($_POST['tipo']);
    $stockmin = (int) $_POST['stockmin'];

    // Insertar el nuevo accesorio en la base de datos
    $sql = "INSERT INTO accesorios_y_componentes (nombre, descripcion, stock, precio, tipo, stockmin)
            VALUES ('$nombre', '$descripcion', '$stock', '$precio', '$tipo', '$stockmin')";

    // Ejecutar la consulta y verificar si fue exitosa
    if ($conn->query($sql) === TRUE) {
        echo "Accesorio registrado exitosamente.";
    } else {
        echo "Error al registrar el accesorio: " . $conn->error;
    }
} else {
    echo "Por favor, complete todos los campos.";
}

$conn->close();
?>
