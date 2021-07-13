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
        $fields .= "e.id as plantao, e.dataplantao as data_inicial_plantao, ";
        $fields .= "e.horainicialplantao as hora_entrada, ";
        $fields .= "e.horafinalplantao as hora_saida, ";
        $fields .= "p.id as profissional_id, ";
        $fields .= "s.nome as setor_nome, ";
        $fields .= "p.nome as nome_profissional, ";
        $fields .= "j.status as status, ";
        $fields .= "j.entrada_justificada as entrada_justificada, ";
        $fields .= "j.saida_justificada as saida_justificada, j.create_at ";


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
        $sql .= " order by e.dataplantao ";
        
        $query = $this->db->query($sql);

        return $query->result();
    }

    public function get_justificativas_por_profissional($profissional, $datainicial, $datafinal, $status)
    {
        $fields = "j.id as id, ";
        $fields .= "e.id as plantao, e.dataplantao as data_inicial_plantao, ";
        $fields .= "e.horainicialplantao as hora_entrada, ";
        $fields .= "e.horafinalplantao as hora_saida, ";
        $fields .= "p.id as profissional_id, ";
        $fields .= "s.nome as setor_nome, ";
        $fields .= "p.nome as nome_profissional, ";
        $fields .= "j.status as status, ";
        $fields .= "j.entrada_justificada as entrada_justificada, ";
        $fields .= "j.saida_justificada as saida_justificada ";


        $sql = "SELECT $fields FROM justificativas as j ";
        $sql .= "JOIN escalas e on (j.escala_id = e.id) ";
        $sql .= "JOIN profissionais p on (j.profissional_id = p.id) ";
        $sql .= "JOIN setores s on (j.setor_id = s.id) ";
        $sql .= "WHERE ";
        $sql .= "p.id = $profissional ";
        $sql .= "AND j.data_plantao BETWEEN '$datainicial' and '$datafinal'";
        if ($status != 3){
            $sql .= "AND j.status = $status";
        }
        $sql .= " order by e.dataplantao desc";
        
        $query = $this->db->query($sql);

        return $query->result();
    }

    var $column_search = array('escalas.dataplantao');
    var $column_order = array('escalas.dataplantao');

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

        $this->db->select('escalas.id as escala_id, escalas.dataplantao, escalas.setor_id, escalas.profissional_id, escalas.horainicialplantao ');
        $this->db->from('escalas');
        $this->db->join('passagenstrocas', 'passagenstrocas.escala_id = escalas.id and passagenstrocas.statuspassagem = 1', 'left');
        $this->db->where('escalas.justificativa', 1);
        $this->db->where_in('passagenstrocas.profissionalsubstituto_id', $profissional);
        $this->db->where_in('passagenstrocas.statuspassagem', 1);
        $this->db->or_where('escalas.profissional_id', $profissional);
        $this->db->where_in('escalas.justificativa', 1);
        $this->db->where_in('passagenstrocas.statuspassagem is null');


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

    var $column_search2 = array('setores.nome', 'profissionais.nome');
    var $column_order2 = array( 'escalas.dataplantao','setores.nome', 'profissionais.nome');

    private function _get_justificativas_pendentes($data_ini, $data_fim) {

        $search = NULL;
        if ($this->input->post("search")) {
            $search = $this->input->post("search")["value"];
        }
        $order_column = NULL;
        $order_dir = 'DESC';
        $order = $this->input->post("order");
        if (isset($order)) {
            $order_column = $order[0]["column"];
            $order_dir = $order[0]["dir"];
        }

        $this->db->select('escalas.id as escala_id, escalas.dataplantao, escalas.horainicialplantao, escalas.profissional_id, profissionais.nome as profissional_nome, setores.nome as setor_nome');
        $this->db->from('escalas');
        $this->db->join('profissionais', 'escalas.profissional_id = profissionais.id');
        $this->db->join('setores', 'escalas.setor_id = setores.id');
        $this->db->where_in('justificativa', 1);

            $this->db->where('escalas.dataplantao >=', $data_ini);
            $this->db->where('escalas.dataplantao <=', $data_fim);

        $this->db->order_by('escalas.dataplantao desc');

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
        } else {
            $this->db->order_by('str_to_date(escalas.dataplantao, "%Y-%b-%b")');
        }
    }

    public function get_justificativas_pendentes($data_ini, $data_fim) {

        $length = $this->input->post("length");
        $start = $this->input->post("start");
        $this->_get_justificativas_pendentes($data_ini, $data_fim);
        if (isset($length) && $length != -1) {
            $this->db->limit($length, $start);
        }
        return $this->db->get()->result();
    }

    public function records_pendentes_filtered() {

        $this->_get_justificativas_pendentes();
        return $this->db->get()->num_rows();

    }

    public function records_total() {

        $this->db->from($this->table);
        return $this->db->count_all_results();

    }

    public function records_datatable_filtered($profissional) {

        $this->_get_datatable($profissional);
        return $this->db->get()->num_rows();

    }
}
