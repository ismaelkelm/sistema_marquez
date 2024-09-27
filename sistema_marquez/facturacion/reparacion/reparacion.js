
$(document).ready(function() {
    // Función que se ejecuta cuando se cambia el total o el tipo de comprobante
    $('#total, #id_tipo_comprobante').on('input change', function() {
        // Obtener los valores del formulario
        var total = $('#total').val();
        var id_tipo_comprobante = $('#id_tipo_comprobante').val();

        if (total && id_tipo_comprobante) {
            // Enviar los datos al servidor usando AJAX
            $.ajax({
                url: 'calcular_iva.php',
                type: 'POST',
                data: {
                    total: total,
                    id_tipo_comprobante: id_tipo_comprobante
                },
                dataType: 'json',
                success: function(response) {
                    if (response.error) {
                        $('#resultado').html(response.message);
                    } else {
                        $('#resultado').html(
                            'IVA (21%): $' + response.iva + '<br>' +
                            'Total con IVA: $' + response.total_con_iva
                        );
                    }
                },
                error: function() {
                    $('#resultado').html('Hubo un error en el cálculo. Inténtalo nuevamente.');
                }
            });
        }
    });
});