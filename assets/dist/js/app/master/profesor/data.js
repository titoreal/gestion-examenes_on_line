var table;

$(document).ready(function() {
    ajaxcsrf();

    table = $("#profesor").DataTable({
        initComplete: function() {
            var api = this.api();
            $("#profesor_filter input")
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
                exportOptions: { columns: [1, 2, 3, 4] }
            },
            {
                extend: "print",
                exportOptions: { columns: [1, 2, 3, 4] }
            },
            {
                extend: "excel",
                exportOptions: { columns: [1, 2, 3, 4] }
            },
            {
                extend: "pdf",
                exportOptions: { columns: [1, 2, 3, 4] }
            }
        ],
        oLanguage: {
            sProcessing: "loading..."
        },
        processing: true,
        serverSide: true,
        ajax: {
            url: base_url + "profesor/data",
            type: "POST"
        },
        columns: [{
                data: "id_profesor",
                orderable: false,
                searchable: false
            },
            { data: "codigop" },
            { data: "nombre_profesor" },
            { data: "email" },
            { data: "nombre_curso" }
        ],
        columnDefs: [{
                searchable: false,
                targets: 5,
                data: {
                    id_profesor: "id_profesor",
                    ada: "ada"
                },
                render: function(data, type, row, meta) {
                    let btn;
                    if (data.ada > 0) {
                        btn = "";
                    } else {
                        btn = `<button type="button" class="btn btn-aktif btn-primary btn-xs" data-id="${data.id_profesor}">
								<i class="fa fa-user-plus"></i> Activo
							</button>`;
                    }
                    return `<div class="text-center">
							<a href="${base_url}profesor/edit/${data.id_profesor}" class="btn btn-xs btn-primary">
								<i class="fa fa-pencil"></i> Editar
							</a>
							${btn}
						</div>`;
                }
            },
            {
                targets: 6,
                data: "id_profesor",
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
        .appendTo("#profesor_wrapper .col-md-6:eq(0)");

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

    $("#profesor tbody").on("click", "tr .check", function() {
        var check = $("#profesor tbody tr .check").length;
        var checked = $("#profesor tbody tr .check:checked").length;
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
                        title: "Proceso exitoso",
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
                    text: "Sin datos en uso",
                    type: "error"
                });
            }
        });
    });

    $("#profesor").on("click", ".btn-aktif", function() {
        let id = $(this).data("id");

        $.ajax({
            url: base_url + "profesor/create_user",
            data: "id=" + id,
            type: "GET",
            success: function(response) {
                if (response.msg) {
                    var title = response.status ? "Proceso Exitoso" : "Fallido";
                    var type = response.status ? "success" : "error";
                    Swal({
                        title: title,
                        text: response.msg,
                        type: type
                    });
                }
                reload_ajax();
            }
        });
    });
});

function bulk_delete() {
    if ($("#profesor tbody tr .check:checked").length == 0) {
        Swal({
            title: "Fallo",
            text: "Datos sin seleccionar",
            type: "error"
        });
    } else {
        Swal({
            title: "Estas seguro?",
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