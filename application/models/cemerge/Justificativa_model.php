<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Justificativa_model extends MY_Model
{
    protected $table = 'justificativas';

    public function __construct()
    {
        parent::__construct($this->table);
    }


    public function get_justificativas_profissional($datainicial, $datafinal, $status)
    {
        $fields = "j.id as id, ";
        $fields .= "e.dataplantao as data_inicial_plantao, ";
        $fields .= "e.horainicialplantao as hora_entrada, ";
        $fields .= "e.horafinalplantao as hora_saida, ";
        $fields .= "p.id as profissional_id, ";
        $fields .= "s.nome as setor_nome, ";
        $fields .= "p.nome as nome_profissional, ";
        $fields .= "j.status as status, ";
        $fields .= "j.hora_entrada as entrada_justificada, ";
        $fields .= "j.hora_saida as saida_justificada ";


        $sql = "SELECT $fields FROM justificativas as j ";
        $sql .= "JOIN escalas e on (j.escala_id = e.id) ";
        $sql .= "JOIN profissionais p on (j.profissional_id = p.id) ";
        $sql .= "JOIN setores s on (j.setor_id = s.id) ";
        $sql .= "WHERE ";
        $sql .= "j.data_plantao BETWEEN '$datainicial' and '$datafinal'";
        if ($status == 3){

        } else {
            $sql .= "AND j.status = $status";
        }
        
        $query = $this->db->query($sql);

        return $query->result();
    }

    public function get_justificativas_por_profissional($profissional, $datainicial, $datafinal, $status)
    {
        $fields = "j.id as id, ";
        $fields .= "e.dataplantao as data_inicial_plantao, ";
        $fields .= "e.horainicialplantao as hora_entrada, ";
        $fields .= "e.horafinalplantao as hora_saida, ";
        $fields .= "p.id as profissional_id, ";
        $fields .= "s.nome as setor_nome, ";
        $fields .= "p.nome as nome_profissional, ";
        $fields .= "j.status as status, ";
        $fields .= "j.hora_entrada as entrada_justificada, ";
        $fields .= "j.hora_saida as saida_justificada ";


        $sql = "SELECT $fields FROM justificativas as j ";
        $sql .= "JOIN escalas e on (j.escala_id = e.id) ";
        $sql .= "JOIN profissionais p on (j.profissional_id = p.id) ";
        $sql .= "JOIN setores s on (j.setor_id = s.id) ";
        $sql .= "WHERE ";
        $sql .= "p.id = $profissional ";
        $sql .= "AND j.data_plantao BETWEEN '$datainicial' and '$datafinal'";
        if ($status == 3){

        } else {
            $sql .= "AND j.status = $status";
        }
        
        $query = $this->db->query($sql);

        return $query->result();
    }

    var $column_search = array('dataplantao');
    var $column_order = array('dataplantao');

    private function _get_datatable($profissional) {

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

        $this->db->from('escalas');
        $this->db->where_in('profissional_id', $profissional);
        $this->db->where_in('justificativa', 1);

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

    public function get_datatable($profissional) {

        $length = $this->input->post("length");
        $start = $this->input->post("start");
        $this->_get_datatable($profissional);
        if (isset($length) && $length != -1) {
            $this->db->limit($length, $start);
        }
        return $this->db->get()->result();
    }

    var $column_search2 = array('dataplantao');
    var $column_order2 = array('dataplantao');

    private function _get_justificativas_pendentes() {

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

        $this->db->from('escalas');
        $this->db->where_in('justificativa', 1);

        if (isset($search)) {
            $first = TRUE;
            foreach ($this->column_search2 as $field) {
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
            $this->db->order_by($this->column_order2[$order_column], $order_dir);
        }
    }

    public function get_justificativas_pendentes() {

        $length = $this->input->post("length");
        $start = $this->input->post("start");
        $this->_get_justificativas_pendentes();
        if (isset($length) && $length != -1) {
            $this->db->limit($length, $start);
        }
        return $this->db->get()->result();
    }
}
