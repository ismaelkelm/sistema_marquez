<?php
include '../../base_datos/db.php'; // Conexión a la base de datos

// Verificar la conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Consulta para obtener los datos especificados
$sql = "SELECT nombre, descripcion, stock, precio FROM accesorios_y_componentes";
$result = $conn->query($sql);

// Verificar si hay resultados y mostrarlos
if ($result->num_rows > 0) {
    echo "
    <style>
        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            font-family: Arial, sans-serif;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #0056b3;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #e0e0e0;
        }
            .button {
            background-color: #007bff; /* Color de fondo */
            color: white; /* Color del texto */
            border: none; /* Sin borde */
            padding: 10px 20px; /* Espaciado interno */
            border-radius: 5px; /* Bordes redondeados */
            cursor: pointer; /* Cursor de puntero */
            font-size: 16px; /* Tamaño de fuente */
            transition: background-color 0.3s, transform 0.2s; /* Transiciones suaves */
        }

        .button:hover {
            background-color: #0056b3; /* Color de fondo al pasar el ratón */
            transform: scale(1.05); /* Aumenta ligeramente el tamaño */
        }

        .button:active {
            transform: scale(0.95); /* Reduce ligeramente el tamaño al hacer clic */
        }
        
        .mt-3 {
            margin-top: 1rem; /* Margen superior */
        }

        .button-back {
            text-decoration: none; /* Sin subrayado */
        }
    </style>";
    echo '<button onclick="location.href=\'compra.html\'" class="mt-3 button button-back">Volver</button>';

    echo "<table>";
    echo "<tr><th>Nombre</th><th>Descripción</th><th>Stock</th><th>Precio</th></tr>";
    
    // Variable para controlar si es el primer registro
    $isFirstRow = true;
    
    while($row = $result->fetch_assoc()) {
        // Saltar el primer registro
        if ($isFirstRow) {
            $isFirstRow = false;
            continue;
        }

        echo "<tr>";
        echo "<td>" . htmlspecialchars($row["nombre"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["descripcion"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["stock"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["precio"]) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "No se encontraron resultados.";
}

// Cerrar la conexión
$conn->close();
?>
