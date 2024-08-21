<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Resultados_model extends CI_Model {
    
    public function getDataResultados($id)
    {
        $this->datatables->select('a.id_resultados, a.token, a.nombre_resultados, b.nombre_curso, a.total_preguntas, CONCAT(a.inicio, " <br/> (", a.duracion, " Minute)") as duracion, a.seleccion');
        $this->datatables->from('resultados a');
        $this->datatables->join('curso b', 'a.curso_id = b.id_curso');
        if($id!==null){
            $this->datatables->where('profesor_id', $id);
        }
        return $this->datatables->generate();
    }
    
    public function getListResultados($id, $clase)
    {
        $this->datatables->select("a.id_resultados, e.nombre_profesor, d.nombre_clase, a.nombre_resultados, b.nombre_curso, a.total_preguntas, CONCAT(a.inicio, ' <br/> (', a.duracion, ' Minute)') as duracion,  (SELECT COUNT(id) FROM evaluacion h WHERE h.estudiante_id = {$id} AND h.evaluacio_id = a.id_resultados) AS ada");
        $this->datatables->from('resultados a');
        $this->datatables->join('curso b', 'a.curso_id = b.id_curso');
        $this->datatables->join('clase_profesor c', "a.profesor_id = c.profesor_id");
        $this->datatables->join('clase d', 'c.clase_id = d.id_clase');
        $this->datatables->join('profesor e', 'e.id_profesor = c.profesor_id');
        $this->datatables->where('d.id_clase', $clase);
        return $this->datatables->generate();
    }

    public function getResultadosById($id)
    {
        $this->db->select('*');
        $this->db->from('resultados a');
        $this->db->join('profesor b', 'a.profesor_id=b.id_profesor');
        $this->db->join('curso c', 'a.curso_id=c.id_curso');
        $this->db->where('id_resultados', $id);
        return $this->db->get()->row();
    }

    public function getIdProfesor($codigop)
    {
        $this->db->select('id_profesor, nombre_profesor')->from('profesor')->where('codigop', $codigop);
        return $this->db->get()->row();
    }

    public function getTotalPreguntas($profesor)
    {
        $this->db->select('COUNT(id_pregunta) as jml_pregunta');
        $this->db->from('planificacion');
        $this->db->where('profesor_id', $profesor);
        return $this->db->get()->row();
    }

    public function getIdEstudiante($codigoe)
    {
        $this->db->select('*');
        $this->db->from('estudiante a');
        $this->db->join('clase b', 'a.clase_id=b.id_clase');
        $this->db->join('area c', 'b.area_id=c.id_area');
        $this->db->where('codigoe', $codigoe);
        return $this->db->get()->row();
    }

    public function HslResultados($id, $mhs)
    {
        $this->db->select('*, UNIX_TIMESTAMP(fin) as duracion_habis');
        $this->db->from('evaluacion');
        $this->db->where('resultados_id', $id);
        $this->db->where('estudiante_id', $mhs);
        return $this->db->get();
    }

    public function getPregunta($id)
    {
        $resultados = $this->getResultadosById($id);
        $order = $resultados->seleccion==="Random" ? 'rand()' : 'id_pregunta';

        $this->db->select('id_pregunta, pregunta, file, type_file, opsi_a, opsi_b, opsi_c, opsi_d, opsi_e, respuesta');
        $this->db->from('planificacion');
        $this->db->where('profesor_id', $resultados->profesor_id);
        $this->db->where('curso_id', $resultados->curso_id);
        $this->db->order_by($order);
        $this->db->limit($resultados->total_preguntas);
        return $this->db->get()->result();
    }

    public function ambilPregunta($pc_urut_pregunta1, $pc_urut_pregunta_arr)
    {
        $this->db->select("*, {$pc_urut_pregunta1} AS respuesta");
        $this->db->from('planificacion');
        $this->db->where('id_pregunta', $pc_urut_pregunta_arr);
        return $this->db->get()->row();
    }

    public function getRespuesta($id_tes)
    {
        $this->db->select('list_j');
        $this->db->from('evaluacion');
        $this->db->where('id', $id_tes);
        return $this->db->get()->row()->list_respuesta;
    }

    public function getHasilResultados($codigop = null)
    {
        $this->datatables->select('b.id_resultados, b.nombre_resultados, b.total_preguntas, CONCAT(b.duracion, " Minute") as duracion, b.fecha');
        $this->datatables->select('c.nombre_curso, d.nombre_profesor');
        $this->datatables->from('evaluacion a');
        $this->datatables->join('resultados b', 'a.resultados_id = b.id_resultados');
        $this->datatables->join('curso c', 'b.curso_id = c.id_curso');
        $this->datatables->join('profesor d', 'b.profesor_id = d.id_profesor');
        $this->datatables->group_by('b.id_resultados');
        if($codigop !== null){
            $this->datatables->where('d.codigop', $codigop);
        }
        return $this->datatables->generate();
    }

    public function HslResultadosById($id, $dt=false)
    {
        if($dt===false){
            $db = "db";
            $get = "get";
        }else{
            $db = "datatables";
            $get = "generate";
        }
        
        $this->$db->select('d.id, a.nombre, b.nombre_clase, c.nombre_area, d.jml, d.score');
        $this->$db->from('estudiante a');
        $this->$db->join('clase b', 'a.clase_id=b.id_clase');
        $this->$db->join('area c', 'b.area_id=c.id_area');
        $this->$db->join('evaluacion d', 'a.id_estudiante=d.estudiante_id');
        $this->$db->where(['d.resultados_id' => $id]);
        return $this->$db->$get();
    }

    public function bandingscore($id)
    {
        $this->db->select_min('score', 'min_score');
        $this->db->select_max('score', 'max_score');
        $this->db->select_avg('FORMAT(FLOOR(score),0)', 'avg_score');
        $this->db->where('resultados_id', $id);
        return $this->db->get('evaluacion')->row();
    }

}