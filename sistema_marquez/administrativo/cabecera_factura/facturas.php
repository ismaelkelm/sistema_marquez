<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cargar Factura</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Carga de Factura</h2>

        <!-- Formulario de cabecera de la factura -->
        <form action="procesar_factura.php" method="POST">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="cliente">Cliente</label>
                        <select name="id_cliente" class="form-control" required>
                            <option value="">Selecciona el cliente</option>
                            <!-- Opciones dinámicas desde la base de datos -->
                            <?php
                            // Consulta para traer clientes
                            $clientes = $db->query("SELECT id_clientes, nombre, apellido FROM clientes");
                            while ($row = $clientes->fetch_assoc()) {
                                echo "<option value='{$row['id_clientes']}'>{$row['nombre']} {$row['apellido']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="fecha_factura">Fecha de Factura</label>
                        <input type="date" name="fecha_factura" class="form-control" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="tipo_comprobante">Tipo de Comprobante</label>
                        <select name="id_tipo_comprobante" class="form-control" required>
                            <option value="">Selecciona el tipo de comprobante</option>
                            <?php
                            // Consulta para traer tipos de comprobante
                            $tipos_comprobante = $db->query("SELECT id_tipo_comprobante, tipo_comprobante FROM tipo_comprobante");
                            while ($row = $tipos_comprobante->fetch_assoc()) {
                                echo "<option value='{$row['id_tipo_comprobante']}'>{$row['tipo_comprobante']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="numero_comprobante">Número de Comprobante</label>
                        <input type="text" name="numero_comprobante" class="form-control" value="COMP<?php echo str_pad($ultimoComprobante + 1, 3, '0', STR_PAD_LEFT); ?>" readonly>
                    </div>
                </div>
            </div>

            <!-- Detalle de productos/servicios -->
            <h4 class="mt-4">Agregar Productos/Servicios</h4>
            <div id="detalles_factura">
                <div class="form-row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="producto_servicio[]">Producto/Servicio</label>
                            <select name="id_producto_servicio[]" class="form-control" required>
                                <option value="">Selecciona el producto/servicio</option>
                                <?php
                                // Consulta para traer productos/servicios
                                $productos_servicios = $db->query("SELECT id_producto, descripcion FROM productos");
                                while ($row = $productos_servicios->fetch_assoc()) {
                                    echo "<option value='{$row['id_producto']}'>{$row['descripcion']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="cantidad[]">Cantidad</label>
                            <input type="number" name="cantidad[]" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="precio[]">Precio Unitario</label>
                            <input type="text" name="precio[]" class="form-control" required>
                        </div>
                    </div>
                </div>
            </div>

            <button type="button" id="agregar_producto" class="btn btn-secondary mt-2">Agregar otro producto/servicio</button>

            <!-- Botones de acción -->
            <div class="form-group mt-4">
                <button type="submit" class="btn btn-success">Guardar Factura</button>
                <a href="administrativo.php" class="btn btn-danger">Cancelar</a>
            </div>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#agregar_producto').click(function() {
                let nuevoDetalle = `<div class="form-row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="producto_servicio[]">Producto/Servicio</label>
                                                <select name="id_producto_servicio[]" class="form-control" required>
                                                    <option value="">Selecciona el producto/servicio</option>
                                                    <?php
                                                    $productos_servicios = $db->query("SELECT id_producto, descripcion FROM productos");
                                                    while ($row = $productos_servicios->fetch_assoc()) {
                                                        echo "<option value='{$row['id_producto']}'>{$row['descripcion']}</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="cantidad[]">Cantidad</label>
                                                <input type="number" name="cantidad[]" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="precio[]">Precio Unitario</label>
                                                <input type="text" name="precio[]" class="form-control" required>
                                            </div>
                                        </div>
                                    </div>`;
                $('#detalles_factura').append(nuevoDetalle);
            });
        });
    </script>
</body>
</html>
