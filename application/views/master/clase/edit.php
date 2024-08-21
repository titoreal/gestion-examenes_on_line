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
            <div class="col-sm-offset-3 col-sm-6">
                <div class="my-2">
                    <div class="form-horizontal form-inline">
                        <a href="<?= base_url('clase') ?>" class="btn btn-default btn-xs">
                            <i class="fa fa-arrow-left"></i> Cancelar
                        </a>
                        <div class="pull-right">
                            <span> Monto : </span><label for=""><?= count($clase) ?></label>
                        </div>
                    </div>
                </div>
                <?= form_open('clase/save', array('id' => 'clase'), array('mode' => 'edit')) ?>
                <table id="form-table" class="table text-center table-condensed">
                    <thead>
                        <tr>
                            <th># No</th>
                            <th>Clase</th>
                            <th>Área</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        foreach ($clase as $row) : ?>
                            <tr>
                                <td><?= $i ?></td>
                                <td>
                                    <div class="form-group">
                                        <?= form_hidden('id_clase[' . $i . ']', $row->id_clase); ?>
                                        <input required="required" autofocus="autofocus" onfocus="this.select()" value="<?= $row->nombre_clase ?>" type="text" name="nombre_clase[<?= $i ?>]" class="form-control">
                                        <span class="d-none">No eliminar esto</span>
                                        <small class="help-block text-right"></small>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <select required="required" name="area_id[<?= $i ?>]" class="input-sm form-control select2" style="width: 100%!important">
                                            <option value="" disabled>-- Escoger --</option>
                                            <?php foreach ($area as $j) : ?>
                                                <option <?= $row->area_id == $j->id_area ? "selected='selected'" : "" ?> value="<?= $j->id_area ?>"><?= $j->nombre_area ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <small class="help-block text-right"></small>
                                    </div>
                                </td>
                            </tr>
                        <?php $i++;
                        endforeach; ?>
                    </tbody>
                </table>
                <button id="submit" type="submit" class="mb-4 btn btn-block btn-flat bg-purple">
                    <i class="fa fa-edit"></i> Guardar Cambios
                </button>
                <?= form_close() ?>
            </div>
        </div>
    </div>
</div>

<script src="<?= base_url() ?>assets/dist/js/app/master/clase/edit.js"></script>