<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Unidadehospitalar_model extends MY_Model {
    protected $table = 'unidadeshospitalares';

    public function __construct()
    {
        parent::__construct($this->table);
    }

    public function get_by_profissional($profissional_id)
    {
        $this->db->select('u.*');
        $this->db->from($this->table . " u");
        $this->db->join('setores s', 'u.id = s.unidadehospitalar_id');
        $this->db->join('profissionalsetor ps', 's.id = ps.setor_id');
        $this->db->where(array('ps.profissional_id' => $profissional_id));
        $this->db->order_by('u.razaosocial');
        $query = $this->db->get();

        return $query->result();
    }

    public function get_by_user($user_id)
    {
        $this->db->select('u.*');
        $this->db->from($this->table . " u");
        $this->db->join('setores s', 'u.id = s.unidadehospitalar_id');
        $this->db->join('usuariosetor us', 's.id = us.setor_id');
        $this->db->where(array('us.user_id' => $user_id));
        $this->db->order_by('u.razaosocial');
        $query = $this->db->get();

        return $query->result();
    }

    public function get_by_setor($setor_id)
    {
        $this->db->select('u.*');
        $this->db->from($this->table . " u");
        $this->db->join('setores s', 'u.id = s.unidadehospitalar_id');
        $this->db->where(array('s.id' => $setor_id));
        $this->db->order_by('u.razaosocial');
        $query = $this->db->get();

        return $query->row();
    }

    public function get_unidadeshospitalares()
    {
        $unidades = $this->get_all();

        $unidadeshospitalares = array(
            '' => 'Selecione uma unidade hospitalar',
        );
        foreach ($unidades as $unidade) {
            $unidadeshospitalares[$unidade->id] = $unidade->razaosocial;
        }

        return $unidadeshospitalares;
    }

    public function get_unidadeshospitalares_assessus()
    {
        $sql = "select pj.cd_pes_jur, pj.nm_pes_jur ";
        $sql .= "from tb_pes_jur pj ";
        $sql .= "join grupos_unidadeshospitalares gu on (pj.cd_pes_jur = gu.cd_pes_jur) ";
        $sql .= "order by pj.nm_pes_jur";

        $query = $this->db->query($sql);

        return $query->result();
    }
}
