<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Setor_model extends MY_Model {
    protected $table = 'setores';

    public function __construct()
    {
        parent::__construct($this->table);
    }

    public function get_by_unidade_profissional($unidadehospitalar_id, $profissional_id)
    {
        $fields = 'setores.*';

        $this->db->select($fields);
        $this->db->from($this->table);
        $this->db->join('unidadehospitalar', 'setores.unidadehospitalar_id = unidadehospitalar.id');
        $this->db->join('profissionalsetor', 'setores.id = profissionalsetor.setor_id');
        $this->db->join('profissionais', 'profissionais.id = profissionalsetor.profissional_id');
        $this->db->where(array('profissional_id' => $profissional_id, 'unidadehospitalar.id' => $unidadehospitalar_id));
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
}
