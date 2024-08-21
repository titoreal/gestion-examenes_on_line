<?php
defined('BASEPATH') or exit('No direct script access allowed');

class claseprofesor extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		if (!$this->ion_auth->logged_in()) {
			redirect('auth');
		} else if (!$this->ion_auth->is_admin()) {
			show_error('Sólo los administradores pueden acceder a éste lugar, <a href="' . base_url('pizarra') . '">Back to main menu</a>', 403, 'Forbidden Access');
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
			'judul'	=> 'Clase Profesor',
			'subjudul' => 'Datos de Clase Profesor'
		];
		$this->load->view('_templates/pizarra/_header.php', $data);
		$this->load->view('relasi/claseprofesor/data');
		$this->load->view('_templates/pizarra/_footer.php');
	}

	public function data()
	{
		$this->output_json($this->master->getClaseProfesor(), false);
	}

	public function add()
	{
		$data = [
			'user' 		=> $this->ion_auth->user()->row(),
			'judul'		=> 'Agregar Profesor a Clase',
			'subjudul'	=> 'Agregar Datos de Profesor a Clase',
			'profesor'		=> $this->master->getAllProfesor(),
			'clase'	    => $this->master->getAllClase()
		];
		$this->load->view('_templates/pizarra/_header.php', $data);
		$this->load->view('relasi/claseprofesor/add');
		$this->load->view('_templates/pizarra/_footer.php');
	}

	public function edit($id)
	{
		$data = [
			'user' 			=> $this->ion_auth->user()->row(),
			'judul'			=> 'Editar Clase de Profesor',
			'subjudul'		=> 'Editar datos de clase del profesor',
			'profesor'			=> $this->master->getProfesorById($id),
			'id_profesor'		=> $id,
			'all_clase'	    => $this->master->getAllClase(),
			'clase'		    => $this->master->getClaseByProfesor($id)
		];
		$this->load->view('_templates/pizarra/_header.php', $data);
		$this->load->view('relasi/claseprofesor/edit');
		$this->load->view('_templates/pizarra/_footer.php');
	}

	public function save()
	{
		$method = $this->input->post('method', true);
		$this->form_validation->set_rules('profesor_id', 'Tutor', 'required');
		$this->form_validation->set_rules('clase_id[]', 'Class', 'required');

		if ($this->form_validation->run() == FALSE) {
			$data = [
				'status'	=> false,
				'errors'	=> [
					'profesor_id' => form_error('profesor_id'),
					'clase_id[]' => form_error('clase_id[]'),
				]
			];
			$this->output_json($data);
		} else {
			$profesor_id = $this->input->post('profesor_id', true);
			$clase_id = $this->input->post('clase_id', true);
			$input = [];
			foreach ($clase_id as $key => $val) {
				$input[] = [
					'profesor_id'  => $profesor_id,
					'clase_id' => $val
				];
			}
			if ($method === 'add') {
				$action = $this->master->create('clase_profesor', $input, true);
			} else if ($method === 'edit') {
				$id = $this->input->post('profesor_id', true);
				$this->master->delete('clase_profesor', $id, 'profesor_id');
				$action = $this->master->create('clase_profesor', $input, true);
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
			if ($this->master->delete('clase_profesor', $chk, 'profesor_id')) {
				$this->output_json(['status' => true, 'total' => count($chk)]);
			}
		}
	}
}
