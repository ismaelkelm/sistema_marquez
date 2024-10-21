<?php
// Configuración de la base de datos: se establecen los parámetros para la conexión a la base de datos MySQL.
$servername = "arielon23.duckdns.org:3306"; // Nombre del servidor donde se aloja la base de datos.
$username = "marquez";        // Nombre de usuario para acceder a la base de datos.
$password = "marquez2024";      // Contraseña del usuario para acceder a la base de datos.
$dbname = "pruebas_marquez2"; // Nombre de la base   http://arielon23.duckdns.org:10000/

// Crear conexión: se crea un nuevo objeto mysqli para conectarse a la base de datos utilizando los parámetros definidos.
$conn = new mysqli($servername, $username, $password, $dbname);
//echo"conectado";
// Verificar conexión: se comprueba si la conexión fue exitosa.
if ($conn->connect_error) { // Si hay un error en la conexión...
    die("Conexión fallida: " . $conn->connect_error); // ...se muestra un mensaje de error y se termina el script.
}
// Si la conexión es exitosa, el script continúa ejecutándose sin mostrar ningún mensaje de error.



date_default_timezone_set('America/Argentina/Buenos_Aires'); // Cambia a tu zona horaria
$fecha_hora_actual = date('Y-m-d H:i:s'); // Obtener fecha y hora actual
?>      