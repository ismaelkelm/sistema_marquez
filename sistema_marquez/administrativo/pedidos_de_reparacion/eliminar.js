document.addEventListener('DOMContentLoaded', function () {
    const agregarDispositivoBtn = document.getElementById('agregar-dispositivo-btn');
    const dispositivoContenedor = document.getElementById('dispositivo-contenedor');

    // Función para eliminar un dispositivo
    function eliminarDispositivo(event) {
        const dispositivoItem = event.target.closest('.dispositivo-item'); // Encontrar el elemento contenedor del dispositivo
        dispositivoItem.remove(); // Eliminar el dispositivo del DOM
    }

    // Agregar la funcionalidad de eliminar a los dispositivos iniciales
    dispositivoContenedor.querySelectorAll('.eliminar-dispositivo-btn').forEach(btn => {
        btn.addEventListener('click', eliminarDispositivo);
    });

    // Agregar otro dispositivo (nuevo)
    agregarDispositivoBtn.addEventListener('click', function () {
        // Clonamos el primer conjunto de campos de dispositivo
        const dispositivoItem = document.querySelector('.dispositivo-item');
        const nuevoDispositivo = dispositivoItem.cloneNode(true);

        // Limpiamos los valores de los inputs en el nuevo clon
        nuevoDispositivo.querySelectorAll('input').forEach(input => {
            input.value = '';
        });

        // Añadir el nuevo dispositivo al contenedor
        dispositivoContenedor.appendChild(nuevoDispositivo);

        // Agregar la funcionalidad de eliminar al nuevo dispositivo
        nuevoDispositivo.querySelector('.eliminar-dispositivo-btn').addEventListener('click', eliminarDispositivo);
    });
});
