<?php
// Conexión a la base de datos
include '../../base_datos/db.php';

// Obtener el último número de serie sugerido
$sql = "SELECT MAX(numero_de_seguimiento) AS ultimo_numero FROM sugerencias_id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $ultimo_numero = $row["ultimo_numero"];
    $nuevo_numero = $ultimo_numero + 1; // Incrementar el número de serie
} else {
    $nuevo_numero = 1; // Si no hay registros, iniciar en 1
}

// Insertar un nuevo registro en la tabla (opcional, si quieres mantener un historial)
$sql = "INSERT INTO sugerencias_id (numero_de_seguimiento) VALUES ('$nuevo_numero')";
if ($conn->query($sql) === TRUE) {
    echo json_encode(['numero_de_seguimiento' => $nuevo_numero]);
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>