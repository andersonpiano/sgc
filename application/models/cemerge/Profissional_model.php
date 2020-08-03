<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profissional_model extends MY_Model
{
    protected $table = 'profissionais';

    public function __construct()
    {
        parent::__construct($this->table);
    }

    public function get_profissionais_por_setor($setor_id) {
        $fields = 'profissionais.*';

        $this->db->select($fields);
        $this->db->from($this->table);
        $this->db->join('profissionalsetor', 'profissionais.id = profissionalsetor.profissional_id');
        $this->db->join('setores', 'setores.id = profissionalsetor.setor_id');
        $this->db->where('setores.id', $setor_id);
        $query = $this->db->get();

        return $query->result();
    }
}
