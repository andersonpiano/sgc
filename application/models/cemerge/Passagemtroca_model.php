<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Passagemtroca_model extends MY_Model {
    protected $table = 'passagenstrocas';

    public function __construct()
    {
        parent::__construct($this->table);
    }

    public function set_passagem_troca_valida($escala_id, $passagem_troca_valida)
    {
        $sql = "update passagenstrocas ";
        $sql .= "set statuspassagem = 3 ";
        $sql .= "where escala_id = " . $escala_id . " ";
        $sql .= "and id <> " . $passagem_troca_valida;

        return $this->db->query($sql);
    }
}
