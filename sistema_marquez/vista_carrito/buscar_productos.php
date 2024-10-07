<?php
// Configuración de la conexión a la base de datos
$servername = "localhost"; // Cambia esto si es necesario
$username = "root";   // Cambia esto por tu usuario
$password = ""; // Cambia esto por tu contraseña
$dbname = "pruebas_marquez2"; // Cambia esto por tu base de datos

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Comprobar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener la consulta de búsqueda
$query = isset($_GET['query']) ? $_GET['query'] : '';

// Preparar la consulta SQL
$sql = "SELECT * FROM productos WHERE nombre LIKE ? OR descripcion LIKE ? OR categoria LIKE ?";
$stmt = $conn->prepare($sql);
$searchTerm = '%' . $query . '%';
$stmt->bind_param('sss', $searchTerm, $searchTerm, $searchTerm);

// Ejecutar la consulta
$stmt->execute();
$result = $stmt->get_result();

// Crear un array para almacenar los resultados
$productos = [];
while ($row = $result->fetch_assoc()) {
    $productos[] = $row;
}

// Cerrar la conexión
$stmt->close();
$conn->close();

// Devolver resultados como JSON
header('Content-Type: application/json');
echo json_encode($productos);
?>
