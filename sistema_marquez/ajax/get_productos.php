<?php
header('Content-Type: application/json'); // Indica que el contenido es JSON

include('db.php'); // Incluye el archivo de conexión a la base de datos

// Verifica si se ha pasado el parámetro 'query' por GET
if (isset($_GET['query'])) {
    $query = $_GET['query']; // Captura el parámetro 'query'

    // Consulta SQL para obtener la información del producto
    $sql = "SELECT nombre, descripcion, categoria, precio FROM productos WHERE nombre LIKE ? OR descripcion LIKE ? OR categoria LIKE ?"; // Solo selecciona los campos necesarios
    $stmt = $conn->prepare($sql); // Prepara la declaración

    // Verifica si la preparación fue exitosa
    if ($stmt) {
        $searchTerm = '%' . $query . '%'; // Crea el término de búsqueda
        $stmt->bind_param('sss', $searchTerm, $searchTerm, $searchTerm); // Vincula los parámetros

        // Ejecutar la consulta
        $stmt->execute();
        $result = $stmt->get_result();

        // Crear un array para almacenar los resultados
        $productos = [];
        while ($row = $result->fetch_assoc()) {
            $productos[] = $row; // Agrega cada producto al array
        }

        // Cerrar la declaración
        $stmt->close();
        
        // Devolver resultados como JSON
        echo json_encode($productos);
    } else {
        // Si no se puede preparar la consulta, devuelve un mensaje de error
        echo json_encode(['error' => 'Error al preparar la consulta.']);
    }
} else {
    // Si no se ha pasado el parámetro 'query', devuelve un mensaje de error
    echo json_encode(['error' => 'Parámetro "query" no especificado.']);
}

$conn->close(); // Cierra la conexión a la base de datos
?>
