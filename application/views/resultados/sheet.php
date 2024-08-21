<?php
if (time() >= $pregunta->duracion_habis) {
    redirect('resultados/list', 'location', 301);
}
?>
<div class="row">
    <div class="col-sm-3">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Question Navigation</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body text-center" id="tampil_respuesta">
            </div>
        </div>
    </div>
    <div class="col-sm-9">
        <?= form_open('', array('id' => 'resultados'), array('id' => $id_tes)); ?>
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><span class="badge bg-blue"># de Pregunta<span id="preguntake"></span> </span></h3>
                <div class="box-tools pull-right">
                    <span class="badge bg-red">Tiempo Faltante <span class="sisaduracion" data-time="<?= $pregunta->fin ?>"></span></span>
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body">
                <?= $html ?>
            </div>
            <div class="box-footer text-center">
                <a class="action back btn btn-info" rel="0" onclick="return back();"><i class="glyphicon glyphicon-chevron-left"></i> Volver</a>
                <a class="ragu_ragu btn btn-primary" rel="1" onclick="return tidak_jawab();">Dudoso</a>
                <a class="action next btn btn-info" rel="2" onclick="return next();"><i class="glyphicon glyphicon-chevron-right"></i> Siguiente</a>
                <a class="selesai action submit btn btn-danger" onclick="return simpan_akhir();"><i class="glyphicon glyphicon-stop"></i> Terminado</a>
                <input type="hidden" name="jml_pregunta" id="jml_pregunta" value="<?= $no; ?>">
            </div>
        </div>
        <?= form_close(); ?>
    </div>
</div>

<script type="text/javascript">
    var base_url = "<?= base_url(); ?>";
    var id_tes = "<?= $id_tes; ?>";
    var widget = $(".step");
    var total_widget = widget.length;
</script>

<script src="<?= base_url() ?>assets/dist/js/app/resultados/sheet.js"></script>