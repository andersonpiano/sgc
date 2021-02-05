<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Frequencia_model extends MY_Model {
    protected $table = 'frequencias';

    public function __construct()
    {
        parent::__construct($this->table);
    }

    public function get_frequencias_mt($unidadehospitalar_id, $setor_id, $data_plantao)
    {
        $sql = "select f.id, f.unidadehospitalar_id, f.setor_id, f.escala_id, ";
        $sql .= "f.profissional_id, p.nomecurto as profissional_nome, f.datahorabatida, f.tipobatida ";
        $sql .= "from frequencias f ";
        $sql .= "join profissionais p on (f.profissional_id = p.id) ";
        $sql .= "where f.tipobatida in (3,4) ";
        $sql .= "and date(datahorabatida) = '$data_plantao' ";
        $sql .= "order by f.unidadehospitalar_id, f.setor_id, f.profissional_id, f.datahorabatida";

        $query = $this->db->query($sql);

        return $query->result();
    }

    public function get_frequencias_justificadas($unidadehospitalar_id, $setor_id, $data_plantao)
    {
        $sql = "select f.id, f.unidadehospitalar_id, f.setor_id, f.escala_id, ";
        $sql .= "f.profissional_id, p.nomecurto as profissional_nome, f.datahorabatida, f.tipobatida ";
        $sql .= "from frequencias f ";
        $sql .= "join profissionais p on (f.profissional_id = p.id) ";
        $sql .= "where f.tipobatida in (5,6) ";
        $sql .= "and date(datahorabatida) = '$data_plantao' ";
        $sql .= "order by f.unidadehospitalar_id, f.setor_id, f.profissional_id, f.datahorabatida";

        $query = $this->db->query($sql);

        return $query->result();
    }

}