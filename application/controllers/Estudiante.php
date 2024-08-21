<?php
defined('BASEPATH') or exit('No se permite el acceso directo al script');

class Estudiante extends CI_Controller
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
			'judul'	=> 'Estudiantes',
			'subjudul' => 'Datos del Estudiante'
		];
		$this->load->view('_templates/pizarra/_header.php', $data);
		$this->load->view('master/estudiante/data');
		$this->load->view('_templates/pizarra/_footer.php');
	}

	public function data()
	{
		$this->output_json($this->master->getDataEstudiante(), false);
	}

	public function add()
	{
		$data = [
			'user' => $this->ion_auth->user()->row(),
			'judul'	=> 'Estudiante',
			'subjudul' => 'Agregar Datos del Estudiante'
		];
		$this->load->view('_templates/pizarra/_header.php', $data);
		$this->load->view('master/estudiante/add');
		$this->load->view('_templates/pizarra/_footer.php');
	}

	public function edit($id)
	{
		$mhs = $this->master->getEstudianteById($id);
		$data = [
			'user' 		=> $this->ion_auth->user()->row(),
			'judul'		=> 'Estudiante',
			'subjudul'	=> 'Editar Datos del Estudiante',
			'area'	=> $this->master->getArea(),
			'clase'		=> $this->master->getClaseByArea($mhs->area_id),
			'estudiante' => $mhs
		];
		$this->load->view('_templates/pizarra/_header.php', $data);
		$this->load->view('master/estudiante/edit');
		$this->load->view('_templates/pizarra/_footer.php');
	}

	public function validasi_estudiante($method)
	{
		$id_estudiante 	= $this->input->post('id_estudiante', true);
		$codigoe 			= $this->input->post('codigoe', true);
		$email 			= $this->input->post('email', true);
		if ($method == 'add') {
			$u_codigoe = '|is_unique[estudiante.codigoe]';
			$u_email = '|is_unique[estudiante.email]';
		} else {
			$dbdata 	= $this->master->getEstudianteById($id_estudiante);
			$u_codigoe		= $dbdata->codigoe === $codigoe ? "" : "|is_unique[estudiante.codigoe]";
			$u_email	= $dbdata->email === $email ? "" : "|is_unique[estudiante.email]";
		}
		$this->form_validation->set_rules('codigoe', 'codigoe', 'required|numeric|trim|min_length[8]|max_length[12]' . $u_codigoe);
		$this->form_validation->set_rules('nombre', 'Nombre', 'required|trim|min_length[3]|max_length[50]');
		$this->form_validation->set_rules('email', 'Correo', 'required|trim|valid_email' . $u_email);
		$this->form_validation->set_rules('seleccion_kelamin', 'Género', 'required');
		$this->form_validation->set_rules('area', 'Área', 'required');
		$this->form_validation->set_rules('clase', 'Clase', 'required');

		$this->form_validation->set_message('required', 'Kolom {field} wajib diisi');
	}

	public function save()
	{
		$method = $this->input->post('method', true);
		$this->validasi_estudiante($method);

		if ($this->form_validation->run() == FALSE) {
			$data = [
				'status'	=> false,
				'errors'	=> [
					'codigoe' => form_error('codigoe'),
					'nombre' => form_error('nombre'),
					'email' => form_error('email'),
					'seleccion_kelamin' => form_error('seleccion_kelamin'),
					'area' => form_error('area'),
					'clase' => form_error('clase'),
				]
			];
			$this->output_json($data);
		} else {
			$input = [
				'codigoe' 			=> $this->input->post('codigoe', true),
				'email' 		=> $this->input->post('email', true),
				'nombre' 			=> $this->input->post('nombre', true),
				'seleccion_kelamin' => $this->input->post('seleccion_kelamin', true),
				'clase_id' 		=> $this->input->post('clase', true),
			];
			if ($method === 'add') {
				$action = $this->master->create('estudiante', $input);
			} else if ($method === 'edit') {
				$id = $this->input->post('id_estudiante', true);
				$action = $this->master->update('estudiante', $input, 'id_estudiante', $id);
			}

			if ($action) {
				$this->output_json(['status' => true]);
			} else {
				$this->output_json(['status' => false]);
			}
		}
	}

	public function delete()
	{
		$chk = $this->input->post('checked', true);
		if (!$chk) {
			$this->output_json(['status' => false]);
		} else {
			if ($this->master->delete('estudiante', $chk, 'id_estudiante')) {
				$this->output_json(['status' => true, 'total' => count($chk)]);
			}
		}
	}

	public function create_user()
	{
		$id = $this->input->get('id', true);
		$data = $this->master->getEstudianteById($id);
		$nombre = explode(' ', $data->nombre);
		$first_name = $nombre[0];
		$last_name = end($nombre);

		$username = $data->codigoe;
		$password = $data->codigoe;
		$email = $data->email;
		$additional_data = [
			'first_name'	=> $first_name,
			'last_name'		=> $last_name
		];
		$group = array('3'); // Sets user to profesor.

		if ($this->ion_auth->username_check($username)) {
			$data = [
				'status' => false,
				'msg'	 => 'Nombre de usuario no disponible (ya utilizado).'
			];
		} else if ($this->ion_auth->email_check($email)) {
			$data = [
				'status' => false,
				'msg'	 => 'El correo electrónico no está disponible (ya está en uso).'
			];
		} else {
			$this->ion_auth->register($username, $password, $email, $additional_data, $group);
			$data = [
				'status'	=> true,
				'msg'	 => 'Usuario creado con éxito. codigop se utiliza como contraseña al iniciar sesión.'
			];
		}
		$this->output_json($data);
	}

	public function import($import_data = null)
	{
		$data = [
			'user' => $this->ion_auth->user()->row(),
			'judul'	=> 'Estudiante',
			'subjudul' => 'Importar Datos del Estudiante',
			'clase' => $this->master->getAllClase()
		];
		if ($import_data != null) $data['import'] = $import_data;

		$this->load->view('_templates/pizarra/_header', $data);
		$this->load->view('master/estudiante/import');
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
					echo "unknown file ext";
					die;
			}

			$spreadsheet = $reader->load($file);
			$sheetData = $spreadsheet->getActiveSheet()->toArray();
			$data = [];
			for ($i = 1; $i < count($sheetData); $i++) {
				$data[] = [
					'codigoe' => $sheetData[$i][0],
					'nombre' => $sheetData[$i][1],
					'email' => $sheetData[$i][2],
					'seleccion_kelamin' => $sheetData[$i][3],
					'clase_id' => $sheetData[$i][4]
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
			$data[] = [
				'codigoe' => $d->codigoe,
				'nombre' => $d->nombre,
				'email' => $d->email,
				'seleccion_kelamin' => $d->seleccion_kelamin,
				'clase_id' => $d->clase_id
			];
		}

		$save = $this->master->create('estudiante', $data, true);
		if ($save) {
			redirect('estudiante');
		} else {
			redirect('estudiante/import');
		}
	}
}
