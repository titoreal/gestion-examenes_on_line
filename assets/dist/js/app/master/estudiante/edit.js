function load_clase(id) {
    $('#clase').find('option').not(':first').remove();

    $.ajax({
        url: base_url+'clase/clase_by_area/' + id,
        type: 'GET',
        success: function (data) {
            var option = [];
            for (let i = 0; i < data.length; i++) {
                option.push({
                    id: data[i].id_clase,
                    text: data[i].nombre_clase
                });
            }
            $('#clase').select2({
                data: option
            })
        }
    });
}

$(document).ready(function () {

    ajaxcsrf();

    // Load clase By area
    $('#area').on('change', function () {
        load_clase($(this).val());
    });


    $('form#estudiante input, form#estudiante select').on('change', function () {
        $(this).closest('.form-group').removeClass('has-error has-success');
        $(this).nextAll('.help-block').eq(0).text('');
    });

    $('[name="seleccion_kelamin"]').on('change', function () {
        $(this).parent().nextAll('.help-block').eq(0).text('');
    });

    $('form#estudiante').on('submit', function (e) {
        e.preventDefault();
        e.stopImmediatePropagation();

        var btn = $('#submit');
        btn.attr('disabled', 'disabled').text('Cargando');

        $.ajax({
            url: $(this).attr('action'),
            data: $(this).serialize(),
            type: 'POST',
            success: function (data) {
                btn.removeAttr('disabled').text('Guardar');
                if (data.status) {
                    Swal({
                        "title": "Proceso Exitoso",
                        "text": "Datos Guardados Exitosamente",
                        "type": "success"
                    }).then((result) => {
                        if (result.value) {
                            window.location.href = base_url+'estudiante';
                        }
                    });
                } else {
                    console.log(data.errors);
                    $.each(data.errors, function (key, value) {
                        $('[name="' + key + '"]').nextAll('.help-block').eq(0).text(value);
                        $('[name="' + key + '"]').closest('.form-group').addClass('has-error');
                        if (value == '') {
                            $('[name="' + key + '"]').nextAll('.help-block').eq(0).text('');
                            $('[name="' + key + '"]').closest('.form-group').removeClass('has-error').addClass('has-success');
                        }
                    });
                }
            }
        });
    });
});