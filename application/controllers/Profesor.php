<?php
defined('BASEPATH') or exit('No se permite el acceso directo al script');

class Profesor extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		if (!$this->ion_auth->logged_in()) {
			redirect('auth');
		} else if (!$this->ion_auth->is_admin()) {
			show_error('Solo los Administradores están autorizados a acceder a esta página, <a href="' . base_url('pizarra') . '">Volver al menú principal</a>', 403, 'Acceso Prohibido');
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
			'judul'	=> 'Profesor',
			'subjudul' => 'Datos Profesor'
		];
		$this->load->view('_templates/pizarra/_header.php', $data);
		$this->load->view('master/profesor/data');
		$this->load->view('_templates/pizarra/_footer.php');
	}

	public function data()
	{
		$this->output_json($this->master->getDataProfesor(), false);
	}

	public function add()
	{
		$data = [
			'user' => $this->ion_auth->user()->row(),
			'judul'	=> 'Agregar Profesor',
			'subjudul' => 'Agregar Datos de Profesor',
			'curso'	=> $this->master->getAllCurso()
		];
		$this->load->view('_templates/pizarra/_header.php', $data);
		$this->load->view('master/profesor/add');
		$this->load->view('_templates/pizarra/_footer.php');
	}

	public function edit($id)
	{
		$data = [
			'user' 		=> $this->ion_auth->user()->row(),
			'judul'		=> 'Editar Profesor',
			'subjudul'	=> 'Editar Datos de Profesor',
			'curso'	=> $this->master->getAllCurso(),
			'data' 		=> $this->master->getProfesorById($id)
		];
		$this->load->view('_templates/pizarra/_header.php', $data);
		$this->load->view('master/profesor/edit');
		$this->load->view('_templates/pizarra/_footer.php');
	}

	public function save()
	{
		$method 	= $this->input->post('method', true);
		$id_profesor 	= $this->input->post('id_profesor', true);
		$codigop 		= $this->input->post('codigop', true);
		$nombre_profesor = $this->input->post('nombre_profesor', true);
		$email 		= $this->input->post('email', true);
		$curso 	= $this->input->post('curso', true);
		if ($method == 'add') {
			$u_codigop = '|is_unique[profesor.codigop]';
			$u_email = '|is_unique[profesor.email]';
		} else {
			$dbdata 	= $this->master->getProfesorById($id_profesor);
			$u_codigop		= $dbdata->codigop === $codigop ? "" : "|is_unique[profesor.codigop]";
			$u_email	= $dbdata->email === $email ? "" : "|is_unique[profesor.email]";
		}
		$this->form_validation->set_rules('codigop', 'codigop', 'required|numeric|trim|min_length[8]|max_length[12]' . $u_codigop);
		$this->form_validation->set_rules('nombre_profesor', 'nombre profesor', 'required|trim|min_length[3]|max_length[50]');
		$this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email' . $u_email);
		$this->form_validation->set_rules('curso', 'Mata Kuliah', 'required');

		if ($this->form_validation->run() == FALSE) {
			$data = [
				'status'	=> false,
				'errors'	=> [
					'codigop' => form_error('codigop'),
					'nombre_profesor' => form_error('nombre_profesor'),
					'email' => form_error('email'),
					'curso' => form_error('curso'),
				]
			];
			$this->output_json($data);
		} else {
			$input = [
				'codigop'			=> $codigop,
				'nombre_profesor' 	=> $nombre_profesor,
				'email' 		=> $email,
				'curso_id' 	=> $curso
			];
			if ($method === 'add') {
				$action = $this->master->create('profesor', $input);
			} else if ($method === 'edit') {
				$action = $this->master->update('profesor', $input, 'id_profesor', $id_profesor);
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
			if ($this->master->delete('profesor', $chk, 'id_profesor')) {
				$this->output_json(['status' => true, 'total' => count($chk)]);
			}
		}
	}

	public function create_user()
	{
		$id = $this->input->get('id', true);
		$data = $this->master->getProfesorById($id);
		$nombre = explode(' ', $data->nombre_profesor);
		$first_name = $nombre[0];
		$last_name = end($nombre);

		$username = $data->codigop;
		$password = $data->codigop;
		$email = $data->email;
		$additional_data = [
			'first_name'	=> $first_name,
			'last_name'		=> $last_name
		];
		$group = array('2'); // Sets user to profesor.

		if ($this->ion_auth->username_check($username)) {
			$data = [
				'status' => false,
				'msg'	 => 'El nombre de usuario no está disponible (ya está en uso).'
			];
		} else if ($this->ion_auth->email_check($email)) {
			$data = [
				'status' => false,
				'msg'	 => 'Correo no está disponible (actualmente en uso).'
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
			'judul'	=> 'Profesor',
			'subjudul' => 'Importar Datos Profesor',
			'curso' => $this->master->getAllCurso()
		];
		if ($import_data != null) $data['import'] = $import_data;

		$this->load->view('_templates/pizarra/_header', $data);
		$this->load->view('master/profesor/import');
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
					'codigop' => $sheetData[$i][0],
					'nombre_profesor' => $sheetData[$i][1],
					'email' => $sheetData[$i][2],
					'curso_id' => $sheetData[$i][3]
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
				'codigop' => $d->codigop,
				'nombre_profesor' => $d->nombre_profesor,
				'email' => $d->email,
				'curso_id' => $d->curso_id
			];
		}

		$save = $this->master->create('profesor', $data, true);
		if ($save) {
			redirect('profesor');
		} else {
			redirect('profesor/import');
		}
	}
}
