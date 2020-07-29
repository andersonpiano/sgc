<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Setor_model extends MY_Model {
    protected $table = 'setores';

    public function __construct()
    {
        parent::__construct($this->table);
    }

    public function get_setores_por_profissional($profissional_id) {
        $query = $this->db->get_where('vw_profissionais_setor', ['id' => $profissional_id]);

        return $query->result();
    }
}
