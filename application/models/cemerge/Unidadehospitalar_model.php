<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Unidadehospitalar_model extends CI_Model {
    private $table = 'unidadeshospitalares';

    public function __construct()
    {
        parent::__construct();
    }

    public function get_all()
    {
        $query = $this->db->get($this->table);

        return $query->result();
    }

    public function get_by_id($id)
    {
        $query = $this->db->get_where($this->table, array('id' => $id));

        return $query->row();
    }

    public function update($id, $data)
    {
        $where = "id = $id";

        return $this->db->update($this->table, $data, $where);
    }
}
