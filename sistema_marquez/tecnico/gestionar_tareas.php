<?php
// Incluir el archivo de conexión a la base de datos
require_once '../base_datos/db.php';
include('../includes/header.php');

// Verificar si el usuario ha iniciado sesión y obtener el id_tecnico desde la sesión
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 3) {
    header("Location: ../login/login.php");
    exit;
}

// Obtener el id_tecnico desde la sesión (suponiendo que fue guardado correctamente en el panel del técnico)
$id_tecnico = $_SESSION['user_id'];

// Verificar si se ha enviado el formulario para asignar la tarea
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['asignar_tarea'])) {
    $id_pedido = $_POST['id_pedido'];

    // Actualizar el estado del pedido y asignar el técnico en la base de datos
    $sql_update = "UPDATE pedidos_de_reparacion SET estado = 'En Progreso', id_tecnico = ? WHERE id_pedidos_de_reparacion = ?";
    if ($stmt_update = $conn->prepare($sql_update)) {
        $stmt_update->bind_param("ii", $id_tecnico, $id_pedido);
        if ($stmt_update->execute()) {
            echo "<p class='success-message'>Tarea asignada correctamente.</p>";
        } else {
            echo "<p class='error-message'>Error al asignar la tarea: " . htmlspecialchars($stmt_update->error) . "</p>";
        }
        $stmt_update->close();
    } else {
        echo "<p class='error-message'>Error en la preparación de la consulta: " . htmlspecialchars($conn->error) . "</p>";
    }
}

// Consultar todos los pedidos que están en estado "Pendiente"
$sql = "SELECT * FROM pedidos_de_reparacion WHERE estado = 'Pendiente' ORDER BY id_pedidos_de_reparacion ASC";
$result = $conn->query($sql);
?>

<div class="container">
    <h1>Gestión de Pedidos de Reparación</h1>

    <a href="../tecnico/tecnico_panel.php" class="btn btn-secondary btn-back">
        <i class="fas fa-arrow-left"></i> Volver Atrás
    </a>
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
                                <input type="submit" name="asignar_tarea" value="Asignarme Tarea" class="btn btn-primary">
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No hay pedidos pendientes registrados.</p>
    <?php endif; ?>
</div>

<?php include('../includes/footer.php'); ?>

<?php
// Cerrar la conexión
$conn->close();
?>
