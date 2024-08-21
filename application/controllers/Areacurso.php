<?php
defined('BASEPATH') or exit('No direct script access allowed');

class areacurso extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		if (!$this->ion_auth->logged_in()) {
			redirect('auth');
		} else if (!$this->ion_auth->is_admin()) {
			show_error('Solo los Administradores están autorizados a acceder a esta página, <a href="' . base_url('pizarra') . '">Regresar al menú principal</a>', 403, 'Acceso prohibido');
		}
		$this->load->library(['datatables', 'form_validation']); // Load Library Ignited-Datatables
		$this->load->model('Master_model', 'master');
		$this->form_validation->set_error_delimiters('', '');
	}

	public function output_json($data, $encode = true)
	{
		if ($encode) $data = json_encode($data);
		$this->output->set_content_type('application/json')->set_output($data);
	}

	public function index()
	{
		$data = [
			'user' => $this->ion_auth->user()->row(),
			'judul'	=> 'Curso Área',
			'subjudul' => 'Datos Curso Area'
		];
		$this->load->view('_templates/pizarra/_header.php', $data);
		$this->load->view('relasi/areacurso/data');
		$this->load->view('_templates/pizarra/_footer.php');
	}

	public function data()
	{
		$this->output_json($this->master->getAreaCurso(), false);
	}

	public function getAreaId($id)
	{
		$this->output_json($this->master->getAllArea($id));
	}

	public function add()
	{
		$data = [
			'user' 		=> $this->ion_auth->user()->row(),
			'judul'		=> 'Agregar Curso al Área',
			'subjudul'	=> 'Agregar Datos de Curso al Área',
			'curso'	=> $this->master->getCurso()
		];
		$this->load->view('_templates/pizarra/_header.php', $data);
		$this->load->view('relasi/areacurso/add');
		$this->load->view('_templates/pizarra/_footer.php');
	}

	public function edit($id)
	{
		$data = [
			'user' 			=> $this->ion_auth->user()->row(),
			'judul'			=> 'Editar Curso de Departamento',
			'subjudul'		=> 'Edit Datos de Curso de Departamento.',
			'curso'		=> $this->master->getCursoById($id, true),
			'id_curso'		=> $id,
			'all_area'	=> $this->master->getAllArea(),
			'area'		=> $this->master->getAreaByIdCurso($id)
		];
		$this->load->view('_templates/pizarra/_header.php', $data);
		$this->load->view('relasi/areacurso/edit');
		$this->load->view('_templates/pizarra/_footer.php');
	}

	public function save()
	{
		$method = $this->input->post('method', true);
		$this->form_validation->set_rules('curso_id', 'Curso', 'required');
		$this->form_validation->set_rules('area_id[]', 'Área', 'required');

		if ($this->form_validation->run() == FALSE) {
			$data = [
				'status'	=> false,
				'errors'	=> [
					'curso_id' => form_error('curso_id'),
					'area_id[]' => form_error('area_id[]'),
				]
			];
			$this->output_json($data);
		} else {
			$curso_id 	= $this->input->post('curso_id', true);
			$area_id = $this->input->post('area_id', true);
			$input = [];
			foreach ($area_id as $key => $val) {
				$input[] = [
					'curso_id' 	=> $curso_id,
					'area_id'  	=> $val
				];
			}
			if ($method === 'add') {
				$action = $this->master->create('area_curso', $input, true);
			} else if ($method === 'edit') {
				$id = $this->input->post('curso_id', true);
				$this->master->delete('area_curso', $id, 'curso_id');
				$action = $this->master->create('area_curso', $input, true);
			}
			$data['status'] = $action ? TRUE : FALSE;
		}
		$this->output_json($data);
	}

	public function delete()
	{
		$chk = $this->input->post('checked', true);
		if (!$chk) {
			$this->output_json(['status' => false]);
		} else {
			if ($this->master->delete('area_curso', $chk, 'curso_id')) {
				$this->output_json(['status' => true, 'total' => count($chk)]);
			}
		}
	}
}
