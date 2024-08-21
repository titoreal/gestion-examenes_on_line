<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Master_model extends CI_Model
{
    public function __construct()
    {
        $this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
    }

    public function create($table, $data, $batch = false)
    {
        if ($batch === false) {
            $insert = $this->db->insert($table, $data);
        } else {
            $insert = $this->db->insert_batch($table, $data);
        }
        return $insert;
    }

    public function update($table, $data, $pk, $id = null, $batch = false)
    {
        if ($batch === false) {
            $insert = $this->db->update($table, $data, array($pk => $id));
        } else {
            $insert = $this->db->update_batch($table, $data, $pk);
        }
        return $insert;
    }

    public function delete($table, $data, $pk)
    {
        $this->db->where_in($pk, $data);
        return $this->db->delete($table);
    }

    /**
     * Data clase
     */

    public function getDataClase()
    {
        $this->datatables->select('id_clase, nombre_clase, id_area, nombre_area');
        $this->datatables->from('clase');
        $this->datatables->join('area', 'area_id=id_area','');
        $this->datatables->add_column('bulk_select', '<div class="text-center"><input type="checkbox" class="check" name="checked[]" value="$1"/></div>', 'id_clase, nombre_clase, id_area, nombre_area');
        return $this->datatables->generate();
    }

    public function getClaseById($id)
    {
        $this->db->where_in('id_clase', $id);
        $this->db->order_by('nombre_clase');
        $query = $this->db->get('clase')->result();
        return $query;
    }

    /**
     * Data area
     */

    public function getDataArea()
    {
        $this->datatables->select('id_area, nombre_area');
        $this->datatables->from('area');
        $this->datatables->add_column('bulk_select', '<div class="text-center"><input type="checkbox" class="check" name="checked[]" value="$1"/></div>', 'id_area, nombre_area');
        return $this->datatables->generate();
    }

    public function getAreaById($id)
    {
        $this->db->where_in('id_area', $id);
        $this->db->order_by('nombre_area');
        $query = $this->db->get('area')->result();
        return $query;
    }

    /**
     * Data estudiante
     */

    public function getDataEstudiante()
    {
        $this->datatables->select('a.id_estudiante, a.nombre, a.codigoe, a.email, b.nombre_clase, c.nombre_area');
        $this->datatables->select('(SELECT COUNT(id) FROM users WHERE username = a.codigoe) AS ada');
        $this->datatables->from('estudiante a');
        $this->datatables->join('clase b', 'a.clase_id=b.id_clase');
        $this->datatables->join('area c', 'b.area_id=c.id_area');
        return $this->datatables->generate();
    }

    public function getEstudianteById($id)
    {
        $this->db->select('*');
        $this->db->from('estudiante');
        $this->db->join('clase', 'clase_id=id_clase');
        $this->db->join('area', 'area_id=id_area');
        $this->db->where(['id_estudiante' => $id]);
        return $this->db->get()->row();
    }

    public function getArea()
    {
        $this->db->select('id_area, nombre_area');
        $this->db->from('clase');
        $this->db->join('area', 'area_id=id_area');
        $this->db->order_by('nombre_area', 'ASC');
        $this->db->group_by('id_area');
        $query = $this->db->get();
        return $query->result();
    }

    public function getAllArea($id = null)
    {
        if ($id === null) {
            $this->db->order_by('nombre_area', 'ASC');
            return $this->db->get('area')->result();
        } else {
            $this->db->select('area_id');
            $this->db->from('area_curso');
            $this->db->where('curso_id', $id);
            $area = $this->db->get()->result();
            $id_area = [];
            foreach ($area as $j) {
                $id_area[] = $j->area_id;
            }
            if ($id_area === []) {
                $id_area = null;
            }
            
            $this->db->select('*');
            $this->db->from('area');
            $this->db->where_not_in('id_area', $id_area);
            $curso = $this->db->get()->result();
            return $curso;
        }
    }

    public function getClaseByArea($id)
    {
        $query = $this->db->get_where('clase', array('area_id'=>$id));
        return $query->result();
    }

    /**
     * Data profesor
     */

    public function getDataProfesor()
    {
        $this->datatables->select('a.id_profesor,a.codigop, a.nombre_profesor, a.email, a.curso_id, b.nombre_curso, (SELECT COUNT(id) FROM users WHERE username = a.codigop OR email = a.email) AS ada');
        $this->datatables->from('profesor a');
        $this->datatables->join('curso b', 'a.curso_id=b.id_curso');
        return $this->datatables->generate();
    }

    public function getProfesorById($id)
    {
        $query = $this->db->get_where('profesor', array('id_profesor'=>$id));
        return $query->row();
    }

    /**
     * Data curso
     */

    public function getDataCurso()
    {
        $this->datatables->select('id_curso, nombre_curso');
        $this->datatables->from('curso');
        return $this->datatables->generate();
    }

    public function getAllCurso()
    {
        return $this->db->get('curso')->result();
    }

    public function getCursoById($id, $single = false)
    {
        if ($single === false) {
            $this->db->where_in('id_curso', $id);
            $this->db->order_by('nombre_curso');
            $query = $this->db->get('curso')->result();
        } else {
            $query = $this->db->get_where('curso', array('id_curso'=>$id))->row();
        }
        return $query;
    }

    /**
     * Data clase profesor
     */

    public function getClaseProfesor()
    {
        $this->datatables->select('clase_profesor.id, profesor.id_profesor, profesor.codigop, profesor.nombre_profesor, GROUP_CONCAT(clase.nombre_clase) as clase');
        $this->datatables->from('clase_profesor');
        $this->datatables->join('clase', 'clase_id=id_clase');
        $this->datatables->join('profesor', 'profesor_id=id_profesor');
        $this->datatables->group_by('profesor.nombre_profesor');
        return $this->datatables->generate();
    }

    public function getAllProfesor($id = null)
    {
        $this->db->select('profesor_id');
        $this->db->from('clase_profesor');
        if ($id !== null) {
            $this->db->where_not_in('profesor_id', [$id]);
        }
        $profesor = $this->db->get()->result();
        $id_profesor = [];
        foreach ($profesor as $d) {
            $id_profesor[] = $d->profesor_id;
        }
        if ($id_profesor === []) {
            $id_profesor = null;
        }

        $this->db->select('id_profesor, codigop, nombre_profesor');
        $this->db->from('profesor');
        $this->db->where_not_in('id_profesor', $id_profesor);
        return $this->db->get()->result();
    }

    
    public function getAllClase()
    {
        $this->db->select('id_clase, nombre_clase, nombre_area');
        $this->db->from('clase');
        $this->db->join('area', 'area_id=id_area');
        $this->db->order_by('nombre_clase');
        return $this->db->get()->result();
    }
    
    public function getClaseByProfesor($id)
    {
        $this->db->select('clase.id_clase');
        $this->db->from('clase_profesor');
        $this->db->join('clase', 'clase_profesor.clase_id=clase.id_clase');
        $this->db->where('profesor_id', $id);
        $query = $this->db->get()->result();
        return $query;
    }
    /**
     * Data area curso
     */

    public function getAreaCurso()
    {
        $this->datatables->select('area_curso.id, curso.id_curso, curso.nombre_curso, area.id_area, GROUP_CONCAT(area.nombre_area) as nombre_area');
        $this->datatables->from('area_curso');
        $this->datatables->join('curso', 'curso_id=id_curso');
        $this->datatables->join('area', 'area_id=id_area');
        $this->datatables->group_by('curso.nombre_curso');
        return $this->datatables->generate();
    }

    public function getCurso($id = null)
    {
        $this->db->select('curso_id');
        $this->db->from('area_curso');
        if ($id !== null) {
            $this->db->where_not_in('curso_id', [$id]);
        }
        $curso = $this->db->get()->result();
        $id_curso = [];
        foreach ($curso as $d) {
            $id_curso[] = $d->curso_id;
        }
        if ($id_curso === []) {
            $id_curso = null;
        }

        $this->db->select('id_curso, nombre_curso');
        $this->db->from('curso');
        $this->db->where_not_in('id_curso', $id_curso);
        return $this->db->get()->result();
    }

    public function getAreaByIdCurso($id)
    {
        $this->db->select('area.id_area');
        $this->db->from('area_curso');
        $this->db->join('area', 'area_curso.area_id=area.id_area');
        $this->db->where('curso_id', $id);
        $query = $this->db->get()->result();
        return $query;
    }
}
