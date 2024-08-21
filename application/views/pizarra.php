<?php if ($this->ion_auth->is_admin()) : ?>
    <div class="row">
        <?php foreach ($info_box as $info) : ?>
            <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-<?= $info->box ?>">
                    <div class="inner">
                        <h3><?= $info->total; ?></h3>
                        <p><?= $info->text; ?></p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-<?= $info->icon ?>"></i>
                    </div>
                    <a href="<?= base_url() . strtolower($info->title); ?>" class="small-box-footer">
                        Más información <i class="fa fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

<?php elseif ($this->ion_auth->in_group('Tutor')) : ?>

    <div class="row">
        <div class="col-sm-4">
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title">Informacion de la Cuenta</h3>
                </div>
                <table class="table table-hover">
                    <tr>
                        <th>Nombre</th>
                        <td><?= $profesor->nombre_profesor ?></td>
                    </tr>
                    <tr>
                        <th>Código</th>
                        <td><?= $profesor->codigop ?></td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td><?= $profesor->email ?></td>
                    </tr>
                    <tr>
                        <th>Curso</th>
                        <td><?= $profesor->nombre_curso ?></td>
                    </tr>
                    <tr>
                        <th>Lista</th>
                        <td>
                            <ol class="pl-4">
                                <?php foreach ($clase as $k) : ?>
                                    <li><?= $k->nombre_clase ?></li>
                                <?php endforeach; ?>
                            </ol>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

<?php else : ?>

    <div class="row">
        <div class="col-sm-4">
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title">Información de la cuenta</h3>
                </div>
                <table class="table table-hover">
                    <tr>
                        <th>Código</th>
                        <td><?= $estudiante->codigoe ?></td>
                    </tr>
                    <tr>
                        <th>Nombre</th>
                        <td><?= $estudiante->nombre ?></td>
                    </tr>
                    <tr>
                        <th>Género</th>
                        <td><?= $estudiante->seleccion_kelamin === 'M' ? "Masculino" : "Femenino"; ?></td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td><?= $estudiante->email ?></td>
                    </tr>
                    <tr>
                        <th>Area</th>
                        <td><?= $estudiante->nombre_area ?></td>
                    </tr>
                    <tr>
                        <th>Clase</th>
                        <td><?= $estudiante->nombre_clase ?></td>
                    </tr>
                </table>
            </div>
        </div>

    </div>

<?php endif; ?>