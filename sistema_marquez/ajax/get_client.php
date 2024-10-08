<?php
header('Content-Type: application/json'); // Indica que el contenido es JSON

include('db.php'); // Incluye el archivo de conexión a la base de datos

// Verifica si se ha pasado el parámetro 'query' por GET
if (isset($_GET['query'])) {
    $name = $conn->real_escape_string($_GET['query']); // Escapa caracteres especiales para evitar inyecciones SQL

    // Consulta SQL para obtener la información del cliente
    $sql = "SELECT * FROM clientes WHERE nombre LIKE '%$name%'"; // Busca por nombre
    $result = $conn->query($sql); // Ejecuta la consulta

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc(); // Obtiene la fila de resultados
        echo json_encode($row); // Convierte la fila en JSON y la imprime
    } else {
        // Si no se encuentra el cliente, devuelve un mensaje de error
        echo json_encode(['error' => 'Cliente no encontrado.']);
    }
} else {
    // Si no se ha pasado el parámetro 'query', devuelve un mensaje de error
    echo json_encode(['error' => 'Parámetro "query" no especificado.']);
}

$conn->close(); // Cierra la conexión a la base de datos
?>
