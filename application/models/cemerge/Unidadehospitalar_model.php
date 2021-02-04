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
}
