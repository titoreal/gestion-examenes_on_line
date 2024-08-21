<div class="row">
    <div class="col-sm-3">
        <div class="alert bg-green">
            <h4>Clase<i class="pull-right fa fa-building-o"></i></h4>
            <span class="d-block"> <?= $mhs->nombre_clase ?></span>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="alert bg-blue">
            <h4>Departmento<i class="pull-right fa fa-graduation-cap"></i></h4>
            <span class="d-block"> <?= $mhs->nombre_area ?></span>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="alert bg-yellow">
            <h4>Fecha<i class="pull-right fa fa-calendar"></i></h4>
            <span class="d-block"> <?= date('l, d M Y') ?></span>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="alert bg-red">
            <h4>Hora<i class="pull-right fa fa-clock-o"></i></h4>
            <span class="d-block"> <span class="live-clock"><?= date('H:i:s') ?></span></span>
        </div>
    </div>
    <div class="col-sm-12">
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
                    <div class="col-sm-4">
                        <button type="button" onclick="reload_ajax()" class="btn btn-sm btn-flat bg-purple"><i class="fa fa-refresh"></i> Recarga</button>
                    </div>
                </div>
            </div>
            <div class="table-responsive px-4 pb-3" style="border: 0">
                <table id="resultados" class="w-100 table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nombre de Examen</th>
                            <th>Curso</th>
                            <th>Profesor</th>
                            <th>Número de planificacion</th>
                            <th>Tiempo</th>
                            <th class="text-center">Acción</th>
                        </tr>
                    </thead>
                    <!-- <tfoot>
                    <tr>
                        <th>#</th>
                        <th>Exam Name</th>
                        <th>Course</th>
						<th>Tutor</th>
                        <th>Number of Questions</th>
                        <th>Time</th>
                        <th class="text-center">Action</th>
                    </tr>
                </tfoot> -->
                </table>
            </div>
        </div>
    </div>
</div>

<script src="<?= base_url() ?>assets/dist/js/app/resultados/list.js"></script>