<?php
defined('BASEPATH') or exit('No se permite el acceso directo al script');

class clase extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		if (!$this->ion_auth->logged_in()) {
			redirect('auth');
		} else if (!$this->ion_auth->is_admin()) {
			show_error('Solo los Administradores están autorizados a acceder a esta página, <a href="' . base_url('pizarra') . '">Volver al menú</a>', 403, 'Acceso Prohíbido');
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
			'judul'	=> 'Clase',
			'subjudul' => 'Datos de Clase'
		];
		$this->load->view('_templates/pizarra/_header.php', $data);
		$this->load->view('master/clase/data');
		$this->load->view('_templates/pizarra/_footer.php');
	}

	public function data()
	{
		$this->output_json($this->master->getDataClase(), false);
	}

	public function add()
	{
		$data = [
			'user' 		=> $this->ion_auth->user()->row(),
			'judul'		=> 'Agregar Clase',
			'subjudul'	=> 'Agregar Datos de Clase',
			'banyak'	=> $this->input->post('banyak', true),
			'area'	=> $this->master->getAllArea()
		];
		$this->load->view('_templates/pizarra/_header.php', $data);
		$this->load->view('master/clase/add');
		$this->load->view('_templates/pizarra/_footer.php');
	}

	public function edit()
	{
		$chk = $this->input->post('checked', true);
		if (!$chk) {
			redirect('admin/clase');
		} else {
			$clase = $this->master->getClaseById($chk);
			$data = [
				'user' 		=> $this->ion_auth->user()->row(),
				'judul'		=> 'Editar Clase',
				'subjudul'	=> 'Editar Datos de Clase',
				'area'	=> $this->master->getAllArea(),
				'clase'		=> $clase
			];
			$this->load->view('_templates/pizarra/_header.php', $data);
			$this->load->view('master/clase/edit');
			$this->load->view('_templates/pizarra/_footer.php');
		}
	}

	public function save()
	{
		$rows = count($this->input->post('nombre_clase', true));
		$mode = $this->input->post('mode', true);
		for ($i = 1; $i <= $rows; $i++) {
			$nombre_clase 	= 'nombre_clase[' . $i . ']';
			$area_id 	= 'area_id[' . $i . ']';
			$this->form_validation->set_rules($nombre_clase, 'Class', 'required');
			$this->form_validation->set_rules($area_id, 'Dept.', 'required');
			$this->form_validation->set_message('required', '{field} Required');

			if ($this->form_validation->run() === FALSE) {
				$error[] = [
					$nombre_clase 	=> form_error($nombre_clase),
					$area_id 	=> form_error($area_id),
				];
				$status = FALSE;
			} else {
				if ($mode == 'add') {
					$insert[] = [
						'nombre_clase' 	=> $this->input->post($nombre_clase, true),
						'area_id' 	=> $this->input->post($area_id, true)
					];
				} else if ($mode == 'edit') {
					$update[] = array(
						'id_clase'		=> $this->input->post('id_clase[' . $i . ']', true),
						'nombre_clase' 	=> $this->input->post($nombre_clase, true),
						'area_id' 	=> $this->input->post($area_id, true)
					);
				}
				$status = TRUE;
			}
		}
		if ($status) {
			if ($mode == 'add') {
				$this->master->create('clase', $insert, true);
				$data['insert']	= $insert;
			} else if ($mode == 'edit') {
				$this->master->update('clase', $update, 'id_clase', null, true);
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
			if ($this->master->delete('clase', $chk, 'id_clase')) {
				$this->output_json(['status' => true, 'total' => count($chk)]);
			}
		}
	}

	public function clase_by_area($id)
	{
		$data = $this->master->getClaseByArea($id);
		$this->output_json($data);
	}

	public function import($import_data = null)
	{
		$data = [
			'user' => $this->ion_auth->user()->row(),
			'judul'	=> 'Clase',
			'subjudul' => 'Importar Clase',
			'area' => $this->master->getAllarea()
		];
		if ($import_data != null) $data['import'] = $import_data;

		$this->load->view('_templates/pizarra/_header', $data);
		$this->load->view('master/clase/import');
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
					echo "Archivo de extensión desconocida";
					die;
			}

			$spreadsheet = $reader->load($file);
			$sheetData = $spreadsheet->getActiveSheet()->toArray();
			$data = [];
			for ($i = 1; $i < count($sheetData); $i++) {
				$data[] = [
					'clase' => $sheetData[$i][0],
					'area' => $sheetData[$i][1]
				];
			}

			unlink($file);

			$this->import($data);
		}
	}
	public function do_import()
	{
		$input = json_decode($this->input->post('data', true));
		$data = [];
		foreach ($input as $d) {
			$data[] = ['nombre_clase' => $d->clase, 'area_id' => $d->area];
		}

		$save = $this->master->create('clase', $data, true);
		if ($save) {
			redirect('clase');
		} else {
			redirect('clase/import');
		}
	}
}
