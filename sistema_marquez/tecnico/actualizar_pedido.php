<?php
require_once '../base_datos/db.php';
include('../includes/header.php');

// Iniciar la sesión
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verificar si el usuario ha iniciado sesión y obtener el id_UsuarioTecnico desde la sesión
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 3) {
    header("Location: ../login/login.php");
    exit;
}

$id_UsuarioTecnico = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['actualizar_seleccionados']) && isset($_POST['seleccionados'])) {
        foreach ($_POST['seleccionados'] as $id_pedido) {
            $estado = $_POST['estado'][$id_pedido];
            $descripcion = $_POST['descripcion'][$id_pedido];
            $fecha_reparacion = date('Y-m-d');

            // Actualizar la tabla pedidos_de_reparacion
            $sql_update_pedido = "UPDATE pedidos_de_reparacion SET estado = ? WHERE id_pedidos_de_reparacion = ?";
            if ($stmt = $conn->prepare($sql_update_pedido)) {
                $stmt->bind_param("si", $estado, $id_pedido);
                if ($stmt->execute()) {
                    echo "Estado actualizado con éxito para el pedido ID: $id_pedido";
                } else {
                    echo "Error al actualizar el estado: " . $stmt->error;
                }
            } else {
                echo "Error al preparar la consulta: " . $conn->error;
            }

            // Verificar si el dispositivo está en la tabla reparaciones
            $sql_check_reparacion = "SELECT id_reparaciones FROM reparaciones WHERE id_dispositivos = (SELECT id_dispositivos FROM pedidos_de_reparacion WHERE id_pedidos_de_reparacion = ?)";
            if ($stmt = $conn->prepare($sql_check_reparacion)) {
                $stmt->bind_param("i", $id_pedido);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    // Si existe, actualizar la descripción y la fecha
                    $sql_update_reparacion = "UPDATE reparaciones SET descripcion = ?, fecha_de_reparacion = ? WHERE id_dispositivos = (SELECT id_dispositivos FROM pedidos_de_reparacion WHERE id_pedidos_de_reparacion = ?)";
                    if ($stmt = $conn->prepare($sql_update_reparacion)) {
                        $stmt->bind_param("ssi", $descripcion, $fecha_reparacion, $id_pedido);
                        $stmt->execute();
                    }
                } else {
                    // Si no existe, insertar una nueva entrada
                    $sql_insert_reparacion = "INSERT INTO reparaciones (id_dispositivos, descripcion, fecha_de_reparacion) VALUES ((SELECT id_dispositivos FROM pedidos_de_reparacion WHERE id_pedidos_de_reparacion = ?), ?, ?)";
                    if ($stmt = $conn->prepare($sql_insert_reparacion)) {
                        $stmt->bind_param("iss", $id_pedido, $descripcion, $fecha_reparacion);
                        $stmt->execute();
                    }
                }

                // Obtener el ID de la reparación
                $id_reparacion = $stmt->insert_id;
            }
            echo "id reparacion", $id_reparacion;
            // Insertar los detalles de la reparación y actualizar el stock de las piezas utilizadas
            if (isset($_POST['piezas_componentes']) && isset($_POST['cantidad_usada'])) {
                foreach ($_POST['piezas_componentes'] as $index => $id_pieza) {
                    $cantidad_usada = $_POST['cantidad_usada'][$index];

                    // Insertar el detalle de la reparación en la tabla `detalle_reparaciones`
                    $sql_insert_detalle = "INSERT INTO detalle_reparaciones (id_reparacion, id_piezas_y_componentes, cantidad_usada) VALUES (?, ?, ?)";
                    if ($stmt_detalle = $conn->prepare($sql_insert_detalle)) {
                        $stmt_detalle->bind_param("iii", $id_reparacion, $id_pieza, $cantidad_usada);
                        if ($stmt_detalle->execute()) {
                            echo "Detalle de reparación agregado para la reparación ID: $id_reparacion";
                        } else {
                            echo "Error al insertar el detalle de reparación: " . $stmt_detalle->error;
                        }
                    } else {
                        echo "Error al preparar la consulta para insertar el detalle: " . $conn->error;
                    }

                    // Descontar la cantidad usada del stock
                    $sql_update_stock = "UPDATE piezas_y_componentes SET stock = stock - ? WHERE id_piezas_y_componentes = ?";
                    if ($stmt = $conn->prepare($sql_update_stock)) {
                        $stmt->bind_param("ii", $cantidad_usada, $id_pieza);
                        if ($stmt->execute()) {
                            echo "Stock actualizado para la pieza ID: $id_pieza";
                        } else {
                            echo "Error al actualizar el stock: " . $stmt->error;
                        }
                    } else {
                        echo "Error al preparar la consulta para actualizar el stock: " . $conn->error;
                    }
                }
            }
        }
    }

    // Redirigir de vuelta a la página de pedidos de reparación
    // header("Location: lista_pedido.php");
    // exit;
}
?>
