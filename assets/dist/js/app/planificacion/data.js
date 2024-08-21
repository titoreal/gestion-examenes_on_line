var table;

$(document).ready(function() {
    ajaxcsrf();

    table = $("#pregunta").DataTable({
        initComplete: function() {
            var api = this.api();
            $("#pregunta_filter input")
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
                exportOptions: { columns: [2, 3, 4, 5] }
            },
            {
                extend: "print",
                exportOptions: { columns: [2, 3, 4, 5] }
            },
            {
                extend: "excel",
                exportOptions: { columns: [2, 3, 4, 5] }
            },
            {
                extend: "pdf",
                exportOptions: { columns: [2, 3, 4, 5] }
            }
        ],
        oLanguage: {
            sProcessing: "cargando..."
        },
        processing: true,
        serverSide: true,
        ajax: {
            url: base_url + "pregunta/data",
            type: "POST"
        },
        columns: [{
                data: "id_pregunta",
                orderable: false,
                searchable: false
            },
            {
                data: "id_pregunta",
                orderable: false,
                searchable: false
            },
            { data: "nombre_profesor" },
            { data: "nombre_curso" },
            { data: "pregunta" },
            { data: "created_on" }
        ],
        columnDefs: [{
                targets: 0,
                data: "id_pregunta",
                render: function(data, type, row, meta) {
                    return `<div class="text-center">
									<input name="checked[]" class="check" value="${data}" type="checkbox">
								</div>`;
                }
            },
            {
                targets: 6,
                data: "id_pregunta",
                render: function(data, type, row, meta) {
                    return `<div class="text-center">
                                <a href="${base_url}pregunta/detail/${data}" class="btn btn-xs btn-success">
                                    <i class="fa fa-eye"></i> Información
                                </a>
                                <a href="${base_url}pregunta/edit/${data}" class="btn btn-xs btn-primary">
                                    <i class="fa fa-edit"></i> Editar
                                </a>
                            </div>`;
                }
            }
        ],
        order: [
            [5, "desc"]
        ],
        rowId: function(a) {
            return a;
        },
        rowCallback: function(row, data, iDisplayIndex) {
            var info = this.fnPagingInfo();
            var page = info.iPage;
            var length = info.iLength;
            var index = page * length + (iDisplayIndex + 1);
            $("td:eq(1)", row).html(index);
        }
    });

    table
        .buttons()
        .container()
        .appendTo("#pregunta_wrapper .col-md-6:eq(0)");

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

    $("#pregunta tbody").on("click", "tr .check", function() {
        var check = $("#pregunta tbody tr .check").length;
        var checked = $("#pregunta tbody tr .check:checked").length;
        if (check === checked) {
            $(".select_all").prop("checked", true);
        } else {
            $(".select_all").prop("checked", false);
        }
    });

    $("#bulk").on("submit", function(e) {
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
                        title: "Proceso fallido",
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
    });
});

function bulk_delete() {
    if ($("#pregunta tbody tr .check:checked").length == 0) {
        Swal({
            title: "Proceso fallido",
            text: "Sin datos seleccionados",
            type: "error"
        });
    } else {
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