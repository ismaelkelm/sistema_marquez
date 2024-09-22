<?php
// Incluir el archivo de conexión a la base de datos
include '../../base_datos/db.php'; // Ajusta la ruta según la ubicación del archivo

// Procesar el formulario cuando se envía
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario y proteger contra inyecciones SQL
    $id_facturas = mysqli_real_escape_string($conn, $_POST['id_facturas']);
    $id_proveedores = mysqli_real_escape_string($conn, $_POST['id_proveedores']);
    $fecha_de_emision = mysqli_real_escape_string($conn, $_POST['fecha_de_emision']);
    $subtotal = mysqli_real_escape_string($conn, $_POST['subtotal']);
    $impuestos = mysqli_real_escape_string($conn, $_POST['impuestos']);
    $total = mysqli_real_escape_string($conn, $_POST['total']);

    // Preparar la consulta SQL para actualizar la factura
    $query = "UPDATE facturas 
              SET id_proveedores = '$id_proveedores', fecha_de_emision = '$fecha_de_emision', 
                  subtotal = '$subtotal', impuestos = '$impuestos', total = '$total' 
              WHERE id_facturas = '$id_facturas'";

    // Ejecutar la consulta y verificar si fue exitosa
    if (mysqli_query($conn, $query)) {
        header("Location: index.php"); // Redirigir a la página principal de la lista
        exit();
    } else {
        echo "Error: " . mysqli_error($conn); // Mostrar mensaje de error
    }
}
?>
