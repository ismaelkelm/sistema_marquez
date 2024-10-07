<?php
// Incluir el archivo de conexión a la base de datos
include '../../base_datos/db.php'; 
// Consulta para obtener los usuarios con el rol 3 (técnicos)
$sql = "SELECT id_usuario, nombre FROM usuario WHERE id_roles = 3";
$resultado = $conn->query($sql);

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

?>

<?php include('../../includes/header.php'); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="cliente.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <title>Registrar Pedido</title>
</head>
<body>
    <form id="" action="registrar_pedido.php" method="POST">
        <div class="container mt-5">
            <a href="../administrativo.php" class="btn btn-secondary mb-3">Volver</a>

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
            <!-- Botones para seleccionar la acción -->
            <button type="button" id="buscar-dispositivo-btn">Buscar Dispositivo Cargado</button>
            <button type="button" id="nuevo-dispositivo-btn">Cargar Nuevo Dispositivo</button>

            <!-- Datos del dispositivo -->
            <div id="buscar-dispositivo-form" style="display:none;">
                <h2 class="mt-5">Seleccionar Dispositivo</h2>

                <div id="dispositivo-contenedor">
                    <div class="dispositivo-item">
                        <label for="numero_serie_dispositivo">Número de Serie del Dispositivo:</label>
                        <button type="button" id="traer_dispositivos">¿Traer los últimos dispositivos cargados?</button>
                        <input type="text" class="numero_serie_dispositivo" name="numero_serie_dispositivo[]" required><br><br>

                        <<label for="marca_dispositivo">Marca del Dispositivo:</label>
                        <input type="text" class="marca_dispositivo" name="marca_dispositivo[]" required><br><br>

                        <label for="modelo_dispositivo">Modelo del Dispositivo:</label>
                        <input type="text" class="modelo_dispositivo" name="modelo_dispositivo[]" required><br><br>
                        
                        <input type="hidden" class="id_dispositivos" name="id_dispositivos[]">
                    </div>
                </div>

                <button type="button" id="agregar-dispositivo-btn">Agregar otro dispositivo</button>
            </div>

            <!-- Formulario para registrar nuevo dispositivo -->
            <div id="nuevo-dispositivo-form" style="display:none;">
                <h3>Nuevo Dispositivo</h3>
                <label for="nueva_marca">Marca:</label>
                <input type="text" id="nueva_marca" name="nueva_marca"><br><br>

                <label for="nuevo_modelo">Modelo:</label>
                <input type="text" id="nuevo_modelo" name="nuevo_modelo"><br><br>

                <label for="nuevo_numero_serie">Número de Serie:</label>
                <input type="text" id="nuevo_numero_serie" name="nuevo_numero_serie"><br><br>

                <button type="button" id="registrar_dispositivo">Registrar Dispositivo</button>
                <button type="button" id="sugerir_numero_serie">Sugerir Número de Serie</button>
            </div>
            <script src="dispositivos.js"></script>

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
                <input type="hidden" id="id_servicio" name="id_servicio" value="1">
                <input type="hidden" id="estado_reparacion" name="estado_reparacion" value="Pendiente">

                <!-- Contenedor que se mostrará cuando el usuario haga clic en el botón -->
                <div id="asignar_tecnico_container" style="display:none;">
                    <!-- Aquí se mostrará el select de los técnicos (usuarios con rol 3) -->
                    <?php if ($resultado->num_rows > 0): ?>
                        <label for="input_id_tecnico">Seleccionar Técnico:</label>
                        <select id="input_id_tecnico" name="input_id_tecnico" onchange="asignarTecnico(this.value)">
                            <option value="0">Selecciona un técnico</option> <!-- Opción por defecto -->
                            <?php while ($fila = $resultado->fetch_assoc()): ?>
                                <option value="<?php echo $fila['id_usuario']; ?>"><?php echo $fila['nombre']; ?></option>
                            <?php endwhile; ?>
                        </select>
                    <?php else: ?>
                        <p>No se encontraron técnicos</p>
                    <?php endif; ?>

                    <!-- Botón de Cancelar -->
                    <button type="button" onclick="ocultarCampoTecnico()">Cancelar</button>
                </div>

                <script>
                    document.getElementById('agregar-dispositivo-btn').addEventListener('click', function() {
                        var numeroSerie = document.querySelector('.numero_serie_dispositivo').value;
                        var marca = document.querySelector('.marca_dispositivo').value;
                        var modelo = document.querySelector('.modelo_dispositivo').value;

                        // Verifica que los campos estén completos antes de enviar
                        if (numeroSerie && marca && modelo) {
                            // Enviar datos a través de AJAX para insertar dispositivo
                            var xhr = new XMLHttpRequest();
                            xhr.open("POST", "insertar_dispositivo.php", true);
                            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                            
                            xhr.onreadystatechange = function () {
                                if (xhr.readyState === 4 && xhr.status === 200) {
                                    // Una vez insertado, se recibe el ID del dispositivo
                                    var idDispositivo = xhr.responseText;
                                    document.querySelector('.id_dispositivos').value = idDispositivo; // Guardar ID en campo oculto
                                }
                            };

                            // Enviar los datos del dispositivo al servidor
                            xhr.send("numero_serie=" + numeroSerie + "&marca=" + marca + "&modelo=" + modelo);
                        } else {
                            alert("Por favor, completa todos los campos del dispositivo.");
                        }
                    });
                </script>

                <div class="col-md-10">
                    <button type="submit" name="registrar_pedido" class="btn btn-primary">Registrar Pedido</button>
                </div>
            </div>
        </div>
    </form>
</body>
</html>
