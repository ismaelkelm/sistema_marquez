<?php
// Incluir el archivo de conexión a la base de datos
require_once '../base_datos/db.php';

// Consultar todas las notificaciones
$sql = "SELECT * FROM notificaciones ORDER BY fecha_de_envío DESC";
$result = $conn->query($sql);

include('../includes/header.php');
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notificaciones - Mi Empresa</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

    <div class="container my-4">
        <h2 class="text-center">Notificaciones</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>ID Usuario</th>
                    <th>Mensaje</th>
                    <th>Fecha de Envío</th>
                    <th>Estado</th>
                    <th>Número de Pedido</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>{$row['id_notificaciones']}</td>";
                        echo "<td>{$row['id_usuarios']}</td>";
                        echo "<td>{$row['mensaje']}</td>";
                        echo "<td>{$row['fecha_de_envío']}</td>";
                        echo "<td>{$row['estado']}</td>";
                        echo "<td>{$row['numero_pedido']}</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No hay notificaciones.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Scripts de Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
// Cerrar la conexión
$conn->close();
?>
