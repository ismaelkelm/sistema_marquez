<form action="../pdf/facturaAA.PHP" method="post">
    <label for="cliente">Selecciona un cliente:</label>
    <select name="cliente_id" id="cliente">
        <?php
        // Conexión a la base de datos
        $conn = mysqli_connect("localhost", "root", "543210", "pruebas_marquez2");
        $query_clientes = "SELECT id_cliente, nombre_cliente FROM clientes";
        $result_clientes = mysqli_query($conn, $query_clientes);

        // Iterar sobre los resultados y crear opciones en el select
        while ($cliente = mysqli_fetch_assoc($result_clientes)) {
            echo "<option value='" . $cliente['id_cliente'] . "'>" . $cliente['nombre_cliente'] . "</option>";
        }
        ?>
    </select>
    <button type="submit">Generar Factura</button>
</form>
