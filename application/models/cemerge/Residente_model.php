<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Residente_model extends MY_Model
{
    protected $table = 'residentes';

    public function __construct()
    {
        parent::__construct($this->table);
    }

    public function get_residentes_por_unidadehospitalar($unidadehospitalar_id)
    {
        $fields = 'residentes.*';

        $this->db->select($fields);
        $this->db->from($this->table);
        $this->db->join('residenteunidadehospitalar', 'residentes.id = residenteunidadehospitalar.residente_id');
        $this->db->join('unidadeshospitalares', 'unidadeshospitalares.id = residenteunidadehospitalar.unidadehospitalar_id');
        $this->db->where('unidadeshospitalares.id', $unidadehospitalar_id);
        $this->db->order_by('residentes.nome');
        $query = $this->db->get();

        return $query->result();
    }

    public function get_by_user_id($user_id)
    {
        $fields = 'residentes.id, residentes.registro, residentes.nome, residentes.nomecurto, residentes.email';

        $this->db->select($fields);
        $this->db->from($this->table);
        $this->db->join('usuariosresidentes', 'residentes.id = usuariosresidentes.residente_id');
        $this->db->join('users', 'users.id = usuariosresidentes.user_id');
        $this->db->where('users.id', $user_id);
        $query = $this->db->get();

        return $query->row();
    }
}
