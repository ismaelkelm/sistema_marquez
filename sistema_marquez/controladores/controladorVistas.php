<?php
// Controlador para gestionar vistas

function mostrarVista($vista) {
    include("vistas/{$vista}.html");
}
?>
