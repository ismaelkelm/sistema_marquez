// Función para agregar un nuevo accesorio al formulario
$('#add-accesorio').on('click', function () {
    var newAccesorio = $('.detalle-accesorio:first').clone();  // Clonar el primer accesorio
    newAccesorio.find('input').val(''); // Limpiar los valores de cantidad del nuevo campo
    $('#detalles-accesorios').append(newAccesorio); // Agregar el nuevo accesorio al formulario
});

// Manejar el cambio en el selector de accesorios
$(document).on('change', 'select[name="id_accesorios_y_componentes[]"]', function () {
    // Aquí podrías manejar alguna lógica adicional si es necesario al seleccionar el accesorio.
    // En este caso, no hay precios involucrados, por lo que no hacemos nada.
});

// Manejar el cambio en la cantidad (opcional, solo si necesitas hacer algo al cambiar la cantidad)
$(document).on('input', 'input[name="cantidad_venta[]"]', function () {
    // No necesitas actualizar ningún subtotal, pero puedes agregar validaciones si es necesario.
});
