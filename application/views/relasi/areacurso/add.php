<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">Formulario <?= $judul ?></h3>
        <div class="box-tools pull-right">
            <a href="<?= base_url() ?>areacurso" class="btn btn-primary btn-flat btn-sm">
                <i class="fa fa-arrow-left"></i> Cancelar
            </a>
        </div>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-sm-4">
                <div class="alert bg-purple">
                    <h4><i class="fa fa-info-circle"></i> Información</h4>
                    Si la columna Curso está vacía, las siguientes son posibles causas:
                    <br><br>
                    <ol class="pl-4">
                        <li>No ha agregado datos del curso principal (el curso principal está vacío/sin datos).</li>
                        <li>Se han agregado cursos, por lo que no necesita agregar más. Solo necesita editar los datos para el departamento del curso.</li>
                    </ol>
                </div>
            </div>
            <div class="col-sm-4">
                <?= form_open('areacurso/save', array('id' => 'areacurso'), array('method' => 'add')) ?>
                <div class="form-group">
                    <label>Curso</label>
                    <select name="curso_id" class="form-control select2" style="width: 100%!important">
                        <option value="" disabled selected></option>
                        <?php foreach ($curso as $m) : ?>
                            <option value="<?= $m->id_curso ?>"><?= $m->nombre_curso ?></option>
                        <?php endforeach; ?>
                    </select>
                    <small class="help-block text-right"></small>
                </div>
                <div class="form-group">
                    <label>Departmento</label>
                    <select id="area" multiple="multiple" name="area_id[]" class="form-control select2" style="width: 100%!important">
                    </select>
                    <small class="help-block text-right"></small>
                </div>
                <div class="form-group pull-right">
                    <button type="reset" class="btn btn-flat btn-default">
                        <i class="fa fa-rotate-left"></i> Resetear
                    </button>
                    <button id="submit" type="submit" class="btn btn-flat bg-purple">
                        <i class="fa fa-save"></i> Guardar
                    </button>
                </div>
                <?= form_close() ?>
            </div>
        </div>
    </div>
</div>

<script src="<?= base_url() ?>assets/dist/js/app/relasi/areacurso/add.js"></script>