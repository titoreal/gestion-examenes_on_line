<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Planificacion extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        if (!$this->ion_auth->logged_in()) {
            redirect('auth');
        } else if (!$this->ion_auth->is_admin() && !$this->ion_auth->in_group('Tutor')) {
            show_error('Sólo los administradores están autorizados para acceder  a este lugar, <a href="' . base_url('pizarra') . '">Back to main menu</a>', 403, 'Forbidden Access');
        }
        $this->load->library(['datatables', 'form_validation']); // Load Library Ignited-Datatables
        $this->load->helper('my'); // Load Library Ignited-Datatables
        $this->load->model('Master_model', 'master');
        $this->load->model('Planificacion_model', 'planificacion');
        $this->form_validation->set_error_delimiters('', '');
    }

    public function output_json($data, $encode = true)
    {
        if ($encode) $data = json_encode($data);
        $this->output->set_content_type('application/json')->set_output($data);
    }

    public function index()
    {
        $user = $this->ion_auth->user()->row();
        $data = [
            'user' => $user,
            'judul'    => 'Pregunta',
            'subjudul' => 'Banco de preguntas'
        ];

        if ($this->ion_auth->is_admin()) {
            //Jika admin maka tampilkan semua curso
            $data['curso'] = $this->master->getAllCurso();
        } else {
            //Jika bukan maka curso dipilih otomatis sesuai curso profesor
            $data['curso'] = $this->planificacion->getCursoProfesor($user->username);
        }

        $this->load->view('_templates/pizarra/_header.php', $data);
        $this->load->view('planificacion/data');
        $this->load->view('_templates/pizarra/_footer.php');
    }

    public function detail($id)
    {
        $user = $this->ion_auth->user()->row();
        $data = [
            'user'      => $user,
            'judul'        => 'Planificacion',
            'subjudul'  => 'Editar pregunta',
            'pregunta'      => $this->pregunta->getPreguntaById($id),
        ];

        $this->load->view('_templates/pizarra/_header.php', $data);
        $this->load->view('planificacion/detail');
        $this->load->view('_templates/pizarra/_footer.php');
    }

    public function add()
    {
        $user = $this->ion_auth->user()->row();
        $data = [
            'user'      => $user,
            'judul'        => 'planificacion',
            'subjudul'  => 'Crear pregunta'
        ];

        if ($this->ion_auth->is_admin()) {
            //Jika admin maka tampilkan semua curso
            $data['profesor'] = $this->pregunta->getAllProfesor();
        } else {
            //Jika bukan maka curso dipilih otomatis sesuai curso profesor
            $data['profesor'] = $this->pregunta->getCursoProfesor($user->username);
        }

        $this->load->view('_templates/pizarra/_header.php', $data);
        $this->load->view('planificacion/add');
        $this->load->view('_templates/pizarra/_footer.php');
    }

    public function edit($id)
    {
        $user = $this->ion_auth->user()->row();
        $data = [
            'user'      => $user,
            'judul'        => 'planificacion',
            'subjudul'  => 'Editar pregunta',
            'pregunta'      => $this->pregunta->getPreguntaById($id),
        ];

        if ($this->ion_auth->is_admin()) {
            //Jika admin maka tampilkan semua curso
            $data['profesor'] = $this->pregunta->getAllProfesor();
        } else {
            //Jika bukan maka curso dipilih otomatis sesuai curso profesor
            $data['profesor'] = $this->pregunta->getCursoProfesor($user->username);
        }

        $this->load->view('_templates/pizarra/_header.php', $data);
        $this->load->view('planificacion/edit');
        $this->load->view('_templates/pizarra/_footer.php');
    }

    public function data($id = null, $profesor = null)
    {
        $this->output_json($this->pregunta->getDataPregunta($id, $profesor), false);
    }

    public function validasi()
    {
        if ($this->ion_auth->is_admin()) {
            $this->form_validation->set_rules('profesor_id', 'Tutor', 'required');
        }
        // $this->form_validation->set_rules('pregunta', 'pregunta', 'required');
        // $this->form_validation->set_rules('respuesta_a', 'respuesta A', 'required');
        // $this->form_validation->set_rules('respuesta_b', 'respuesta B', 'required');
        // $this->form_validation->set_rules('respuesta_c', 'respuesta C', 'required');
        // $this->form_validation->set_rules('respuesta_d', 'respuesta D', 'required');
        // $this->form_validation->set_rules('respuesta_e', 'respuesta E', 'required');
        $this->form_validation->set_rules('respuesta', 'Answer key', 'required');
        $this->form_validation->set_rules('bobot', 'Question Weight', 'required|max_length[2]');
    }

    public function file_config()
    {
        $allowed_type     = [
            "image/jpeg", "image/jpg", "image/png", "image/gif",
            "audio/mpeg", "audio/mpg", "audio/mpeg3", "audio/mp3", "audio/x-wav", "audio/wave", "audio/wav",
            "video/mp4", "application/octet-stream"
        ];
        $config['upload_path']      = FCPATH . 'uploads/bank_pregunta/';
        $config['allowed_types']    = 'jpeg|jpg|png|gif|mpeg|mpg|mpeg3|mp3|wav|wave|mp4';
        $config['encrypt_name']     = TRUE;

        return $this->load->library('upload', $config);
    }

    public function save()
    {

        $method = $this->input->post('method', true);
        $this->validasi();
        $this->file_config();


        if ($this->form_validation->run() === FALSE) {
            $method === 'add' ? $this->add() : $this->edit();
        } else {
            $data = [
                'pregunta'      => $this->input->post('pregunta', true),
                'respuesta'   => $this->input->post('respuesta', true),
                'bobot'     => $this->input->post('bobot', true),
            ];

            $abjad = ['a', 'b', 'c', 'd', 'e'];

            // Inputan Opsi
            foreach ($abjad as $abj) {
                $data['opsi_' . $abj]    = $this->input->post('respuesta_' . $abj, true);
            }

            $i = 0;
            foreach ($_FILES as $key => $val) {
                $img_src = FCPATH . 'uploads/bank_pregunta/';
                $getpregunta = $this->pregunta->getPreguntaById($this->input->post('id_pregunta', true));

                $error = '';
                if ($key === 'file_pregunta') {
                    if (!empty($_FILES['file_pregunta']['tmp_name'])) {
                        if (!$this->upload->do_upload('file_pregunta')) {
                            $error = $this->upload->display_errors();
                            show_error($error, 500, 'File Ques. Error');
                            exit();
                        } else {
                            if ($method === 'edit') {
                                if (!unlink($img_src . $getpregunta->file)) {
                                    show_error('Error when deleting image <br/>' . var_dump($getpregunta), 500, 'Image Editing Error');
                                    exit();
                                }
                            }
                            $data['file'] = $this->upload->data('file_name');
                            $data['type_file'] = $this->upload->data('file_type');
                        }
                    }
                } else {
                    $file_abj = 'file_' . $abjad[$i];
                    if (!empty($_FILES[$file_abj]['tmp_name'])) {

                        if (!$this->upload->do_upload($file_abj)) {
                            $error = $this->upload->display_errors();
                            show_error($error, 500, 'Option Files ' . strtoupper($abjad[$i]) . ' Error');
                            exit();
                        } else {
                            if ($method === 'edit') {
                                if (!empty($getpregunta->$file_abj) && !unlink($img_src . $getpregunta->$file_abj)) {
                                    show_error('Error when deleting image', 500, 'Image Editing Error');
                                    exit();
                                }
                            }
                            $data[$file_abj] = $this->upload->data('file_name');
                        }
                    }
                    $i++;
                }
            }

            if ($this->ion_auth->is_admin()) {
                $pecah = $this->input->post('profesor_id', true);
                $pecah = explode(':', $pecah);
                $data['profesor_id'] = $pecah[0];
                $data['curso_id'] = end($pecah);
            } else {
                $data['profesor_id'] = $this->input->post('profesor_id', true);
                $data['curso_id'] = $this->input->post('curso_id', true);
            }

            if ($method === 'add') {
                //push array
                $data['created_on'] = time();
                $data['updated_on'] = time();
                //insert data
                $this->master->create('planificacion', $data);
            } else if ($method === 'edit') {
                //push array
                $data['updated_on'] = time();
                //update data
                $id_pregunta = $this->input->post('id_pregunta', true);
                $this->master->update('planificacion', $data, 'id_pregunta', $id_pregunta);
                redirect('pregunta/detail/' . $id_pregunta);
            } else {
                show_error('Method unknown', 404);
            }
            redirect('pregunta');
        }
    }

    public function delete()
    {
        $chk = $this->input->post('checked', true);

        // Delete File
        foreach ($chk as $id) {
            $abjad = ['a', 'b', 'c', 'd', 'e'];
            $path = FCPATH . 'uploads/bank_pregunta/';
            $pregunta = $this->planificacion->getPreguntaById($id);
            // Hapus File pregunta
            if (!empty($pregunta->file)) {
                if (file_exists($path . $pregunta->file)) {
                    unlink($path . $pregunta->file);
                }
            }
            //Hapus File Opsi
            $i = 0; //index
            foreach ($abjad as $abj) {
                $file_opsi = 'file_' . $abj;
                if (!empty($pregunta->$file_opsi)) {
                    if (file_exists($path . $pregunta->$file_opsi)) {
                        unlink($path . $pregunta->$file_opsi);
                    }
                }
            }
        }

        if (!$chk) {
            $this->output_json(['status' => false]);
        } else {
            if ($this->master->delete('planificacion', $chk, 'id_pregunta')) {
                $this->output_json(['status' => true, 'total' => count($chk)]);
            }
        }
    }
}
