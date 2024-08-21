<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">Formulario <?= $judul ?></h3>
        <div class="box-tools pull-right">
            <a href="<?= base_url('estudiante') ?>" class="btn btn-sm btn-flat btn-primary">
                <i class="fa fa-arrow-left"></i> Cancelar
            </a>
        </div>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-sm-4 col-sm-offset-4">
                <?= form_open('estudiante/save', array('id' => 'estudiante'), array('method' => 'edit', 'id_estudiante' => $estudiante->id_estudiante)) ?>
                <div class="form-group">
                    <label for="codigoe">codigoe</label>
                    <input value="<?= $estudiante->codigoe ?>" autofocus="autofocus" onfocus="this.select()" placeholder="codigoe" type="text" name="codigoe" class="form-control">
                    <small class="help-block"></small>
                </div>
                <div class="form-group">
                    <label for="nombre">Nombre</label>
                    <input value="<?= $estudiante->nombre ?>" placeholder="Nombre" type="text" name="nombre" class="form-control">
                    <small class="help-block"></small>
                </div>
                <div class="form-group">
                    <label for="email">Correo</label>
                    <input value="<?= $estudiante->email ?>" placeholder="Correo" type="email" name="email" class="form-control">
                    <small class="help-block"></small>
                </div>
                <div class="form-group">
                    <label for="seleccion_kelamin">Género</label>
                    <select name="seleccion_kelamin" class="form-control select2">
                        <option value="">-- Seleccionar --</option>
                        <option <?= $estudiante->seleccion_kelamin === "M" ? "selected" : "" ?> value="M">Masculino</option>
                        <option <?= $estudiante->seleccion_kelamin === "F" ? "selected" : "" ?> value="F">Femenino</option>
                    </select>
                    <small class="help-block"></small>
                </div>
                <div class="form-group">
                    <label for="area">Departmento</label>
                    <select id="area" name="area" class="form-control select2">
                        <option value="" disabled selected>-- Seleccionar --</option>
                        <?php foreach ($area as $j) : ?>
                            <option <?= $estudiante->id_area === $j->id_area ? "selected" : "" ?> value="<?= $j->id_area ?>">
                                <?= $j->nombre_area ?>
                            </option>
                        <?php endforeach ?>
                    </select>
                    <small class="help-block"></small>
                </div>
                <div class="form-group">
                    <label for="clase">Clase</label>
                    <select id="clase" name="clase" class="form-control select2">
                        <option value="" disabled selected>-- Seleccionar --</option>
                        <?php foreach ($clase as $k) : ?>
                            <option <?= $estudiante->id_clase === $k->id_clase ? "selected" : "" ?> value="<?= $k->id_clase ?>">
                                <?= $k->nombre_clase ?>
                            </option>
                        <?php endforeach ?>
                    </select>
                    <small class="help-block"></small>
                </div>
                <div class="form-group pull-right">
                    <button type="reset" class="btn btn-flat btn-danger"><i class="fa fa-rotate-left"></i> Resetear</button>
                    <button type="submit" id="submit" class="btn btn-flat bg-green"><i class="fa fa-save"></i> Guardar</button>
                </div>
                <?= form_close() ?>
            </div>
        </div>
    </div>
</div>

<script src="<?= base_url() ?>assets/dist/js/app/master/estudiante/edit.js"></script>