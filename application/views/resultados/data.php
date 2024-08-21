<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title"><?= $subjudul ?></h3>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
        </div>
    </div>
    <div class="box-body">
        <button type="button" onclick="bulk_delete()" class="btn btn-sm btn-flat btn-danger"><i class="fa fa-trash"></i> Eliminación Masiva</button>
        <div class="pull-right">
            <a href="<?= base_url('resultados/add') ?>" class="btn bg-blue btn-sm btn-flat"><i class="fa fa-plus"></i> Conducir Nuevo Examen</a>
            <button type="button" onclick="reload_ajax()" class="btn btn-sm btn-flat bg-maroon"><i class="fa fa-refresh"></i> Recargar</button>
        </div>
    </div>
    <?= form_open('resultados/delete', array('id' => 'bulk')) ?>
    <div class="table-responsive px-4 pb-3" style="border: 0">
        <table id="resultados" class="w-100 table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th class="text-center">
                        <input type="checkbox" class="select_all">
                    </th>
                    <th>#</th>
                    <th>Nombre de Examen</th>
                    <th>Curso</th>
                    <th>Total planificacion</th>
                    <th>Tiempo</th>
                    <th>Patrón</th>
                    <th class="text-center">Token</th>
                    <th class="text-center">Acción</th>
                </tr>
            </thead>
            <!-- <tfoot>
            <tr>
				<th class="text-center">
					<input type="checkbox" class="select_all">
				</th>
                <th>#</th>
                <th>Exam Name</th>
                <th>Course</th>
                <th>Number of Ques.</th>
                <th>Time</th>
                <th>Pattern</th>                
				<th	class="text-center">Token</th>
				<th class="text-center">Action</th>
            </tr>
        </tfoot> -->
        </table>
    </div>
    <?= form_close(); ?>
</div>

<script type="text/javascript">
    var id_profesor = '<?= $profesor->id_profesor ?>';
</script>

<script src="<?= base_url() ?>assets/dist/js/app/resultados/data.js"></script>