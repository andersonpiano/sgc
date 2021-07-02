<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Setor_model extends MY_Model {
    protected $table = 'setores';

    public function __construct()
    {
        parent::__construct($this->table);
    }

    public function get_by_unidadehospitalar($unidadehospitalar_id)
    {
        $where = array('unidadehospitalar_id' => $unidadehospitalar_id);

        return $this->get_where($where, null, 'nome');
    }

    public function get_by_unidade_profissional($unidadehospitalar_id, $profissional_id)
    {
        $fields = 'setores.*';

        $this->db->select($fields);
        $this->db->from($this->table);
        $this->db->join('unidadeshospitalares', 'setores.unidadehospitalar_id = unidadeshospitalares.id');
        $this->db->join('profissionalsetor', 'setores.id = profissionalsetor.setor_id');
        $this->db->join('profissionais', 'profissionais.id = profissionalsetor.profissional_id');
        $this->db->where(array('profissional_id' => $profissional_id, 'unidadeshospitalares.id' => $unidadehospitalar_id));
        $this->db->order_by('setores.nome');
        $query = $this->db->get();

        return $query->result();
    }

    public function get_by_unidade_usuario($unidadehospitalar_id, $usuario_id)
    {
        $fields = 'setores.*, usuariosetor.coordenador';

        $where = array('usuariosetor.user_id' => $usuario_id, 'unidadeshospitalares.id' => $unidadehospitalar_id);

        $this->db->select($fields);
        $this->db->from($this->table);
        $this->db->join('unidadeshospitalares', 'setores.unidadehospitalar_id = unidadeshospitalares.id');
        $this->db->join('usuariosetor', 'setores.id = usuariosetor.setor_id');
        $this->db->where($where);
        $this->db->order_by('setores.nome');
        $query = $this->db->get();

        return $query->result();
    }

    public function get_setores_por_profissional($profissional_id)
    {
        $fields = 'setores.*';

        $this->db->select($fields);
        $this->db->from($this->table);
        $this->db->join('profissionalsetor', 'setores.id = profissionalsetor.setor_id', 'left');
        $this->db->join('profissionais', 'profissionais.id = profissionalsetor.profissional_id', 'left');
        $this->db->where('profissional_id', $profissional_id);
        $query = $this->db->get();

        return $query->result();
    }

    public function get_setores_por_unidade($unidade)
    {
        $fields = 'setores.*';

        $this->db->select($fields);
        $this->db->from($this->table);
        $this->db->join('profissionalsetor', 'setores.id = profissionalsetor.setor_id', 'left');
        $this->db->join('profissionais', 'profissionais.id = profissionalsetor.profissional_id', 'left');
        $this->db->where('unidadehospitalar_id', $unidade);
        $this->db->where_in('setores.active', 1);
        $query = $this->db->get();

        return $query->result();
    }

    public function get_setor_por_id($id)
    {
        $fields = '*';

        $this->db->select($fields);
        $this->db->from($this->table);
        $this->db->where('id', $id);
        $query = $this->db->get();

        return $query->row();
    }

    public function get_setores_coordenados_por_profissional($profissional_id)
    {
        $fields = 'setores.*';

        $this->db->select($fields);
        $this->db->from($this->table);
        $this->db->join('usuariosetor', 'usuariosetor.setor_id = setores.id', 'left');
        $this->db->join('users', 'users.id = usuariosetor.user_id', 'left');
        $this->db->join('usuariosprofissionais', 'usuariosprofissionais.user_id = users.id', 'left');
        $this->db->join('profissionais', 'profissionais.id = usuariosprofissionais.profissional_id', 'left');
        $this->db->where(array('profissionais.id' => $profissional_id, 'coordenador' => 1));
        $query = $this->db->get();

        return $query->result();
    }

    public function get_setores_coordenados_por_usuario($usuario_id)
    {
        $fields = 'setores.*';

        $this->db->select($fields);
        $this->db->from($this->table);
        $this->db->join('usuariosetor', 'usuariosetor.setor_id = setores.id', 'left');
        $this->db->join('users', 'users.id = usuariosetor.user_id', 'left');
        $this->db->where(array('users.id' => $usuario_id, 'usuariosetor.coordenador' => 1));
        $query = $this->db->get();

        return $query->result();
    }

    public function get_setores_assessus_por_cd_pes_jur($cd_pes_jur)
    {
        $sql = "select s.cd_set, s.nm_set ";
        $sql .= "from tb_set s ";
        $sql .= "where cd_pes_jur = $cd_pes_jur ";
        $sql .= "order by s.nm_set";

        $query = $this->db->query($sql);

        return $query->result();
    }

    public function assessus_x_sgc($setor_id)
    {
        $sql = "select id, setor_id, cd_set ";
        $sql .= "from grupos_setores ";
        $sql .= "where setor_id = $setor_id ";

        $query = $this->db->query($sql);

        return $query->result();
    }

    public function setor_assessus($setor_id){
        $sql = "select s.nm_set ";
        $sql .= "from tb_set s ";
        $sql .= "where cd_set = $setor_id ";

        $query = $this->db->query($sql);

        return $query->row();
    }

    public function vagas($setor){

        $sql  = "SELECT * ";
        $sql .= "FROM `escalas` ";
        $sql .= "WHERE dataplantao ";
        $sql .= "BETWEEN '".date('Y-m-d')."' and '".date('Y-m-d', strtotime("+30 days"))."'"; 
        $sql .= "and setor_id = $setor ";
        $sql .= "and profissional_id = 0 ";
        
        $query = $this->db->query($sql);

        return $query->num_rows();
        
}

    public function sgc_x_assessus($cd_set)
    {
        $sql = "select id, setor_id, cd_set ";
        $sql .= "from grupos_setores ";
        $sql .= "where cd_set = $cd_set ";

        $query = $this->db->query($sql);

        return $query->row();
    }

    var $column_search = array('id', "nome");
    var $column_order = array('id', "nome");

    var $column_search1 = array('id','profissional_id',"setor_id");
    var $column_order1 = array('id','profissional_id',"setor_id");

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

    public function get_datatable_setor_profissional($profissional_id) {

        $length = $this->input->post("length");
        $start = $this->input->post("start");
        $this->_get_datatable_setor_profissional($profissional_id);
        if (isset($length) && $length != -1) {
            $this->db->limit($length, $start);
        }
        return $this->db->get()->result();
    }

    private function _get_datatable_setor_profissional($profissional_id) {

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

        $this->db->from('profissionalsetor');
        $this->db->where('profissional_id', $profissional_id);
        if (isset($search)) {
            $first = TRUE;
            foreach ($this->column_search1 as $field) {
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
            $this->db->order_by($this->column_order1[$order_column], $order_dir);
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

    var $column_search_vagos = array('dataplantao');
    var $column_order_vagos = array('dataplantao');

    private function _get_datatable_vagas($setor_id) {

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
        $this->db->where("dataplantao BETWEEN '".date('Y-m-d')."' and '".date('Y-m-d', strtotime("+30 days"))."'");
        $this->db->where('setor_id', $setor_id);
        $this->db->where('profissional_id', 0);

        if (isset($search)) {
            $first = TRUE;
            foreach ($this->column_search_vagos as $field) {
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
            $this->db->order_by($this->column_order_vagos[$order_column], $order_dir);
        }
    }

    public function get_datatable_vagas($setor_id) {

        $length = $this->input->post("length");
        $start = $this->input->post("start");
        $this->_get_datatable_vagas($setor_id);
        if (isset($length) && $length != -1) {
            $this->db->limit($length, $start);
        }
        return $this->db->get()->result();
    }

    public function records_filtered_vagas($setor_id) {

        $this->_get_datatable_vagas($setor_id);
        return $this->db->get()->num_rows();

    }

    public function records_total_vagas($setor_id) {

        $this->db->from($this->table);
        $this->db->where('id', $setor_id);
        return $this->db->count_all_results();

    }

}
