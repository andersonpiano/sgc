<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_model extends CI_Model {
    protected $table = '';

    public function __construct($table)
    {
        parent::__construct();
        $this->table = $table;
    }

    public function get_all($order_by = null)
    {
        $this->db->from($this->table);
        if ($order_by) {
            $this->db->order_by($order_by);
        }
        $query = $this->db->get();

        return $query->result();
    }

    public function get_by_id($id)
    {
        $query = $this->db->get_where($this->table, array('id' => $id));

        return $query->row();
    }

    public function get_where($where, $where_in = null, $order_by = null)
    {
        $this->db->from($this->table);
        $this->db->where($where);
        if ($where_in) {
            $this->db->where_in($where_in);
        }
        if ($order_by) {
            $this->db->order_by($order_by);
        }
        $query = $this->db->get();

        return $query->result();
    }

    public function get_like($field, $value, $order_by = null) {
        $this->db->from($this->table);
        $this->db->like($field, $value);
        if ($order_by) {
            $this->db->order_by($order_by);
        }
        $query = $this->db->get();

        return $query->result();
    }

    public function update($id, $data)
    {
        $where = "id = $id";

        return $this->db->update($this->table, $data, $where);
    }

    public function insert($data)
    {
        $this->db->insert($this->table, $data);

        return $this->db->insert_id();
    }
}
