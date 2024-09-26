<?php
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

// Obtener el id_tecnico desde la sesión
$id_UsuarioTecnico = $_SESSION['user_id'];

?>
<div class="container">
    <h1>Lista de Pedidos de Reparación</h1>
    <a href="../tecnico/tecnico_panel.php" class="btn btn-secondary btn-back">
        <i class="fas fa-arrow-left"></i> Volver Atrás
    </a>
    <?php
    // Modifica la consulta para excluir los pedidos completados
    $sql = "SELECT * FROM pedidos_de_reparacion WHERE id_UsuarioTecnico	 = ? AND estado NOT IN ('Completado', 'Pendiente') ORDER BY id_pedidos_de_reparacion DESC";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $id_UsuarioTecnico);
        $stmt->execute();
        $result = $stmt->get_result();
    }

    ?>

    <?php if ($result->num_rows > 0): ?>
        <form method="POST" action="actualizar_pedido.php">
            <table class="table table-striped mt-4">
                <thead>
                    <tr>
                        <th>Seleccionar</th>
                        <th>ID</th>
                        <th>Cliente</th>
                        <th>Dispositivo</th>
                        <th>Fecha de Pedido</th>
                        <th>Estado</th>
                        <th>Número de Orden</th>
                        <th>Descripción de la Reparación</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><input type="checkbox" name="seleccionados[]" value="<?php echo htmlspecialchars($row['id_pedidos_de_reparacion']); ?>"></td>
                            <td><?php echo htmlspecialchars($row['id_pedidos_de_reparacion']); ?></td>
                            <td><?php echo htmlspecialchars($row['id_clientes']); ?></td>
                            <td><?php echo htmlspecialchars($row['id_dispositivos']); ?></td>
                            <td><?php echo htmlspecialchars($row['fecha_de_pedido']); ?></td>
                            <td>
                                <select name="estado[<?php echo htmlspecialchars($row['id_pedidos_de_reparacion']); ?>]" class="form-control">
                                    <option value="En progreso" <?php echo $row['estado'] == 'En progreso' ? 'selected' : ''; ?>>En progreso</option>
                                    <option value="Completado" <?php echo $row['estado'] == 'Completado' ? 'selected' : ''; ?>>Completado</option>
                                    <option value="Retrasado" <?php echo $row['estado'] == 'Retrasado' ? 'selected' : ''; ?>>Retrasado</option>
                                    <option value="Pendiente" <?php echo $row['estado'] == 'Pendiente' ? 'selected' : ''; ?>>Quitar pedido</option>
                                </select>
                            </td>
                            <td><?php echo htmlspecialchars($row['numero_orden']); ?></td>
                            <td>
                                <input type="text" name="descripcion[<?php echo htmlspecialchars($row['id_pedidos_de_reparacion']); ?>]" class="form-control mb-2" placeholder="Descripción de la reparación">
                                <input type="submit" name="actualizar_seleccionados" value="Actualizar Estado" class="btn btn-primary mt-3">
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <h3>Piezas y Componentes Utilizados</h3>
            <div id="piezas-componentes">
                <div class="form-group">
                    <label for="pieza">Pieza/Componente:</label>
                    <select name="piezas_componentes[]" class="form-control mb-2">
                        <?php
                        // Obtener todas las piezas/componentes disponibles
                        $sql_piezas = "SELECT * FROM piezas_y_componentes";
                        $result_piezas = $conn->query($sql_piezas);
                        while ($pieza = $result_piezas->fetch_assoc()):
                        ?>
                            <option value="<?php echo $pieza['id_piezas_y_componentes']; ?>">
                                <?php echo htmlspecialchars($pieza['nombre']); ?> - Stock: <?php echo htmlspecialchars($pieza['stock']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                    <input type="number" name="cantidad_usada[]" class="form-control mb-2" placeholder="Cantidad utilizada">
                </div>
            </div>

            <button type="button" class="btn btn-secondary" id="agregar-pieza">Agregar otra pieza/componente</button>
        </form>
    <?php else: ?>
        <p>No tienes trabajos pendientes.</p>
    <?php endif; ?>
</div>

<?php include('../includes/footer.php'); ?>

<script>
document.getElementById('agregar-pieza').addEventListener('click', function() {
    var nuevoComponente = document.querySelector('.form-group').cloneNode(true);
    document.getElementById('piezas-componentes').appendChild(nuevoComponente);
});
</script>

<?php
$conn->close();
?>
