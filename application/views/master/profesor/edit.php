<?= form_open('profesor/save', array('id' => 'formprofesor'), array('method' => 'edit', 'id_profesor' => $data->id_profesor)); ?>
<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">Formulario <?= $subjudul ?></h3>
        <div class="box-tools pull-right">
            <a href="<?= base_url() ?>profesor" class="btn btn-sm btn-flat btn-primary">
                <i class="fa fa-arrow-left"></i> Cancelar
            </a>
        </div>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-sm-4 col-sm-offset-4">
                <div class="form-group">
                    <label for="codigop">codigop</label>
                    <input value="<?= $data->codigop ?>" autofocus="autofocus" onfocus="this.select()" type="number" id="codigop" class="form-control" name="codigop" placeholder="codigop">
                    <small class="help-block"></small>
                </div>
                <div class="form-group">
                    <label for="nombre_profesor">Nombre Profesor</label>
                    <input value="<?= $data->nombre_profesor ?>" type="text" class="form-control" name="nombre_profesor" placeholder="Nombre Profesor">
                    <small class="help-block"></small>
                </div>
                <div class="form-group">
                    <label for="email">Correo Profesor</label>
                    <input value="<?= $data->email ?>" type="text" class="form-control" name="email" placeholder="Correo Profesor">
                    <small class="help-block"></small>
                </div>
                <div class="form-group">
                    <label for="curso">Curso</label>
                    <select name="curso" id="curso" class="form-control select2" style="width: 100%!important">
                        <option value="" disabled selected>Elegir Curso</option>
                        <?php foreach ($curso as $row) : ?>
                            <option <?= $data->curso_id === $row->id_curso ? "selected" : "" ?> value="<?= $row->id_curso ?>"><?= $row->nombre_curso ?></option>
                        <?php endforeach; ?>
                    </select>
                    <small class="help-block"></small>
                </div>
                <div class="form-group pull-right">
                    <button type="reset" class="btn btn-flat btn-danger">
                        <i class="fa fa-rotate-left"></i> Resetear
                    </button>
                    <button type="submit" id="submit" class="btn btn-flat bg-green">
                        <i class="fa fa-pencil"></i> Actualizar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<?= form_close(); ?>

<script src="<?= base_url() ?>assets/dist/js/app/master/profesor/edit.js"></script>