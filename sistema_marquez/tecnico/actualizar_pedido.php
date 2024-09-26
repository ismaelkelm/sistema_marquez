<?php
require_once '../base_datos/db.php';

// Iniciar la sesión
session_start();
$id_tecnico = $_SESSION['id_tecnico'] ?? null;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['actualizar_seleccionados'])) {
    if (isset($_POST['seleccionados']) && is_array($_POST['seleccionados'])) {
        $seleccionados = $_POST['seleccionados'];
        $descripciones = $_POST['descripcion'] ?? [];
        $piezas_componentes = $_POST['piezas_componentes'] ?? [];
        $cantidades_usadas = $_POST['cantidad_usada'] ?? [];

        foreach ($seleccionados as $id_pedido) {
            // Actualizar el estado del pedido a "Completado"
            $nuevo_estado = "Completado";

            // Actualizar la tabla `pedidos_de_reparacion`
            $sql_update = "UPDATE pedidos_de_reparacion SET estado = ? WHERE id_pedidos_de_reparacion = ?";
            if ($stmt_update = $conn->prepare($sql_update)) {
                $stmt_update->bind_param("si", $nuevo_estado, $id_pedido);
                $stmt_update->execute();
                $stmt_update->close();
            }

            // Insertar en la tabla `reparaciones`
            $descripcion_reparacion = $descripciones[$id_pedido] ?? '';
            $sql_insert_reparacion = "INSERT INTO reparaciones (id_dispositivos, descripcion, estado, fecha_de_reparacion) 
                                      VALUES ((SELECT id_dispositivos FROM pedidos_de_reparacion WHERE id_pedidos_de_reparacion = ?), ?, ?, NOW())";
            if ($stmt_insert_reparacion = $conn->prepare($sql_insert_reparacion)) {
                $stmt_insert_reparacion->bind_param("iss", $id_pedido, $descripcion_reparacion, $nuevo_estado);
                $stmt_insert_reparacion->execute();
                $id_reparacion = $stmt_insert_reparacion->insert_id;  // Obtener el ID de la nueva reparación
                $stmt_insert_reparacion->close();
            }

            // Insertar en la tabla `detalle_reparaciones`
            foreach ($piezas_componentes as $index => $id_pieza) {
                $cantidad_usada = $cantidades_usadas[$index] ?? 0;

                // Insertar los detalles de la reparación
                $sql_insert_detalle = "INSERT INTO detalle_reparaciones (id_reparacion, id_piezas_y_componentes, cantidad_usada) 
                                       VALUES (?, ?, ?)";
                if ($stmt_insert_detalle = $conn->prepare($sql_insert_detalle)) {
                    $stmt_insert_detalle->bind_param("iii", $id_reparacion, $id_pieza, $cantidad_usada);
                    $stmt_insert_detalle->execute();
                    $stmt_insert_detalle->close();
                }

                // Actualizar el stock en la tabla `piezas_y_componentes`
                $sql_update_stock = "UPDATE piezas_y_componentes SET stock = stock - ? WHERE id_piezas_y_componentes = ?";
                if ($stmt_update_stock = $conn->prepare($sql_update_stock)) {
                    $stmt_update_stock->bind_param("ii", $cantidad_usada, $id_pieza);
                    $stmt_update_stock->execute();
                    $stmt_update_stock->close();
                }
            }
        }

        echo "<p class='success-message'>Los estados han sido actualizados correctamente y se han registrado las reparaciones.</p>";
    }
}

// Redirigir de nuevo a la lista de pedidos
header("Location: lista_pedido.php");
exit;

?>
