<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Escala_model extends MY_Model {
    protected $table = 'escalas';

    public function __construct()
    {
        parent::__construct($this->table);
    }

    public function get_escalas_originais($where, $where_in = null, $order_by = null)
    {
        $fields = 'escalas.*, profissionais.id as profissional_id, ';
        $fields .= 'profissionais.registro as profissional_registro, ';
        $fields .= 'profissionais.nome as profissional_nome, ';
        $fields .= 'setores.id as setor_id, setores.nome as setor_nome, ';
        $fields .= 'unidadeshospitalares.id as unidadehospitalar_id, ';
        $fields .= 'unidadeshospitalares.razaosocial as unidadehospitalar_razaosocial, ';
        $fields .= 'unidadeshospitalares.nomefantasia as unidadehospitalar_nomefantasia ';

        $this->db->select($fields);
        $this->db->from($this->table);
        $this->db->join('profissionais', 'profissionais.id = escalas.profissional_id', 'left');
        $this->db->join('setores', 'setores.id = escalas.setor_id', 'left');
        $this->db->join('unidadeshospitalares', 'unidadeshospitalares.id = setores.unidadehospitalar_id', 'left');
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

    public function get_escalas_consolidadas($where, $where_in = null, $order_by = null)
    {
        $fields = 'escalas.*, profissionais.id as profissional_id, ';
        $fields .= 'profissionais.registro as profissional_registro, ';
        $fields .= 'profissionais.nome as profissional_nome, ';
        $fields .= 'setores.id as setor_id, setores.nome as setor_nome, ';
        $fields .= 'unidadeshospitalares.id as unidadehospitalar_id, ';
        $fields .= 'unidadeshospitalares.razaosocial as unidadehospitalar_razaosocial, ';
        $fields .= 'unidadeshospitalares.nomefantasia as unidadehospitalar_nomefantasia, ';
        $fields .= 'passagenstrocas.id as passagenstrocas_id, ';
        $fields .= 'passagenstrocas.profissional_id as passagenstrocas_profissional_id, ';
        $fields .= 'profissional_passagem.registro as profissional_passagem_registro, ';
        $fields .= 'profissional_passagem.nome as profissional_passagem_nome, ';
        $fields .= 'passagenstrocas.profissionalsubstituto_id as passagenstrocas_profissionalsubstituto_id, ';
        $fields .= 'profissional_substituto.registro as profissional_substituto_registro, ';
        $fields .= 'profissional_substituto.nome as profissional_substituto_nome, ';
        $fields .= 'passagenstrocas.tipopassagem as passagenstrocas_tipopassagem, ';
        $fields .= 'passagenstrocas.datahorapassagem as passagenstrocas_datahorapassagem, ';
        $fields .= 'passagenstrocas.datahoraconfirmacao as passagenstrocas_datahoraconfirmacao, ';
        $fields .= 'passagenstrocas.statuspassagem as passagenstrocas_statuspassagem, ';
        $fields .= 'passagenstrocas.escalatroca_id as passagenstrocas_escalatroca_id, ';
        $fields .= 'escala_trocada.dataplantao as escala_trocada_dataplantao, ';
        $fields .= 'escala_trocada.horainicialplantao as escala_trocada_horainicialplantao, ';
        $fields .= 'escala_trocada.horafinalplantao as escala_trocada_horafinalplantao ';

        $this->db->select($fields);
        $this->db->from($this->table);
        $this->db->join('passagenstrocas', 'passagenstrocas.escala_id = escalas.id', 'left');
        $this->db->join('profissionais profissional_passagem', 'profissional_passagem.id = passagenstrocas.profissional_id', 'left');
        $this->db->join('profissionais profissional_substituto', 'profissional_substituto.id = passagenstrocas.profissionalsubstituto_id', 'left');
        $this->db->join('escalas escala_trocada', 'escala_trocada.id = passagenstrocas.escalatroca_id', 'left');
        $this->db->join('profissionais', 'profissionais.id = escalas.profissional_id', 'left');
        $this->db->join('setores', 'setores.id = escalas.setor_id', 'left');
        $this->db->join('unidadeshospitalares', 'unidadeshospitalares.id = setores.unidadehospitalar_id', 'left');
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

    public function get_escala($where)
    {
        return $this->db->get_where($this->table, $where)->row();
    }
}
