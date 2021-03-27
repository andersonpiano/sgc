<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profissional_model extends MY_Model
{
    protected $table = 'profissionais';

    public function __construct()
    {
        parent::__construct($this->table);
    }

    public function get_profissionais_por_setor($setor_id) {
        $fields = 'profissionais.id, profissionais.cd_pes_fis, profissionais.registro, profissionais.cpf, profissionais.nome, profissionais.nomecurto, profissionais.email';

        $this->db->select($fields);
        $this->db->from($this->table);
        $this->db->join('profissionalsetor', 'profissionais.id = profissionalsetor.profissional_id');
        $this->db->join('setores', 'setores.id = profissionalsetor.setor_id');
        $this->db->where('setores.id', $setor_id);
        $this->db->order_by('profissionais.nome');
        $query = $this->db->get();

        return $query->result();
    }

    public function get_vinculo_por_profissional($profissional_id){
        
        $this->db->select('vinculo_id');
        $this->db->from($this->table);
        $this->db->where('id', $profissional_id);
        $query = $this->db->get();

        return $query->result();


    }

    public function get_profissionais_por_unidade_hospitalar($unidadehospitalar_id) {
        $fields = 'profissionais.id, profissionais.nome, profissionais.nomecurto';

        $this->db->select($fields);
        $this->db->distinct();
        $this->db->from($this->table);
        $this->db->join('profissionalsetor', 'profissionais.id = profissionalsetor.profissional_id');
        $this->db->join('setores', 'setores.id = profissionalsetor.setor_id');
        $this->db->join('unidadeshospitalares', 'setores.unidadehospitalar_id = unidadeshospitalares.id');
        $this->db->where('unidadeshospitalares.id', $unidadehospitalar_id);
        $this->db->order_by('profissionais.nome');
        $query = $this->db->get();

        return $query->result();
    }

    public function get_by_user_id($user_id) {
        $fields = 'profissionais.id, profissionais.cd_pes_fis, profissionais.registro, profissionais.nome, profissionais.nomecurto, profissionais.email';

        $this->db->select($fields);
        $this->db->from($this->table);
        $this->db->join('usuariosprofissionais', 'profissionais.id = usuariosprofissionais.profissional_id');
        $this->db->join('users', 'users.id = usuariosprofissionais.user_id');
        $this->db->where('users.id', $user_id);
        $query = $this->db->get();

        return $query->row();
    }

    public function get_cd_pes_fis($user_id){
        $this->db->select('cd_pes_fis');
        $this->db->from($this->table);
        $this->db->where('id', $user_id);
        $query = $this->db->get();

        return $query->row();
    }

    public function get_profissional_by_id($profissional_id){
        $this->db->select('*');
        $this->db->from($this->table);
        $this->db->where('id', $profissional_id);
        $query = $this->db->get();

        return $query->row();
    }

    public function get_by_cd_pes_fis($cd_pes_fis) {
        $fields = 'id, cd_pes_fis, registro, nome, nomecurto';

        $this->db->select($fields);
        $this->db->from($this->table);
        $this->db->where('cd_pes_fis', $cd_pes_fis);
        $query = $this->db->get();

        return $query->row();
    }

    var $column_search = array("nome");
    var $column_order = array("id", "nome", "registro", "email");

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
}
