<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title"><?= $subjudul ?></h3>
        <div class="box-tools pull-right">
            <a href="<?= base_url() ?>resultados/master" class="btn btn-sm btn-flat btn-primary">
                <i class="fa fa-arrow-left"></i> Cancelar
            </a>
        </div>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-sm-4">
                <div class="alert bg-purple">
                    <h4>Curso <i class="fa fa-book pull-right"></i></h4>
                    <p><?= $curso->nombre_curso ?></p>
                </div>
                <div class="alert bg-purple">
                    <h4>Profesor <i class="fa fa-address-book-o pull-right"></i></h4>
                    <p><?= $profesor->nombre_profesor ?></p>
                </div>
            </div>
            <div class="col-sm-4">
                <?= form_open('resultados/save', array('id' => 'formresultados'), array('method' => 'add', 'profesor_id' => $profesor->id_profesor, 'curso_id' => $curso->curso_id)) ?>
                <div class="form-group">
                    <label for="nombre_resultados">Nombre de Examen</label>
                    <input autofocus="autofocus" onfocus="this.select()" placeholder="Nombre de Examen" type="text" class="form-control" name="nombre_resultados">
                    <small class="help-block"></small>
                </div>
                <div class="form-group">
                    <label for="total_preguntas">Número de planificacion</label>
                    <input placeholder="Número de planificacion" type="number" class="form-control" name="total_preguntas">
                    <small class="help-block"></small>
                </div>
                <div class="form-group">
                    <label for="fecha">Fecha de Inicio</label>
                    <input name="fecha" type="text" class="datetimepicker form-control" placeholder="Fecha de Inicio">
                    <small class="help-block"></small>
                </div>
                <div class="form-group">
                    <label for="fin">Fecha de realización</label>
                    <input name="fin" type="text" class="datetimepicker form-control" placeholder="Fecha de realización">
                    <small class="help-block"></small>
                </div>
                <div class="form-group">
                    <label for="duracion">Hora</label>
                    <input placeholder="en minutos" type="number" class="form-control" name="duracion">
                    <small class="help-block"></small>
                </div>
                <div class="form-group">
                    <label for="seleccion">Patrón de planificacion</label>
                    <select name="seleccion" class="form-control">
                        <option value="" disabled selected>--- Seleccionar ---</option>
                        <option value="Random">planificacion Aleatorias</option>
                        <option value="Sort">Ordenar planificacion</option>
                    </select>
                    <small class="help-block"></small>
                </div>
                <div class="form-group pull-right">
                    <button type="reset" class="btn btn-default btn-flat">
                        <i class="fa fa-rotate-left"></i> Resetear
                    </button>
                    <button id="submit" type="submit" class="btn btn-flat bg-purple"><i class="fa fa-save"></i> Guardar</button>
                </div>
                <?= form_close() ?>
            </div>
        </div>
    </div>
</div>

<script src="<?= base_url() ?>assets/dist/js/app/resultados/add.js"></script>