<?php
// Configuración de la base de datos: se establecen los parámetros para la conexión a la base de datos MySQL.
$servername = "localhost"; // Nombre del servidor donde se aloja la base de datos.
$username = "root";        // Nombre de usuario para acceder a la base de datos.
$password = "";      // Contraseña del usuario para acceder a la base de datos.
$dbname = "pruebas_marquez2"; // Nombre de la base de datos a la cual se quiere conectar.

// Crear conexión: se crea un nuevo objeto mysqli para conectarse a la base de datos utilizando los parámetros definidos.
$conn = new mysqli($servername, $username, $password, $dbname);
//echo"conectado";
// Verificar conexión: se comprueba si la conexión fue exitosa.
if ($conn->connect_error) { // Si hay un error en la conexión...
    die("Conexión fallida: " . $conn->connect_error); // ...se muestra un mensaje de error y se termina el script.
}
// Si la conexión es exitosa, el script continúa ejecutándose sin mostrar ningún mensaje de error.
?>