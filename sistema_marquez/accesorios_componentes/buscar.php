<?php
session_start();
require_once '../base_datos/db.php'; // Asegúrate de que la ruta a tu base de datos es correcta

if (isset($_GET['search'])) {
    $searchTerm = trim($_GET['search']);

    // Evitar inyecciones SQL
    $sql = "SELECT nombre, descripcion, stock, precio FROM accesorios_y_componentes WHERE nombre LIKE ?";
    if ($stmt = $conn->prepare($sql)) {
        $param = "%" . $searchTerm . "%"; // Búsqueda parcial
        $stmt->bind_param("s", $param);
        $stmt->execute();
        $result = $stmt->get_result();

        echo "<div class='container mt-5'>";
        if ($result->num_rows > 0) {
            // Mostrar los resultados
            echo "<h2 class='mb-4'>Resultados de la búsqueda para '<strong>" . htmlspecialchars($searchTerm) . "</strong>':</h2>";
            echo "<table class='table table-striped table-bordered'>";
            echo "<thead class='thead-dark'><tr><th>Nombre</th><th>Descripción</th><th>Stock</th><th>Precio</th></tr></thead>";
            echo "<tbody>";

            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['nombre']) . "</td>";
                echo "<td>" . htmlspecialchars($row['descripcion']) . "</td>";
                echo "<td>" . htmlspecialchars($row['stock']) . "</td>";
                echo "<td>$" . htmlspecialchars(number_format($row['precio'], 2)) . "</td>";
                echo "</tr>";
            }
            echo "</tbody></table>";
        } else {
            echo "<div class='alert alert-warning'>No se encontraron resultados para '<strong>" . htmlspecialchars($searchTerm) . "</strong>'.</div>";
        }
        $stmt->close();
        echo "</div>"; // Cerrar el contenedor
    } else {
        echo "<div class='container mt-5 alert alert-danger'>Error en la preparación de la consulta: " . $conn->error . "</div>";
    }
} else {
    echo "<div class='container mt-5 alert alert-danger'>No se ha realizado ninguna búsqueda.</div>";
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Botón para volver al índice -->
    <div class="text-center">
            <a href="../index.html" class="btn btn-secondary w-20">Volver al Inicio</a>
        </div>
</head>
<body>
    
</body>
</html>