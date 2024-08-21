<div class="callout callout-info">
    <h4>Exam Rules!</h4>
    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Maxime minus dolores accusantium fugiat debitis modi voluptates non consequuntur nemo expedita nihil laudantium commodi voluptatum voluptatem molestiae consectetur incidunt acodigoei, qui exercitationem? Nisi illo, magnam perferendis commodi consequuntur impedit, et nihil excepturi quas iste cum sunt debitis odio beatae placeat nemo..</p>
</div>
<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">Confirm Data</h3>
    </div>
    <div class="box-body">
        <span id="id_resultados" data-key="<?= $encrypted_id ?>"></span>
        <div class="row">
            <div class="col-sm-6">
                <table class="table table-bordered">
                    <tr>
                        <th>Name</th>
                        <td><?= $mhs->nombre ?></td>
                    </tr>
                    <tr>
                        <th>Tutor</th>
                        <td><?= $resultados->nombre_profesor ?></td>
                    </tr>
                    <tr>
                        <th>Class/Department</th>
                        <td><?= $mhs->nombre_clase ?> / <?= $mhs->nombre_area ?></td>
                    </tr>
                    <tr>
                        <th>Exam Name</th>
                        <td><?= $resultados->nombre_resultados ?></td>
                    </tr>
                    <tr>
                        <th>Number of Questions</th>
                        <td><?= $resultados->total_preguntas ?></td>
                    </tr>
                    <tr>
                        <th>Time</th>
                        <td><?= $resultados->duracion ?> Minute</td>
                    </tr>
                    <tr>
                        <th>Late</th>
                        <td>
                            <?= date('d M Y', strtotime($resultados->terlambat)) ?>
                            <?= date('h:i A', strtotime($resultados->terlambat)) ?>
                        </td>
                    </tr>
                    <tr>
                        <th style="vertical-align:middle">Token</th>
                        <td>
                            <input autocomplete="off" id="token" placeholder="Token" type="text" class="input-sm form-control">
                        </td>
                    </tr>
                </table>
            </div>
            <div class="col-sm-6">
                <div class="box box-solid">
                    <div class="box-body pb-0">
                        <div class="callout callout-info">
                            <p>
                                The time to take the exam is when the "START" button is green.
                            </p>
                        </div>
                        <?php
                        $mulai = strtotime($resultados->fecha);
                        $terlambat = strtotime($resultados->terlambat);
                        $now = time();
                        if ($mulai > $now) :
                        ?>
                            <div class="callout callout-success">
                                <strong><i class="fa fa-clock-o"></i> The exam will start on</strong>
                                <br>
                                <span class="countdown" data-time="<?= date('Y-m-d H:i:s', strtotime($resultados->fecha)) ?>">00 Days, 00 Hours, 00 Minutes, 00 Seconds</strong><br />
                            </div>
                        <?php elseif ($terlambat > $now) : ?>
                            <button id="btncek" data-id="<?= $resultados->id_resultados ?>" class="btn btn-success btn-lg mb-4">
                                <i class="fa fa-pencil"></i> Inicio
                            </button>
                            <div class="callout callout-danger">
                                <i class="fa fa-clock-o"></i> <strong class="countdown" data-time="<?= date('Y-m-d H:i:s', strtotime($resultados->terlambat)) ?>">00 Days, 00 Hours, 00 Minutes, 00 Seconds</strong><br />
                                Tiempo de espera para presionar el botón de inicio.
                            </div>
                        <?php else : ?>
                            <div class="callout callout-danger">
                                El momento de presionar el <strong>botón de inicio</strong> Inicio<br />
                                Póngase en contacto con su profesor para poder realizar el examen SUSTITUTO.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?= base_url() ?>assets/dist/js/app/resultados/token.js"></script>