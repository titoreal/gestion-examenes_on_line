<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title"><?= $subjudul ?></h3>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
        </div>
    </div>
    <div class="box-body">
        <ul class="alert alert-info" style="padding-left: 40px">
            <li>Please import data from excel, using the provided format</li>
            <li>Data must not be empty, all must be filled in.</li>
            <li>For Class data, it can only be filled using Class ID. <a data-toggle="modal" href="#claseId" style="text-decoration:none" class="btn btn-xs btn-primary">View ID</a>.</li>
        </ul>
        <div class="text-center">
            <a href="<?= base_url('uploads/import/format/estudiante.xlsx') ?>" class="btn-default btn">Download Format</a>
        </div>
        <br>
        <div class="row">
            <?= form_open_multipart('estudiante/preview'); ?>
            <label for="file" class="col-sm-offset-1 col-sm-3 text-right">Choose File</label>
            <div class="col-sm-4">
                <div class="form-group">
                    <input type="file" name="upload_file">
                </div>
            </div>
            <div class="col-sm-3">
                <button name="preview" type="submit" class="btn btn-sm btn-success">Preview</button>
            </div>
            <?= form_close(); ?>
            <div class="col-sm-6 col-sm-offset-3">
                <?php if (isset($_POST['preview'])) : ?>
                    <br>
                    <h4>Preview Data</h4>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <td>No</td>
                                <td>codigoe</td>
                                <td>Name</td>
                                <td>Email</td>
                                <td>Gender</td>
                                <td>Class ID</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $status = true;
                                if (empty($import)) {
                                    echo '<tr><td colspan="2" class="text-center">Empty data! make sure you use the format provided.</td></tr>';
                                } else {
                                    $no = 1;
                                    foreach ($import as $data) :
                                        ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td class="<?= $data['codigoe'] == null ? 'bg-danger' : ''; ?>">
                                            <?= $data['codigoe'] == null ? 'NOT FILLED' : $data['codigoe']; ?>
                                        </td>
                                        <td class="<?= $data['nombre'] == null ? 'bg-danger' : ''; ?>">
                                            <?= $data['nombre'] == null ? 'NOT FILLED' : $data['nombre'];; ?>
                                        </td>
                                        <td class="<?= $data['email'] == null ? 'bg-danger' : ''; ?>">
                                            <?= $data['email'] == null ? 'NOT FILLED' : $data['email'];; ?>
                                        </td>
                                        <td class="<?= $data['seleccion_kelamin'] == null ? 'bg-danger' : ''; ?>">
                                            <?= $data['seleccion_kelamin'] == null ? 'NOT FILLED' : $data['seleccion_kelamin'];; ?>
                                        </td>
                                        <td class="<?= $data['clase_id'] == null ? 'bg-danger' : ''; ?>">
                                            <?= $data['clase_id'] == null ? 'NOT FILLED' : $data['clase_id'];; ?>
                                        </td>
                                    </tr>
                            <?php
                                        if ($data['codigoe'] == null || $data['nombre'] == null || $data['email'] == null || $data['seleccion_kelamin'] == null || $data['clase_id'] == null) {
                                            $status = false;
                                        }
                                    endforeach;
                                }
                                ?>
                        </tbody>
                    </table>
                    <?php if ($status) : ?>

                        <?= form_open('estudiante/do_import', null, ['data' => json_encode($import)]); ?>
                        <button type='submit' class='btn btn-block btn-flat bg-purple'>Import</button>
                        <?= form_close(); ?>

                    <?php endif; ?>
                    <br>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="claseId">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Class Data</h4>
            </div>
            <div class="modal-body">
                <table id="clase" class="table table-bordered table-condensed table-striped">
                    <thead>
                        <th>ID</th>
                        <th>Class</th>
                        <th>Dept</th>
                    </thead>
                    <tbody>
                        <?php foreach ($clase as $k) : ?>
                            <tr>
                                <td><?= $k->id_clase; ?></td>
                                <td><?= $k->nombre_clase; ?></td>
                                <td><?= $k->nombre_area; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        let table;
        table = $("#clase").DataTable({
            "lengthMenu": [
                [5, 10, 25, 50, 100, -1],
                [5, 10, 25, 50, 100, "All"]
            ],
        });
    });
</script>