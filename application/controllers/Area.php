<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Writer\Csv;

class area extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		if (!$this->ion_auth->logged_in()) {
			redirect('auth');
		} else if (!$this->ion_auth->is_admin()) {
			show_error('Solo los Administradores están autorizados a acceder a esta página, <a href="' . base_url('pizarra') . '">Volver al menú</a>', 403, 'Acceso Prohibido');
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
			'judul'	=> 'Áreas',
			'subjudul' => 'Datos de las Áreas'
		];
		$this->load->view('_templates/pizarra/_header', $data);
		$this->load->view('master/area/data');
		$this->load->view('_templates/pizarra/_footer');
	}

	public function add()
	{
		$data = [
			'user' 		=> $this->ion_auth->user()->row(),
			'judul'		=> 'Agregar Área',
			'subjudul'	=> 'Agregar Datos de Área',
			'banyak'	=> $this->input->post('banyak', true)
		];
		$this->load->view('_templates/pizarra/_header', $data);
		$this->load->view('master/area/add');
		$this->load->view('_templates/pizarra/_footer');
	}

	public function data()
	{
		$this->output_json($this->master->getDataArea(), false);
	}

	public function edit()
	{
		$chk = $this->input->post('checked', true);
		if (!$chk) {
			redirect('area');
		} else {
			$area = $this->master->getAreaById($chk);
			$data = [
				'user' 		=> $this->ion_auth->user()->row(),
				'judul'		=> 'Editar Área',
				'subjudul'	=> 'Editar Datos del Área',
				'area'	=> $area
			];
			$this->load->view('_templates/pizarra/_header', $data);
			$this->load->view('master/area/edit');
			$this->load->view('_templates/pizarra/_footer');
		}
	}

	public function save()
	{
		$rows = count($this->input->post('nombre_area', true));
		$mode = $this->input->post('mode', true);
		for ($i = 1; $i <= $rows; $i++) {
			$nombre_area = 'nombre_area[' . $i . ']';
			$this->form_validation->set_rules($nombre_area, 'Dept.', 'required');
			$this->form_validation->set_message('required', '{field} Required');

			if ($this->form_validation->run() === FALSE) {
				$error[] = [
					$nombre_area => form_error($nombre_area)
				];
				$status = FALSE;
			} else {
				if ($mode == 'add') {
					$insert[] = [
						'nombre_area' => $this->input->post($nombre_area, true)
					];
				} else if ($mode == 'edit') {
					$update[] = array(
						'id_area'	=> $this->input->post('id_area[' . $i . ']', true),
						'nombre_area' 	=> $this->input->post($nombre_area, true)
					);
				}
				$status = TRUE;
			}
		}
		if ($status) {
			if ($mode == 'add') {
				$this->master->create('area', $insert, true);
				$data['insert']	= $insert;
			} else if ($mode == 'edit') {
				$this->master->update('area', $update, 'id_area', null, true);
				$data['update'] = $update;
			}
		} else {
			if (isset($error)) {
				$data['errors'] = $error;
			}
		}
		$data['status'] = $status;
		$this->output_json($data);
	}

	public function delete()
	{
		$chk = $this->input->post('checked', true);
		if (!$chk) {
			$this->output_json(['status' => false]);
		} else {
			if ($this->master->delete('area', $chk, 'id_area')) {
				$this->output_json(['status' => true, 'total' => count($chk)]);
			}
		}
	}

	public function load_area()
	{
		$data = $this->master->getArea();
		$this->output_json($data);
	}

	public function import($import_data = null)
	{
		$data = [
			'user' => $this->ion_auth->user()->row(),
			'judul'	=> 'Área',
			'subjudul' => 'Importar Área'
		];
		if ($import_data != null) $data['import'] = $import_data;

		$this->load->view('_templates/pizarra/_header', $data);
		$this->load->view('master/area/import');
		$this->load->view('_templates/pizarra/_footer');
	}

	public function preview()
	{
		$config['upload_path']		= './uploads/import/';
		$config['allowed_types']	= 'xls|xlsx|csv';
		$config['max_size']			= 2048;
		$config['encrypt_name']		= true;

		$this->load->library('upload', $config);

		if (!$this->upload->do_upload('upload_file')) {
			$error = $this->upload->display_errors();
			echo $error;
			die;
		} else {
			$file = $this->upload->data('full_path');
			$ext = $this->upload->data('file_ext');

			switch ($ext) {
				case '.xlsx':
					$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
					break;
				case '.xls':
					$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
					break;
				case '.csv':
					$reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
					break;
				default:
					echo "Extensión de archivo irreconocible";
					die;
			}

			$spreadsheet = $reader->load($file);
			$sheetData = $spreadsheet->getActiveSheet()->toArray();
			$area = [];
			for ($i = 1; $i < count($sheetData); $i++) {
				if ($sheetData[$i][0] != null) {
					$area[] = $sheetData[$i][0];
				}
			}

			unlink($file);

			$this->import($area);
		}
	}
	public function do_import()
	{
		$data = json_decode($this->input->post('area', true));
		$area = [];
		foreach ($data as $j) {
			$area[] = ['nombre_area' => $j];
		}

		$save = $this->master->create('area', $area, true);
		if ($save) {
			redirect('area');
		} else {
			redirect('area/import');
		}
	}
}
