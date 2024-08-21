<?php
defined('BASEPATH') or exit('No se permite el acceso directo al script');

class Pizarra extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		if (!$this->ion_auth->logged_in()) {
			redirect('auth');
		}
		$this->load->model('Pizarra_model', 'pizarra');
		$this->user = $this->ion_auth->user()->row();
	}

	public function admin_box()
	{
		$box = [
			[
				'box' 		=> 'green',
				'total' 	=> $this->pizarra->total('area'),
				'title'		=> 'area',
				'text'      => 'Áreas',
				'icon'		=> 'th-large'
			],
			[
				'box' 		=> 'yellow',
				'total' 	=> $this->pizarra->total('clase'),
				'title'		=> 'clase',
				'text'      => 'Clase',
				'icon'		=> 'building-o'
			],
			[
				'box' 		=> 'red',
				'total' 	=> $this->pizarra->total('profesor'),
				'title'		=> 'profesor',
				'text'      => 'Profesores',
				'icon'		=> 'users'
			],
			[
				'box' 		=> 'blue',
				'total' 	=> $this->pizarra->total('estudiante'),
				'title'		=> 'estudiante',
				'text'      => 'Estudiantes',
				'icon'		=> 'graduation-cap'
			],
			[
				'box' 		=> 'aqua',
				'total' 	=> $this->pizarra->total('curso'),
				'title'		=> 'curso',
				'text'      => 'Cursos',
				'icon'		=> 'th'
			],
			[
				'box' 		=> 'purple',
				'total' 	=> $this->pizarra->total('planificacion'),
				'title'		=> 'planificacion',
				'text'      => 'Preguntas',
				'icon'		=> 'file-text'
			],
			[
				'box' 		=> 'olive',
				'total' 	=> $this->pizarra->total('evaluacion'),
				'title'		=> 'evaluacion',
				'text'      => 'Resultados Generados',
				'icon'		=> 'file'
			],
			[
				'box' 		=> 'black',
				'total' 	=> $this->pizarra->total('users'),
				'title'		=> 'users',
				'text'      => 'Usuarios del Sistema',
				'icon'		=> 'key'
			],
		];
		$info_box = json_decode(json_encode($box), FALSE);
		return $info_box;
	}

	public function index()
	{
		$user = $this->user;
		$data = [
			'user' 		=> $user,
			'judul'		=> 'pizarra',
			'subjudul'	=> 'Datos de Aplicación',
		];

		if ($this->ion_auth->is_admin()) {
			$data['info_box'] = $this->admin_box();
		} elseif ($this->ion_auth->in_group('Tutor')) {
			$curso = ['curso' => 'profesor.curso_id=curso.id_curso'];
			$data['profesor'] = $this->pizarra->get_where('profesor', 'codigop', $user->username, $curso)->row();

			$clase = ['clase' => 'clase_profesor.clase_id=clase.id_clase'];
			$data['clase'] = $this->pizarra->get_where('clase_profesor', 'profesor_id', $data['profesor']->id_profesor, $clase, ['nombre_clase' => 'ASC'])->result();
		} else {
			$join = [
				'clase b' 	=> 'a.clase_id = b.id_clase',
				'area c'	=> 'b.area_id = c.id_area'
			];
			$data['estudiante'] = $this->pizarra->get_where('estudiante a', 'codigoe', $user->username, $join)->row();
		}

		$this->load->view('_templates/pizarra/_header.php', $data);
		$this->load->view('pizarra');
		$this->load->view('_templates/pizarra/_footer.php');
	}
}
