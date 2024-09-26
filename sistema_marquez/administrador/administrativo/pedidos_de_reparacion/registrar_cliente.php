<?php
// Incluir el archivo de conexión a la base de datos
include '../../base_datos/db.php'; // Ajusta la ruta según la ubicación del archivo

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $telefono = $_POST['telefono'];
    $correo_electronico = $_POST['correo_electronico'];
    $direccion = $_POST['direccion'];
    $dni = $_POST['dni'];

    $query = "INSERT INTO clientes (nombre, apellido, telefono, correo_electronico, direccion, dni) 
              VALUES ('$nombre', '$apellido', '$telefono', '$correo_electronico', '$direccion', '$dni')";

    if (mysqli_query($conn, $query)) {
        // Mostrar mensaje de éxito y redirigir después de 2 segundos
        echo "<script>
                alert('Cliente registrado con éxito');
                setTimeout(function() {
                    window.location.href = 'http://sistema.local.com/administrativo/pedidos_de_reparacion/registrar_pedido.php';
                }, 1000);
              </script>";
        exit; // Asegurarse de que el script se detenga
    } else {
        echo "Error: " . $query . "<br>" . mysqli_error($conn);
    }
}
?>

<?php include('../../includes/header.php'); ?>

<div class="container mt-5">
    <h1 class="mb-4">Agregar Cliente</h1>
    
    <a href="http://sistema.local.com/administrativo/pedidos_de_reparacion/registrar_pedido.php" class="btn btn-secondary mb-3">Volver</a>
    <button type="submit" class="btn btn-primary mb-3" form="clienteForm">Guardar</button> <!-- Botón "Guardar" arriba -->

    <form id="clienteForm" method="post" action="">
        <div class="form-group">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="apellido">Apellido:</label>
            <input type="text" id="apellido" name="apellido" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="telefono">Teléfono:</label>
            <input type="text" id="telefono" name="telefono" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="correo_electronico">Correo Electrónico:</label>
            <input type="email" id="correo_electronico" name="correo_electronico" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="direccion">Dirección:</label>
            <textarea id="direccion" name="direccion" class="form-control" required></textarea>
        </div>
        <div class="form-group">
            <label for="dni">DNI:</label>
            <input type="text" id="dni" name="dni" class="form-control" required>
        </div>
    </form>
</div>

<?php include('../../includes/footer.php'); ?>

<?php
// Cerrar la conexión a la base de datos
mysqli_close($conn);
?>
