<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Planificacion_model extends CI_Model {
    
    public function getDataPlanificacion($id, $profesor)
    {
        $this->datatables->select('a.id_pregunta, a.planificacion, FROM_UNIXTIME(a.created_on) as created_on, FROM_UNIXTIME(a.updated_on) as updated_on, b.nombre_curso, c.nombre_profesor');
        $this->datatables->from('planificacion a');
        $this->datatables->join('curso b', 'b.id_curso=a.curso_id');
        $this->datatables->join('profesor c', 'c.id_profesor=a.profesor_id');
        if ($id!==null && $profesor===null) {
            $this->datatables->where('a.curso_id', $id);            
        }else if($id!==null && $profesor!==null){
            $this->datatables->where('a.profesor_id', $profesor);
        }
        return $this->datatables->generate();
    }

    public function getPlanificacionById($id)
    {
        return $this->db->get_where('planificacion', ['id_pregunta' => $id])->row();
    }

    public function getCursoProfesor($codigop)
    {
        $this->db->select('curso_id, nombre_curso, id_profesor, nombre_profesor');
        $this->db->join('curso', 'curso_id=id_curso');
        $this->db->from('profesor')->where('codigop', $codigop);
        return $this->db->get()->row();
    }

    public function getAllProfesor()
    {
        $this->db->select('*');
        $this->db->from('profesor a');
        $this->db->join('curso b', 'a.curso_id=b.id_curso');
        return $this->db->get()->result();
    }
}