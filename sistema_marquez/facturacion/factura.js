$(document).ready(function () {
    $('#add-accesorio').on('click', function () {
        var newAccesorio = $('.detalle-accesorio:first').clone();
        newAccesorio.find('input').val(''); // Limpiar valores del nuevo campo
        newAccesorio.find('input[name="precio_unitario_V[]"]').val(''); // Limpiar precio unitario
        $('#detalles-accesorios').append(newAccesorio); // Agregar nuevo detalle
    });

    // Manejar el cambio en el selector de accesorios
    $(document).on('change', 'select[name="id_accesorios_y_componentes[]"]', function () {
        var id_accesorio = $(this).val();
        var $detalleAccesorio = $(this).closest('.detalle-accesorio');

        $.ajax({
            url: '../accesorios_componentes/get_precio.php',
            method: 'POST',
            data: { id: id_accesorio },
            dataType: 'json',
            success: function (response) {
                if (response.status === 'success') {
                    var precioUnitario = response.precio;
                    $detalleAccesorio.find('input[name="precio_unitario_V[]"]').val(precioUnitario); // Mostrar el precio unitario
                    actualizarSubtotal($detalleAccesorio); // Actualizar subtotal
                } else {
                    alert('Error al obtener el precio del accesorio.');
                }
            }
        });
    });

    // Manejar el cambio en la cantidad
    $(document).on('input', 'input[name="cantidad_venta[]"]', function () {
        var $detalleAccesorio = $(this).closest('.detalle-accesorio');
        actualizarSubtotal($detalleAccesorio); // Actualizar subtotal
    });

    // Función para actualizar el subtotal
    function actualizarSubtotal($detalleAccesorio) {
        var cantidad = $detalleAccesorio.find('input[name="cantidad_venta[]"]').val();
        var precioUnitario = $detalleAccesorio.find('input[name="precio_unitario_V[]"]').val();

        var subtotal = 0;
        if (cantidad && precioUnitario) {
            subtotal = precioUnitario * cantidad;
        }

        // Actualizar el subtotal del detalle de accesorio
        $detalleAccesorio.find('input[name="subtotal[]"]').val(subtotal.toFixed(2));

        // Recalcular el subtotal total de la factura
        var totalSubtotal = 0;
        $('input[name="subtotal[]"]').each(function() {
            totalSubtotal += parseFloat($(this).val()) || 0;
        });

        $('#subtotal_factura').val(totalSubtotal.toFixed(2)); // Actualizar el subtotal total
    }

    // Verificar el DNI del cliente al cambiar el valor del campo
    $('#dni_cliente').on('input', function () {
        var dni = $(this).val();
        if (dni.length >= 7) { // Realiza la consulta si el DNI tiene una longitud mínima razonable
            $.ajax({
                url: '../cliente/buscar_cliente.php',
                method: 'POST',
                data: { dni: dni },
                dataType: 'json',
                success: function (response) {
                    if (response.status === 'exists') {
                        // Llenar los campos del cliente
                        $('#id_clientes').val(response.cliente.id_clientes);
                        $('#nombre_cliente').val(response.cliente.nombre + ' ' + response.cliente.apellido);
                        $('#mensaje_cliente').text('');
                        $('#form-levantar-cliente').hide();
                        $('#submit_button').prop('disabled', false); // Habilitar el botón
                    } else {
                        // Mostrar mensaje de error y habilitar el formulario para crear cliente
                        $('#id_clientes').val('');
                        $('#nombre_cliente').val('');
                        $('#mensaje_cliente').text('Cliente no encontrado. Por favor, registre el cliente.');
                        $('#form-levantar-cliente').show(); // Mostrar el formulario para levantar cliente
                        $('#submit_button').prop('disabled', true); // Deshabilitar el botón
                    }
                }
            });
        } else {
            // Restablecer campos y deshabilitar el botón si el DNI es inválido
            $('#id_clientes').val('');
            $('#nombre_cliente').val('');
            $('#mensaje_cliente').text('DNI demasiado corto.');
            $('#form-levantar-cliente').hide();
            $('#submit_button').prop('disabled', true);
        }
    });

    // Manejar el registro de nuevo cliente
    $('#registrar_cliente').on('click', function () {
        var nuevo_nombre = $('#nuevo_nombre').val();
        var nuevo_apellido = $('#nuevo_apellido').val();
        var nuevo_telefono = $('#nuevo_telefono').val();
        var nuevo_correo = $('#nuevo_correo').val();
        var nueva_direccion = $('#nueva_direccion').val();
        var dni_cliente = $('#dni_cliente').val();

        $.ajax({
            url: '../cliente/registrar_cliente.php',
            method: 'POST',
            data: {
                nombre: nuevo_nombre,
                apellido: nuevo_apellido,
                telefono: nuevo_telefono,
                correo: nuevo_correo,
                direccion: nueva_direccion,
                dni: dni_cliente
            },
            success: function (response) {
                console.log(response); // Muestra la respuesta en la consola
                try {
                    response = JSON.parse(response); // Asegúrate de que la respuesta es JSON
                } catch (e) {
                    alert('Error en la respuesta del servidor.');
                    return;
                }

                if (response.status === 'success') {
                    alert('Cliente registrado correctamente.');
                    location.reload(); // Recargar la página para continuar con la factura
                } else {
                    alert('Hubo un error al registrar el cliente: ' + (response.message || ''));
                }
            }
        });
    });
});
