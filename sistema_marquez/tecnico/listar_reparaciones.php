<?php
// Incluir el archivo de conexión a la base de datos
require_once '../base_datos/db.php';

// Incluir los archivos de cabecera y pie de página
include('../includes/header.php');
// No incluir nav.php para evitar problemas con session_start()

// Consultar las reparaciones, uniendo las tablas clientes y dispositivos
$sql = "
    SELECT pr.id_pedidos_de_reparacion, pr.fecha_de_pedido, pr.estado, pr.numero_pedido,
           c.nombre AS nombre_cliente, d.marca, d.modelo, d.numero_de_serie
    FROM pedidos_de_reparacion pr
    JOIN clientes c ON pr.id_clientes = c.id_clientes
    JOIN dispositivos d ON pr.id_dispositivos = d.id_dispositivos
    ORDER BY pr.numero_pedido ASC
";

$result = $conn->query($sql);

?>

<div class="container">
    <h1>Listado de Reparaciones</h1>

    <button onclick="window.history.back();" class="btn-back">Volver Atrás</button>

    <?php if ($result->num_rows > 0): ?>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Fecha de Pedido</th>
                    <th>Estado</th>
                    <th>Número de Pedido</th>
                    <th>Nombre del Cliente</th>
                    <th>Marca del Dispositivo</th>
                    <th>Modelo del Dispositivo</th>
                    <th>Número de Serie</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id_pedidos_de_reparacion']); ?></td>
                        <td><?php echo htmlspecialchars($row['fecha_de_pedido']); ?></td>
                        <td><?php echo htmlspecialchars($row['estado']); ?></td>
                        <td><?php echo htmlspecialchars($row['numero_pedido']); ?></td>
                        <td><?php echo htmlspecialchars($row['nombre_cliente']); ?></td>
                        <td><?php echo htmlspecialchars($row['marca']); ?></td>
                        <td><?php echo htmlspecialchars($row['modelo']); ?></td>
                        <td><?php echo htmlspecialchars($row['numero_de_serie']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No hay reparaciones registradas.</p>
    <?php endif; ?>
</div>

<?php
// Incluir el pie de página
include('../includes/footer.php');

// Cerrar la conexión
$conn->close();
?>
