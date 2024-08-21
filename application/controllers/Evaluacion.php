<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Evaluacion extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		if (!$this->ion_auth->logged_in()) {
			redirect('auth');
		}

		$this->load->library(['datatables']); // Load Library Ignited-Datatables
		$this->load->model('Master_model', 'master');
		$this->load->model('Resultados_model', 'resultados');

		$this->user = $this->ion_auth->user()->row();
	}

	public function output_json($data, $encode = true)
	{
		if ($encode) $data = json_encode($data);
		$this->output->set_content_type('application/json')->set_output($data);
	}

	public function data()
	{
		$codigop_profesor = null;

		if ($this->ion_auth->in_group('Tutor')) {
			$codigop_profesor = $this->user->username;
		}

		$this->output_json($this->resultados->getEvaluacion($codigop_profesor), false);
	}

	public function scoreMhs($id)
	{
		$this->output_json($this->resultados->EvaluacionById($id, true), false);
	}

	public function index()
	{
		$data = [
			'user' => $this->user,
			'judul'	=> 'Examen',
			'subjudul' => 'Resultados de Examen',
		];
		$this->load->view('_templates/pizarra/_header.php', $data);
		$this->load->view('resultados/hasil');
		$this->load->view('_templates/pizarra/_footer.php');
	}

	public function detail($id)
	{
		$resultados = $this->resultados->getResultadosById($id);
		$score = $this->resultados->bandingscore($id);

		$data = [
			'user' => $this->user,
			'judul'	=> 'Examen',
			'subjudul' => 'Información de Resultados de Examen',
			'resultados'	=> $resultados,
			'score'	=> $score
		];

		$this->load->view('_templates/pizarra/_header.php', $data);
		$this->load->view('resultados/detail_hasil');
		$this->load->view('_templates/pizarra/_footer.php');
	}

	public function cetak($id)
	{
		$this->load->library('Pdf');

		$mhs 	= $this->resultados->getIdEstudiante($this->user->username);
		$hasil 	= $this->resultados->Evaluacion($id, $mhs->id_estudiante)->row();
		$resultados 	= $this->resultados->getResultadosById($id);

		$data = [
			'resultados' => $resultados,
			'hasil' => $hasil,
			'mhs'	=> $mhs
		];

		$this->load->view('resultados/cetak', $data);
	}

	public function cetak_detail($id)
	{
		$this->load->library('Pdf');

		$resultados = $this->resultados->getResultadosById($id);
		$score = $this->resultados->bandingscore($id);
		$hasil = $this->resultados->EvaluacionById($id)->result();

		$data = [
			'resultados'	=> $resultados,
			'score'	=> $score,
			'hasil'	=> $hasil
		];

		$this->load->view('resultados/cetak_detail', $data);
	}
}
