<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Escala_model extends MY_Model {
    protected $table = 'escalas';
    protected $view = 'vw_escalas';

    public function __construct()
    {
        parent::__construct($this->table);
    }

    public function get_escalas($where, $where_in = null)
    {
        if (!$where_in) {
            $query = $this->db->get_where($this->view, $where);
        } else {
            $this->db->from($this->view);
            $this->db->where($where);
            $this->db->where_in($where_in);
            $this->db->order_by('dataplantao, horainicialplantao', 'ASC');
            $query = $this->db->get();
        }
        //$query = $this->db->order_by('dataplantao, horainicialplantao', 'ASC')->get_where($this->view, $where);

        return $query->result();
    }
}
