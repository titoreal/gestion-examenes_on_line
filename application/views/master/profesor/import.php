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
            <li>For course data, it can only be filled in using the course ID. <a data-toggle="modal" href="#cursoId" style="text-decoration:none" class="btn btn-xs btn-primary">View ID</a>.</li>
        </ul>
        <div class="text-center">
            <a href="<?= base_url('uploads/import/format/profesor.xlsx') ?>" class="btn-default btn">Download Format</a>
        </div>
        <br>
        <div class="row">
            <?= form_open_multipart('profesor/preview'); ?>
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
                                <td>codigop</td>
                                <td>Name</td>
                                <td>Email</td>
                                <td>Course ID</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $status = true;
                            if (empty($import)) {
                                echo '<tr><td colspan="2" class="text-center">¡Datos vacíos! Asegúrese de utilizar el formato proporcionado.</td></tr>';
                            } else {
                                $no = 1;
                                foreach ($import as $data) :
                            ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td class="<?= $data['codigop'] == null ? 'bg-danger' : ''; ?>">
                                            <?= $data['codigop'] == null ? 'NOT FILLED' : $data['codigop']; ?>
                                        </td>
                                        <td class="<?= $data['nombre_profesor'] == null ? 'bg-danger' : ''; ?>">
                                            <?= $data['nombre_profesor'] == null ? 'NOT FILLED' : $data['nombre_profesor'];; ?>
                                        </td>
                                        <td class="<?= $data['email'] == null ? 'bg-danger' : ''; ?>">
                                            <?= $data['email'] == null ? 'NOT FILLED' : $data['email'];; ?>
                                        </td>
                                        <td class="<?= $data['curso_id'] == null ? 'bg-danger' : ''; ?>">
                                            <?= $data['curso_id'] == null ? 'NOT FILLED' : $data['curso_id'];; ?>
                                        </td>
                                    </tr>
                            <?php
                                    if ($data['codigop'] == null || $data['nombre_profesor'] == null || $data['email'] == null || $data['curso_id'] == null) {
                                        $status = false;
                                    }
                                endforeach;
                            }
                            ?>
                        </tbody>
                    </table>
                    <?php if ($status) : ?>

                        <?= form_open('profesor/do_import', null, ['data' => json_encode($import)]); ?>
                        <button type='submit' class='btn btn-block btn-flat bg-purple'>Importar</button>
                        <?= form_close(); ?>

                    <?php endif; ?>
                    <br>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="cursoId">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Datos del Curso</h4>
            </div>
            <div class="modal-body">
                <table id="curso" class="table table-condensed table-striped">
                    <thead>
                        <th>ID</th>
                        <th>Curso</th>
                    </thead>
                    <tbody>
                        <?php foreach ($curso as $m) : ?>
                            <tr>
                                <td><?= $m->id_curso; ?></td>
                                <td><?= $m->nombre_curso; ?></td>
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
        table = $("#curso").DataTable({
            "lengthMenu": [
                [5, 10, 25, 50, 100, -1],
                [5, 10, 25, 50, 100, "All"]
            ],
        });
    });
</script>