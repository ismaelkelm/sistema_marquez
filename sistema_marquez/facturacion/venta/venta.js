$(document).ready(function () {
      // Función que se ejecuta cuando se cambia el subtotal o el tipo de comprobante
    $('#subtotal_factura, #id_tipo_comprobante').on('input change', function() {
        // Obtener los valores del formulario
        var subtotal = $('#subtotal_factura').val();
        var id_tipo_comprobante = $('#id_tipo_comprobante').val();

        if (total && id_tipo_comprobante) {
            // Enviar los datos al servidor usando AJAX
            $.ajax({
                url: '../calcular_iva.php',
                type: 'POST',
                data: {
                    subtotal: subtotal,
                    id_tipo_comprobante: id_tipo_comprobante
                },
                dataType: 'json',
                success: function(response) {
                    if (response.error) {
                        $('#resultado').html(response.message);
                        // Ocultar los campos de IVA y total
                        $('#iva_label').hide();
                        $('#iva_resultado').hide();
                        $('#total_label').show();
                        //muestro el total sin IVA
                        $('#total').val(response.total);
                        $('#total').show();
                    } else {
                        $('#resultado').html('Cálculo exitoso');
                        $('#iva_resultado').val(response.iva);
                        $('#total').val(response.total);
                        
                        // Mostrar los campos de IVA y total
                        $('#iva_label').show();
                        $('#iva_resultado').show();
                        $('#total_label').show();
                        $('#total').show();
                    }
                },
                error: function() {
                    $('#resultado').html('Hubo un error en el cálculo. Inténtalo nuevamente.');
                    // Ocultar los campos de IVA y total
                    $('#iva_label').hide();
                    $('#iva_resultado').hide();
                    $('#total_label').hide();
                    $('#total_con_iva').hide();
                }
            });
        } else {
            // Si no hay subtotal o tipo de comprobante, ocultar los campos
            $('#iva_label').hide();
            $('#iva_resultado').hide();
            $('#total_label').hide();
            $('#total_con_iva').hide();
        }
    });

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
            url: '../../accesorios_componentes/get_precio.php',
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


    function obtenerUltimoIdCabeceraFactura() {
        $.ajax({
            url: '../facturacion/venta/obtener_ultimo_id_cabecera_factura.php', // Archivo PHP que retorna el último ID
            method: 'POST',
            dataType: 'json',
            success: function (response) {
                if (response.status === 'success') {
                    var ultimoId = response.id_cabecera_factura; // Guarda el ID obtenido
                    console.log("Último id_cabecera_factura:", ultimoId); // Verifica en la consola
    
                    // Redirigir al archivo facturaB.php con el ID
                    window.location.href = '../facturacion/facturaB.php?id=' + ultimoId; // Redirige con el ID
                } else {
                    alert('Error al obtener el último id_cabecera_factura.');
                }
            },
            error: function () {
                alert('Hubo un error en la solicitud para obtener el último id_cabecera_factura.');
            }
        });
    }
    
    

    $('#submit_factura').on('click', function() {
        // Registrar la factura antes de obtener el último ID
        $.ajax({
            url: '../../facturacion/carga_factura.php', // Archivo PHP que registra la factura
            method: 'POST',
            data: $('#form-factura').serialize(), // Serializa todos los datos del formulario de factura
            success: function (response) {
                try {
                    response = JSON.parse(response);
                } catch (e) {
                    alert('Error en la respuesta del servidor.');
                    return;
                }
    
                if (response.status === 'success') {
                    alert('Factura registrada correctamente.');
                    // Llamar la función para obtener el último ID
                    obtenerUltimoIdCabeceraFactura(); // Redirige a la página del PDF
                } else {
                    alert('Hubo un error al registrar la factura: ' + (response.message || ''));
                }
            },
            error: function () {
                alert('Hubo un error al registrar la factura.');
            }
        });
    });
    
});
