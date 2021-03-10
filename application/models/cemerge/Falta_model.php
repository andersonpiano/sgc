<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Classe de Faltas
 */
class Falta_model extends MY_Model
{
    protected $table = 'faltas';

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct($this->table);
    }

    public function get_faltas_por_profissional_e_periodo($profissional_id, $data_inicial, $data_final)
    {
        $fields = 'f.id, f.tipo_falta, f.justificativa, f.datahora_insercao, f.user_id, ';
        $fields .= 'p.id as profissional_id, p.registro as profissional_registro, p.nome as profissional_nome, p.nomecurto as profissional_nomecurto, ';
        $fields .= 's.id as setor_id, s.nome as setor_nome, ';
        $fields .= 'e.id as escala_id, e.dataplantao, e.datafinalplantao, e.horainicialplantao, e.horafinalplantao';
        
        $where = array (
            'p.id' => $profissional_id,
            'e.dataplantao >= ' => $data_inicial,
            'e.dataplantao <= ' => $data_final,
        );

        $this->db->select($fields);
        $this->db->distinct();
        $this->db->from($this->table . " f");
        $this->db->join('escalas e', 'e.id = f.escala_id');
        $this->db->join('setores s', 's.id = e.setor_id');
        $this->db->join('profissionais p', 'p.id = f.profissional_id');
        $this->db->where($where);
        $this->db->order_by('e.dataplantao, p.nome');
        $query = $this->db->get();

        return $query->result();
    }
}
