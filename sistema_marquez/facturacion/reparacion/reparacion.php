
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calcular IVA</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Asegúrate de tener jQuery cargado -->
    <script src="reparacion.js"></script>
</head>
<body>
    <h1>Calcular IVA según tipo de Factura</h1>

    <form id="ivaForm">
        <label for="total">Ingrese el precio (total):</label>
        <input type="number" name="total" id="total" step="0.01" required>

        <label for="id_tipo_comprobante">Seleccione el tipo de comprobante:</label>
        <select name="id_tipo_comprobante" id="id_tipo_comprobante">
            <option value="1">Factura B</option>
            <option value="2">Factura A</option>
            <!-- Puedes agregar más tipos según los tengas en tu base de datos -->
        </select>

        <p id="resultado"></p>
    </form>

    
</body>
</html>
