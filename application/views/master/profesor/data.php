<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title"><?= $subjudul ?></h3>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
        </div>
    </div>
    <div class="box-body">
        <div class="mt-2 mb-4">
            <a href="<?= base_url('profesor/add') ?>" class="btn btn-sm bg-blue btn-flat"><i class="fa fa-plus"></i> Agregar Datos</a>
            <a href="<?= base_url('profesor/import') ?>" class="btn btn-sm btn-flat btn-success"><i class="fa fa-upload"></i> Importar</a>
            <button type="button" onclick="reload_ajax()" class="btn btn-sm bg-maroon btn-default btn-flat"><i class="fa fa-refresh"></i> Recargar</button>
            <div class="pull-right">
                <button onclick="bulk_delete()" class="btn btn-sm btn-danger btn-flat" type="button"><i class="fa fa-trash"></i> Eliminar</button>
            </div>
        </div>
        <?= form_open('profesor/delete', array('id' => 'bulk')) ?>
        <table id="profesor" class="w-100 table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>codigop</th>
                    <th>Nombre Profesor</th>
                    <th>Correo</th>
                    <th>Curso</th>
                    <th class="text-center">Acción</th>
                    <th class="text-center">
                        <input type="checkbox" class="select_all">
                    </th>
                </tr>
            </thead>
            <tbody></tbody>
            <!-- <tfoot>
                <tr>
                    <th>#</th>
                    <th>codigop</th>
                    <th>Tutor Name</th>
                    <th>Email</th>
                    <th>Course</th>
                    <th class="text-center">Action</th>
                    <th class="text-center">
                        <input type="checkbox" class="select_all">
                    </th>
                </tr>
            </tfoot> -->
        </table>
        <?= form_close() ?>
    </div>
</div>

<script src="<?= base_url() ?>assets/dist/js/app/master/profesor/data.js"></script>