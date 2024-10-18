<?php
require_once '../../base_datos/db.php';


// Verificar si el usuario ha iniciado sesión y obtener el id desde la sesión
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || !in_array($_SESSION['role'], [1, 2])) {
    header("Location: ../login/login.php");
    exit;
}

// Obtener el id_usuario desde la sesión
$id_usuario = $_SESSION['user_id'];
$id_operacion = 1;

$query_tipo_pago = "SELECT id_tipo_de_pago, descripcion_de_pago FROM tipo_de_pago";
$result_tipo_pago = mysqli_query($conn, $query_tipo_pago);

$query_tipo_comprobante = "SELECT id_tipo_comprobante, tipo_comprobante FROM tipo_comprobante";
$result_tipo_comprobante = mysqli_query($conn, $query_tipo_comprobante);

$query_accesorios_componentes = "SELECT id_accesorios_y_componentes, nombre, precio FROM accesorios_y_componentes";
$result_accesorios_componentes = mysqli_query($conn, $query_accesorios_componentes);
?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Factura</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="venta.js"></script>
    <style>
        body {
            background-color: #f0f4f8;
            font-family: 'Arial', sans-serif;
        }

        h2 {
            margin-bottom: 20px;
            color: #2c3e50;
            font-size: 2rem;
            text-align: center;
        }

        .form-section {
            background-color: #ffffff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .btn-custom {
            background-color: #3498db;
            color: white;
            transition: background-color 0.3s, transform 0.2s;
        }

        .btn-custom:hover {
            background-color: #2980b9;
            transform: translateY(-2px);
        }

        .btn-back {
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
            color: #34495e;
        }

        .text-danger {
            font-size: 0.9rem;
            margin-top: 5px;
        }

        .form-control {
            border: 1px solid #ced4da;
            border-radius: 4px;
            transition: border-color 0.3s;
        }

        .form-control:focus {
            border-color: #3498db;
            box-shadow: 0 0 5px rgba(52, 152, 219, 0.5);
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .rounded-input {
            border-radius: 4px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        /* Estilos específicos para los campos de resultado */
        #iva_resultados, #total_resultados {
            background-color: #eaf6fd;
            padding: 10px;
            border: 1px solid #b0e0f0;
            border-radius: 4px;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <!-- Botón Volver Atrás -->
        <a href="javascript:history.back()" class="btn btn-secondary btn-back">
            <i class="fas fa-arrow-left"></i> Volver Atrás
        </a>
        
        <h2>Registrar Factura</h2>
        <form id="form-factura" action="../cargaFactura.php" method="POST">
            <div class="form-section">
                <h3>Cabecera de Factura</h3>

                <div class="form-group">
                    <label for="dni_cliente">DNI del Cliente:</label>
                    <input type="number" class="form-control" id="dni_cliente" name="dni_cliente" required>
                    <div id="mensaje_cliente" class="text-danger"></div>
                </div>

                <div class="form-group">
                    <label for="nombre_cliente">Nombre del Cliente:</label>
                    <input type="text" class="form-control" id="nombre_cliente" name="nombre_cliente" readonly>
                    <input type="hidden" id="id_clientes" name="id_clientes">
                </div>

                <div id="form-levantar-cliente" style="display:none;">
                    <h3>Nuevo Cliente</h3>
                    <div class="form-group">
                        <label for="nuevo_nombre">Nombre:</label>
                        <input type="text" class="form-control" id="nuevo_nombre" name="nuevo_nombre">
                    </div>
                    <div class="form-group">
                        <label for="nuevo_apellido">Apellido:</label>
                        <input type="text" class="form-control" id="nuevo_apellido" name="nuevo_apellido">
                    </div>
                    <div class="form-group">
                        <label for="nuevo_telefono">Teléfono:</label>
                        <input type="text" class="form-control" id="nuevo_telefono" name="nuevo_telefono">
                    </div>
                    <div class="form-group">
                        <label for="nuevo_correo">Correo Electrónico:</label>
                        <input type="email" class="form-control" id="nuevo_correo" name="nuevo_correo">
                    </div>
                    <div class="form-group">
                        <label for="nueva_direccion">Dirección:</label>
                        <input type="text" class="form-control" id="nueva_direccion" name="nueva_direccion">
                    </div>
                    <button type="button" class="btn btn-custom" id="registrar_cliente">Registrar Cliente</button>
                </div>

                <div class="form-group">
                    <label for="fecha_factura">Fecha de Factura:</label>
                    <input type="text" class="form-control" id="fecha_factura" name="fecha_factura" value="<?php echo date('Y-m-d'); ?>" readonly>
                </div>

                <div class="form-group">
                    <label for="id_tipo_de_pago">Tipo de Pago:</label>
                    <select class="form-control" id="id_tipo_de_pago" name="id_tipo_de_pago" required>
                        <?php while ($row_pago = mysqli_fetch_assoc($result_tipo_pago)) { ?>
                            <option value="<?php echo $row_pago['id_tipo_de_pago']; ?>">
                                <?php echo $row_pago['descripcion_de_pago']; ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <input type="hidden" id="id_usuario" name="id_usuario" value="<?php echo $id_usuario; ?>">
                <input type="hidden" id="id_operacion" name="id_operacion" value="<?php echo $id_operacion; ?>">
                <input type="hidden" id="id_servicio" name="id_servicio" value="0">
            </div>

            <div class="form-section">
                <h3>Detalle de Factura</h3>
                <div id="detalles-accesorios">
                    <div class="detalle-accesorio">
                        <div class="form-group">
                            <label for="cantidad_venta[]">Cantidad Vendida:</label>
                            <input type="number" class="form-control" name="cantidad_venta[]" required>
                        </div>
                        <div class="form-group">
                            <label for="precio_unitario_V[]">Precio Unitario:</label>
                            <input type="number" step="0.01" class="form-control" name="precio_unitario_V[]" required readonly>
                        </div>
                        <div class="form-group">
                            <label for="subtotal[]">Subtotal:</label>
                            <input type="number" step="0.01" class="form-control" name="subtotal[]" readonly>
                        </div>
                        <div class="form-group">
                            <label for="id_accesorios_y_componentes[]">Accesorios o Componentes:</label>
                            <select class="form-control" name="id_accesorios_y_componentes[]" required>
                                <?php while ($row_accesorio = mysqli_fetch_assoc($result_accesorios_componentes)) { ?>
                                    <option value="<?php echo $row_accesorio['id_accesorios_y_componentes']; ?>">
                                        <?php echo $row_accesorio['nombre']; ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-custom" id="add-accesorio">Agregar otro accesorio</button>
            </div>

            <div class="form-section">
                <h3>Resumen de Factura</h3>
                <div class="form-group">
                    <label for="subtotal_factura">Subtotal:</label>
                    <input type="number" step="0.01" class="form-control" id="subtotal_factura" name="subtotal_factura" required readonly>
                </div>
                <div class="form-group">
                    <label for="id_tipo_comprobante">Tipo de Comprobante:</label>
                    <select class="form-control" id="id_tipo_comprobante" name="id_tipo_comprobante" required>
                        <?php while ($row_comprobante = mysqli_fetch_assoc($result_tipo_comprobante)) { ?>
                            <option value="<?php echo $row_comprobante['id_tipo_comprobante']; ?>">
                                <?php echo $row_comprobante['tipo_comprobante']; ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <div id="resultado" style="margin-top: 20px;"></div>
                <div class="form-group" style="display: none;" id="iva_resultados">
                    <label for="iva_resultado" id="iva_label">IVA (21%):</label>
                    <input type="text" id="iva_resultado" name="iva_resultado" value="" readonly>
                </div>
                <div class="form-group" style="display: none;" id="total_resultados">
                    <label for="total" id="total_label">Total:</label>
                    <input type="text" id="total" name="total" value="" readonly>
                </div>

                <button type="submit" class="btn btn-custom" id="submit_button" disabled>Registrar Factura</button>
            </div>
        </form>
    </div>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
// Cerrar la conexión a la base de datos
mysqli_close($conn);
?>
