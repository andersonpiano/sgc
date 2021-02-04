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

    public function get_by_cd_pes_fis($cd_pes_fis) {
        $fields = 'id, cd_pes_fis, registro, nome, nomecurto';

        $this->db->select($fields);
        $this->db->from($this->table);
        $this->db->where('cd_pes_fis', $cd_pes_fis);
        $query = $this->db->get();

        return $query->row();
    }
}
