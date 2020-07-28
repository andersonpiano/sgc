<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profissional_model extends MY_Model {
    protected $table = 'profissionais';

    public function __construct()
    {
        parent::__construct($this->table);
    }

    public function get_profissionais_por_setor($setor_id) {
        $query = $this->db->get_where('vw_profissionais_setor', ['setor_id' => $setor_id]);

        return $query->result();
    }
}
