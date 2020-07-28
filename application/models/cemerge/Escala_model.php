<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Escala_model extends MY_Model {
    protected $table = 'escalas';
    protected $view = 'vw_escalas';

    public function __construct()
    {
        parent::__construct($this->table);
    }

    public function get_escalas($where)
    {
        $query = $this->db->order_by('dataplantao, horainicialplantao', 'ASC')->get_where($this->view, $where);

        return $query->result();
    }
}
