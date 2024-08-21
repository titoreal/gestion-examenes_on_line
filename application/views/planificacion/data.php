<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title"><?= $subjudul ?></h3>
		<div class="box-tools pull-right">
			<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
			</button>
		</div>
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-sm-4">
				<button type="button" onclick="bulk_delete()" class="btn btn-flat btn-sm bg-red"><i class="fa fa-trash"></i> Eliminación Masiva</button>
			</div>
			<div class="form-group col-sm-4 text-center">
				<?php if ($this->ion_auth->is_admin()) : ?>
					<select id="curso_filter" class="form-control select2" style="width:100% !important">
						<option value="all">Todos los Cursos</option>
						<?php foreach ($curso as $m) : ?>
							<option value="<?= $m->id_curso ?>"><?= $m->nombre_curso ?></option>
						<?php endforeach; ?>
					</select>
				<?php endif; ?>
				<?php if ($this->ion_auth->in_group('Tutor')) : ?>
					<input id="curso_id" value="<?= $curso->nombre_curso; ?>" type="text" readonly="readonly" class="form-control">
				<?php endif; ?>
			</div>
			<div class="col-sm-4">
				<div class="pull-right">
					<a href="<?= base_url('pregunta/add') ?>" class="btn bg-blue btn-flat btn-sm"><i class="fa fa-plus"></i> Crear planificacion</a>
					<button type="button" onclick="reload_ajax()" class="btn btn-flat btn-sm bg-maroon"><i class="fa fa-refresh"></i> Recargar</button>
				</div>
			</div>
		</div>
	</div>
	<?= form_open('pregunta/delete', array('id' => 'bulk')) ?>
	<div class="table-responsive px-4 pb-3" style="border: 0">
		<table id="pregunta" class="w-100 table table-striped table-bordered table-hover">
			<thead>
				<tr>
					<th class="text-center">
						<input type="checkbox" class="select_all">
					</th>
					<th width="25">#</th>
					<th></th>
					<th>Curso</th>
					<th>planificacion</th>
					<th>Fecha Creación</th>
					<th class="text-center">Acción</th>
				</tr>
			</thead>
			<!-- <tfoot>
            <tr>
				<th class="text-center">
					<input type="checkbox" class="select_all">
				</th>
                <th width="25">#</th>
				<th>Tutor</th>
                <th>Course</th>
				<th>Question</th>
				<th>Created Date</th>
				<th class="text-center">Action</th>
            </tr>
        </tfoot> -->
		</table>
	</div>
	<?= form_close(); ?>
</div>

<script src="<?= base_url() ?>assets/dist/js/app/pregunta/data.js"></script>

<?php if ($this->ion_auth->is_admin()) : ?>
	<script type="text/javascript">
		$(document).ready(function() {
			$('#curso_filter').on('change', function() {
				let id_curso = $(this).val();
				let src = '<?= base_url() ?>pregunta/data';
				let url;

				if (id_curso !== 'all') {
					let src2 = src + '/' + id_curso;
					url = $(this).prop('checked') === true ? src : src2;
				} else {
					url = src;
				}
				table.ajax.url(url).load();
			});
		});
	</script>
<?php endif; ?>
<?php if ($this->ion_auth->in_group('Tutor')) : ?>
	<script type="text/javascript">
		$(document).ready(function() {
			let id_curso = '<?= $curso->curso_id ?>';
			let id_profesor = '<?= $curso->id_profesor ?>';
			let src = '<?= base_url() ?>pregunta/data';
			let url = src + '/' + id_curso + '/' + id_profesor;

			table.ajax.url(url).load();
		});
	</script>
<?php endif; ?>