// Muestra el formulario al hacer clic en el botón
document.getElementById("nuevo-dispositivo-btn").addEventListener("click", function() {
    const formulario = document.getElementById("nuevo-dispositivo-form");
    formulario.style.display = "block"; // Cambia a block para mostrar el formulario
});

// Código existente para registrar dispositivo
document.getElementById("registrar_dispositivo").addEventListener("click", function() {
    // Obtener los valores del formulario
    const marca = document.getElementById("nueva_marca").value.trim();
    const modelo = document.getElementById("nuevo_modelo").value.trim();
    const numeroSerie = document.getElementById("nuevo_numero_serie").value.trim();
    const mensajeRegistro = document.getElementById("mensaje-registro");

    // Verificar que los campos no estén vacíos
    if (!marca || !modelo || !numeroSerie) {
        mensajeRegistro.textContent = "Todos los campos son obligatorios.";
        return;
    }

    // Crear el objeto de datos para enviar
    const formData = new FormData();
    formData.append('marca', marca);
    formData.append('modelo', modelo);
    formData.append('numero_serie', numeroSerie);

    // Realizar la solicitud AJAX con fetch
    fetch('../dispositivos/registrar_dispositivo.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            mensajeRegistro.style.color = 'green';
            mensajeRegistro.textContent = data.message;

            // Limpiar los campos del formulario
            document.getElementById("nueva_marca").value = '';
            document.getElementById("nuevo_modelo").value = '';
            document.getElementById("nuevo_numero_serie").value = '';              

            // Actualizar el listado de dispositivos si es necesario
            const nuevoDispositivo = document.createElement("option");
            nuevoDispositivo.value = data.id; // Asegúrate de que el ID se envíe en la respuesta
            nuevoDispositivo.text = `${marca} - ${modelo}`;
            document.getElementById("dispositivo-select").appendChild(nuevoDispositivo);

            setTimeout(() => {
                location.reload();  // Esto recarga la página actual
            }, 2000); // 2000 milisegundos = 2 segundos
        } else {
            mensajeRegistro.style.color = 'red';
            mensajeRegistro.textContent = data.message;
        }
    })
    .catch(error => {
        mensajeRegistro.style.color = 'red';
        mensajeRegistro.textContent = 'Error al procesar la solicitud: ' + error.message;
    });
});


      // Cuando se haga clic en el botón, se mostrará el select
      document.getElementById("buscar-dispositivo-btn").addEventListener("click", function() {
                    document.getElementById("dispositivo-container").style.display = "block";
                });

                // Array para almacenar los IDs de los dispositivos seleccionados
                let dispositivosSeleccionados = [];

                // Cuando se haga clic en el botón "Agregar dispositivo"
                document.getElementById("agregar-dispositivo-btn").addEventListener("click", function() {
                    // Obtener el ID del dispositivo seleccionado
                    const selectElement = document.getElementById("dispositivo-select");
                    const dispositivoId = selectElement.value;
                    const dispositivoTexto = selectElement.options[selectElement.selectedIndex].text;

                    // Verificar si el dispositivo ya está en la lista
                    if (!dispositivosSeleccionados.includes(dispositivoId)) {
                        // Agregar el ID al array
                        dispositivosSeleccionados.push(dispositivoId);

                        // Crear un nuevo elemento de lista con el dispositivo seleccionado y un botón para eliminarlo
                        const li = document.createElement("li");
                        li.textContent = dispositivoTexto;
                        const removeButton = document.createElement("button");
                        removeButton.textContent = "Quitar";
                        removeButton.style.marginLeft = "10px";

                        // Función para quitar el dispositivo de la lista y del array
                        removeButton.addEventListener("click", function() {
                            // Eliminar el ID del array de dispositivos seleccionados
                            dispositivosSeleccionados = dispositivosSeleccionados.filter(id => id !== dispositivoId);

                            // Actualizar el campo oculto con los IDs seleccionados
                            document.getElementById("dispositivos-seleccionados").value = dispositivosSeleccionados.join(",");

                            // Eliminar el elemento <li> de la lista
                            li.remove();
                        });

                        // Añadir el botón de eliminar al <li>
                        li.appendChild(removeButton);

                        // Añadir el <li> al <ul> de la lista
                        document.getElementById("dispositivo-list").appendChild(li);
                    }

                    // Actualizar el campo oculto con los IDs seleccionados (separados por comas)
                    document.getElementById("dispositivos-seleccionados").value = dispositivosSeleccionados.join(",");
                });
