<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class funcionario_model extends MY_Model
{
    protected $table = 'funcionarios';
    public function __construct()
    {
        parent::__construct($this->table);
    }

    public function get_funcionarios_por_setor($setor_id) {
        $fields = 'funcionarios.id, funcionarios.cd_pes_fis, funcionarios.registro, funcionarios.cpf, funcionarios.nome, funcionarios.nomecurto, funcionarios.email';
        $this->db->select($fields);
        $this->db->from($this->table);
        $this->db->join('funcionariosetor', 'funcionarios.id = funcionariosetor.funcionario_id');
        $this->db->join('setores', 'setores.id = funcionariosetor.setor_id');
        $this->db->where('setores.id', $setor_id);
        $this->db->order_by('funcionarios.nome');
        $query = $this->db->get();
        return $query->result();
    }

    public function get_funcionarios_por_setor_disponiveis($setor_id, $plantao)
    {
        $fields = 'funcionarios.id, funcionarios.cd_pes_fis, funcionarios.registro, funcionarios.cpf, funcionarios.nome, funcionarios.nomecurto, funcionarios.email';
        $sql = "select ".$fields." from funcionarios
        join funcionariosetor on funcionarios.id = funcionariosetor.funcionario_id
        join setores on setores.id = funcionariosetor.setor_id
        where setor_id = " . $setor_id;
        $sql .= " 
        AND funcionarios.id not in (
            SELECT escalas.funcionario_id FROM escalas 
            LEFT JOIN passagenstrocas on passagenstrocas.escala_id = escalas.id
            WHERE passagenstrocas.statuspassagem <> 1 
            AND dataplantao = (select dataplantao from escalas where id = ".$plantao.") 
            AND horainicialplantao = (select horainicialplantao from escalas where id = ".$plantao.") 
            AND escalas.funcionario_id <> 0 
            )";
        //$sql .= "order by ec.nomesetor, ec.dataplantao, ec.nome_funcionario, ec.horainicialplantao, f_entrada.cd_ctl_frq";
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function get_vinculo_por_funcionario($funcionario_id){
        $this->db->select('vinculo_id');
        $this->db->from($this->table);
        $this->db->where('id', $funcionario_id);
        $query = $this->db->get();
        return $query->result();
    }

    public function get_funcionarios_por_unidade_hospitalar($unidadehospitalar_id) {
        $fields = 'funcionarios.id, funcionarios.nome, funcionarios.nomecurto';
        $this->db->select($fields);
        $this->db->distinct();
        $this->db->from($this->table);
        $this->db->join('funcionariosetor', 'funcionarios.id = funcionariosetor.funcionario_id');
        $this->db->join('setores', 'setores.id = funcionariosetor.setor_id');
        $this->db->join('unidadeshospitalares', 'setores.unidadehospitalar_id = unidadeshospitalares.id');
        $this->db->where('unidadeshospitalares.id', $unidadehospitalar_id);
        $this->db->order_by('funcionarios.nome');
        $query = $this->db->get();
        return $query->result();
    }

    public function get_by_user_id($user_id) {
        $fields = 'funcionarios.id, funcionarios.cd_pes_fis, funcionarios.registro, funcionarios.nome, funcionarios.nomecurto, funcionarios.email';
        $this->db->select($fields);
        $this->db->from($this->table);
        $this->db->join('usuariosfuncionarios', 'funcionarios.id = usuariosfuncionarios.funcionario_id');
        $this->db->join('users', 'users.id = usuariosfuncionarios.user_id');
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

    public function get_funcionario_by_id($funcionario_id){
        $this->db->select('*');
        $this->db->from($this->table);
        $this->db->where('id', $funcionario_id);
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
    var $column_search = array('matricula',"nome","registro");
    var $column_order = array('id',"matricula", "nome", "registro", "email");

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
