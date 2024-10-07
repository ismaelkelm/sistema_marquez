
$(document).ready(function () {
 // Verificar el DNI del cliente al cambiar el valor del campo
 $('#dni_cliente').on('input', function () {
    var dni = $(this).val();
    if (dni.length >= 7) { // Realiza la consulta si el DNI tiene una longitud mínima razonable
        $.ajax({
            url: '../../cliente/buscar_cliente.php',
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
        url: '../../cliente/registrar_cliente.php',
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
