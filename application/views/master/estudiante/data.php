<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">Master <?= $subjudul ?></h3>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
        </div>
    </div>
    <div class="box-body">
        <div class="mt-2 mb-3">
            <a href="<?= base_url('estudiante/add') ?>" class="btn btn-sm btn-flat bg-blue"><i class="fa fa-plus"></i> Agregar</a>
            <a href="<?= base_url('estudiante/import') ?>" class="btn btn-sm btn-flat btn-success"><i class="fa fa-upload"></i> Importar</a>
            <button type="button" onclick="reload_ajax()" class="btn btn-sm bg-maroon btn-flat btn-default"><i class="fa fa-refresh"></i> Recargar</button>
            <div class="pull-right">
                <button onclick="bulk_delete()" class="btn btn-sm btn-flat btn-danger" type="button"><i class="fa fa-trash"></i> Eliminar</button>
            </div>
        </div>
        <?= form_open('estudiante/delete', array('id' => 'bulk')); ?>
        <div class="table-responsive">
            <table id="estudiante" class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Codigo</th>
                        <th>Nombre</th>
                        <th>Correo</th>
                        <th>Clase</th>
                        <th>Área</th>
                        <th width="100" class="text-center">Acción</th>
                        <th width="100" class="text-center">
                            <input class="select_all" type="checkbox">
                        </th>
                    </tr>
                </thead>
                <!-- <tfoot>
                    <tr>
                        <th>#</th>
                        <th>Código</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Clase</th>
                        <th>Área</th>
                        <th width="100" class="text-center">Action</th>
                        <th width="100" class="text-center">
                            <input class="select_all" type="checkbox">
                        </th>
                    </tr>
                </tfoot> -->
            </table>
        </div>
        <?= form_close() ?>
    </div>
</div>

<script src="<?= base_url() ?>assets/dist/js/app/master/estudiante/data.js"></script>