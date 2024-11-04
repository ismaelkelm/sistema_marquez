<?php
include '../../base_datos/db.php'; // Conexión a la base de datos

// Consultas para obtener las opciones de proveedores, tipo de pago, tipo de comprobante, y accesorios
$proveedores = $conn->query("SELECT id_proveedores, nombre FROM proveedores");
$tipos_pago = $conn->query("SELECT id_tipo_de_pago, descripcion_de_pago FROM tipo_de_pago");
$tipos_comprobante = $conn->query("SELECT id_tipo_comprobante, tipo_comprobante FROM tipo_comprobante");
$accesorios = $conn->query("SELECT id_accesorios_y_componentes, nombre, stock FROM accesorios_y_componentes");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Compra</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        h2 {
            color: #333;
            text-align: center;
        }

        a.btn {
            display: inline-block;
            padding: 10px 20px;
            margin-bottom: 20px;
            background-color: #6c757d;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        a.btn:hover {
            background-color: #5a6268;
        }

        form {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: 0 auto;
        }

        label {
            display: block;
            margin: 10px 0 5px;
            color: #555;
        }

        select, input[type="date"], input[type="number"], input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        button[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button[type="submit"]:hover {
            background-color: #0056b3;
        }

        .button {
            background-color: #007bff; /* Color de fondo */
            color: white; /* Color del texto */
            border: none; /* Sin borde */
            padding: 10px 20px; /* Espaciado interno */
            border-radius: 5px; /* Bordes redondeados */
            cursor: pointer; /* Cursor de puntero */
            font-size: 16px; /* Tamaño de fuente */
            transition: background-color 0.3s, transform 0.2s; /* Transiciones suaves */
        }

        .button:hover {
            background-color: #0056b3; /* Color de fondo al pasar el ratón */
            transform: scale(1.05); /* Aumenta ligeramente el tamaño */
        }

        .button:active {
            transform: scale(0.95); /* Reduce ligeramente el tamaño al hacer clic */
        }
        
        .mt-3 {
            margin-top: 1rem; /* Margen superior */
        }

        .button-back {
            text-decoration: none; /* Sin subrayado */
        }
    </style>
</head>
<body>
<?php echo '<button onclick="location.href=\'compra.html\'" class="mt-3 button button-back">Volver</button>';?>
 
<h2>Registrar Compra</h2>
<a href="../../administrativo/accesorios_componentes/index.php" class="btn">Cargar nuevo accesorio??</a>

<form action="../../facturacion/compra/procesarcompra.php" method="POST">
    <label for="proveedor">Proveedor:</label>
    <select name="id_proveedores" required>
        <?php while ($row = $proveedores->fetch_assoc()) {
            echo "<option value='{$row['id_proveedores']}'>{$row['nombre']}</option>";
        } ?>
    </select>

    <label for="tipo_pago">Tipo de Pago:</label>
    <select name="id_tipo_pago" required>
        <?php while ($row = $tipos_pago->fetch_assoc()) {
            echo "<option value='{$row['id_tipo_pago']}'>{$row['descripcion_de_pago']}</option>";
        } ?>
    </select>

    <label for="tipo_comprobante">Tipo de Comprobante:</label>
    <select name="tipo_comprobante" required>
        <?php while ($row = $tipos_comprobante->fetch_assoc()) {
            echo "<option value='{$row['id_tipo_comprobante']}'>{$row['tipo_comprobante']}</option>";
        } ?>
    </select>

    <label for="producto">Producto:</label>
    <select name="id_accesorios_y_componentes" required>
        <?php while ($row = $accesorios->fetch_assoc()) {
            echo "<option value='{$row['id_accesorios_y_componentes']}'>{$row['nombre']} (Stock actual: {$row['stock']})</option>";
        } ?>
    </select>

    <label for="fecha_de_compra">Fecha de Compra:</label>
    <input type="date" name="fecha_de_compra" required>

    <label for="cantidad_comprada">Cantidad Comprada:</label>
    <input type="number" name="cantidad_comprada" min="1" required>

    <label for="num_de_comprobante">Número de Comprobante:</label>
    <input type="text" name="num_de_comprobante" required>

    <label for="precio_por_unidad">Precio por Unidad:</label>
    <input type="number" name="precio_por_unidad" step="0.01" min="0" required>

    <label for="total_pagado">Total Pagado:</label>
    <input type="number" name="total_pagado" step="0.01" min="0" required>

    <button type="submit">Registrar Compra</button>
</form>

<?php $conn->close(); ?>

</body>
</html>
