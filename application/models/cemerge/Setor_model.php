<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Setor_model extends MY_Model {
    protected $table = 'setores';

    public function __construct()
    {
        parent::__construct($this->table);
    }

    public function get_setores_por_profissional($profissional_id) {
        $fields = 'setores.*';

        $this->db->select($fields);
        $this->db->from($this->table);
        $this->db->join('profissionalsetor', 'setores.id = profissionalsetor.setor_id', 'left');
        $this->db->join('profissionais', 'profissionais.id = profissionalsetor.profissional_id', 'left');
        $this->db->where('profissional_id', $profissional_id);
        $query = $this->db->get();

        return $query->result();
    }
}
