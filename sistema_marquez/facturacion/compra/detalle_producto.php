<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar Productos Vendidos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            padding: 20px;
        }

        .search-bar {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 20px;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .search-bar input[type="text"],
        .search-bar input[type="date"],
        .search-bar button {
            width: 100%;
            max-width: 300px; /* Limita el ancho máximo */
            margin: 10px 0; /* Espacio entre los elementos */
            padding: 10px; /* Espacio interno */
            border: 1px solid #ced4da;
            border-radius: 5px;
            font-size: 16px; /* Tamaño de fuente */
        }

        .search-bar input[type="text"]:focus,
        .search-bar input[type="date"]:focus {
            border-color: #80bdff;
            outline: none; /* Sin borde de enfoque */
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .search-bar button {
            background-color: #007bff; /* Color de fondo del botón */
            color: white; /* Color del texto */
            border: none; /* Sin borde */
            cursor: pointer; /* Cursor de puntero */
            transition: background-color 0.3s; /* Transición suave */
        }

        .search-bar button:hover {
            background-color: #0056b3; /* Color de fondo al pasar el ratón */
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
    </style>
</head>
<body>
<?php echo '<button onclick="location.href=\'compra.html\'" class="mt-3 button button-back">Volver</button>';?>

    <form action="" method="GET" class="search-bar">
        <input type="text" id="producto_busqueda" name="producto_busqueda" placeholder="Buscar producto por nombre" required>
        <input type="date" id="fecha_inicio" name="fecha_inicio" placeholder="Fecha desde" required>
        <input type="date" id="fecha_fin" name="fecha_fin" placeholder="Fecha hasta" required>
        
        <button type="submit">Buscar</button>
    </form>

    <?php
    include '../../base_datos/db.php'; // Conexión a la base de datos

    // Verificar la conexión
    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    // Verificar si las fechas y la búsqueda del producto están definidas antes de ejecutar la consulta
    if (isset($_GET['fecha_inicio']) && isset($_GET['fecha_fin']) && isset($_GET['producto_busqueda'])) {
        $fecha_inicio = $_GET['fecha_inicio'];
        $fecha_fin = $_GET['fecha_fin'];
        $producto_busqueda = $_GET['producto_busqueda'];

        // Consulta para obtener las ventas del producto buscado entre las fechas seleccionadas
        $sql = "
            SELECT ac.nombre, ac.descripcion, ac.precio, SUM(df.cantidad_venta) AS total_vendido
            FROM accesorios_y_componentes AS ac
            JOIN detalle_factura AS df ON ac.id_accesorios_y_componentes = df.id_accesorios_y_componentes
            JOIN cabecera_factura AS cf ON df.id_cabecera_factura = cf.id_cabecera_factura
            WHERE cf.fecha_factura BETWEEN ? AND ?
            AND ac.nombre LIKE ?
            GROUP BY ac.id_accesorios_y_componentes
        ";

        // Preparar la declaración
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("Error en la consulta: " . $conn->error); // Imprimir el error de la consulta
        }

        // Agregar comodines para la búsqueda
        $producto_busqueda = "%" . $producto_busqueda . "%";
        $stmt->bind_param("sss", $fecha_inicio, $fecha_fin, $producto_busqueda);
        $stmt->execute();
        $result = $stmt->get_result();

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
            </style>";

            echo "<table>";
            echo "<tr><th>Nombre</th><th>Descripción</th><th>Precio</th><th>Total Vendido</th></tr>";
            
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row["nombre"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["descripcion"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["precio"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["total_vendido"]) . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "No se encontraron ventas para el producto buscado en el rango de fechas.";
        }
        
        // Cerrar el statement
        $stmt->close();
    }

    // Cerrar la conexión
    $conn->close();
    ?>
</body>
</html>
