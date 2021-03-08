<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Justificativa_model extends MY_Model
{
    protected $table = 'justificativas';

    public function __construct()
    {
        parent::__construct($this->table);
    }


    public function get_justificativas_profissional($datainicial, $datafinal, $status)
    {
        $fields = "j.id as id, ";
        $fields .= "e.dataplantao as data_inicial_plantao, ";
        $fields .= "e.horainicialplantao as hora_entrada, ";
        $fields .= "e.horafinalplantao as hora_saida, ";
        $fields .= "p.id as profissional_id, ";
        $fields .= "s.nome as setor_nome, ";
        $fields .= "p.nome as nome_profissional, ";
        $fields .= "j.status as status, ";
        $fields .= "j.hora_entrada as entrada_justificada, ";
        $fields .= "j.hora_saida as saida_justificada ";


        $sql = "SELECT $fields FROM justificativas as j ";
        $sql .= "JOIN escalas e on (j.escala_id = e.id) ";
        $sql .= "JOIN profissionais p on (j.profissional_id = p.id) ";
        $sql .= "JOIN setores s on (j.setor_id = s.id) ";
        $sql .= "WHERE ";
        $sql .= "j.data_plantao BETWEEN '$datainicial' and '$datafinal'";
        if ($status == 3){

        } else {
            $sql .= "AND j.status = $status";
        }
        
        $query = $this->db->query($sql);

        return $query->result();
    }
}
