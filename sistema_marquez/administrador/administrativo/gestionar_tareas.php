<?php
// Incluir el archivo de conexión a la base de datos
require_once '../base_datos/db.php';
include('../includes/header.php');

// Verificar si se ha enviado el formulario para actualizar el estado
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['actualizar_estado'])) {
    $id_pedido = $_POST['id_pedido'];
    $nuevo_estado = $_POST['estado'];

    // Actualizar el estado del pedido en la base de datos
    $sql_update = "UPDATE pedidos_de_reparacion SET estado = ? WHERE id_pedidos_de_reparacion = ?";
    if ($stmt_update = $conn->prepare($sql_update)) {
        $stmt_update->bind_param("si", $nuevo_estado, $id_pedido);
        if ($stmt_update->execute()) {
            echo "<p class='success-message'>Estado actualizado correctamente.</p>";
        } else {
            echo "<p class='error-message'>Error al actualizar el estado: " . htmlspecialchars($stmt_update->error) . "</p>";
        }
        $stmt_update->close();
    } else {
        echo "<p class='error-message'>Error en la preparación de la consulta: " . htmlspecialchars($conn->error) . "</p>";
    }
}

// Consultar todos los pedidos
$sql = "SELECT * FROM pedidos_de_reparacion ORDER BY id_pedidos_de_reparacion ASC";
$result = $conn->query($sql);
?>

<div class="container">
    <h1>Gestión de Pedidos de Reparación</h1>

    <button onclick="window.history.back();" class="btn btn-secondary">Volver Atrás</button>

    <?php if ($result->num_rows > 0): ?>
        <table class="table table-striped mt-4">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Cliente</th>
                    <th>Dispositivo</th>
                    <th>Fecha de Pedido</th>
                    <th>Estado</th>
                    <th>Número de Orden</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id_pedidos_de_reparacion']); ?></td>
                        <td><?php echo htmlspecialchars($row['id_clientes']); ?></td>
                        <td><?php echo htmlspecialchars($row['id_dispositivos']); ?></td>
                        <td><?php echo htmlspecialchars($row['fecha_de_pedido']); ?></td>
                        <td>
                            <span class="<?php echo 'status-' . strtolower(str_replace(' ', '-', $row['estado'])); ?>">
                                <?php echo htmlspecialchars($row['estado']); ?>
                            </span>
                        </td>
                        <td><?php echo htmlspecialchars($row['numero_orden']); ?></td>
                        <td>
                            <form method="POST" action="">
                                <input type="hidden" name="id_pedido" value="<?php echo htmlspecialchars($row['id_pedidos_de_reparacion']); ?>">
                                <select name="estado" class="form-control mb-2">
                                    <option value="Pendiente"<?php echo $row['estado'] == 'Pendiente' ? ' selected' : ''; ?>>Pendiente</option>
                                    <option value="Completado"<?php echo $row['estado'] == 'Completado' ? ' selected' : ''; ?>>Completado</option>
                                    <option value="En Progreso"<?php echo $row['estado'] == 'En Progreso' ? ' selected' : ''; ?>>En Progreso</option>
                                    <option value="Cancelado"<?php echo $row['estado'] == 'Cancelado' ? ' selected' : ''; ?>>Cancelado</option>
                                    <option value="Entregado"<?php echo $row['estado'] == 'Entregado' ? ' selected' : ''; ?>>Entregado</option>
                                </select>
                                <input type="submit" name="actualizar_estado" value="Actualizar" class="btn btn-primary">
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No hay pedidos registrados.</p>
    <?php endif; ?>
</div>

<?php include('../includes/footer.php'); ?>

<?php
// Cerrar la conexión
$conn->close();
?>

<style>
    .btn-secondary {
        margin-top: 20px;
    }
    .status-pendiente {
        color: #ffc107;
        font-weight: bold;
    }
    .status-completado {
        color: #28a745;
        font-weight: bold;
    }
    .status-en-progreso {
        color: #007bff;
        font-weight: bold;
    }
    .status-cancelado {
        color: #dc3545;
        font-weight: bold;
    }
    .status-entregado {
        color: #6c757d;
        font-weight: bold;
    }
    .success-message {
        color: #28a745;
        font-weight: bold;
    }
    .error-message {
        color: #dc3545;
        font-weight: bold;
    }
</style>
