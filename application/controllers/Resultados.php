<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Resultados extends CI_Controller
{

	public $mhs, $user;

	public function __construct()
	{
		parent::__construct();
		if (!$this->ion_auth->logged_in()) {
			redirect('auth');
		}
		$this->load->library(['datatables', 'form_validation']); // Load Library Ignited-Datatables
		$this->load->helper('my');
		$this->load->model('Master_model', 'master');
		$this->load->model('Planificacion_model', 'planificacion');
		$this->load->model('Resultados_model', 'resultados');
		$this->form_validation->set_error_delimiters('', '');

		$this->user = $this->ion_auth->user()->row();
		$this->mhs 	= $this->resultados->getIdEstudiante($this->user->username);
	}

	public function akses_profesor()
	{
		if (!$this->ion_auth->in_group('Tutor')) {
			show_error('This page is specifically for Tutors to make an Online Test, <a href="' . base_url('pizarra') . '">Back to main menu</a>', 403, 'Forbidden Access');
		}
	}

	public function akses_estudiante()
	{
		if (!$this->ion_auth->in_group('Student')) {
			show_error('This page is specifically for students taking the exam, <a href="' . base_url('pizarra') . '">Back to main menu</a>', 403, 'Forbidden Access');
		}
	}

	public function output_json($data, $encode = true)
	{
		if ($encode) $data = json_encode($data);
		$this->output->set_content_type('application/json')->set_output($data);
	}

	public function json($id = null)
	{
		$this->akses_profesor();

		$this->output_json($this->resultados->getDataResultados($id), false);
	}

	public function master()
	{
		$this->akses_profesor();
		$user = $this->ion_auth->user()->row();
		$data = [
			'user' => $user,
			'judul'	=> 'Exam',
			'subjudul' => 'Exam Data',
			'profesor' => $this->resultados->getIdProfesor($user->username),
		];
		$this->load->view('_templates/pizarra/_header.php', $data);
		$this->load->view('resultados/data');
		$this->load->view('_templates/pizarra/_footer.php');
	}

	public function add()
	{
		$this->akses_profesor();

		$user = $this->ion_auth->user()->row();

		$data = [
			'user' 		=> $user,
			'judul'		=> 'Exam',
			'subjudul'	=> 'Add Exam',
			'curso'	=> $this->planificacion->getCursoProfesor($user->username),
			'profesor'		=> $this->resultados->getIdProfesor($user->username),
		];

		$this->load->view('_templates/pizarra/_header.php', $data);
		$this->load->view('resultados/add');
		$this->load->view('_templates/pizarra/_footer.php');
	}

	public function edit($id)
	{
		$this->akses_profesor();

		$user = $this->ion_auth->user()->row();

		$data = [
			'user' 		=> $user,
			'judul'		=> 'Exam',
			'subjudul'	=> 'Edit Exam',
			'curso'	=> $this->planificacion->getCursoProfesor($user->username),
			'profesor'		=> $this->resultados->getIdProfesor($user->username),
			'resultados'		=> $this->resultados->getResultadosById($id),
		];

		$this->load->view('_templates/pizarra/_header.php', $data);
		$this->load->view('resultados/edit');
		$this->load->view('_templates/pizarra/_footer.php');
	}

	public function convert_tgl($tgl)
	{
		$this->akses_profesor();
		return date('Y-m-d H:i:s', strtotime($tgl));
	}

	public function validasi()
	{
		$this->akses_profesor();

		$user 	= $this->ion_auth->user()->row();
		$profesor 	= $this->resultados->getIdProfesor($user->username);
		$jml 	= $this->resultados->getTotalPreguntas($profesor->id_profesor)->jml_pregunta;
		$jml_a 	= $jml + 1; // If you don't understand, please read the user_guide codeigniter about form_validation in the less_than section

		$this->form_validation->set_rules('nombre_resultados', 'Nombre del Examen', 'required|alpha_numeric_spaces|max_length[50]');
		$this->form_validation->set_rules('total_preguntas', 'Numero de preguntas', "required|integer|less_than[{$jml_a}]|greater_than[0]", ['less_than' => "pregunta tidak cukup, anda hanya punya {$jml} pregunta"]);
		$this->form_validation->set_rules('fecha', 'Inicio', 'required');
		$this->form_validation->set_rules('fin', 'Finalización', 'required');
		$this->form_validation->set_rules('duracion', 'Tiempo', 'required|integer|max_length[4]|greater_than[0]');
		$this->form_validation->set_rules('seleccion', 'Random Question', 'required|in_list[Random,Sort]');
	}

	public function save()
	{
		$this->validasi();
		$this->load->helper('string');

		$method 		= $this->input->post('method', true);
		$profesor_id 		= $this->input->post('profesor_id', true);
		$curso_id 		= $this->input->post('curso_id', true);
		$nombre_resultados 	= $this->input->post('nombre_resultados', true);
		$total_preguntas 	= $this->input->post('total_preguntas', true);
		$fecha 		= $this->convert_tgl($this->input->post('fecha', 	true));
		$fin	= $this->convert_tgl($this->input->post('fin', true));
		$duracion			= $this->input->post('duracion', true);
		$seleccion			= $this->input->post('seleccion', true);
		$token 			= strtoupper(random_string('alpha', 5));

		if ($this->form_validation->run() === FALSE) {
			$data['status'] = false;
			$data['errors'] = [
				'nombre_resultados' 	=> form_error('nombre_resultados'),
				'total_preguntas' 	=> form_error('total_preguntas'),
				'fecha' 	=> form_error('fecha'),
				'fin' 	=> form_error('fin'),
				'duracion' 		=> form_error('duracion'),
				'seleccion' 		=> form_error('seleccion'),
			];
		} else {
			$input = [
				'nombre_resultados' 	=> $nombre_resultados,
				'total_preguntas' 	=> $total_preguntas,
				'fecha' 	=> $fecha,
				'terlambat' 	=> $fin,
				'duracion' 		=> $duracion,
				'seleccion' 		=> $seleccion,
			];
			if ($method === 'add') {
				$input['profesor_id']	= $profesor_id;
				$input['curso_id'] = $curso_id;
				$input['token']		= $token;
				$action = $this->master->create('resultados', $input);
			} else if ($method === 'edit') {
				$id_resultados = $this->input->post('id_resultados', true);
				$action = $this->master->update('resultados', $input, 'id_resultados', $id_resultados);
			}
			$data['status'] = $action ? TRUE : FALSE;
		}
		$this->output_json($data);
	}

	public function delete()
	{
		$this->akses_profesor();
		$chk = $this->input->post('checked', true);
		if (!$chk) {
			$this->output_json(['status' => false]);
		} else {
			if ($this->master->delete('resultados', $chk, 'id_resultados')) {
				$this->output_json(['status' => true, 'total' => count($chk)]);
			}
		}
	}

	public function refresh_token($id)
	{
		$this->load->helper('string');
		$data['token'] = strtoupper(random_string('alpha', 5));
		$refresh = $this->master->update('resultados', $data, 'id_resultados', $id);
		$data['status'] = $refresh ? TRUE : FALSE;
		$this->output_json($data);
	}

	/**
	 * BAGIAN estudiante
	 */

	public function list_json()
	{
		$this->akses_estudiante();

		$list = $this->resultados->getListResultados($this->mhs->id_estudiante, $this->mhs->clase_id);
		$this->output_json($list, false);
	}

	public function list()
	{
		$this->akses_estudiante();

		$user = $this->ion_auth->user()->row();

		$data = [
			'user' 		=> $user,
			'judul'		=> 'Exam',
			'subjudul'	=> 'List Exam',
			'mhs' 		=> $this->resultados->getIdEstudiante($user->username),
		];
		$this->load->view('_templates/pizarra/_header.php', $data);
		$this->load->view('resultados/list');
		$this->load->view('_templates/pizarra/_footer.php');
	}

	public function token($id)
	{
		$this->akses_estudiante();
		$user = $this->ion_auth->user()->row();

		$data = [
			'user' 		=> $user,
			'judul'		=> 'Exam',
			'subjudul'	=> 'Token Exam',
			'mhs' 		=> $this->resultados->getIdEstudiante($user->username),
			'resultados'		=> $this->resultados->getResultadosById($id),
			'encrypted_id' => urlencode($this->encryption->encrypt($id))
		];
		$this->load->view('_templates/topnav/_header.php', $data);
		$this->load->view('resultados/token');
		$this->load->view('_templates/topnav/_footer.php');
	}

	public function cektoken()
	{
		$id = $this->input->post('id_resultados', true);
		$token = $this->input->post('token', true);
		$cek = $this->resultados->getResultadosById($id);

		$data['status'] = $token === $cek->token ? TRUE : FALSE;
		$this->output_json($data);
	}

	public function encrypt()
	{
		$id = $this->input->post('id', true);
		$key = urlencode($this->encryption->encrypt($id));
		// $decrypted = $this->encryption->decrypt(rawurldecode($key));
		$this->output_json(['key' => $key]);
	}

	public function index()
	{
		$this->akses_estudiante();
		$key = $this->input->get('key', true);
		$id  = $this->encryption->decrypt(rawurldecode($key));

		$resultados 		= $this->resultados->getResultadosById($id);
		$pregunta 		= $this->resultados->getPlanificacion($id);

		$mhs		= $this->mhs;
		$evaluacion 	= $this->resultados->Evaluacion($id, $mhs->id_estudiante);

		$cek_sudah_ikut = $evaluacion->num_rows();

		if ($cek_sudah_ikut < 1) {
			$pregunta_urut_ok 	= array();
			$i = 0;
			foreach ($pregunta as $s) {
				$pregunta_per = new stdClass();
				$pregunta_per->id_pregunta 		= $s->id_pregunta;
				$pregunta_per->pregunta 		= $s->pregunta;
				$pregunta_per->file 		= $s->file;
				$pregunta_per->type_file 	= $s->type_file;
				$pregunta_per->opsi_a 		= $s->opsi_a;
				$pregunta_per->opsi_b 		= $s->opsi_b;
				$pregunta_per->opsi_c 		= $s->opsi_c;
				$pregunta_per->opsi_d 		= $s->opsi_d;
				$pregunta_per->opsi_e 		= $s->opsi_e;
				$pregunta_per->respuesta 		= $s->respuesta;
				$pregunta_urut_ok[$i] 		= $pregunta_per;
				$i++;
			}
			$pregunta_urut_ok 	= $pregunta_urut_ok;
			$list_id_pregunta	= "";
			$list_respuesta 	= "";
			if (!empty($pregunta)) {
				foreach ($pregunta as $d) {
					$list_id_pregunta .= $d->id_pregunta . ",";
					$list_respuesta .= $d->id_pregunta . "::N,";
				}
			}
			$list_id_pregunta 	= substr($list_id_pregunta, 0, -1);
			$list_respuesta 	= substr($list_respuesta, 0, -1);
			$duracion_selesai 	= date('Y-m-d H:i:s', strtotime("+{$resultados->duracion} minute"));
			$time_mulai		= date('Y-m-d H:i:s');

			$input = [
				'resultados_id' 		=> $id,
				'estudiante_id'	=> $mhs->id_estudiante,
				'list_preguntas'		=> $list_id_pregunta,
				'list_j' 	=> $list_respuesta,
				'jml'		=> 0,
				'score'			=> 0,
				'score_b'	=> 0,
				'incio'		=> $time_mulai,
				'fin'	=> $duracion_selesai,
				'status'		=> 'Y'
			];
			$this->master->create('evaluacion', $input);

			// Setelah insert wajib refresh dulu
			redirect('resultados/?key=' . urlencode($key), 'location', 301);
		}

		$q_pregunta = $evaluacion->row();

		$urut_pregunta 		= explode(",", $q_pregunta->list_respuesta);
		$pregunta_urut_ok	= array();
		for ($i = 0; $i < sizeof($urut_pregunta); $i++) {
			$pc_urut_pregunta	= explode(":", $urut_pregunta[$i]);
			$pc_urut_pregunta1 	= empty($pc_urut_pregunta[1]) ? "''" : "'{$pc_urut_pregunta[1]}'";
			$ambil_pregunta 	= $this->resultados->ambilPregunta($pc_urut_pregunta1, $pc_urut_pregunta[0]);
			$pregunta_urut_ok[] = $ambil_pregunta;
		}

		$detail_tes = $q_pregunta;
		$pregunta_urut_ok = $pregunta_urut_ok;

		$pc_list_respuesta = explode(",", $detail_tes->list_respuesta);
		$arr_jawab = array();
		foreach ($pc_list_respuesta as $v) {
			$pc_v 	= explode(":", $v);
			$idx 	= $pc_v[0];
			$val 	= $pc_v[1];
			$rg 	= $pc_v[2];

			$arr_jawab[$idx] = array("j" => $val, "r" => $rg);
		}

		$arr_opsi = array("a", "b", "c", "d", "e");
		$html = '';
		$no = 1;
		if (!empty($pregunta_urut_ok)) {
			foreach ($pregunta_urut_ok as $s) {
				$path = 'uploads/bank_pregunta/';
				$vrg = $arr_jawab[$s->id_pregunta]["r"] == "" ? "N" : $arr_jawab[$s->id_pregunta]["r"];
				$html .= '<input type="hidden" name="id_pregunta_' . $no . '" value="' . $s->id_pregunta . '">';
				$html .= '<input type="hidden" name="rg_' . $no . '" id="rg_' . $no . '" value="' . $vrg . '">';
				$html .= '<div class="step" id="widget_' . $no . '">';

				$html .= '<div class="text-center"><div class="w-25">' . tampil_media($path . $s->file) . '</div></div>' . $s->pregunta . '<div class="funkyradio">';
				for ($j = 0; $j < $this->config->item('jml_opsi'); $j++) {
					$opsi 			= "opsi_" . $arr_opsi[$j];
					$file 			= "file_" . $arr_opsi[$j];
					$checked 		= $arr_jawab[$s->id_pregunta]["j"] == strtoupper($arr_opsi[$j]) ? "checked" : "";
					$pilihan_opsi 	= !empty($s->$opsi) ? $s->$opsi : "";
					$tampil_media_opsi = (is_file(base_url() . $path . $s->$file) || $s->$file != "") ? tampil_media($path . $s->$file) : "";
					$html .= '<div class="funkyradio-success" onclick="return simpan_sementara();">
						<input type="radio" id="opsi_' . strtolower($arr_opsi[$j]) . '_' . $s->id_pregunta . '" name="opsi_' . $no . '" value="' . strtoupper($arr_opsi[$j]) . '" ' . $checked . '> <label for="opsi_' . strtolower($arr_opsi[$j]) . '_' . $s->id_pregunta . '"><div class="huruf_opsi">' . $arr_opsi[$j] . '</div> <p>' . $pilihan_opsi . '</p><div class="w-25">' . $tampil_media_opsi . '</div></label></div>';
				}
				$html .= '</div></div>';
				$no++;
			}
		}

		// Enkripsi Id Tes
		$id_tes = $this->encryption->encrypt($detail_tes->id);

		$data = [
			'user' 		=> $this->user,
			'mhs'		=> $this->mhs,
			'judul'		=> 'Exam',
			'subjudul'	=> 'Exam Sheet',
			'pregunta'		=> $detail_tes,
			'no' 		=> $no,
			'html' 		=> $html,
			'id_tes'	=> $id_tes
		];
		$this->load->view('_templates/topnav/_header.php', $data);
		$this->load->view('resultados/sheet');
		$this->load->view('_templates/topnav/_footer.php');
	}

	public function simpan_satu()
	{
		// Decrypt Id
		$id_tes = $this->input->post('id', true);
		$id_tes = $this->encryption->decrypt($id_tes);

		$input 	= $this->input->post(null, true);
		$list_respuesta 	= "";
		for ($i = 1; $i < $input['jml_pregunta']; $i++) {
			$_tjawab 	= "opsi_" . $i;
			$_tidpregunta 	= "id_pregunta_" . $i;
			$_ragu 		= "rg_" . $i;
			$respuesta_ 	= empty($input[$_tjawab]) ? "" : $input[$_tjawab];
			$list_respuesta	.= "" . $input[$_tidpregunta] . ":" . $respuesta_ . ":" . $input[$_ragu] . ",";
		}
		$list_respuesta	= substr($list_respuesta, 0, -1);
		$d_simpan = [
			'list_j' => $list_respuesta
		];

		// Simpan respuesta
		$this->master->update('evaluacion', $d_simpan, 'id', $id_tes);
		$this->output_json(['status' => true]);
	}

	public function simpan_akhir()
	{
		// Decrypt Id
		$id_tes = $this->input->post('id', true);
		$id_tes = $this->encryption->decrypt($id_tes);

		// Get respuesta
		$list_respuesta = $this->resultados->getRespuesta($id_tes);

		// Pecah respuesta
		$pc_respuesta = explode(",", $list_respuesta);

		$total_benar 	= 0;
		$total_salah 	= 0;
		$total_ragu  	= 0;
		$score_b 	= 0;
		$total_bobot	= 0;
		$total_preguntas	= sizeof($pc_respuesta);

		foreach ($pc_respuesta as $jwb) {
			$pc_dt 		= explode(":", $jwb);
			$id_pregunta 	= $pc_dt[0];
			$respuesta 	= $pc_dt[1];
			$ragu 		= $pc_dt[2];

			$cek_jwb 	= $this->pregunta->getPreguntaById($id_pregunta);
			$total_bobot = $total_bobot + $cek_jwb->bobot;

			$respuesta == $cek_jwb->respuesta ? $total_benar++ : $total_salah++;
		}

		$score = ($total_benar / $total_preguntas)  * 100;
		$score_b = ($total_bobot / $total_preguntas)  * 100;

		$d_update = [
			'jml'		=> $total_benar,
			'score'			=> number_format(floor($score), 0),
			'score_b'	=> number_format(floor($score_b), 0),
			'status'		=> 'N'
		];

		$this->master->update('evaluacion', $d_update, 'id', $id_tes);
		$this->output_json(['status' => TRUE, 'data' => $d_update, 'id' => $id_tes]);
	}
}
