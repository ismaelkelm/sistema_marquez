<?php
include '../../base_datos/db.php'; // Incluye el archivo de conexión

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $marca = $_POST['marca'];
    $modelo = $_POST['modelo'];
    $numero_de_serie = $_POST['numero_de_serie'];

    $query = "INSERT INTO dispositivos (marca, modelo, numero_de_serie) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'sss', $marca, $modelo, $numero_de_serie);

    if (mysqli_stmt_execute($stmt)) {
        // Mostrar mensaje de éxito y redirigir después de 2 segundos
        echo "<script>
                alert('Dispositivo registrado con éxito');
                setTimeout(function() {
                    window.location.href = 'http://sistema.local.com/administrativo/pedidos_de_reparacion/registrar_pedido.php';
                }, 1000);
              </script>";
        exit; // Detener el script después de la redirección
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<?php include('../../includes/header.php'); ?>

<div class="container mt-5">
    <h1>Agregar Dispositivo</h1>
    <div class="d-flex justify-content-start mb-3">
        <button type="submit" form="dispositivoForm" class="btn btn-success mr-2">Guardar</button> <!-- Botón "Guardar" -->
        <a href="http://sistema.local.com/administrativo/pedidos_de_reparacion/registrar_pedido.php" class="btn btn-secondary">Volver</a> <!-- Botón "Volver" -->
    </div>

    <form id="dispositivoForm" method="POST">
        <div class="form-group">
            <label>Marca</label>
            <input type="text" name="marca" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Modelo</label>
            <input type="text" name="modelo" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Número de Serie</label>
            <input type="text" name="numero_de_serie" class="form-control" required>
        </div>
    </form>
</div>

<?php include('../../includes/footer.php'); ?>
