<?php
include '../../base_datos/db.php'; // Incluye el archivo de conexión

// Obtener las operaciones de la base de datos
$query = "SELECT id_operacion, tipo FROM operacion";
$result = mysqli_query($conn, $query);

// Mapeo de tipos de operación a sus archivos correspondientes
$routes = [
    'Venta' => 'registrar_venta.php',           // Archivo para registrar ventas
    'Reparación' => '../pedidos_de_reparacion/registrar_pedido.php', // Archivo para registrar reparaciones
    'Venta y Reparación' => 'registrar_venta_reparacion.php', // Archivo para ventas y reparaciones
    'Compra' => 'registrar_compra.php',         // Archivo para registrar compras
];

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Operaciones</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa; /* Fondo claro */
            font-family: Arial, sans-serif; /* Fuente más moderna */
        }

        .container {
            max-width: 600px; /* Ancho máximo para el contenedor */
            margin: auto; /* Centrar el contenedor */
            padding: 20px; /* Espaciado interno */
            background: white; /* Fondo blanco */
            border-radius: 8px; /* Bordes redondeados */
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); /* Sombra sutil */
        }

        h1 {
            text-align: center; /* Centrar el título */
            color: #343a40; /* Color del texto */
            margin-bottom: 20px; /* Margen inferior */
        }

        .list-group {
            list-style: none; /* Eliminar viñetas */
            padding: 0; /* Sin padding */
        }

        .list-group-item {
            background-color: #e9ecef; /* Fondo para los elementos de la lista */
            margin-bottom: 10px; /* Espaciado entre elementos */
            padding: 15px; /* Espaciado interno */
            border-radius: 5px; /* Bordes redondeados */
            transition: background-color 0.3s; /* Transición suave */
        }

        .list-group-item a {
            text-decoration: none; /* Sin subrayado */
            color: #007bff; /* Color azul */
            font-weight: bold; /* Negrita */
        }

        .list-group-item:hover {
            background-color: #ced4da; /* Fondo al pasar el ratón */
            cursor: pointer; /* Cambiar el cursor al pasar el ratón */
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <h1>Opciones de Operación</h1>
    <ul class="list-group">
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <?php
                // Obtener la ruta correspondiente a cada tipo
                $tipo = $row['tipo'];
                $ruta = isset($routes[$tipo]) ? $routes[$tipo] : '#'; // Ruta por defecto si no se encuentra
            ?>
            <li class="list-group-item">
                <a href="<?php echo $ruta; ?>">
                    <?php echo $tipo; ?>
                </a>
            </li>
        <?php endwhile; ?>
    </ul>
    <div class="text-center mt-4">
        <a href="http://sistema.local.com/administrativo/pedidos_de_reparacion/registrar_pedido.php" class="btn btn-secondary">Volver</a>
    </div>
</div>

<?php
// Cerrar la conexión a la base de datos
mysqli_close($conn);
?>

</body>
</html>
