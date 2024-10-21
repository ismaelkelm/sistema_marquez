<?php
include_once '../base_datos/db.php';

// Obtener los datos del formulario
$order_number = isset($_POST['order_number']) ? htmlspecialchars(trim($_POST['order_number'])) : '';
$dni = isset($_POST['dni']) ? htmlspecialchars(trim($_POST['dni'])) : '';
$customer_name = isset($_POST['customer_name']) ? htmlspecialchars(trim($_POST['customer_name'])) : '';
$customer_lastname = isset($_POST['customer_lastname']) ? htmlspecialchars(trim($_POST['customer_lastname'])) : '';

$stmt = null;

// if ($_SERVER['REQUEST_METHOD'] == 'POST') {
//     if (!empty($order_number) && !empty($dni)) {
//         // Preparar la consulta
//         $stmt = $conn->prepare("
//             SELECT pr.numero_orden, pr.estado_reparacion, pr.id_clientes, c.id_clientes, c.nombre, c.apellido, c.dni
//             FROM pedidos_de_reparacion pr
//             JOIN clientes c ON pr.id_clientes = c.id_clientes
//             WHERE pr.numero_orden = ? AND c.dni = ? 
//             " . ($customer_name ? "AND c.nombre = ?" : "") . " 
//             " . ($customer_lastname ? "AND c.apellido = ?" : "")
//         );
        
//         if ($stmt) {
//             if ($customer_name && $customer_lastname) {
//                 $stmt->bind_param('ssss', $order_number, $dni, $customer_name, $customer_lastname);
//             } elseif ($customer_name) {
//                 $stmt->bind_param('sss', $order_number, $dni, $customer_name);
//             } elseif ($customer_lastname) {
//                 $stmt->bind_param('sss', $order_number, $dni, $customer_lastname);
//             } else {
//                 $stmt->bind_param('ss', $order_number, $dni);
//             }
            
//             $stmt->execute();
//             $result = $stmt->get_result();

//             // Definir el mensaje y la clase CSS para el estado
//             $status_message = '';
//             $status_class = '';
//             $status_indicator = '';

//             if ($result->num_rows > 0) {
//                 $order_info = $result->fetch_assoc();
//                 $estado = htmlspecialchars($order_info['estado_reparacion']);

//                 // Definir clase CSS según el estado
//                 switch ($estado) {
//                     case 'Pendiente':
//                         $status_class = 'status-pendiente';
//                         $status_indicator = 'indicator-yellow';
//                         break;
//                     case 'Completado':
//                         $status_class = 'status-completado';
//                         $status_indicator = 'indicator-green';
//                         break;
//                     case 'Cancelado':
//                         $status_class = 'status-cancelado';
//                         $status_indicator = 'indicator-red';
//                         break;
//                     case 'En proceso':
//                         $status_class = 'status-en-proceso';
//                         $status_indicator = 'indicator-blue';
//                         break;
//                     case 'Entregado':
//                         $status_class = 'status-entregado';
//                         $status_indicator = 'indicator-lightblue';
//                         break;
//                     default:
//                         $status_class = 'status-default';
//                         $status_indicator = 'indicator-default';
//                         break;
//                 }
//                 $status_message = "<strong>Estado:</strong> <span class='{$status_class}'>{$estado}</span>";
                
//                 echo "<div class='alert alert-info'>";
//                 echo "<p><strong>Número de Orden:</strong> " . htmlspecialchars($order_info['numero_orden']) . "</p>";
//                 echo "<p><strong>Nombre del Cliente:</strong> " . htmlspecialchars($order_info['nombre']) . " " . htmlspecialchars($order_info['apellido']) . "</p>";
//                 echo "<p><strong>DNI del Cliente:</strong> " . htmlspecialchars($order_info['dni']) . "</p>";
//                 echo "<div class='semaforo'><div class='{$status_indicator} blink'></div></div>";
//                 echo "<p>{$status_message}</p>";
//                 echo "</div>";
//             } else {
//                 echo "<div class='alert alert-warning'>No se encontró ninguna información con los datos proporcionados.</div>";
//             }
//             $stmt->close();
//         } else {
//             echo "<div class='alert alert-danger'>Error al preparar la consulta.</div>";
//         }
//     } else {
//         echo "<div class='alert alert-warning'>Por favor, complete los campos obligatorios: Número de Orden y DNI.</div>";
//     }
// }

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!empty($order_number) && !empty($dni)) {
        // Preparar la consulta
        $stmt = $conn->prepare("
            SELECT pr.numero_orden, pr.estado_reparacion, pr.id_clientes, c.id_clientes, c.nombre, c.apellido, c.dni
            FROM pedidos_de_reparacion pr
            JOIN clientes c ON pr.id_clientes = c.id_clientes
            WHERE pr.numero_orden = ? AND c.dni = ? 
            " . ($customer_name ? "AND c.nombre = ?" : "") . " 
            " . ($customer_lastname ? "AND c.apellido = ?" : "")
        );

        if ($stmt) {
            if ($customer_name && $customer_lastname) {
                $stmt->bind_param('ssss', $order_number, $dni, $customer_name, $customer_lastname);
            } elseif ($customer_name) {
                $stmt->bind_param('sss', $order_number, $dni, $customer_name);
            } elseif ($customer_lastname) {
                $stmt->bind_param('sss', $order_number, $dni, $customer_lastname);
            } else {
                $stmt->bind_param('ss', $order_number, $dni);
            }

            $stmt->execute();
            $result = $stmt->get_result();

            // Colores del semáforo
            $semaforo_colors = [
                'Pendiente' => 'indicator-yellow',
                'Completado' => 'indicator-green',
                'Cancelado' => 'indicator-red',
                'En proceso' => 'indicator-blue',
                'Entregado' => 'indicator-lightblue'
            ];

            if ($result->num_rows > 0) {
                $order_info = $result->fetch_assoc();
                $estado = htmlspecialchars($order_info['estado_reparacion']);

                echo "<div class='alert alert-info'>";
                echo "<p><strong>Número de Orden:</strong> " . htmlspecialchars($order_info['numero_orden']) . "</p>";
                echo "<p><strong>Nombre del Cliente:</strong> " . htmlspecialchars($order_info['nombre']) . " " . htmlspecialchars($order_info['apellido']) . "</p>";
                echo "<p><strong>DNI del Cliente:</strong> " . htmlspecialchars($order_info['dni']) . "</p>";
                
                // Mensaje del estado
                echo "<p><strong>Estado:</strong> <span class='status-{$estado}'>{$estado}</span></p>";

                // Semáforo fijo
                echo "<div class='semaforo'>";
                foreach ($semaforo_colors as $key => $color) {
                    $blink_class = ($key === $estado) ? 'blink' : '';
                    echo "<div class='{$color} {$blink_class}'></div>";
                }
                echo "</div>";

                echo "</div>"; // Cierre de la alerta
            } else {
                echo "<div class='alert alert-warning'>No se encontró ninguna información con los datos proporcionados.</div>";
            }
            $stmt->close();
        } else {
            echo "<div class='alert alert-danger'>Error al preparar la consulta.</div>";
        }
    } else {
        echo "<div class='alert alert-warning'>Por favor, complete los campos obligatorios: Número de Orden y DNI.</div>";
    }
}




?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta de Estado - Mi Empresa</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>

        /* Estilo para el contenedor de información */
        .alert-info {
            padding: 1px 15px; /* Menor padding para achicar altura */
            margin-bottom: 10px; /* Espacio inferior */
            border-radius: 8px; /* Bordes redondeados */
        }

        /* Estilo para cada línea de información */
        .alert-info p {
            font-size: 0.9rem; /* Tamaño de fuente más pequeño */
            margin: 1px 0; /* Margen superior e inferior reducido */
        }

        /* Estilo para el título dentro de la alerta */
        .alert-info strong {
            font-size: 1.1rem; /* Tamaño de fuente del título */
        }

        .container {
            background-color: #ffffff; /* Fondo blanco para contrastar con el fondo general */
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15); /* Sombra para profundidad */
            max-width: 600px;
            margin: 0 auto;
            border: 1px solid #d3d3d3;
            margin-top: 30px; /* Espacio superior para separar del borde de la ventana */
        }

        /* Estilo para el título de la página */
        h2.text-center {
            font-size: 2rem;
            color: #333;
            font-weight: bold;
            margin-bottom: 30px; /* Separación con el formulario */
            text-transform: uppercase; /* Texto en mayúsculas */
            text-align: center; /* Centrado del texto */
            letter-spacing: 1px; /* Espacio entre letras */
            border-bottom: 2px solid #007bff; /* Línea decorativa bajo el título */
            padding-bottom: 10px; /* Espacio entre el texto y la línea */
        }

        /* Estilo del formulario en su conjunto */
        form {
            margin-top: 20px; /* Separación del título */
        }

        /* Estilo para el botón de volver */
        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
            padding: 10px 20px;
            font-size: 1rem;
            border-radius: 8px;
            display: block;
            margin: 20px auto 0 auto; /* Centrado automático y margen */
            transition: background-color 0.3s ease, border-color 0.3s ease;
        }

        /* Hover sobre el botón de volver */
        .btn-secondary:hover {
            background-color: #5a6268;
            border-color: #545b62;
        }
        /* Estilos para el formulario */
        form {
            background-color: grey; /* Fondo neutro */
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Sombra ligera */
            max-width: 500px;
            margin: 0 auto;
            border: 1px solid #dee2e6;
        }

        /* Estilos para las etiquetas */
        form label {
            font-weight: bold;
            color: #333;
            margin-bottom: 8px;
            display: block;
        }

        /* Estilos para los campos de entrada */
        form input[type="text"] {
            border: 1px solid #ced4da;
            border-radius: 5px;
            padding: 10px;
            font-size: 1rem;
            width: 100%;
            transition: border-color 0.3s ease;
        }

        /* Cambios al enfocar el campo de texto */
        form input[type="text"]:focus {
            border-color: #80bdff;
            outline: none;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.25);
        }

        /* Botón de envío */
        form .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            padding: 10px 20px;
            font-size: 1rem;
            font-weight: bold;
            border-radius: 5px;
            transition: background-color 0.3s ease, border-color 0.3s ease;
        }

        /* Efecto hover en el botón */
        form .btn-primary:hover {
            background-color: #0056b3;
            border-color: #004085;
        }

        /* Margen inferior entre los grupos de formulario */
        .form-group {
            margin-bottom: 10px;
        }

        /* Colores y tamaños para los estados */
        .status-completado {
            font-size: 2em;
            color: green;
            font-weight: bold;
        }
        .status-pendiente {
            font-size: 2em;
            color: #ffc107;
            font-weight: bold;
        }
        .status-cancelado {
            font-size: 2em;
            color: #dc3545;
            font-weight: bold;
        }
        .status-en-proceso {
            font-size: 2em;
            color: #17a2b8;
            font-weight: bold;
        }
        .status-entregado {
            font-size: 2em;
            color: #007bff;
            font-weight: bold;
        }
        .status-default {
            font-size: 2em;
            color: #6c757d;
            font-weight: bold;
        }

        .semaforo {
            display: flex;
            justify-content: center; /* Centrado horizontal del semáforo */
            width: 100%;
            margin: 20px auto; /* Margen para separación */
            padding: 10px;
            border-radius: 10px;
            background-color: #f8f9fa; /* Color de fondo neutro */
            position: relative; /* Para controlar su posición */
        }

        .fixed {
            position: absolute; /* Mantiene el semáforo fijo en su lugar */
            bottom: 0; /* Ajusta según necesites */
            left: 50%; /* Centrado horizontal */
            transform: translateX(-50%); /* Centrado exacto */
        }

        .semaforo div {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 3px solid #000; /* Agregar borde negro alrededor de cada indicador */
        }

        .semaforo {
            display: flex;
            justify-content: space-between;
            width: 100%;
            max-width: 300px;
            margin: 20px auto;
            padding: 10px;
            border: 2px solid #ccc;
            border-radius: 10px;
            background-color: #f8f9fa; /* Color de fondo neutro */
        }

        .semaforo div {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 3px solid #000; /* Agregar borde negro alrededor de cada indicador */
        }


        /* Indicadores de semáforo */
        .indicator-red { background-color: red; }
        .indicator-yellow { background-color: yellow; }
        .indicator-green { background-color: green; }
        .indicator-blue { background-color: blue; }
        .indicator-lightblue { background-color: lightblue; }
        .indicator-default { background-color: #ccc; }

        /* Animación de parpadeo */
        .blink {
            animation: blink 1s infinite;
        }

        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0; }
        }
    </style>
</head>
<body>
<div class="container mt-4">

    <!-- <a href="../cliente/cliente.php" class="btn btn-secondary">Volver</a> -->
    <h2 class="text-center">Consulta de Estado</h2>
    
    <!-- Formulario para ingresar los datos -->
    <form action="" method="POST" class="mb-4">
        <div class="form-group">
            <label for="order_number">Número de Orden:</label>
            <input type="text" id="order_number" name="order_number" class="form-control" placeholder="ORD" required onfocus="addPrefix()" oninput="addPrefix()" maxlength="10">
        </div>

        <div class="form-group">
            <label for="dni">Número de DNI del Cliente:</label>
            <input type="text" id="dni" name="dni" class="form-control" placeholder="Ingrese el DNI del cliente" required>
        </div>
        
        <button type="submit" class="btn btn-primary">Consultar</button>
    </form>

    <script>
        function addPrefix() {
            const orderInput = document.getElementById('order_number');
            const prefix = 'ORD';
            
            if (!orderInput.value.startsWith(prefix)) {
                orderInput.value = prefix + orderInput.value;
            }
        }
    </script>

    <!-- Barra de semáforo (la mantienes igual como está) -->
    <div class="semaforo">
        <div class="indicator-red"></div>
        <div class="indicator-yellow"></div>
        <div class="indicator-green"></div>
        <div class="indicator-blue"></div>
        <div class="indicator-lightblue"></div>
    </div>

    <!-- Botón de Volver -->
    <div class="mt-4">
        <a href="../index.html" class="btn btn-secondary">Volver</a>
    </div>
</div>

    <!-- Scripts de Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.com/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
