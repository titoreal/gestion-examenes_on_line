var save_label;
var table;

$(document).ready(function() {
    ajaxcsrf();

    table = $("#areacurso").DataTable({
        initComplete: function() {
            var api = this.api();
            $("#areacurso_filter input")
                .off(".DT")
                .on("keyup.DT", function(e) {
                    api.search(this.value).draw();
                });
        },
        dom: "<'row'<'col-sm-3'l><'col-sm-6 text-center'B><'col-sm-3'f>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-5'i><'col-sm-7'p>>",
        buttons: [{
                extend: "copy",
                exportOptions: { columns: [1, 2] }
            },
            {
                extend: "print",
                exportOptions: { columns: [1, 2] }
            },
            {
                extend: "excel",
                exportOptions: { columns: [1, 2] }
            },
            {
                extend: "pdf",
                exportOptions: { columns: [1, 2] }
            }
        ],
        oLanguage: {
            sProcessing: "cargando..."
        },
        processing: true,
        serverSide: true,
        ajax: {
            url: base_url + "areacurso/data",
            type: "POST"
        },
        columns: [{
                data: "id",
                orderable: false,
                searchable: false
            },
            { data: "nombre_curso" }
        ],
        columnDefs: [{
                targets: 2,
                searchable: false,
                orderable: false,
                title: "area",
                data: "nombre_area",
                render: function(data, type, row, meta) {
                    let curso = data.split(",");
                    let badge = [];
                    $.each(curso, function(i, val) {
                        var newcurso = `<span class="badge bg-green">${val}</span>`;
                        badge.push(newcurso);
                    });
                    return badge.join(" ");
                }
            },
            {
                targets: 3,
                searchable: false,
                orderable: false,
                data: "id_curso",
                render: function(data, type, row, meta) {
                    return `<div class="text-center">
									<a href="${base_url}areacurso/edit/${data}" class="btn btn-primary btn-xs">
										<i class="fa fa-pencil"></i>
									</a>
								</div>`;
                }
            },
            {
                targets: 4,
                data: "id_curso",
                render: function(data, type, row, meta) {
                    return `<div class="text-center">
									<input name="checked[]" class="check" value="${data}" type="checkbox">
								</div>`;
                }
            }
        ],
        order: [
            [1, "asc"]
        ],
        rowId: function(a) {
            return a;
        },
        rowCallback: function(row, data, iDisplayIndex) {
            var info = this.fnPagingInfo();
            var page = info.iPage;
            var length = info.iLength;
            var index = page * length + (iDisplayIndex + 1);
            $("td:eq(0)", row).html(index);
        }
    });

    table
        .buttons()
        .container()
        .appendTo("#areacurso_wrapper .col-md-6:eq(0)");

    $("#myModal").on("shown.modal.bs", function() {
        $(':input[name="banyak"]').select();
    });

    $(".select_all").on("click", function() {
        if (this.checked) {
            $(".check").each(function() {
                this.checked = true;
                $(".select_all").prop("checked", true);
            });
        } else {
            $(".check").each(function() {
                this.checked = false;
                $(".select_all").prop("checked", false);
            });
        }
    });

    $("#areacurso tbody").on("click", "tr .check", function() {
        var check = $("#areacurso tbody tr .check").length;
        var checked = $("#areacurso tbody tr .check:checked").length;
        if (check === checked) {
            $(".select_all").prop("checked", true);
        } else {
            $(".select_all").prop("checked", false);
        }
    });

    $("#bulk").on("submit", function(e) {
        if ($(this).attr("action") == base_url + "areacurso/delete") {
            e.preventDefault();
            e.stopImmediatePropagation();

            $.ajax({
                url: $(this).attr("action"),
                data: $(this).serialize(),
                type: "POST",
                success: function(respon) {
                    if (respon.status) {
                        Swal({
                            title: "Proceso Exitoso",
                            text: respon.total + " Datos eliminados correctamente",
                            type: "success"
                        });
                    } else {
                        Swal({
                            title: "Proceso Fallido",
                            text: "Sin datos seleccionados",
                            type: "error"
                        });
                    }
                    reload_ajax();
                },
                error: function() {
                    Swal({
                        title: "Proceso Fallido",
                        text: "Datos en uso",
                        type: "error"
                    });
                }
            });
        }
    });
});

function bulk_delete() {
    if ($("#areacurso tbody tr .check:checked").length == 0) {
        Swal({
            title: "Proceso Fallido",
            text: "Sin datos seleccionados",
            type: "error"
        });
    } else {
        $("#bulk").attr("action", base_url + "areacurso/delete");
        Swal({
            title: "Seguro?",
            text: "Estos datos serán eliminados!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Eliminar!"
        }).then(result => {
            if (result.value) {
                $("#bulk").submit();
            }
        });
    }
}