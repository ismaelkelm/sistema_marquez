<?php
require_once '../base_datos/db.php';
include('../includes/header.php');

// Iniciar la sesión
session_start();
$id_tecnico = $_SESSION['id_tecnico'] ?? null;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['actualizar_reparacion'])) {
    $id_pedido = $_POST['id_pedido'];
    $nuevo_estado = $_POST['estado'];

    // Verificar si el pedido está asignado al técnico logueado
    $sql_verificar = "SELECT estado FROM pedidos_de_reparacion WHERE id_pedidos_de_reparacion = ? AND id_tecnico = ?";
    if ($stmt_verificar = $conn->prepare($sql_verificar)) {
        $stmt_verificar->bind_param("ii", $id_pedido, $id_tecnico);
        $stmt_verificar->execute();
        $stmt_verificar->store_result();

        if ($stmt_verificar->num_rows > 0) {
            $sql_update = "UPDATE pedidos_de_reparacion SET estado = ? WHERE id_pedidos_de_reparacion = ?";
            if ($stmt_update = $conn->prepare($sql_update)) {
                $stmt_update->bind_param("si", $nuevo_estado, $id_pedido);
                $stmt_update->execute();
                $stmt_update->close();
                echo "<p class='success-message'>Estado actualizado correctamente.</p>";
            } else {
                echo "<p class='error-message'>Error al actualizar el estado: " . htmlspecialchars($stmt_update->error) . "</p>";
            }
        } else {
            echo "<p class='error-message'>No tienes permiso para actualizar este pedido.</p>";
        }
        $stmt_verificar->close();
    }
}
?>

<div class="container">
    <h1>Actualizar Estado de Reparación</h1>

    <?php
    $sql = "SELECT * FROM pedidos_de_reparacion WHERE id_tecnico = ? ORDER BY id_pedidos_de_reparacion DESC";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $id_tecnico);
        $stmt->execute();
        $result = $stmt->get_result();
    }
    ?>

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
                    <th>Actualizar Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id_pedidos_de_reparacion']); ?></td>
                        <td><?php echo htmlspecialchars($row['id_clientes']); ?></td>
                        <td><?php echo htmlspecialchars($row['id_dispositivos']); ?></td>
                        <td><?php echo htmlspecialchars($row['fecha_de_pedido']); ?></td>
                        <td><?php echo htmlspecialchars($row['estado']); ?></td>
                        <td><?php echo htmlspecialchars($row['numero_orden']); ?></td>
                        <td>
                            <form method="POST" action="">
                                <input type="hidden" name="id_pedido" value="<?php echo htmlspecialchars($row['id_pedidos_de_reparacion']); ?>">
                                <select name="estado" class="form-control mb-2">
                                    <option value="Completado"<?php echo $row['estado'] == 'Completado' ? ' selected' : ''; ?>>Completado</option>
                                    <option value="Cancelado"<?php echo $row['estado'] == 'Cancelado' ? ' selected' : ''; ?>>Cancelado</option>
                                    <option value="Entregado"<?php echo $row['estado'] == 'Entregado' ? ' selected' : ''; ?>>Entregado</option>
                                </select>
                                <input type="submit" name="actualizar_reparacion" value="Actualizar" class="btn btn-primary">
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No tienes pedidos asignados.</p>
    <?php endif; ?>
</div>

<?php include('../includes/footer.php'); ?>

<?php
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
