function loadarea(id) {
    $('#area option').remove();
    $.getJSON(base_url+'areacurso/getareaId/' + id, function (data) {
        console.log(data);
        let opsi;
        $.each(data, function (key, val) {
            opsi = `
                    <option value="${val.id_area}">${val.nombre_area}</option>
                `;
            $('#area').append(opsi);
        });
    });
}

$(document).ready(function () {
    $('[name="curso_id"]').on('change', function () {
        loadarea($(this).val());
    });

    $('form#areacurso select').on('change', function () {
        $(this).closest('.form-group').removeClass('has-error');
        $(this).nextAll('.help-block').eq(0).text('');
    });

    $('form#areacurso').on('submit', function (e) {
        e.preventDefault();
        e.stopImmediatePropagation();

        var btn = $('#submit');
        btn.attr('disabled', 'disabled').text('Cargando');

        $.ajax({
            url: $(this).attr('action'),
            data: $(this).serialize(),
            method: 'POST',
            success: function (data) {
                btn.removeAttr('disabled').text('Guardar');
                console.log(data);
                if (data.status) {
                    Swal({
                        "title": "Proceso Exitoso",
                        "text": "Data Datos Guardados Exitosamente",
                        "type": "success"
                    }).then((result) => {
                        if (result.value) {
                            window.location.href = base_url+'areacurso';
                        }
                    });
                } else {
                    if (data.errors) {
                        let j;
                        $.each(data.errors, function (key, val) {
                            j = $('[name="' + key + '"]');
                            j.closest('.form-group').addClass('has-error');
                            j.nextAll('.help-block').eq(0).text(val);
                            if (val == '') {
                                j.parent().addClass('has-error');
                                j.nextAll('.help-block').eq(0).text('');
                            }
                        });
                    }
                }
            }
        });
    });
});