$(document).ready(function() {
    ajaxcsrf();

    $('#btncek').on('click', function() {
        var token = $('#token').val();
        var idresultados = $(this).data('id');
        if (token === '') {
            Swal('Failed', 'Token must be filled', 'error');
        } else {
            var key = $('#id_resultados').data('key');
            $.ajax({
                url: base_url + 'resultados/cektoken/',
                type: 'POST',
                data: {
                    id_resultados: idresultados,
                    token: token
                },
                cache: false,
                success: function(result) {
                    Swal({
                        "type": result.status ? "success" : "error",
                        "title": result.status ? "Successful" : "Failed",
                        "text": result.status ? "True Token" : "Incorrect Token"
                    }).then((data) => {
                        if (result.status) {
                            location.href = base_url + 'resultados/?key=' + key;
                        }
                    });
                }
            });
        }
    });

    var time = $('.countdown');
    if (time.length) {
        countdown(time.data('time'));
    }
});