<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro y Verificación de Dispositivos</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</head>
<body>
<!-- Botones para seleccionar la acción -->
<button type="button" id="buscar-dispositivo-btn">Buscar Dispositivo Cargado</button>
<button type="button" id="nuevo-dispositivo-btn">Cargar Nuevo Dispositivo</button>

<!-- Datos del dispositivo -->
<div id="buscar-dispositivo-form" style="display:none;">
    <h2 class="mt-5">Seleccionar Dispositivo</h2>

    <div id="dispositivo-contenedor">
        <div class="dispositivo-item">
            <label for="numero_serie_dispositivo">Número de Serie del Dispositivo:</label>
            <button type="button" id="traer_dispositivos">¿Traer los últimos dispositivos cargados?</button>
            <input type="text" class="numero_serie_dispositivo" name="numero_serie_dispositivo[]" required><br><br>

            <label for="marca_dispositivo">Marca del Dispositivo:</label>
            <input type="text" class="marca_dispositivo" name="marca_dispositivo[]" readonly><br><br>

            <label for="modelo_dispositivo">Modelo del Dispositivo:</label>
            <input type="text" class="modelo_dispositivo" name="modelo_dispositivo[]" readonly><br><br>

            <input type="hidden" class="id_dispositivos" name="id_dispositivos[]">
        </div>
    </div>

    <button type="button" id="agregar-dispositivo-btn">Agregar otro dispositivo</button>
</div>

<!-- Formulario para registrar nuevo dispositivo -->
<div id="nuevo-dispositivo-form" style="display:none;">
    <h3>Nuevo Dispositivo</h3>
    <label for="nueva_marca">Marca:</label>
    <input type="text" id="nueva_marca" name="nueva_marca"><br><br>

    <label for="nuevo_modelo">Modelo:</label>
    <input type="text" id="nuevo_modelo" name="nuevo_modelo"><br><br>

    <label for="nuevo_numero_serie">Número de Serie:</label>
    <input type="text" id="nuevo_numero_serie" name="nuevo_numero_serie"><br><br>

    <button type="button" id="registrar_dispositivo">Registrar Dispositivo</button>
    <button type="button" id="sugerir_numero_serie">Sugerir Número de Serie</button>
</div>
<script src="copia.js"></script>

</body>


<script>
                    // Función que muestra el campo para seleccionar el técnico
                    function mostrarCampoTecnico() {
                        var contenedor = document.getElementById('asignar_tecnico_container');
                        contenedor.style.display = 'block'; // Muestra el contenedor del ID de técnico
                    }

                    // Función que oculta el campo para seleccionar el técnico
                    function ocultarCampoTecnico() {
                        var contenedor = document.getElementById('asignar_tecnico_container');
                        contenedor.style.display = 'none'; // Oculta el contenedor del ID de técnico
                    }

                    // Función para asignar el técnico seleccionado
                    function asignarTecnico(tecnicoId) {
                        document.getElementById('id_tecnicos').value = tecnicoId; // Actualiza el campo oculto con el ID del técnico seleccionado
                         // Cambia el estado de la reparación a "En progreso" si se asigna un técnico
                        if (tecnicoId !== '0') {
                            document.getElementById('estado_reparacion').value = 'En progreso';
                        } else {
                            document.getElementById('estado_reparacion').value = 'Pendiente'; // Reestablece a "Pendiente" si se selecciona "Ninguno"
                        }
                    }
                </script>

                <!-- Botón que pregunta si quieres asignar técnico -->
                <button type="button" onclick="mostrarCampoTecnico()">¿Asignar Técnico?</button>

</html>
