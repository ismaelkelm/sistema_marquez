<?php
// Incluir el archivo de conexión a la base de datos
include '../../base_datos/db.php'; // Ajusta la ruta según la ubicación del archivo

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $numero_serie = $_POST['numero_serie'];
    $marca = $_POST['marca'];
    $modelo = $_POST['modelo'];

    // Consulta para insertar dispositivo
    $sql = "INSERT INTO dispositivos (numero_serie, marca, modelo) VALUES ('$numero_serie', '$marca', '$modelo')";
    if (mysqli_query($conn, $sql)) {
        // Obtener el ID del dispositivo insertado
        $id_dispositivo = mysqli_insert_id($conn);
        echo $id_dispositivo; // Devolver el ID del nuevo dispositivo
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
    
    mysqli_close($conn);
}
?>
