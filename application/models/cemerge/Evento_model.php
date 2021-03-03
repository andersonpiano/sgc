<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Evento_model extends MY_Model
{
    protected $table = 'fopag_eventos';

    public function __construct()
    {
        parent::__construct($this->table);
    }

    public function is_duplicated($field, $value, $id = NULL) {
        if (!empty($id)) {
            $this->db->where("id <>", $id);
        }
        $this->db->from($this->table);
        $this->db->where($field, $value);
        return $this->db->get()->num_rows() > 0;
    }

    var $column_search = array("nome");
    var $column_order = array("id", "nome");

    private function _get_datatable() {

        $search = NULL;
        if ($this->input->post("search")) {
            $search = $this->input->post("search")["value"];
        }
        $order_column = NULL;
        $order_dir = NULL;
        $order = $this->input->post("order");
        if (isset($order)) {
            $order_column = $order[0]["column"];
            $order_dir = $order[0]["dir"];
        }

        $this->db->from($this->table);
        if (isset($search)) {
            $first = TRUE;
            foreach ($this->column_search as $field) {
                if ($first) {
                    $this->db->group_start();
                    $this->db->like($field, $search);
                    $first = FALSE;
                } else {
                    $this->db->or_like($field, $search);
                }
            }
            if (!$first) {
                $this->db->group_end();
            }
        }

        if (isset($order)) {
            $this->db->order_by($this->column_order[$order_column], $order_dir);
        }
    }

    public function get_datatable() {

        $length = $this->input->post("length");
        $start = $this->input->post("start");
        $this->_get_datatable();
        if (isset($length) && $length != -1) {
            $this->db->limit($length, $start);
        }
        return $this->db->get()->result();
    }

    public function records_filtered() {

        $this->_get_datatable();
        return $this->db->get()->num_rows();

    }

    public function records_total() {

        $this->db->from($this->table);
        return $this->db->count_all_results();

    }

    public function get_eventos(){

        $this->db->select('id, nome');
        $this->db->from($this->table);
        $query = $this->db->get();

        return $query;
    }

    public function get_data($id, $select = NULL) {
		if (!empty($select)) {
			$this->db->select($select);
		}
		$this->db->from($this->table);
		$this->db->where("id", $id);
		return $this->db->get();
    }
    
    public function update($id, $data)
    {
        $where = "id = $id";

        return $this->db->update($this->table, $data, $where);
    }

}