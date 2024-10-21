<?php
// Incluir el archivo de conexión a la base de datos
include '../../base_datos/db.php'; 

// Obtener el siguiente ID de pedido de reparación
$query_order_id = "SELECT MAX(id_pedidos_de_reparacion) AS max_order_id FROM pedidos_de_reparacion";
$result_order_id = mysqli_query($conn, $query_order_id);
$next_order_id = ($result_order_id && $row_order_id = mysqli_fetch_assoc($result_order_id)) ? (int) ($row_order_id['max_order_id'] ?? 0) + 1 : 1;

// Obtener el último número de orden y calcular el siguiente
$query_max_order = "SELECT numero_orden FROM pedidos_de_reparacion ORDER BY id_pedidos_de_reparacion DESC LIMIT 1";
$result_max_order = mysqli_query($conn, $query_max_order);
$last_order_row = mysqli_fetch_assoc($result_max_order);
$last_order_number = $last_order_row['numero_orden'] ?? 'ORD0000';
$next_order = (int) substr($last_order_number, 3) + 1;
$formatted_order_number = 'ORD' . str_pad($next_order, 4, '0', STR_PAD_LEFT);

// Consulta para obtener los dispositivos ordenados desde el último dispositivo cargado
$sql = "SELECT id_dispositivos, marca, modelo FROM dispositivos ORDER BY fecha_de_carga DESC";
$result = $conn->query($sql);
?>

<?php include('../../includes/header.php'); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="pedidos.css">
    <title>Registrar Pedido</title>
</head>
<body>
    <form action="registrar_pedido.php" method="POST">
        <div class="container mt-5">
            <a href="../administrativo.php" class="btn btn-secondary mb-3">Volver</a>
            <br>
            <!-- Botones para alternar entre formulario de dispositivo existente y nuevo -->
            <button type="button" id="buscar-dispositivo-btn">Buscar Dispositivo Cargado</button>
            <!-- Botón para cargar nuevo dispositivo -->
            <button type="button" id="nuevo-dispositivo-btn">Cargar Nuevo Dispositivo</button>

            <br>
            <!-- Contenedor del select que inicialmente estará oculto -->
            <div id="dispositivo-container" style="display:none;">
                <label for="dispositivo">Selecciona un dispositivo:</label>
                <select id="dispositivo-select">
                    <?php
                    if ($result->num_rows > 0) {
                        // Mostrar los dispositivos en el dropdown
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='" . $row['id_dispositivos'] . "'>" . $row['marca'] . " - " . $row['modelo'] . "</option>";
                        }
                    } else {
                        echo "<option>No hay dispositivos disponibles</option>";
                    }
                    ?>
                </select>
                <button type="button" id="agregar-dispositivo-btn">Agregar dispositivo</button>

                <!-- Lista donde se mostrarán los dispositivos seleccionados -->
                <ul id="dispositivo-list"></ul>

                <!-- Campo oculto que contendrá los IDs de los dispositivos seleccionados -->
                <input type="hidden" name="dispositivos_seleccionados" id="dispositivos-seleccionados">
            </div>
           <!-- Formulario para registrar nuevo dispositivo, oculto por defecto -->
            <div id="nuevo-dispositivo-form" style="display:none;">
                <h3>Nuevo Dispositivo</h3>
                <label for="nueva_marca">Marca:</label>
                <input type="text" id="nueva_marca" name="nueva_marca"><br><br>

                <label for="nuevo_modelo">Modelo:</label>
                <input type="text" id="nuevo_modelo" name="nuevo_modelo"><br><br>

                <label for="nuevo_numero_serie">Número de Serie:</label>
                <input type="text" id="nuevo_numero_serie" name="nuevo_numero_serie"><br><br>

                <button type="button" id="registrar_dispositivo">Registrar Dispositivo</button>
                
                <!-- Aquí se mostrará el mensaje de éxito o error -->
                <div id="mensaje-registro" style="color: red;"></div>
            </div>
            <br>
            <!-- Datos del cliente -->
            <label for="dni_cliente">DNI del Cliente:</label>
            <input type="number" id="dni_cliente" name="dni_cliente" required><br><br>

            <label for="nombre_cliente">Nombre del Cliente:</label>
            <input type="text" id="nombre_cliente" name="nombre_cliente" readonly><br><br>

            <input type="hidden" id="id_clientes" name="id_clientes">
            <div id="mensaje_cliente" style="color:red;"></div>
            
            <!-- Formulario para levantar cliente si no existe -->
            <div id="form-levantar-cliente" style="display:none;">
                <h3>Nuevo Cliente</h3>
                <label for="nuevo_nombre">Nombre:</label>
                <input type="text" id="nuevo_nombre" name="nuevo_nombre"><br><br>

                <label for="nuevo_apellido">Apellido:</label>
                <input type="text" id="nuevo_apellido" name="nuevo_apellido"><br><br>

                <label for="nuevo_telefono">Teléfono:</label>
                <input type="text" id="nuevo_telefono" name="nuevo_telefono"><br><br>

                <label for="nuevo_correo">Correo Electrónico:</label>
                <input type="email" id="nuevo_correo" name="nuevo_correo"><br><br>

                <label for="nueva_direccion">Dirección:</label>
                <input type="text" id="nueva_direccion" name="nueva_direccion"><br><br>

                <button type="button" id="registrar_cliente">Registrar Cliente</button>
            </div>
            <!-- Formulario para registrar un nuevo pedido de reparación -->
            <h1 class="mb-4 mt-5">Registrar Nuevo Pedido de Reparación</h1>
            <div class="row g-3">
                <div class="col-md-6 form-floating">
                    <input type="text" class="form-control" id="id_pedidos_de_reparacion" name="id_pedidos_de_reparacion" value="<?php echo $next_order_id; ?>" readonly>
                    <label for="id_pedidos_de_reparacion">ID del Pedido</label>
                </div>
                <div class="col-md-6 form-floating">
                    <input type="text" class="form-control" id="fecha_pedido" name="fecha_pedido" value="<?php echo date('Y-m-d'); ?>">
                    <label for="fecha_pedido">Fecha de Pedido</label>
                </div>
  
                <div class="col-md-6 form-floating">
                    <input type="text" class="form-control" id="numero_orden" name="numero_orden" value="<?php echo $formatted_order_number; ?>" readonly>
                    <label for="numero_orden">Número de Orden</label>
                </div>
                <div class="col-md-6 form-floating">
                    <textarea class="form-control" id="observacion" name="observacion" placeholder="Observación" required></textarea>
                    <label for="observacion">Observación</label>
                </div>

                <!-- Campo oculto por defecto -->
                <input type="hidden" id="id_tecnicos" name="id_tecnicos" value="0">
                <div class="col-md-6 form-floating">
                    <input type="date" class="form-control" id="fecha_estimada" name="fecha_estimada" value="<?php echo date('Y-m-d'); ?>">
                    <label for="fecha_estimada">Fecha Estimada</label>
                </div>

            </div>

            <div class="col-md-10">
            <button type="submit" name="registrar_pedido" class="btn btn-primary" onclick="setTimeout(() => { window.location.href = '../../pdf/facturaC.php'; }, 2000);">Registrar Pedido</button>
        </div>



            <!-- <div class="col-md-10">
                <button type="submit" name="registrar_pedido" class="btn btn-primary">Registrar Pedido</button>

            </div> -->
        </div>
    </form>
    <script>

    </script>
    <!-- Scripts colocados al final para evitar conflictos -->
    <script src="cliente.js"></script>
    <script src="eliminar.js"></script>
    <script src="dispositivos.js"></script>
</body>
</html>
