<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">Formulario <?= $judul ?></h3>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
        </div>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-sm-offset-3 col-sm-6 col-lg-offset-4 col-lg-4">
                <div class="my-2">
                    <div class="form-horizontal form-inline">
                        <a href="<?= base_url('area') ?>" class="btn btn-default btn-xs">
                            <i class="fa fa-arrow-left"></i> Cancelar
                        </a>
                        <div class="pull-right">
                            <span> Monto : </span><label for=""><?= count($area) ?></label>
                        </div>
                    </div>
                </div>
                <?= form_open('area/save', array('id' => 'area'), array('mode' => 'edit')) ?>
                <table id="form-table" class="table text-center table-condensed">
                    <thead>
                        <tr>
                            <th># No</th>
                            <th>Área</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        foreach ($area as $j) : ?>
                            <tr>
                                <td><?= $no ?></td>
                                <td>
                                    <div class="form-group">
                                        <?= form_hidden('id_area[' . $no . ']', $j->id_area) ?>
                                        <input autofocus="autofocus" onfocus="this.select()" autocomplete="off" value="<?= $j->nombre_area ?>" type="text" name="nombre_area[<?= $no ?>]" class="input-sm form-control">
                                        <small class="help-block text-right"></small>
                                    </div>
                                </td>
                            </tr>
                        <?php
                            $no++;
                        endforeach;
                        ?>
                    </tbody>
                </table>
                <button type="submit" class="mb-4 btn btn-block btn-flat bg-purple">
                    <i class="fa fa-save"></i> Guardar Cambios
                </button>
                <?= form_close() ?>
            </div>
        </div>
    </div>
</div>

<script src="<?= base_url() ?>assets/dist/js/app/master/area/edit.js"></script>