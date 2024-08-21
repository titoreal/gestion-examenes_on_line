var save_label;
var table;

$(document).ready(function() {
    ajaxcsrf();

    table = $("#claseprofesor").DataTable({
        initComplete: function() {
            var api = this.api();
            $("#claseprofesor_filter input")
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
                exportOptions: { columns: [1, 2, 3] }
            },
            {
                extend: "print",
                exportOptions: { columns: [1, 2, 3] }
            },
            {
                extend: "excel",
                exportOptions: { columns: [1, 2, 3] }
            },
            {
                extend: "pdf",
                exportOptions: { columns: [1, 2, 3] }
            }
        ],
        oLanguage: {
            sProcessing: "loading..."
        },
        processing: true,
        serverSide: true,
        ajax: {
            url: base_url + "claseprofesor/data",
            type: "POST"
        },
        columns: [{
                data: "id",
                orderable: false,
                searchable: false
            },
            { data: "codigop" },
            { data: "nombre_profesor" }
        ],
        columnDefs: [{
                targets: 3,
                searchable: false,
                orderable: false,
                title: "clase",
                data: "clase",
                render: function(data, type, row, meta) {
                    let clase = data.split(",");
                    let badge = [];
                    $.each(clase, function(i, val) {
                        var newclase = `<span class="badge bg-green">${val}</span>`;
                        badge.push(newclase);
                    });
                    return badge.join(" ");
                }
            },
            {
                targets: 4,
                searchable: false,
                orderable: false,
                data: "id_profesor",
                render: function(data, type, row, meta) {
                    return `<div class="text-center">
									<a href="${base_url}claseprofesor/edit/${data}" class="btn btn-primary btn-xs">
										<i class="fa fa-pencil"></i>
									</a>
								</div>`;
                }
            },
            {
                targets: 5,
                searchable: false,
                orderable: false,
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
        .appendTo("#claseprofesor_wrapper .col-md-6:eq(0)");

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

    $("#claseprofesor tbody").on("click", "tr .check", function() {
        var check = $("#claseprofesor tbody tr .check").length;
        var checked = $("#claseprofesor tbody tr .check:checked").length;
        if (check === checked) {
            $(".select_all").prop("checked", true);
        } else {
            $(".select_all").prop("checked", false);
        }
    });

    $("#bulk").on("submit", function(e) {
        if ($(this).attr("action") == base_url + "claseprofesor/delete") {
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
                        title: "Fallido",
                        text: "Datos en Uso",
                        type: "error"
                    });
                }
            });
        }
    });
});

function bulk_delete() {
    if ($("#claseprofesor tbody tr .check:checked").length == 0) {
        Swal({
            title: "Procesos Fallidos",
            text: "Sin datos seleccionados",
            type: "error"
        });
    } else {
        $("#bulk").attr("action", base_url + "claseprofesor/delete");
        Swal({
            title: "Seguro?",
            text: "Los datos serán borrados!",
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