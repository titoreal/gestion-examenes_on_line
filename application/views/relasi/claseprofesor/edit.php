<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">Formulario <?= $judul ?></h3>
        <div class="box-tools pull-right">
            <a href="<?= base_url() ?>claseprofesor" class="btn btn-primary btn-flat btn-sm">
                <i class="fa fa-arrow-left"></i> Cancelar
            </a>
        </div>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-sm-4 col-sm-offset-4">
                <?= form_open('claseprofesor/save', array('id' => 'claseprofesor'), array('method' => 'edit', 'profesor_id' => $id_profesor)) ?>
                <div class="form-group">
                    <label>Profesor</label>
                    <input type="text" readonly="readonly" value="<?= $profesor->nombre_profesor ?>" class="form-control">
                    <small class="help-block text-right"></small>
                </div>
                <div class="form-group">
                    <label>Clase</label>
                    <select id="clase" multiple="multiple" name="clase_id[]" class="form-control select2" style="width: 100%!important">
                        <?php
                        $sk = [];
                        foreach ($clase as $key => $val) {
                            $sk[] = $val->id_clase;
                        }
                        foreach ($all_clase as $m) : ?>
                            <option <?= in_array($m->id_clase, $sk) ? "selected" : "" ?> value="<?= $m->id_clase ?>"><?= $m->nombre_clase ?> - <?= $m->nombre_area ?></option>
                        <?php endforeach; ?>
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

<script src="<?= base_url() ?>assets/dist/js/app/relasi/claseprofesor/edit.js"></script>