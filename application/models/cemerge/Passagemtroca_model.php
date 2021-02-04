<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Passagemtroca_model extends MY_Model
{
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

        /*
        ANALISAR CORREÇÃO COMO NESTE COMENTÁRIO
        $query = $this->db->query($sql);

        return $query->result();
        */

        return $this->db->query($sql);
    }

    public function get_cessoes_trocas_escalas($data_inicial_escala, $data_final_escala, $status_cessao_troca)
    {
        $sql = "select pt.id, pt.tipopassagem, pt.datahorapassagem, pt.datahoraconfirmacao, pt.statuspassagem, pt.escalatroca_id, ";
        $sql .= "e.id as escala_id, e.dataplantao, e.datafinalplantao, e.horainicialplantao, e.horafinalplantao, ";
        $sql .= "s.id as setor_id, s.nome as setor_nome, ";
        $sql .= "p_passagem.id as profissional_passagem_id, p_passagem.registro as profissional_passagem_registro, ";
        $sql .= "p_passagem.nome as profissional_passagem_nome, p_passagem.nomecurto as p_passagem_nomecurto, ";
        $sql .= "p_substituto.id as profissional_substituto_id, p_substituto.registro as profissional_substituto_registro, ";
        $sql .= "p_substituto.nome as profissional_substituto_nome, p_substituto.nomecurto as p_substituto_nomecurto ";
        $sql .= "from passagenstrocas pt ";
        $sql .= "join escalas e on (pt.escala_id = e.id) ";
        $sql .= "join setores s on (e.setor_id = s.id) ";
        $sql .= "join profissionais p_passagem on (pt.profissional_id = p_passagem.id) ";
        $sql .= "left join profissionais p_substituto on (pt.profissionalsubstituto_id = p_substituto.id) ";
        $sql .= "where e.dataplantao between '" . $data_inicial_escala . "' and '" . $data_final_escala . "' ";
        $sql .= "and statuspassagem = $status_cessao_troca ";
        $sql .= "order by e.dataplantao, e.horainicialplantao ";

        $query = $this->db->query($sql);

        return $query->result();
    }
    public function get_sessoes_from_limbo($data_inicial_escala, $data_final_escala, $status_cessao_troca, $profissional_id)
    {   

        if ($status_cessao_troca == 0){
        $sql = "select pt.id, pt.tipopassagem, pt.datahorapassagem, pt.datahoraconfirmacao, pt.statuspassagem, pt.escalatroca_id, ";
        $sql .= "e.id as escala_id, e.dataplantao, e.datafinalplantao, e.horainicialplantao, e.horafinalplantao, ";
        $sql .= "s.id as setor_id, s.nome as setor_nome, ";
        $sql .= "p_passagem.id as profissional_passagem_id, p_passagem.registro as profissional_passagem_registro, ";
        $sql .= "p_passagem.nome as profissional_passagem_nome, p_passagem.nomecurto as p_passagem_nomecurto, ";
        $sql .= "p_substituto.id as profissional_substituto_id, p_substituto.registro as profissional_substituto_registro, ";
        $sql .= "p_substituto.nome as profissional_substituto_nome, p_substituto.nomecurto as p_substituto_nomecurto ";
        $sql .= "from passagenstrocas pt ";
        $sql .= "join escalas e on (pt.escala_id = e.id) ";
        $sql .= "join setores s on (e.setor_id = s.id) ";
        $sql .= "join profissionais p_passagem on (pt.profissional_id = p_passagem.id) ";
        $sql .= "left join profissionais p_substituto on (pt.profissionalsubstituto_id = p_substituto.id) ";
        $sql .= "join profissionalsetor setor on (setor.profissional_id = pt.profissional_id)";
        $sql .= "where e.dataplantao between '" . $data_inicial_escala . "' and '" . $data_final_escala . "' ";
        $sql .= "and statuspassagem = $status_cessao_troca ";
        $sql .= "and s.id in (select setor_id from profissionalsetor where profissional_id = '".$profissional_id."') ";
        $sql .= "and s.id in (setor.setor_id) ";
        $sql .= "and pt.profissionalsubstituto_id = 9999 ";
        $sql .= "and pt.profissional_id <> '".$profissional_id."' ";
        $sql .= "order by e.dataplantao, e.horainicialplantao ";
        } else {
            $sql = "select pt.id, pt.tipopassagem, pt.datahorapassagem, pt.datahoraconfirmacao, pt.statuspassagem, pt.escalatroca_id, ";
        $sql .= "e.id as escala_id, e.dataplantao, e.datafinalplantao, e.horainicialplantao, e.horafinalplantao, ";
        $sql .= "s.id as setor_id, s.nome as setor_nome, ";
        $sql .= "p_passagem.id as profissional_passagem_id, p_passagem.registro as profissional_passagem_registro, ";
        $sql .= "p_passagem.nome as profissional_passagem_nome, p_passagem.nomecurto as p_passagem_nomecurto, ";
        $sql .= "p_substituto.id as profissional_substituto_id, p_substituto.registro as profissional_substituto_registro, ";
        $sql .= "p_substituto.nome as profissional_substituto_nome, p_substituto.nomecurto as p_substituto_nomecurto ";
        $sql .= "from passagenstrocas pt ";
        $sql .= "join escalas e on (pt.escala_id = e.id) ";
        $sql .= "join setores s on (e.setor_id = s.id) ";
        $sql .= "join profissionais p_passagem on (pt.profissional_id = p_passagem.id) ";
        $sql .= "left join profissionais p_substituto on (pt.profissionalsubstituto_id = p_substituto.id) ";
        $sql .= "join profissionalsetor setor on (setor.profissional_id = pt.profissional_id)";
        $sql .= "where e.dataplantao between '" . $data_inicial_escala . "' and '" . $data_final_escala . "' ";
        $sql .= "and statuspassagem = $status_cessao_troca ";
        $sql .= "and s.id in (select setor_id from profissionalsetor where profissional_id = '".$profissional_id."') ";
        $sql .= "and s.id in (setor.setor_id) ";
        $sql .= "order by e.dataplantao, e.horainicialplantao ";
        }
        $query = $this->db->query($sql);

        return $query->result();
    }
}
