var table;

$(document).ready(function () {

    ajaxcsrf();

    table = $("#resultados").DataTable({
        initComplete: function () {
            var api = this.api();
            $('#resultados_filter input')
                .off('.DT')
                .on('keyup.DT', function (e) {
                    api.search(this.value).draw();
                });
        },
        oLanguage: {
            sProcessing: "cargando..."
        },
        processing: true,
        serverSide: true,
        ajax: {
            "url": base_url+"resultados/list_json",
            "type": "POST",
        },
        columns: [
            {
                "data": "id_resultados",
                "orderable": false,
                "searchable": false
            },
            { "data": 'nombre_resultados' },
            { "data": 'nombre_curso' },
            { "data": 'nombre_profesor' },
            { "data": 'total_preguntas' },
            { "data": 'duracion' },
            {
                "searchable": false,
                "orderable": false
            }
        ],
        columnDefs: [
            {
                "targets": 6,
                "data": {
                    "id_resultados": "id_resultados",
                    "ada": "ada"
                },
                "render": function (data, type, row, meta) {
                    var btn;
                    if (data.ada > 0) {
                        btn = `
								<a class="btn btn-xs btn-success" href="${base_url}hasilresultados/cetak/${data.id_resultados}" target="_blank">
									<i class="fa fa-print"></i> Imprimir Resultados
								</a>`;
                    } else {
                        btn = `<a class="btn btn-xs btn-primary" href="${base_url}resultados/token/${data.id_resultados}">
								<i class="fa fa-pencil"></i> Tomar Examen
							</a>`;
                    }
                    return `<div class="text-center">
									${btn}
								</div>`;
                }
            },
        ],
        order: [
            [1, 'asc']
        ],
        rowId: function (a) {
            return a;
        },
        rowCallback: function (row, data, iDisplayIndex) {
            var info = this.fnPagingInfo();
            var page = info.iPage;
            var length = info.iLength;
            var index = page * length + (iDisplayIndex + 1);
            $('td:eq(0)', row).html(index);
        }
    });
});