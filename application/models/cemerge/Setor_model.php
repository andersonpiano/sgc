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
}
