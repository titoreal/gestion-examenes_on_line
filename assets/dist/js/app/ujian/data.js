var table;

$(document).ready(function() {

    ajaxcsrf();

    table = $("#resultados").DataTable({
        initComplete: function() {
            var api = this.api();
            $('#resultados_filter input')
                .off('.DT')
                .on('keyup.DT', function(e) {
                    api.search(this.value).draw();
                });
        },
        oLanguage: {
            sProcessing: "loading..."
        },
        processing: true,
        serverSide: true,
        ajax: {
            "url": base_url + "resultados/json",
            "type": "POST",
        },
        columns: [{
                "data": "id_resultados",
                "orderable": false,
                "searchable": false
            },
            {
                "data": "id_resultados",
                "orderable": false,
                "searchable": false
            },
            { "data": 'nombre_resultados' },
            { "data": 'nombre_curso' },
            { "data": 'total_preguntas' },
            { "data": 'duracion' },
            { "data": 'seleccion' },
            {
                "data": 'token',
                "orderable": false
            }
        ],
        columnDefs: [{
                "targets": 0,
                "data": "id_resultados",
                "render": function(data, type, row, meta) {
                    return `<div class="text-center">
									<input name="checked[]" class="check" value="${data}" type="checkbox">
								</div>`;
                }
            },
            {
                "targets": 7,
                "data": "token",
                "render": function(data, type, row, meta) {
                    return `<div class="text-center">
								<strong class="badge bg-purple">${data}</strong>
								</div>`;
                }
            },
            {
                "targets": 8,
                "data": "id_resultados",
                "render": function(data, type, row, meta) {
                    return `<div class="text-center">
									<button type="button" data-id="${data}" class="btn btn-token btn-xs bg-purple">
										<i class="fa fa-refresh"></i>
									</button>
									<a href="${base_url}resultados/edit/${data}" class="btn btn-xs btn-primary">
										<i class="fa fa-edit"></i>
									</a>
								</div>`;
                }
            },
        ],
        order: [
            [1, 'desc']
        ],
        rowId: function(a) {
            return a;
        },
        rowCallback: function(row, data, iDisplayIndex) {
            var info = this.fnPagingInfo();
            var page = info.iPage;
            var length = info.iLength;
            var index = page * length + (iDisplayIndex + 1);
            $('td:eq(1)', row).html(index);
        }
    });

    $('.select_all').on('click', function() {
        if (this.checked) {
            $('.check').each(function() {
                this.checked = true;
                $('.select_all').prop('checked', true);
            });
        } else {
            $('.check').each(function() {
                this.checked = false;
                $('.select_all').prop('checked', false);
            });
        }
    });

    $('#resultados tbody').on('click', 'tr .check', function() {
        var check = $('#resultados tbody tr .check').length;
        var checked = $('#resultados tbody tr .check:checked').length;
        if (check === checked) {
            $('.select_all').prop('checked', true);
        } else {
            $('.select_all').prop('checked', false);
        }
    });

    $('#resultados').on('click', '.btn-token', function() {
        let id = $(this).data('id');

        $(this).attr('disabled', 'disabled').children().addClass('fa-spin');
        $.ajax({
            url: base_url + 'resultados/refresh_token/' + id,
            type: 'get',
            dataType: 'json',
            success: function(data) {
                if (data.status) {
                    $(this).removeAttr('disabled');
                    reload_ajax();
                }
            }
        });
    });

    $('#bulk').on('submit', function(e) {
        e.preventDefault();
        e.stopImmediatePropagation();

        $.ajax({
            url: $(this).attr('action'),
            data: $(this).serialize(),
            type: 'POST',
            success: function(respon) {
                if (respon.status) {
                    Swal({
                        "title": "Proceso Exitoso",
                        "text": respon.total + " datos eliminados correctamente",
                        "type": "success"
                    });
                } else {
                    Swal({
                        "title": "Proceso Fallido",
                        "text": "No datos seleccionados",
                        "type": "error"
                    });
                }
                reload_ajax();
            },
            error: function() {
                Swal({
                    "title": "Proceso Fallido",
                    "text": "Hay datos en uso",
                    "type": "error"
                });
            }
        });
    });

    table.ajax.url(base_url + 'resultados/json/' + id_profesor).load();
});

function bulk_delete() {
    if ($('#resultados tbody tr .check:checked').length == 0) {
        Swal({
            title: "Proceso Fallido",
            text: 'No datos seleccionados',
            type: 'error'
        });
    } else {
        Swal({
            title: 'Seguro?',
            text: "Datos serán eliminados",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Delete!'
        }).then((result) => {
            if (result.value) {
                $('#bulk').submit();
            }
        });
    }
}