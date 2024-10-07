<?php
header('Content-Type: application/json'); // Set the content type to JSON

include('db.php'); // Include database connection file

// Check if 'query' parameter is passed via GET
if (isset($_GET['query'])) {
    $name = $conn->real_escape_string($_GET['query']); // Escape special characters to avoid SQL injection

    // SQL query to get device information
    $sql = "SELECT * FROM dispositivos WHERE marca LIKE '%$name%' OR modelo LIKE '%$name%'"; // Search by name or model
    $result = $conn->query($sql); // Execute query

    if ($result && $result->num_rows > 0) {
        $rows = []; // Initialize an array to hold results

        // Fetch all result rows into the array
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row; // Add each row to the array
        }
        
        echo json_encode($rows); // Convert array to JSON and print
    } else {
        // If no device found, return error message
        echo json_encode(['error' => 'Dispositivo no encontrado.']);
    }
} else {
    // If 'query' parameter not specified, return error message
    echo json_encode(['error' => 'Parámetro "query" no especificado.']);
}

$conn->close(); // Close database connection
?>
