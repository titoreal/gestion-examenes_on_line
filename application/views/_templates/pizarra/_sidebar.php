<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">

	<!-- sidebar: style can be found in sidebar.less -->
	<section class="sidebar">

		<!-- Sidebar user panel (optional) -->
		<div class="user-panel">
			<div class="pull-left image">
				<img src="<?= base_url() ?>assets/dist/img/usersys-min.png" class="img-circle" alt="User Image">
			</div>
			<div class="pull-left info">
				<p><?= $user->username ?></p>
				<small><?= $user->email ?></small>
			</div>
		</div>

		<ul class="sidebar-menu" data-widget="tree">
			<li class="header">Menú Principal</li>
			<!-- Optionally, you can add icons to the links -->
			<?php
			$page = $this->uri->segment(1);
			$master = ["area", "clase", "curso", "profesor", "estudiante"];
			$relasi = ["claseprofesor", "areacurso"];
			$users = ["users"];
			?>
			<li class="<?= $page === 'pizarra' ? "active" : "" ?>"><a href="<?= base_url('pizarra') ?>"><i class="fa fa-pizarra"></i> <span>pizarra</span></a></li>
			<?php if ($this->ion_auth->is_admin()) : ?>
				<li class="treeview <?= in_array($page, $master)  ? "active menu-open" : ""  ?>">
					<a href="#"><i class="fa fa-folder-open"></i> <span>Datos Principales</span>
						<span class="pull-right-container">
							<i class="fa fa-angle-left pull-right"></i>
						</span>
					</a>
					<ul class="treeview-menu">
						<li class="<?= $page === 'area' ? "active" : "" ?>">
							<a href="<?= base_url('area') ?>">
								<i class="fa fa-bars"></i>
								Área
							</a>
						</li>
						<li class="<?= $page === 'clase' ? "active" : "" ?>">
							<a href="<?= base_url('clase') ?>">
								<i class="fa fa-bars"></i>
								Clase
							</a>
						</li>
						<li class="<?= $page === 'curso' ? "active" : "" ?>">
							<a href="<?= base_url('curso') ?>">
								<i class="fa fa-bars"></i>
								Curso
							</a>
						</li>
						<li class="<?= $page === 'profesor' ? "active" : "" ?>">
							<a href="<?= base_url('profesor') ?>">
								<i class="fa fa-bars"></i>
								Profesor
							</a>
						</li>
						<li class="<?= $page === 'estudiante' ? "active" : "" ?>">
							<a href="<?= base_url('estudiante') ?>">
								<i class="fa fa-bars"></i>
								Estudiante
							</a>
						</li>
					</ul>
				</li>
				<li class="treeview <?= in_array($page, $relasi)  ? "active menu-open" : ""  ?>">
					<a href="#"><i class="fa fa-link"></i> <span>Relación</span>
						<span class="pull-right-container">
							<i class="fa fa-angle-left pull-right"></i>
						</span>
					</a>
					<ul class="treeview-menu">
						<li class="<?= $page === 'claseprofesor' ? "active" : "" ?>">
							<a href="<?= base_url('claseprofesor') ?>">
								<i class="fa fa-bars"></i>
								Clase - Profesor
							</a>
						</li>
						<li class="<?= $page === 'areacurso' ? "active" : "" ?>">
							<a href="<?= base_url('areacurso') ?>">
								<i class="fa fa-bars"></i>
								Área - Curso
							</a>
						</li>
					</ul>
				</li>
			<?php endif; ?>
			<?php if ($this->ion_auth->is_admin() || $this->ion_auth->in_group('Tutor')) : ?>
				<li class="<?= $page === 'planificacion' ? "active" : "" ?>">
					<a href="<?= base_url('planificacion') ?>" rel="noopener noreferrer">
						<i class="fa fa-file-text"></i> <span>Banco de preguntas</span>
					</a>
				</li>
			<?php endif; ?>
			<?php if ($this->ion_auth->in_group('Tutor')) : ?>
				<li class="<?= $page === 'resultados' ? "active" : "" ?>">
					<a href="<?= base_url('resultados/master') ?>" rel="noopener noreferrer">
						<i class="fa fa-pencil"></i> <span>Evaluación</span>
					</a>
				</li>
			<?php endif; ?>
			<?php if ($this->ion_auth->in_group('Student')) : ?>
				<li class="<?= $page === 'resultados' ? "active" : "" ?>">
					<a href="<?= base_url('resultados/list') ?>" rel="noopener noreferrer">
						<i class="fa fa-pencil"></i> <span>Evaluación</span>
					</a>
				</li>
			<?php endif; ?>
			<?php if (!$this->ion_auth->in_group('Student')) : ?>
				<li class="header">REPORTS</li>
				<li class="<?= $page === 'evaluacion' ? "active" : "" ?>">
					<a href="<?= base_url('evaluacion') ?>" rel="noopener noreferrer">
						<i class="fa fa-file"></i> <span>Resultados de la Evaluación</span>
					</a>
				</li>
			<?php endif; ?>
			<?php if ($this->ion_auth->is_admin()) : ?>
				<li class="header">ADMINISTRATOR</li>
				<li class="<?= $page === 'users' ? "active" : "" ?>">
					<a href="<?= base_url('users') ?>" rel="noopener noreferrer">
						<i class="fa fa-users"></i> <span>Administración de Usuarios</span>
					</a>
				</li>
				<li class="<?= $page === 'settings' ? "active" : "" ?>">
					<a href="<?= base_url('settings') ?>" rel="noopener noreferrer">
						<i class="fa fa-cogs"></i> <span>Configuración</span>
					</a>
				</li>
			<?php endif; ?>
		</ul>

	</section>
	<!-- /.sidebar -->
</aside>