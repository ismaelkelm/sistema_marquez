document.addEventListener('DOMContentLoaded', function () {
    const buscarDispositivoBtn = document.getElementById('buscar-dispositivo-btn');
    const nuevoDispositivoBtn = document.getElementById('nuevo-dispositivo-btn');
    const buscarDispositivoForm = document.getElementById('buscar-dispositivo-form');
    const nuevoDispositivoForm = document.getElementById('nuevo-dispositivo-form');
    const agregarDispositivoBtn = document.getElementById('agregar-dispositivo-btn');
    const dispositivoContenedor = document.getElementById('dispositivo-contenedor');
    const traerDispositivosBtn = document.getElementById('traer_dispositivos');
    const registrarDispositivoBtn = document.getElementById('registrar_dispositivo');

    // Mostrar el formulario correspondiente
    buscarDispositivoBtn.addEventListener('click', function () {
        buscarDispositivoForm.style.display = 'block';
        nuevoDispositivoForm.style.display = 'none';
    });

    nuevoDispositivoBtn.addEventListener('click', function () {
        nuevoDispositivoForm.style.display = 'block';
        buscarDispositivoForm.style.display = 'none';
    });

    // Agregar otro dispositivo
    agregarDispositivoBtn.addEventListener('click', function () {
        // Clonamos el primer conjunto de campos de dispositivo
        const dispositivoItem = document.querySelector('.dispositivo-item');
        const nuevoDispositivo = dispositivoItem.cloneNode(true);

        // Limpiamos los valores de los inputs en el nuevo clon
        nuevoDispositivo.querySelectorAll('input').forEach(input => {
            input.value = '';
        });

        // Agregamos el nuevo clon al contenedor
        dispositivoContenedor.appendChild(nuevoDispositivo);
    });

    // Función para cargar los dispositivos recientes
    traerDispositivosBtn.addEventListener('click', function () {
        // Realizar la petición AJAX al servidor
        fetch('../dispositivos/dispositivos_actuales.php')
            .then(response => response.json())
            .then(data => {
                if (data.length > 0) {
                    // Limpiar los dispositivos anteriores
                    dispositivoContenedor.innerHTML = '';

                    // Recorrer los dispositivos obtenidos y crear nuevos campos
                    data.forEach(dispositivo => {
                        const dispositivoItem = document.createElement('div');
                        dispositivoItem.classList.add('dispositivo-item');

                        dispositivoItem.innerHTML = `
                            <label for="numero_serie_dispositivo">Número de Serie del Dispositivo:</label>
                            <input type="text" class="numero_serie_dispositivo" name="numero_serie_dispositivo[]" value="${dispositivo.numero_de_serie}" readonly><br><br>

                            <label for="marca_dispositivo">Marca del Dispositivo:</label>
                            <input type="text" class="marca_dispositivo" name="marca_dispositivo[]" value="${dispositivo.marca}" readonly><br><br>

                            <label for="modelo_dispositivo">Modelo del Dispositivo:</label>
                            <input type="text" class="modelo_dispositivo" name="modelo_dispositivo[]" value="${dispositivo.modelo}" readonly><br><br>

                            <input type="hidden" class="id_dispositivos" name="id_dispositivos[]" value="${dispositivo.id_dispositivos}">
                        `;

                        // Añadir el dispositivo al contenedor
                        dispositivoContenedor.appendChild(dispositivoItem);
                    });
                } else {
                    alert('No se encontraron dispositivos recientes.');
                }
            })
            .catch(error => {
                console.error('Error al obtener los dispositivos:', error);
                alert('Hubo un error al traer los dispositivos.');
            });
    });

    // Registrar nuevo dispositivo
    registrarDispositivoBtn.addEventListener('click', function () {
        // Capturar los datos del formulario
        const nueva_marca = document.getElementById('nueva_marca').value.trim();
        const nuevo_modelo = document.getElementById('nuevo_modelo').value.trim();
        const nuevo_numero_serie = document.getElementById('nuevo_numero_serie').value.trim();

        // Validar que los campos no estén vacíos
        if (!nueva_marca || !nuevo_modelo || !nuevo_numero_serie) {
            alert("Todos los campos son obligatorios.");
            return;
        }

        // Crear el objeto de datos a enviar
        const formData = new FormData();
        formData.append('marca', nueva_marca);
        formData.append('modelo', nuevo_modelo);
        formData.append('numero_serie', nuevo_numero_serie);

        // Enviar los datos mediante AJAX
        fetch('../dispositivos/registrar_dispositivo.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json()) // Asegurarse de que la respuesta sea JSON
        .then(data => {
            if (data.status === 'success') {
                alert(data.message);  // Mostrar el mensaje de éxito
                // Limpiar los campos del formulario
                document.getElementById('nueva_marca').value = '';
                document.getElementById('nuevo_modelo').value = '';
                document.getElementById('nuevo_numero_serie').value = '';
            } else {
                alert('Error: ' + data.message);  // Mostrar el mensaje de error
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Hubo un problema al registrar el dispositivo.');
        });
    });
});
