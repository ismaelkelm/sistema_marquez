<?php
header('Content-Type: application/json'); // Set the content type to JSON

include('db.php'); // Include database connection file

// Check if 'query' parameter is passed via GET
if (isset($_GET['query'])) {
    $name = $conn->real_escape_string($_GET['query']); // Escape special characters to avoid SQL injection

    // SQL query to get accessory information
    $sql = "SELECT * FROM accesorios_y_componentes WHERE nombre LIKE '%$name%'"; // Search by name
    $result = $conn->query($sql); // Execute query

    if ($result && $result->num_rows > 0) {
        $rows = []; // Initialize an array to hold results

        // Fetch all result rows into the array
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row; // Add each row to the array
        }
        
        echo json_encode($rows); // Convert array to JSON and print
    } else {
        // If no accessory found, return error message
        echo json_encode(['error' => 'Accesorio no encontrado.']);
    }
} else {
    // If 'query' parameter not specified, return error message
    echo json_encode(['error' => 'Parámetro "query" no especificado.']);
}

$conn->close(); // Close database connection
?>
