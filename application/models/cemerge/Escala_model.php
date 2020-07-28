<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Escala_model extends MY_Model {
    protected $table = 'escalas';

    public function __construct()
    {
        parent::__construct($this->table);
    }

    public function get_escalas($where)
    {
        $query = $this->db->get_where('vw_escalas', $where);

        return $query->result();
    }
}
