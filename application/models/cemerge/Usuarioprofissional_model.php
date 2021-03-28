<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Usuarioprofissional_model extends MY_Model {
    protected $table = 'usuariosprofissionais';

    public function __construct()
    {
        parent::__construct($this->table);
    }

    public function get_usuario_por_profissional($profissional_id){
        $fields = 'usuariosprofissionais.*';

        $this->db->select('user_id');
        $this->db->from($this->table);
        $this->db->where('profissional_id', $profissional_id);
        $query = $this->db->get();

        return $query->result();
    }
}
