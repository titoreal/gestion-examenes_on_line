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
                <button type="button" onclick="reload_ajax()" class="btn bg-purple btn-flat btn-sm"><i class="fa fa-refresh"></i> Recarga</button>
            </div>
        </div>
    </div>
    <div class="table-responsive px-4 pb-3" style="border: 0">
        <table id="hasil" class="w-100 table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Examen</th>
                    <th>Curso</th>
                    <th>Profesor</th>
                    <th>Total de preguntas</th>
                    <th>Hora</th>
                    <th>Fecha</th>
                    <th class="text-center">
                        <i class="fa fa-search"></i>
                    </th>
                </tr>
            </thead>
            <!-- <tfoot>
            <tr>
                <th>#</th>
                <th>Nombre del examen</th>
                <th>Curso</th>
                <th>Tutor</th>
                <th>Total Preguntas.</th>
                <th>Hora</th>
                <th>Fecha</th>
                <th class="text-center">
                    <i class="fa fa-search"></i>
                </th>
            </tr>
        </tfoot> -->
        </table>
    </div>
</div>

<script src="<?= base_url() ?>assets/dist/js/app/resultados/hasil.js"></script>