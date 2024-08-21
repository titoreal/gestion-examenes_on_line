<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title"><?= $subjudul ?></h3>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
        </div>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12 mb-4">
                <a href="<?= base_url() ?>hasilresultados" class="btn btn-flat btn-sm btn-primary"><i class="fa fa-arrow-left"></i> Volver</a>
                <button type="button" onclick="reload_ajax()" class="btn btn-flat btn-sm bg-purple"><i class="fa fa-refresh"></i> Recargar</button>
                <div class="pull-right">
                    <a target="_blank" href="<?= base_url() ?>hasilresultados/cetak_detail/<?= $this->uri->segment(3) ?>" class="btn bg-maroon btn-flat btn-sm">
                        <i class="fa fa-download"></i> Descargar/Imprimir
                    </a>
                </div>
            </div>
            <div class="col-sm-6">
                <table class="table w-100">
                    <tr>
                        <th>Nombre de Examen</th>
                        <td><?= $resultados->nombre_resultados ?></td>
                    </tr>
                    <tr>
                        <th>Total planificacion</th>
                        <td><?= $resultados->total_preguntas ?></td>
                    </tr>
                    <tr>
                        <th>Hora</th>
                        <td><?= $resultados->duracion ?> Minute</td>
                    </tr>
                    <tr>
                        <th>Fecha de Inicio</th>
                        <td><?= date('l, d M d', strtotime($resultados->fecha)) ?></td>
                    </tr>
                    <tr>
                        <th>Fecha de Finalización</th>
                        <td><?= date('l, d M d', strtotime($resultados->terlambat)) ?></td>
                    </tr>
                </table>
            </div>
            <div class="col-sm-6">
                <table class="table w-100">
                    <tr>
                        <th>Curso</th>
                        <td><?= $resultados->nombre_curso ?></td>
                    </tr>
                    <tr>
                        <th>Profesor</th>
                        <td><?= $resultados->nombre_profesor ?></td>
                    </tr>
                    <tr>
                        <th>Más baja calificación</th>
                        <td><?= $score->min_score ?></td>
                    </tr>
                    <tr>
                        <th>Más alta calificación</th>
                        <td><?= $score->max_score ?></td>
                    </tr>
                    <tr>
                        <th>Promedio</th>
                        <td><?= $score->avg_score ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="table-responsive px-4 pb-3" style="border: 0">
        <table id="detail_hasil" class="w-100 table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nombre</th>
                    <th>Clase</th>
                    <th>Departamento</th>
                    <th>Respuesta Correcta</th>
                    <th>Puntaje</th>
                </tr>
            </thead>
            <!-- <tfoot>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Class</th>
                <th>Dept.</th>
                <th>Correct Ans.</th>
                <th>Score</th>
            </tr>
        </tfoot> -->
        </table>
    </div>
</div>

<script type="text/javascript">
    var id = '<?= $this->uri->segment(3) ?>';
</script>

<script src="<?= base_url() ?>assets/dist/js/app/resultados/detail_hasil.js"></script>