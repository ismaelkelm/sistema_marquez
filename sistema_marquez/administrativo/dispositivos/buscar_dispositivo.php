<?php
// Conexión a la base de datos
include '../../base_datos/db.php'; 

// Consulta para obtener los dispositivos
$sql = "SELECT id_dispositivos, numero_de_serie, marca FROM dispositivos ORDER BY fecha_de_carga DESC";
$result = $conn->query($sql);

// Crear un array para almacenar los dispositivos
$dispositivos = [];

if ($result->num_rows > 0) {
    // Guardar cada dispositivo en el array
    while($row = $result->fetch_assoc()) {
        $dispositivos[] = $row;
    }
}

// Devolver los dispositivos como JSON
echo json_encode($dispositivos);

$conexion->close();
?>
