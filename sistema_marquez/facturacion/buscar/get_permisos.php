<?php
header('Content-Type: application/json'); // Indica que la respuesta será en formato JSON

include('db.php'); // Incluye el archivo de conexión a la base de datos

// Verifica si se ha pasado el parámetro 'descripcion' por GET
if (isset($_GET['descripcion'])) {
    $descripcion = $_GET['descripcion'] . '%'; // Se añade un comodín para búsqueda con autocompletado

    // Prepara la consulta SQL para buscar permisos por descripción
    $stmt = $conn->prepare("SELECT id_permisos, descripcion FROM permisos WHERE descripcion LIKE ?");
    $stmt->bind_param("s", $descripcion); // Vincula el parámetro
    $stmt->execute(); // Ejecuta la consulta
    $result = $stmt->get_result(); // Obtiene los resultados de la consulta

    if ($result->num_rows > 0) {
        $permisos = []; // Array para almacenar los permisos encontrados

        // Itera sobre los resultados y los almacena en el array
        while ($row = $result->fetch_assoc()) {
            $permisos[] = $row;
        }

        // Devuelve los permisos en formato JSON
        echo json_encode($permisos);
    } else {
        // Si no se encuentran permisos, devuelve un mensaje de error
        echo json_encode([]);
    }

    $stmt->close(); // Cierra la consulta preparada
} else {
    // Si no se ha pasado el parámetro 'descripcion', devuelve un mensaje de error
    echo json_encode([]);
}

$conn->close(); // Cierra la conexión a la base de datos
?>
