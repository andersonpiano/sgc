<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Escala_model extends MY_Model
{
    protected $table = 'escalas';

    public function __construct()
    {
        parent::__construct($this->table);
    }

    public function get_escala_referencia($setor_id, $datainicial)
    {
        $fields = 'dataplantao, horainicialplantao, horafinalplantao, duracao, profissional_id, tipo_plantao, extra';
        $where = 'dataplantao = \'' . $datainicial . '\' - INTERVAL 28 DAY ';
        //$where .= 'and dataplantao < \'' . $datainicial . '\' ';
        $where .= 'and setor_id = ' . $setor_id . ' ';
        $where .= 'and (extra = 0 or extra is null) ';
        $where .= 'and tipo_plantao = 0 ';
        $order_by = 'dataplantao, horainicialplantao, id';

        $this->db->select($fields);
        $this->db->from($this->table);
        $this->db->where($where);
        $this->db->order_by($order_by);
        //$this->db->limit($limite);
        //$this->db->limit('84');

        $query = $this->db->get();

        return $query->result();
    }

    public function get_escalas_originais($where, $where_in_column = null, $where_in_values = null, $order_by = null)
    {
        // escalas.*
        $fields = 'escalas.id, escalas.dataplantao, escalas.datafinalplantao, escalas.tipo_escala, ';
        $fields .= 'escalas.horainicialplantao, escalas.horafinalplantao, escalas.duracao, ';
        $fields .= 'escalas.profissional_id, escalas.setor_id, profissionais.vinculo_id, ';
        $fields .= 'escalas.tipo_plantao, escalas.extra, ';
        $fields .= 'escalas.publicada, ';
        $fields .= 'profissionais.id as profissional_id, ';
        $fields .= 'profissionais.registro as profissional_registro, ';
        $fields .= 'profissionais.nome as profissional_nome, ';
        $fields .= 'setores.id as setor_id, setores.nome as setor_nome, ';
        $fields .= 'unidadeshospitalares.id as unidadehospitalar_id, ';
        $fields .= 'unidadeshospitalares.razaosocial as unidadehospitalar_razaosocial, ';
        $fields .= 'unidadeshospitalares.nomefantasia as unidadehospitalar_nomefantasia ';

        $this->db->select($fields);
        $this->db->from($this->table);
        $this->db->join('profissionais', 'profissionais.id = escalas.profissional_id', 'left');
        $this->db->join('setores', 'setores.id = escalas.setor_id');
        $this->db->join('unidadeshospitalares', 'unidadeshospitalares.id = setores.unidadehospitalar_id');
        $this->db->where($where);
        if (!is_null($where_in_column) and !empty($where_in_values)) {
            $this->db->where_in($where_in_column, $where_in_values);
        }
        if ($order_by) {
            $this->db->order_by($order_by);
        }
        $query = $this->db->get();

        return $query->result();
    }

    public function escala_por_profissional($data, $profissional_id){
        $this->db->select('*');
        $this->db->from($this->table);
        $this->db->where_in('dataplantao', $data);
        $this->db->where_in('profissional_id', $profissional_id);
        $this->db->order_by('dataplantao');

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
        $this->db->join('passagenstrocas', 'passagenstrocas.escala_id = escalas.id and passagenstrocas.statuspassagem = 1', 'left');
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


    public function get_escalas_consolidadas_validacao($setor_id, $ano, $mes)
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

        // Escala de Janeiro
        if ($mes == 1) {
            $mesanterior = 12;
            $anoanterior = $ano - 1;
            $datainicial = $anoanterior . '-' . $mesanterior . '-' . '21';
            $datafinal = $ano . '-' . $mes . '-' . '20';
            $where = 'escalas.setor_id = ' . $setor_id . ' ';
            $where .= 'and escalas.dataplantao between \'' . $datainicial . '\' and \'' . $datafinal . '\' ';
            $where .= 'and escalas.validada is null ';
        } else {
            $mesanterior = $mes - 1;
            $datainicial = $ano . '-' . $mesanterior . '-' . '21';
            $datafinal = $ano . '-' . $mes . '-' . '20';
            $where = 'escalas.setor_id = ' . $setor_id . ' ';
            $where .= 'and escalas.dataplantao between \'' . $datainicial . '\' and \'' . $datafinal . '\' ';
            $where .= 'and escalas.validada is null ';
        }

        $this->db->select($fields);
        $this->db->from($this->table);
        $this->db->join('passagenstrocas', 'passagenstrocas.escala_id = escalas.id and passagenstrocas.statuspassagem = 1', 'left');
        $this->db->join('profissionais profissional_passagem', 'profissional_passagem.id = passagenstrocas.profissional_id', 'left');
        $this->db->join('profissionais profissional_substituto', 'profissional_substituto.id = passagenstrocas.profissionalsubstituto_id', 'left');
        $this->db->join('escalas escala_trocada', 'escala_trocada.id = passagenstrocas.escalatroca_id', 'left');
        $this->db->join('profissionais', 'profissionais.id = escalas.profissional_id', 'left');
        $this->db->join('setores', 'setores.id = escalas.setor_id', 'left');
        $this->db->join('unidadeshospitalares', 'unidadeshospitalares.id = setores.unidadehospitalar_id', 'left');
        $this->db->where($where);
        $this->db->order_by('dataplantao, horainicialplantao');
        $query = $this->db->get();

        return $query->result();
    }

    public function get_escalas_consolidadas_grade_frequencia($setor_id, $ano, $mes)
    {
        $sql = "
        select escalas.id, escalas.dataplantao, escalas.datafinalplantao, escalas.horainicialplantao, escalas.horafinalplantao,
        setores.nome as nomesetor, unidadeshospitalares.razaosocial as nomeunidade,
        profissionais.nomecurto as profissional_titular,
        profissional_passagem.nomecurto as profissional_passagem,
        profissional_substituto.nomecurto as profissional_substituto,
        coalesce(profissional_substituto.nomecurto, profissionais.nomecurto) as nome_profissional
        from escalas
        left join passagenstrocas on (passagenstrocas.escala_id = escalas.id and passagenstrocas.statuspassagem = 1)
        left join profissionais profissional_passagem on (profissional_passagem.id = passagenstrocas.profissional_id)
        left join profissionais profissional_substituto on (profissional_substituto.id = passagenstrocas.profissionalsubstituto_id)
        left join profissionais on (profissionais.id = escalas.profissional_id)
        left join setores on (setores.id = escalas.setor_id)
        left join unidadeshospitalares on (unidadeshospitalares.id = setores.unidadehospitalar_id)
        where escalas.setor_id = 5
        and escalas.dataplantao between '2020-10-01' and '2020-10-31'
        order by nome_profissional, escalas.dataplantao, escalas.horainicialplantao
        ";

        // Escala de Janeiro
        /*
        if ($mes == 1) {
            $mesanterior = 12;
            $anoanterior = $ano - 1;
            $datainicial = $anoanterior . '-' . $mesanterior . '-' . '21';
            $datafinal = $ano . '-' . $mes . '-' . '20';
            $where = 'escalas.setor_id = ' . $setor_id . ' ';
            $where .= 'and escalas.dataplantao between \'' . $datainicial . '\' and \'' . $datafinal . '\' ';
            $where .= 'and escalas.validada is null ';
        } else {
            $mesanterior = $mes - 1;
            $datainicial = $ano . '-' . $mesanterior . '-' . '21';
            $datafinal = $ano . '-' . $mes . '-' . '20';
            $where = 'escalas.setor_id = ' . $setor_id . ' ';
            $where .= 'and escalas.dataplantao between \'' . $datainicial . '\' and \'' . $datafinal . '\' ';
            $where .= 'and escalas.validada is null ';
        }

        $this->db->select($fields);
        $this->db->from($this->table);
        $this->db->join('passagenstrocas', 'passagenstrocas.escala_id = escalas.id and passagenstrocas.statuspassagem = 1', 'left');
        $this->db->join('profissionais profissional_passagem', 'profissional_passagem.id = passagenstrocas.profissional_id', 'left');
        $this->db->join('profissionais profissional_substituto', 'profissional_substituto.id = passagenstrocas.profissionalsubstituto_id', 'left');
        $this->db->join('escalas escala_trocada', 'escala_trocada.id = passagenstrocas.escalatroca_id', 'left');
        $this->db->join('profissionais', 'profissionais.id = escalas.profissional_id', 'left');
        $this->db->join('setores', 'setores.id = escalas.setor_id', 'left');
        $this->db->join('unidadeshospitalares', 'unidadeshospitalares.id = setores.unidadehospitalar_id', 'left');
        $this->db->where($where);
        $this->db->order_by('dataplantao, horainicialplantao');
        $query = $this->db->get();
        */
        $query = $this->db->query($sql);

        return $query->result();
    }

    public function get_escalas_consolidadas_cal($setor_id, $data_inicial, $data_final)
    {
        $sql = "
        select id, idsetor, nomesetor, idunidade, nomeunidade,
        dataplantao, datafinalplantao, horainicialplantao, horafinalplantao,
        id_profissional, cd_pes_fis_profissional, crm_profissional, nome_profissional
        from vw_escalas_consolidadas ec
        where ec.idsetor = $setor_id
        and ec.dataplantao between '$data_inicial' and '$data_final'
        order by ec.dataplantao, ec.horainicialplantao
        ";

        $query = $this->db->query($sql);

        return $query->result();
    }

    public function get_escalas_consolidadas_por_profissional_cal($profissional_id, $setor_id, $data_inicial, $data_final)
    {
        $sql = "
        select id, idsetor, nomesetor, idunidade, nomeunidade,
        dataplantao, datafinalplantao, horainicialplantao, horafinalplantao,
        id_profissional, cd_pes_fis_profissional, crm_profissional, nome_profissional
        from vw_escalas_consolidadas ec
        where ec.idsetor = $setor_id
        and ec.dataplantao between '$data_inicial' and '$data_final'
        and ec.id_profissional = $profissional_id
        order by ec.dataplantao, ec.horainicialplantao
        ";

        $query = $this->db->query($sql);

        return $query->result();
    }

    public function get_trocas_passagens_por_profissional_cal($profissional_id, $setor_id, $data_inicial, $data_final)
    {
        $sql = "
        select id, idsetor, nomesetor, idunidade, nomeunidade,
        dataplantao, datafinalplantao, horainicialplantao, horafinalplantao,
        id_profissional, cd_pes_fis_profissional, crm_profissional, nome_profissional
        from vw_escalas_consolidadas ec
        where ec.idsetor = $setor_id
        and ec.dataplantao between '$data_inicial' and '$data_final'
        and ec.id_profissional = $profissional_id
        and ec.nome_profissional <> ec.profissional_titular
        order by ec.dataplantao, ec.horainicialplantao
        ";

        $query = $this->db->query($sql);

        return $query->result();
    }

    public function get_escalas_originais_cal($setor_id, $data_inicial, $data_final)
    {
        $sql = "
        select e.id, s.id as idsetor, s.nome as nomesetor, u.id as idunidade, u.razaosocial as nomeunidade,
        e.dataplantao, e.datafinalplantao, e.horainicialplantao, e.horafinalplantao,
        p.id as id_profissional, p.cd_pes_fis as cd_pes_fis_profissional, p.registro as crm_profissional, p.nomecurto as nome_profissional
        from escalas e
        left join profissionais p on (p.id = e.profissional_id)
        join setores s on (s.id = e.setor_id)
        join unidadeshospitalares u on (u.id = s.unidadehospitalar_id)
        where e.setor_id = $setor_id
        and e.dataplantao between '$data_inicial' and '$data_final'
        order by e.dataplantao, e.horainicialplantao
        ";

        $query = $this->db->query($sql);

        return $query->result();
    }

    
    public function get_passagens_trocas_cal($setor_id, $data_inicial, $data_final)
    {
        $sql = "
            select id, idsetor, nomesetor, idunidade, nomeunidade,
            dataplantao, datafinalplantao, horainicialplantao, horafinalplantao,
            id_profissional, cd_pes_fis_profissional, crm_profissional, nome_profissional
            from vw_escalas_consolidadas ec
            where ec.idsetor = $setor_id
            and ec.dataplantao between '$data_inicial' and '$data_final'
            and ec.nome_profissional <> profissional_titular
            order by ec.dataplantao, ec.horainicialplantao
        ";

        $query = $this->db->query($sql);

        return $query->result();
    }

    public function get_escalas_consolidadas_por_unidade_hospitalar($unidadehospitalar_id, $datainicial, $datafinal)
    {
        $sql = "
        select escalas.id, escalas.dataplantao, escalas.datafinalplantao, escalas.horainicialplantao, escalas.horafinalplantao,
        setores.nome as nomesetor, unidadeshospitalares.razaosocial as nomeunidade,
        profissionais.nomecurto as profissional_titular,
        profissional_passagem.nomecurto as profissional_passagem,
        profissional_substituto.nomecurto as profissional_substituto,
        coalesce(profissional_substituto.cd_pes_fis, profissionais.cd_pes_fis) as cd_pes_fis_profissional,
        coalesce(profissional_substituto.registro, profissionais.registro) as crm_profissional,
        coalesce(profissional_substituto.nomecurto, profissionais.nomecurto) as nome_profissional
        from escalas
        left join passagenstrocas on (passagenstrocas.escala_id = escalas.id and passagenstrocas.statuspassagem = 1)
        left join profissionais profissional_passagem on (profissional_passagem.id = passagenstrocas.profissional_id)
        left join profissionais profissional_substituto on (profissional_substituto.id = passagenstrocas.profissionalsubstituto_id)
        left join profissionais on (profissionais.id = escalas.profissional_id)
        left join setores on (setores.id = escalas.setor_id)
        left join unidadeshospitalares on (unidadeshospitalares.id = setores.unidadehospitalar_id)
        where unidadeshospitalares.id = $unidadehospitalar_id
        and escalas.dataplantao between '$datainicial' and '$datafinal'
        order by escalas.dataplantao, escalas.horainicialplantao
        ";

        $query = $this->db->query($sql);

        return $query->result();
    }

    public function get_escala_processada($setor_id, $datainicial, $datafinal, $order)
    {
        $sql = "select ec.dataplantao, ec.horainicialplantao, ec.horafinalplantao, ";
        $sql .= "ec.nomesetor, ec.nomeunidade, ec.crm_profissional, ec.nome_profissional, ";
        $sql .= "f_entrada.DT_FRQ as batidaentrada, f_saida.DT_FRQ as batidasaida ";
        $sql .= "from vw_escalas_consolidadas ec ";
        $sql .= "join escalas e_entrada on (ec.id = e_entrada.id) ";
        $sql .= "join escalas e_saida on (ec.id = e_saida.id) ";
        $sql .= "left join tb_ctl_frq f_entrada on (e_entrada.frequencia_entrada_id = f_entrada.cd_ctl_frq) ";
        $sql .= "left join tb_ctl_frq f_saida on (e_saida.frequencia_saida_id = f_saida.cd_ctl_frq) ";
        $sql .= "where ec.dataplantao between '$datainicial' and '$datafinal' ";
        $sql .= "and ec.idsetor = $setor_id ";
        $sql .= "and ec.nome_profissional is not null ";
        if ($order == 1){
        $sql .= "order by ec.nomesetor, ec.dataplantao, ec.nome_profissional, ec.horainicialplantao, f_entrada.cd_ctl_frq ";
        } else if ($order == 2){
        $sql .= "order by ec.nomesetor, ec.dataplantao ";
        } else if ($order == 3){
        $sql .= "order by ec.nome_profissional, ec.dataplantao ";   
        } else if ($order == 4){
        $sql .="order by DAYOFWEEK(ec.dataplantao) ";
        }
        //$sql .= "order by ec.nomesetor, ec.dataplantao, ec.nome_profissional, ec.horainicialplantao, f_entrada.cd_ctl_frq";

        $query = $this->db->query($sql);

        return $query->result();
    }

    public function get_escala_processada_setor($profissional_id, $setor_id, $datainicial, $datafinal, $order)
    {
        $sql = "select ec.dataplantao, ec.horainicialplantao, ec.horafinalplantao, ";
        $sql .= "ec.nomesetor, ec.nomeunidade, ec.crm_profissional, ec.nome_profissional, ";
        $sql .= "f_entrada.DT_FRQ as batidaentrada, f_saida.DT_FRQ as batidasaida ";
        $sql .= "from vw_escalas_consolidadas ec ";
        $sql .= "join escalas e_entrada on (ec.id = e_entrada.id) ";
        $sql .= "join escalas e_saida on (ec.id = e_saida.id) ";
        $sql .= "left join tb_ctl_frq f_entrada on (e_entrada.frequencia_entrada_id = f_entrada.cd_ctl_frq) ";
        $sql .= "left join tb_ctl_frq f_saida on (e_saida.frequencia_saida_id = f_saida.cd_ctl_frq) ";
        $sql .= "where ec.dataplantao between '$datainicial' and '$datafinal' ";
        $sql .= "and ec.idsetor = $setor_id ";
        $sql .= "and ec.nome_profissional is not null ";
        $sql .= "and ec.id_profissional = $profissional_id ";
        if ($order == 1){
        $sql .= "order by ec.dataplantao ";
        } else if ($order == 2){
        $sql .= "order by ec.nomesetor, ec.dataplantao ";
        } else if ($order == 3){
        $sql .= "order by ec.nome_profissional, ec.dataplantao ";   
        } else if ($order == 4){
        $sql .="order by DAYOFWEEK(ec.dataplantao) ";
        }
        //$sql .= "order by ec.nomesetor, ec.dataplantao, ec.nome_profissional, ec.horainicialplantao, f_entrada.cd_ctl_frq";

        $query = $this->db->query($sql);

        return $query->result();
    }

    public function get_justificativas_a_confirmar($profissional_id)
    {

        $sql = "SELECT ec.id plantao_id, setor_id, profissional_id, dataplantao, nome,  justificativa, horainicialplantao, horafinalplantao FROM escalas as ec ";
        $sql .= "join profissionais p on (p.id = ec.profissional_id) ";
        $sql .= "WHERE ";
        $sql .= "p.id = ".$profissional_id;
        $sql .= " AND ec.justificativa = 1 ";
                
        $query = $this->db->query($sql);

        return $query->result();
    }

    public function get_escala_processada_profissional($profissional_id, $datainicial, $datafinal, $order)
    {

        $sql = "select ec.dataplantao, ec.horainicialplantao, ec.horafinalplantao, ";
        $sql .= "ec.nomesetor, ec.nomeunidade, ec.crm_profissional, ec.nome_profissional, ";
        $sql .= "f_entrada.DT_FRQ as batidaentrada, f_saida.DT_FRQ as batidasaida ";
        $sql .= "from vw_escalas_consolidadas ec ";
        $sql .= "join escalas e_entrada on (ec.id = e_entrada.id) ";
        $sql .= "join escalas e_saida on (ec.id = e_saida.id) ";
        $sql .= "left join tb_ctl_frq f_entrada on (e_entrada.frequencia_entrada_id = f_entrada.cd_ctl_frq) ";
        $sql .= "left join tb_ctl_frq f_saida on (e_saida.frequencia_saida_id = f_saida.cd_ctl_frq) ";
        $sql .= "where ec.dataplantao between '$datainicial' and '$datafinal' ";
        $sql .= "and ec.id_profissional = $profissional_id ";
        $sql .= "and ec.nome_profissional is not null ";
        if ($order == 1){
        $sql .= "order by ec.nomesetor, ec.dataplantao, ec.nome_profissional, ec.horainicialplantao, f_entrada.cd_ctl_frq";
        } else if ($order == 2){
        $sql .= "order by ec.nomesetor";
        } else if ($order == 3){
        $sql .= "order by ec.nome_profissional";   
        } else if ($order == 4){
        $sql .="order by DAYOFWEEK(ec.dataplantao) ";
        }
        

        $query = $this->db->query($sql);

        return $query->result();
    }

    public function get_plantao_conflito($idprofissional, $dataplantao, $horainicialplantao)
    {
        $sql = "select id, dataplantao, datafinalplantao, ";
        $sql .= "horainicialplantao, horafinalplantao, idsetor, nomesetor ";
        $sql .= "from vw_escalas_consolidadas ";
        $sql .= "where id_profissional = $idprofissional ";
        $sql .= "and dataplantao = '$dataplantao' ";
        $sql .= "and horainicialplantao = '$horainicialplantao'";

        $query = $this->db->query($sql);

        return $query->row();
    }

    public function get_plantoes_mt($unidadehospitalar_id, $dataplantao)
    {
        $sql = "select ec.id, ec.dataplantao, ec.datafinalplantao, ec.horainicialplantao, ec.horafinalplantao, ";
        $sql .= "ec.idsetor, ec.nomesetor, ec.idunidade, ec.nomeunidade, ec.id_profissional, ec.cd_pes_fis_profissional, ec.nome_profissional ";
        $sql .= "FROM `vw_escalas_consolidadas` ec ";
        $sql .= "join escalas e on (ec.id = e.id and ((e.horainicialplantao = '07:00:00' and e.frequencia_entrada_id is not null) or e.horainicialplantao = '13:00:00')) ";
        $sql .= "where (ec.horainicialplantao = '07:00:00' or ec.horainicialplantao = '13:00:00') ";
        $sql .= "and ec.dataplantao = '$dataplantao' ";
        $sql .= "and ec.id_profissional is not null ";
        $sql .= "and ec.id not in (select escala_id from frequencias where escala_id is not null) ";
        $sql .= "order by ec.idsetor, ec.nome_profissional, ec.horainicialplantao";

        $query = $this->db->query($sql);

        return $query->result();
    }

    public function get_frequencia_sem_escala($unidadehospitalar_id, $setor_id, $datainicial, $datafinal)
    {
        $sql = "select f.cd_ctl_frq, f.dt_frq, f.hr_frq, f.tp_frq, f.cd_ctl_frq_ant, ";
        $sql .= "p.id as id_profissional, p.registro as crm, p.nome as nome_profissional, p.nomecurto as nome_curto_profissional, ";
        $sql .= "s.nm_set as nome_setor, s2.nome as nome_setor_sgc ";
        $sql .= "from tb_ctl_frq f ";
        $sql .= "join tb_set s on (f.cd_set = s.cd_set) ";
        $sql .= "join grupos_setores gs on (gs.cd_set = s.cd_set) ";
        $sql .= "join setores s2 on (gs.setor_id = s2.id) ";
        $sql .= "join profissionais p on (f.cd_pes_fis = p.cd_pes_fis) ";
        $sql .= "where f.escala_id is null ";
        $sql .= "and date(f.dt_frq) between '$datainicial' and '$datafinal' ";
        $sql .= "and (f.ignorar = 0 or f.ignorar is null) ";
        $sql .= "and f.cd_pes_jur in ";
        $sql .= "(select cd_pes_jur ";
        $sql .= "from grupos_unidadeshospitalares ";
        $sql .= "where unidadehospitalar_id = $unidadehospitalar_id) ";
        if ($setor_id) {
            $sql .= "and f.cd_set in ";
            $sql .= "(select cd_set ";
            $sql .= "from grupos_setores ";
            $sql .= "where setor_id = $setor_id) ";
        }
        $sql .= "order by f.dt_frq";

        $query = $this->db->query($sql);

        return $query->result();
    }

    public function get_profissional_escalado($data, $hora, $profissional_id){

        $sql = "SELECT * FROM escalas ";
        $sql .= "where profissional_id = '$profissional_id' ";
        $sql .= "and dataplantao = '$data' ";
        $sql .= "and horainicialplantao = '$hora' ";

        $query = $this->db->query($sql);

        return $query->num_rows();
    }

    public function get_frequencias_escalas($unidadehospitalar_id, $setor_id, $datainicial, $datafinal)
    {
        $sql = "select p.nome as nome_profissional_frq, f.cd_ctl_frq, f.cd_pes_jur, f.cd_set, tb_set.nm_set, ";
        $sql .= "f.cd_pes_fis, f.dt_frq, f.hr_frq, f.tp_frq, f.escala_id, f.tipo_batida, ";
        $sql .= "ec.id, ec.dataplantao, ec.datafinalplantao, ec.horainicialplantao, ec.horafinalplantao, ";
        $sql .= "ec.idsetor, ec.nomesetor, ec.idunidade, ec.nomeunidade, ";
        $sql .= "ec.id_profissional, ec.cd_pes_fis_profissional, ec.crm_profissional, ec.nome_profissional ";
        $sql .= "from tb_ctl_frq f ";
        $sql .= "left join vw_escalas_consolidadas ec on (f.escala_id = ec.id) ";
        $sql .= "join tb_set on (tb_set.cd_set = f.cd_set) ";
        $sql .= "join profissionais p on (f.cd_pes_fis = p.cd_pes_fis) ";
        $sql .= "where date(f.dt_frq) between '$datainicial' and '$datafinal' ";
        $sql .= "and f.cd_pes_jur = $unidadehospitalar_id ";
        /*
        $sql .= "and f.cd_pes_jur in ";
        $sql .= "(select cd_pes_jur from grupos_unidadeshospitalares where unidadehospitalar_id = $unidadehospitalar_id) ";
        */
        if ($setor_id) {
            $sql .= "and f.cd_set = $setor_id ";
            /*
            $sql .= "and f.cd_set in ";
            $sql .= "(select cd_set ";
            $sql .= "from grupos_setores ";
            $sql .= "where setor_id = $setor_id) ";
            */
        }
        $sql .= "order by p.nome, f.dt_frq";

        $query = $this->db->query($sql);

        return $query->result();
    }

    public function get_escalas_frequencias($unidadehospitalar_id, $setor_id, $datainicial, $datafinal, $turnos, $dias_semana)
    {
        // Considerar join
        $sql = "select ";
        $sql .= "f_entrada.cd_ctl_frq as cd_ctl_frq_entrada, f_entrada.cd_pes_jur as cd_pes_jur_entrada, ";
        $sql .= "f_entrada.cd_pes_fis as cd_pes_fis_entrada, f_entrada.dt_frq as dt_frq_entrada, ";
        $sql .= "f_entrada.hr_frq as hr_frq_entrada, f_entrada.tp_frq as tp_frq_entrada, ";
        $sql .= "f_entrada.escala_id as escala_id_entrada, f_entrada.tipo_batida as tipo_batida_entrada, ";
        $sql .= "f_saida.cd_ctl_frq as cd_ctl_frq_saida, f_saida.cd_pes_jur as cd_pes_jur_saida, ";
        $sql .= "f_saida.cd_pes_fis as cd_pes_fis_saida, f_saida.dt_frq as dt_frq_saida, ";
        $sql .= "f_saida.hr_frq as hr_frq_saida, f_saida.tp_frq as tp_frq_saida, ";
        $sql .= "f_saida.escala_id as escala_id_saida, f_saida.tipo_batida as tipo_batida_saida, ";
        $sql .= "ec.id, ec.dataplantao, ec.datafinalplantao, ec.horainicialplantao, ec.horafinalplantao, ";
        $sql .= "ec.idsetor, ec.nomesetor, ec.prescricao, ec.idunidade, ec.nomeunidade, ec.status, ";
        $sql .= "ec.id_profissional, ec.cd_pes_fis_profissional, ec.crm_profissional, ec.nome_profissional, ec.vinculo_id_profissional, e_entrada.tipo_escala ";
        $sql .= "from vw_escalas_consolidadas ec ";
        $sql .= "join escalas e_entrada on (ec.id = e_entrada.id) ";
        $sql .= "join escalas e_saida on (ec.id = e_saida.id) ";
        $sql .= "left join tb_ctl_frq f_entrada on (e_entrada.frequencia_entrada_id = f_entrada.cd_ctl_frq) ";
        $sql .= "left join tb_ctl_frq f_saida on (e_saida.frequencia_saida_id = f_saida.cd_ctl_frq) ";
        $sql .= "where ec.dataplantao between '$datainicial' and '$datafinal' ";
        $sql .= "and ec.idunidade = $unidadehospitalar_id ";
        //$sql .= "and ec.nome_profissional is not null ";
        if ($setor_id) {
            $sql .= "and ec.idsetor = $setor_id ";
        }
        if (!empty($turnos)) {
            $sql .= "and ec.horainicialplantao in (" . implode(', ', $turnos) . ") ";
        }
        if (!empty($dias_semana)) {
            $sql .= "and dayofweek(ec.dataplantao) in (" . implode(', ', $dias_semana) . ") ";
        }
        $sql .= "order by ec.nomesetor, ec.dataplantao, ec.horainicialplantao, ec.nome_profissional";

        $query = $this->db->query($sql);

        return $query->result();
    }

    public function get_escalas_frequencias_plantao($unidadehospitalar_id, $setor_id, $datainicial, $datafinal, $profissional_id)
    {
        // Considerar join
        $sql = "select ";
        $sql .= "f_entrada.cd_ctl_frq as cd_ctl_frq_entrada, f_entrada.cd_pes_jur as cd_pes_jur_entrada, ";
        $sql .= "f_entrada.cd_pes_fis as cd_pes_fis_entrada, f_entrada.dt_frq as dt_frq_entrada, ";
        $sql .= "f_entrada.hr_frq as hr_frq_entrada, f_entrada.tp_frq as tp_frq_entrada, ";
        $sql .= "f_entrada.escala_id as escala_id_entrada, f_entrada.tipo_batida as tipo_batida_entrada, ";
        $sql .= "f_saida.cd_ctl_frq as cd_ctl_frq_saida, f_saida.cd_pes_jur as cd_pes_jur_saida, ";
        $sql .= "f_saida.cd_pes_fis as cd_pes_fis_saida, f_saida.dt_frq as dt_frq_saida, ";
        $sql .= "f_saida.hr_frq as hr_frq_saida, f_saida.tp_frq as tp_frq_saida, ";
        $sql .= "f_saida.escala_id as escala_id_saida, f_saida.tipo_batida as tipo_batida_saida, ";
        $sql .= "ec.id, ec.dataplantao, ec.datafinalplantao, ec.horainicialplantao, ec.horafinalplantao, ";
        $sql .= "ec.idsetor, ec.nomesetor, ec.prescricao, ec.idunidade, ec.nomeunidade, ec.status, ";
        $sql .= "ec.id_profissional, ec.cd_pes_fis_profissional, ec.crm_profissional, ec.nome_profissional, ec.vinculo_id_profissional ";
        $sql .= "from vw_escalas_consolidadas ec ";
        $sql .= "join escalas e_entrada on (ec.id = e_entrada.id) ";
        $sql .= "join escalas e_saida on (ec.id = e_saida.id) ";
        $sql .= "left join tb_ctl_frq f_entrada on (e_entrada.frequencia_entrada_id = f_entrada.cd_ctl_frq) ";
        $sql .= "left join tb_ctl_frq f_saida on (e_saida.frequencia_saida_id = f_saida.cd_ctl_frq) ";
        $sql .= "where ec.dataplantao between '$datainicial' and '$datafinal' ";
        $sql .= "and ec.idunidade = $unidadehospitalar_id ";
        $sql .= "and ec.id_profissional = $profissional_id ";
        if ($setor_id) {
            $sql .= "and ec.idsetor = $setor_id ";
        }

        $sql .= "order by ec.nomesetor, ec.dataplantao, ec.horainicialplantao, ec.nome_profissional";

        $query = $this->db->query($sql);

        return $query->result();
    }

    public function get_frequencia_por_unidade_hospitalar($unidadehospitalar_id, $datainicial, $datafinal, $processada)
    {
        $sql = "select * ";
        $sql .= "from tb_ctl_frq f ";
        $sql .= "where f.cd_pes_jur = $unidadehospitalar_id ";
        $sql .= "and date(f.dt_frq) between '$datainicial' and '$datafinal' ";
        if ($processada) {
            $sql .= "and escala_id is not null ";
        } else {
            $sql .= "and escala_id is null ";
        }
        $sql .= "order by f.dt_frq";

        $query = $this->db->query($sql);

        return $query->result();
    }

    public function get_frequencia_por_escala($data_batida, $hora_batida, $id_profissional, $id_unidadehospitalar)
    {
        $sql = "select cd_ctl_frq, cd_pes_jur, cd_set, f.cd_pes_fis, ";
        $sql .= "dt_frq, hr_frq, tp_frq, cd_hor_tra, cd_ctl_frq_ant, escala_id, tipo_batida ";
        $sql .= "from tb_ctl_frq f ";
        $sql .= "join profissionais p on (f.cd_pes_fis = p.cd_pes_fis) ";
        $sql .= "where date(f.dt_frq) = '$data_batida' ";
        $sql .= "and (timestampdiff(MINUTE, concat('$data_batida', ' ', '$hora_batida'), f.dt_frq) >= -60 ";
        $sql .= "     and timestampdiff(MINUTE, concat('$data_batida', ' ', '$hora_batida'), f.dt_frq) <= 60) ";
        $sql .= "and p.id = $id_profissional ";
        $sql .= "and f.cd_pes_jur = $id_unidadehospitalar ";
        $sql .= "and f.escala_id is null ";
        $sql .= "order by f.dt_frq";

        $query = $this->db->query($sql);

        return $query->result();
    }

    public function get_frequencia_por_escala_dia($data_batida, $id_profissional, $id_unidadehospitalar) {
        $sql = "select cd_ctl_frq, cd_pes_jur, cd_set, f.cd_pes_fis, ";
        $sql .= "dt_frq, hr_frq, tp_frq, cd_hor_tra, cd_ctl_frq_ant, escala_id, tipo_batida ";
        $sql .= "from tb_ctl_frq f ";
        $sql .= "join profissionais p on (f.cd_pes_fis = p.cd_pes_fis) ";
        $sql .= "where p.id = $id_profissional ";
        $sql .= "and date(f.dt_frq) = '$data_batida' ";
        $sql .= "and p.id = $id_profissional ";
        $sql .= "and f.cd_pes_jur = $id_unidadehospitalar ";
        $sql .= "and f.escala_id is null ";
        $sql .= "order by f.dt_frq";

        $query = $this->db->query($sql);

        return $query->result();
    }

    public function get_frequencia_por_escala_prescricao($data_batida, $id_profissional, $id_unidadehospitalar, $id_setor)
    {
        $sql = "select cd_ctl_frq, cd_pes_jur, cd_set, f.cd_pes_fis, ";
        $sql .= "dt_frq, hr_frq, tp_frq, cd_hor_tra, cd_ctl_frq_ant, escala_id, tipo_batida ";
        $sql .= "from tb_ctl_frq f ";
        $sql .= "join profissionais p on (f.cd_pes_fis = p.cd_pes_fis) ";
        $sql .= "where date(f.dt_frq) = '$data_batida' ";
        $sql .= "and p.id = $id_profissional ";
        $sql .= "and f.cd_pes_jur = $id_unidadehospitalar ";
        $sql .= "and f.cd_set in ";
        $sql .= "(select cd_set ";
        $sql .= "from grupos_setores ";
        $sql .= "where setor_id = $id_setor) ";
        $sql .= "and f.escala_id is null ";
        $sql .= "order by f.dt_frq";

        $query = $this->db->query($sql);

        return $query->result();
    }

    public function get_escala_consolidada_a_processar($unidadehospitalar_id, $setor_id, $datainicial, $datafinal)
    {
        $sql = "SELECT ec.id as escala_id, ec.nomesetor, ec.id_profissional, ec.nome_profissional, ec.dataplantao, ";
        $sql .= "ec.datafinalplantao, ec.horainicialplantao, ec.horafinalplantao, e.frequencia_entrada_id, e.frequencia_saida_id ";
        $sql .= "FROM vw_escalas_consolidadas ec ";
        $sql .= "join escalas e on (e.id = ec.id) ";
        $sql .= "where idunidade = $unidadehospitalar_id ";
        if ($setor_id) {
            $sql .= "and idsetor = $setor_id ";
        } else {
            $sql .= "and idsetor in ( ";
            $sql .= "select id from setores where prescricao = 0 and unidadehospitalar_id = $unidadehospitalar_id ";
            $sql .= ") ";
        }
        
        $sql .= "and ec.dataplantao between '$datainicial' and '$datafinal' ";
        $sql .= "and (e.profissional_id is not null and e.profissional_id != 0) ";
        $sql .= "and (e.frequencia_entrada_id is null or e.frequencia_saida_id is null) ";
        $sql .= "order by ec.dataplantao, ec.horainicialplantao ";

        $query = $this->db->query($sql);

        return $query->result();
    }

    public function get_escala_prescricao_a_processar($unidadehospitalar_id, $datainicial, $datafinal)
    {
        $sql = "SELECT ec.id as escala_id, e.setor_id, ec.nomesetor, ec.id_profissional, ec.nome_profissional, ec.dataplantao, ";
        $sql .= "ec.datafinalplantao, ec.horainicialplantao, ec.horafinalplantao, e.frequencia_entrada_id, e.frequencia_saida_id ";
        $sql .= "FROM vw_escalas_consolidadas ec ";
        $sql .= "join escalas e on (e.id = ec.id) ";
        $sql .= "where idunidade = $unidadehospitalar_id ";
        $sql .= "and idsetor in ( ";
        $sql .= "select id from setores where prescricao = 1 and unidadehospitalar_id = $unidadehospitalar_id ";
        $sql .= ") ";
        $sql .= "and ec.dataplantao between '$datainicial' and '$datafinal' ";
        $sql .= "and (e.profissional_id is not null and e.profissional_id != 0) ";
        $sql .= "and (e.frequencia_entrada_id is null or e.frequencia_saida_id is null) ";
        $sql .= "order by ec.dataplantao, ec.horainicialplantao ";

        $query = $this->db->query($sql);

        return $query->result();
    }

    public function get_frequencias_por_profissional($unidadehospitalar_id, $profissional_id, $datainicial, $datafinal)
    {
        $sql = "select p.nome as nome_profissional_frq, f.cd_ctl_frq, f.cd_pes_jur, f.cd_set, tb_set.nm_set, ";
        $sql .= "f.cd_pes_fis, f.dt_frq, f.hr_frq, f.tp_frq, f.escala_id, f.tipo_batida, ";
        $sql .= "ec.id, ec.dataplantao, ec.datafinalplantao, ec.horainicialplantao, ec.horafinalplantao, ";
        $sql .= "ec.id_profissional, ec.cd_pes_fis_profissional, ec.crm_profissional, ec.nome_profissional ";
        $sql .= "from tb_ctl_frq f ";
        $sql .= "left join vw_escalas_consolidadas ec on (f.escala_id = ec.id) ";
        $sql .= "join tb_set on (tb_set.cd_set = f.cd_set) ";
        $sql .= "join profissionais p on (f.cd_pes_fis = p.cd_pes_fis) ";
        $sql .= "where date(f.dt_frq) between '$datainicial' and '$datafinal' ";
        $sql .= "and f.cd_pes_jur = $unidadehospitalar_id ";
        $sql .= "and p.id = $profissional_id ";

        $query = $this->db->query($sql);

        return $query->result();
    }

    public function get_escala_por_frequencia($datahorafrequencia, $cd_pes_fis_profissional)
    {
        $sql = "SELECT e.id as escala_id, e.nomesetor, e.nome_profissional, e.dataplantao, e.datafinalplantao, ";
        $sql .= "e.horainicialplantao, e.horafinalplantao, f.cd_ctl_frq as frequencia_id, f.dt_frq as frequencia_biometria, ";
        $sql .= "'1' as tipobatida, timediff(concat(dataplantao, ' ', horainicialplantao), f.dt_frq) as diff, ";
        $sql .= "timestampdiff(MINUTE, concat(dataplantao, ' ', horainicialplantao), f.dt_frq) as diffminutes ";
        $sql .= "FROM vw_escalas_consolidadas e ";
        $sql .= "join tb_ctl_frq f on (e.cd_pes_fis_profissional = f.cd_pes_fis and e.dataplantao = date(f.dt_frq)) ";
        $sql .= "where (timestampdiff(MINUTE, concat(dataplantao, ' ', horainicialplantao), f.dt_frq) >= -60 ";
        $sql .= "      and timestampdiff(MINUTE, concat(dataplantao, ' ', horainicialplantao), f.dt_frq) <= 60) ";
        $sql .= "      and f.dt_frq = '$datahorafrequencia' ";
        $sql .= "      and f.cd_pes_fis = $cd_pes_fis_profissional ";
        $sql .= "UNION ";
        $sql .= "SELECT e.id as escala_id, e.nomesetor, e.nome_profissional, e.dataplantao, e.datafinalplantao, ";
        $sql .= "e.horainicialplantao, e.horafinalplantao, f.cd_ctl_frq as frequencia_id, f.dt_frq as frequencia_biometria, ";
        $sql .= "'2' as tipobatida, timediff(concat(dataplantao, ' ', horafinalplantao), f.dt_frq) as diff, ";
        $sql .= "timestampdiff(MINUTE, concat(dataplantao, ' ', horafinalplantao), f.dt_frq) as diffminutes ";
        $sql .= "FROM vw_escalas_consolidadas e ";
        $sql .= "join tb_ctl_frq f on (e.cd_pes_fis_profissional = f.cd_pes_fis and e.dataplantao = date(f.dt_frq)) ";
        $sql .= "where (timestampdiff(MINUTE, concat(dataplantao, ' ', horafinalplantao), f.dt_frq) >= -60 ";
        $sql .= "      and timestampdiff(MINUTE, concat(dataplantao, ' ', horafinalplantao), f.dt_frq) <= 60) ";
        $sql .= "      and f.dt_frq = '$datahorafrequencia' ";
        $sql .= "      and f.cd_pes_fis = $cd_pes_fis_profissional ";
        // Filtrar somente escalas com frequencia_entrada_id ou frequencia_saida_id nulos

        $query = $this->db->query($sql);

        return $query->row();
    }

    public function get_escalas_consolidadas_calendario($mes, $is_mobile)
    {
        $fields = 'case ';
        $fields .= 'when passagenstrocas.id is null then profissionais.nome ';
        $fields .= 'when passagenstrocas.id is not null then profissional_substituto.nome ';
        $fields .= 'end as title, ';
        if ($is_mobile) {
            $fields .= 'escalas.dataplantao as start, ';
            $fields .= 'escalas.datafinalplantao as end, ';
        } else {
            $fields .= 'concat(escalas.dataplantao, \'T\', escalas.horainicialplantao) as start, ';
            $fields .= 'concat(escalas.dataplantao, \'T\', escalas.horafinalplantao) as end, ';
            //$fields .= 'concat(escalas.datafinalplantao, \'T\', escalas.horafinalplantao) as end, ';
        }
        $fields .= 'escalas.id ';

        $this->db->select($fields, false);
        $this->db->from($this->table);
        $this->db->join('profissionais', 'profissionais.id = escalas.profissional_id');
        $this->db->join('passagenstrocas', 'passagenstrocas.escala_id = escalas.id and passagenstrocas.statuspassagem = 1', 'left');
        $this->db->join('profissionais profissional_passagem', 'profissional_passagem.id = passagenstrocas.profissional_id', 'left');
        $this->db->join('profissionais profissional_substituto', 'profissional_substituto.id = passagenstrocas.profissionalsubstituto_id', 'left');
        $this->db->join('escalas escala_trocada', 'escala_trocada.id = passagenstrocas.escalatroca_id', 'left');
        $this->db->where('month(escalas.dataplantao) = ' . $mes);
        $this->db->order_by('escalas.dataplantao, escalas.horainicialplantao');
        // Colocar subquery para obter apenas a última passagemtroca caso exista
        $query = $this->db->get();

        return $query->result();
    }

    public function get_minha_escala_consolidada_calendario(
        $mes, $setor, $profissional, $is_mobile
    ) {
        $sql = 'select coalesce(profissionais.nomecurto, profissionais.nome) as title, ';
        if ($is_mobile) {
            $sql .= 'escalas.dataplantao as start, ';
            $sql .= 'escalas.dataplantao as end, ';
        } else {
            $sql .= 'concat(escalas.dataplantao, \'T\', escalas.horainicialplantao) as start, ';
            $sql .= 'concat(escalas.dataplantao, \'T\', escalas.horafinalplantao) as end, ';
            //$sql .= 'concat(escalas.datafinalplantao, \'T\', escalas.horafinalplantao) as end, ';
        }
        $sql .= 'escalas.id, ';
        $sql .= '\'Original\' as tipoescala ';
        $sql .= 'from escalas ';
        $sql .= 'join profissionais on (escalas.profissional_id = profissionais.id) ';
        $sql .= 'join setores on (escalas.setor_id = setores.id) ';
        //$sql .= 'join unidadeshospitalares on (setores.unidadehospitalar_id = unidadeshospitalares.id) ';
        $sql .= 'where profissionais.id = ' . $profissional . ' ';
        $sql .= 'and escalas.id not in ';
        $sql .= '(select escala_id ';
        $sql .= 'from passagenstrocas ';
        $sql .= 'where escala_id = escalas.id) ';
        $sql .= 'and escalas.setor_id = ' . $setor . ' ';
        $sql .= 'and month(escalas.dataplantao) = ' . $mes . ' ';
        $sql .= 'union ';
        $sql .= 'select coalesce(profissionais.nomecurto, profissionais.nome) as title, ';
        if ($is_mobile) {
            $sql .= 'escalas.dataplantao as start, ';
            $sql .= 'escalas.dataplantao as end, ';
        } else {
            $sql .= 'concat(escalas.dataplantao, \'T\', escalas.horainicialplantao) as start, ';
            $sql .= 'concat(escalas.dataplantao, \'T\', escalas.horafinalplantao) as end, ';
            //$sql .= 'concat(escalas.datafinalplantao, \'T\', escalas.horafinalplantao) as end, ';
        }
        $sql .= 'escalas.id, ';
        $sql .= 'case ';
        $sql .= 'when passagenstrocas.tipopassagem=0 then \'Cessão\' ';
        $sql .= 'when passagenstrocas.tipopassagem=1 then \'Troca\' ';
        $sql .= 'end as tipoescala ';
        $sql .= 'from escalas ';
        $sql .= 'join passagenstrocas on (escalas.id = passagenstrocas.escala_id) ';
        $sql .= 'join profissionais on (passagenstrocas.profissionalsubstituto_id = profissionais.id) ';
        $sql .= 'join setores on (escalas.setor_id = setores.id) ';
        //$sql .= 'join unidadeshospitalares on (setores.unidadehospitalar_id = unidadeshospitalares.id) ';
        $sql .= 'where profissionais.id = ' . $profissional . ' ';
        $sql .= 'and passagenstrocas.statuspassagem = 1 ';
        $sql .= 'and escalas.setor_id = ' . $setor . ' ';
        $sql .= 'and month(escalas.dataplantao) = ' . $mes . ' ';
        $sql .= 'order by start';

        $query = $this->db->query($sql);

        return $query->result();
    }

    public function get_minhas_trocas_passagens_calendario(
        $mes, $setor, $profissional, $is_mobile
    ) {
        $sql = 'select coalesce(profissionais.nomecurto, profissionais.nome) as title, ';
        if ($is_mobile) {
            $sql .= 'escalas.dataplantao as start, ';
            $sql .= 'escalas.dataplantao as end, ';
        } else {
            $sql .= 'concat(escalas.dataplantao, \'T\', escalas.horainicialplantao) as start, ';
            $sql .= 'concat(escalas.dataplantao, \'T\', escalas.horafinalplantao) as end, ';
        }
        $sql .= 'escalas.id, ';
        $sql .= 'case ';
        $sql .= 'when passagenstrocas.tipopassagem=0 then \'Cessão\' ';
        $sql .= 'when passagenstrocas.tipopassagem=1 then \'Troca\' ';
        $sql .= 'end as tipoescala ';
        $sql .= 'from escalas ';
        $sql .= 'join passagenstrocas on (escalas.id = passagenstrocas.escala_id) ';
        $sql .= 'join profissionais on (passagenstrocas.profissionalsubstituto_id = profissionais.id) ';
        $sql .= 'join setores on (escalas.setor_id = setores.id) ';
        $sql .= 'where profissionais.id = ' . $profissional . ' ';
        $sql .= 'and passagenstrocas.statuspassagem = 1 ';
        $sql .= 'and escalas.setor_id = ' . $setor . ' ';
        $sql .= 'and month(escalas.dataplantao) = ' . $mes . ' ';
        $sql .= 'order by start';

        $query = $this->db->query($sql);

        return $query->result();
    }

    public function get_escala_consolidada_setor_calendario(
        $mes, $setor, $is_mobile
    ) {
        $sql = 'select coalesce(profissionais.nomecurto, profissionais.nome) as title, ';
        if ($is_mobile) {
            /*
            $sql .= 'escalas.dataplantao as start, ';
            $sql .= 'escalas.dataplantao as end, ';
            */
            $sql .= 'concat(escalas.dataplantao, \'T\', escalas.horainicialplantao) as start, ';
            $sql .= 'concat(escalas.dataplantao, \'T\', escalas.horafinalplantao) as end, ';
        } else {
            $sql .= 'concat(escalas.dataplantao, \'T\', escalas.horainicialplantao) as start, ';
            $sql .= 'concat(escalas.dataplantao, \'T\', escalas.horafinalplantao) as end, ';
        }
        $sql .= 'escalas.id, ';
        $sql .= '\'Original\' as tipoescala ';
        $sql .= 'from escalas ';
        $sql .= 'join profissionais on (escalas.profissional_id = profissionais.id) ';
        $sql .= 'join setores on (escalas.setor_id = setores.id) ';
        $sql .= 'where escalas.id not in ';
        $sql .= '(select escala_id ';
        $sql .= 'from passagenstrocas ';
        $sql .= 'where escala_id = escalas.id ';
        $sql .= 'and passagenstrocas.statuspassagem = 1) ';
        $sql .= 'and escalas.setor_id = ' . $setor . ' ';
        $sql .= 'and month(escalas.dataplantao) = ' . $mes . ' ';
        $sql .= 'union ';
        $sql .= 'select coalesce(profissionais.nomecurto, profissionais.nome) as title, ';
        if ($is_mobile) {
            /*
            $sql .= 'escalas.dataplantao as start, ';
            $sql .= 'escalas.dataplantao as end, ';
            */
            $sql .= 'concat(escalas.dataplantao, \'T\', escalas.horainicialplantao) as start, ';
            $sql .= 'concat(escalas.dataplantao, \'T\', escalas.horafinalplantao) as end, ';
        } else {
            $sql .= 'concat(escalas.dataplantao, \'T\', escalas.horainicialplantao) as start, ';
            $sql .= 'concat(escalas.dataplantao, \'T\', escalas.horafinalplantao) as end, ';
        }
        $sql .= 'escalas.id, ';
        $sql .= 'case ';
        $sql .= 'when passagenstrocas.tipopassagem=0 then \'Cessão\' ';
        $sql .= 'when passagenstrocas.tipopassagem=1 then \'Troca\' ';
        $sql .= 'end as tipoescala ';
        $sql .= 'from escalas ';
        $sql .= 'join passagenstrocas on (escalas.id = passagenstrocas.escala_id) ';
        $sql .= 'join profissionais on (passagenstrocas.profissionalsubstituto_id = profissionais.id) ';
        $sql .= 'join setores on (escalas.setor_id = setores.id) ';
        $sql .= 'where passagenstrocas.statuspassagem = 1 ';
        $sql .= 'and escalas.setor_id = ' . $setor . ' ';
        $sql .= 'and month(escalas.dataplantao) = ' . $mes . ' ';
        $sql .= 'order by start';

        $query = $this->db->query($sql);

        return $query->result();
    }

    public function get_escala_original_setor_calendario(
        $mes, $setor, $is_mobile
    ) {
        $sql = 'select coalesce(profissionais.nomecurto, profissionais.nome) as title, ';
        if ($is_mobile) {
            $sql .= 'escalas.dataplantao as start, ';
            $sql .= 'escalas.dataplantao as end, ';
        } else {
            $sql .= 'concat(escalas.dataplantao, \'T\', escalas.horainicialplantao) as start, ';
            $sql .= 'concat(escalas.dataplantao, \'T\', escalas.horafinalplantao) as end, ';
        }
        $sql .= 'escalas.id, ';
        $sql .= '\'Original\' as tipoescala ';
        $sql .= 'from escalas ';
        $sql .= 'join profissionais on (escalas.profissional_id = profissionais.id) ';
        $sql .= 'join setores on (escalas.setor_id = setores.id) ';
        $sql .= 'where escalas.setor_id = ' . $setor . ' ';
        $sql .= 'and month(escalas.dataplantao) = ' . $mes . ' ';
        $sql .= 'order by start';

        $query = $this->db->query($sql);

        return $query->result();
    }

    public function get_trocas_passagens_setor_calendario(
        $mes, $setor, $is_mobile
    ) {
        $sql = 'select coalesce(profissionais.nomecurto, profissionais.nome) as title, ';
        if ($is_mobile) {
            $sql .= 'escalas.dataplantao as start, ';
            $sql .= 'escalas.dataplantao as end, ';
        } else {
            $sql .= 'concat(escalas.dataplantao, \'T\', escalas.horainicialplantao) as start, ';
            $sql .= 'concat(escalas.dataplantao, \'T\', escalas.horafinalplantao) as end, ';
        }
        $sql .= 'escalas.id, ';
        $sql .= 'case ';
        $sql .= 'when passagenstrocas.tipopassagem=0 then \'Cessão\' ';
        $sql .= 'when passagenstrocas.tipopassagem=1 then \'Troca\' ';
        $sql .= 'end as tipoescala ';
        $sql .= 'from escalas ';
        $sql .= 'join passagenstrocas on (escalas.id = passagenstrocas.escala_id) ';
        $sql .= 'join profissionais on (passagenstrocas.profissionalsubstituto_id = profissionais.id) ';
        $sql .= 'join setores on (escalas.setor_id = setores.id) ';
        $sql .= 'where passagenstrocas.statuspassagem = 1 ';
        $sql .= 'and escalas.setor_id = ' . $setor . ' ';
        $sql .= 'and month(escalas.dataplantao) = ' . $mes . ' ';
        $sql .= 'order by start';

        $query = $this->db->query($sql);

        return $query->result();
    }

    public function get_passagens_trocas($where, $where_in = null, $order_by = null)
    {
        $fields = 'escalas.*, profissionais.id as profissional_id, ';
        $fields .= 'profissionais.registro as profissional_registro, ';
        $fields .= 'profissionais.nome as profissional_nome, ';
        $fields .= 'profissionais.nomecurto as profissional_nomecurto, ';
        $fields .= 'setores.id as setor_id, setores.nome as setor_nome, ';
        $fields .= 'unidadeshospitalares.id as unidadehospitalar_id, ';
        $fields .= 'unidadeshospitalares.razaosocial as unidadehospitalar_razaosocial, ';
        $fields .= 'unidadeshospitalares.nomefantasia as unidadehospitalar_nomefantasia, ';
        $fields .= 'passagenstrocas.id as passagenstrocas_id, ';
        $fields .= 'passagenstrocas.profissional_id as passagenstrocas_profissional_id, ';
        $fields .= 'profissional_passagem.registro as profissional_passagem_registro, ';
        $fields .= 'profissional_passagem.nome as profissional_passagem_nome, ';
        $fields .= 'profissional_passagem.nomecurto as profissional_passagem_nomecurto, ';
        $fields .= 'passagenstrocas.profissionalsubstituto_id as passagenstrocas_profissionalsubstituto_id, ';
        $fields .= 'profissional_substituto.registro as profissional_substituto_registro, ';
        $fields .= 'profissional_substituto.nome as profissional_substituto_nome, ';
        $fields .= 'profissional_substituto.nomecurto as profissional_substituto_nomecurto, ';
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
        $this->db->join('passagenstrocas', 'passagenstrocas.escala_id = escalas.id and passagenstrocas.statuspassagem = 1');
        $this->db->join('profissionais profissional_passagem', 'profissional_passagem.id = passagenstrocas.profissional_id');
        $this->db->join('profissionais profissional_substituto', 'profissional_substituto.id = passagenstrocas.profissionalsubstituto_id');
        $this->db->join('escalas escala_trocada', 'escala_trocada.id = passagenstrocas.escalatroca_id', 'left');
        $this->db->join('profissionais', 'profissionais.id = escalas.profissional_id');
        $this->db->join('setores', 'setores.id = escalas.setor_id');
        $this->db->join('unidadeshospitalares', 'unidadeshospitalares.id = setores.unidadehospitalar_id');
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

    public function get_passagens_trocas_pendentes($escala_id)
    {
        $this->db->select('*');
        $this->db->from('passagenstrocas');
        $this->db->where(array('escala_id' => $escala_id, 'statuspassagem' => 0));
        
        $query = $this->db->get();

        return $query->result();
    }

    public function get_plantoes_recebidos_a_confirmar($profissional_id)
    {
        $fields = 'escalas.*, ';
        $fields .= 'passagenstrocas.id as passagenstrocas_id, ';
        $fields .= 'profissional_passagem.registro as profissional_passagem_registro, ';
        $fields .= 'profissional_passagem.nome as profissional_passagem_nome, ';
        $fields .= 'setores.nome as setor_nome, ';
        $fields .= 'unidadeshospitalares.razaosocial as unidadehospitalar_razaosocial ';
        $this->db->select($fields);
        $this->db->from($this->table);
        $this->db->join(
            'passagenstrocas',
            'passagenstrocas.escala_id = escalas.id
            and passagenstrocas.statuspassagem = 0
            and passagenstrocas.tipopassagem = 0
            and passagenstrocas.profissionalsubstituto_id = ' . $profissional_id
        );
        $this->db->join('profissionais profissional_passagem', 'profissional_passagem.id = passagenstrocas.profissional_id');
        $this->db->join('setores', 'setores.id = escalas.setor_id');
        $this->db->join('unidadeshospitalares', 'unidadeshospitalares.id = setores.unidadehospitalar_id');
        $this->db->order_by('dataplantao, horainicialplantao');
        $query = $this->db->get();

        return $query->result();
    }

    public function get_plantoes_passados_a_confirmar($profissional_id)
    {
        $fields = 'escalas.*, ';
        $fields .= 'passagenstrocas.id as passagenstrocas_id, ';
        $fields .= 'profissional_passagem.registro as profissional_passagem_registro, ';
        $fields .= 'profissional_passagem.nome as profissional_passagem_nome, ';
        $fields .= 'setores.nome as setor_nome, ';
        $fields .= 'unidadeshospitalares.razaosocial as unidadehospitalar_razaosocial ';
        $this->db->select($fields);
        $this->db->from($this->table);
        $this->db->join(
            'passagenstrocas',
            'passagenstrocas.escala_id = escalas.id
            and passagenstrocas.statuspassagem = 0
            and passagenstrocas.tipopassagem = 0
            and passagenstrocas.profissional_id = ' . $profissional_id
        );
        $this->db->join('profissionais profissional_passagem', 'profissional_passagem.id = passagenstrocas.profissional_id');
        $this->db->join('setores', 'setores.id = escalas.setor_id');
        $this->db->join('unidadeshospitalares', 'unidadeshospitalares.id = setores.unidadehospitalar_id');
        $this->db->order_by('dataplantao, horainicialplantao');
        $query = $this->db->get();

        return $query->result();
    }

    public function get_trocas_propostas_a_confirmar($profissional_id)
    {
        $fields = 'escalas.*, ';
        $fields .= 'passagenstrocas.id as passagenstrocas_id, ';
        $fields .= 'profissional_substituto.registro as profissional_substituto_registro, ';
        $fields .= 'profissional_substituto.nome as profissional_substituto_nome, ';
        $fields .= 'setores.nome as setor_nome, ';
        $fields .= 'unidadeshospitalares.razaosocial as unidadehospitalar_razaosocial ';
        $this->db->select($fields);
        $this->db->from($this->table);
        $this->db->join(
            'passagenstrocas',
            'passagenstrocas.escala_id = escalas.id
            and passagenstrocas.statuspassagem in (0, 2)
            and passagenstrocas.tipopassagem = 1
            and passagenstrocas.profissional_id = ' . $profissional_id
        );
        $this->db->join('profissionais profissional_substituto', 'profissional_substituto.id = passagenstrocas.profissionalsubstituto_id');
        $this->db->join('setores', 'setores.id = escalas.setor_id');
        $this->db->join('unidadeshospitalares', 'unidadeshospitalares.id = setores.unidadehospitalar_id');
        $this->db->order_by('dataplantao, horainicialplantao');
        $query = $this->db->get();

        return $query->result();
    }

    public function get_trocas_recebidas_a_confirmar($profissional_id)
    {
        $fields = 'escalas.*, ';
        $fields .= 'passagenstrocas.id as passagenstrocas_id, ';
        $fields .= 'profissional_passagem.registro as profissional_passagem_registro, ';
        $fields .= 'profissional_passagem.nome as profissional_passagem_nome, ';
        $fields .= 'setores.nome as setor_nome, ';
        $fields .= 'unidadeshospitalares.razaosocial as unidadehospitalar_razaosocial ';
        $this->db->select($fields);
        $this->db->from($this->table);
        $this->db->join(
            'passagenstrocas',
            'passagenstrocas.escala_id = escalas.id
            and passagenstrocas.statuspassagem in (0, 2)
            and passagenstrocas.tipopassagem = 1
            and passagenstrocas.profissionalsubstituto_id = ' . $profissional_id
        );
        $this->db->join('profissionais profissional_passagem', 'profissional_passagem.id = passagenstrocas.profissional_id');
        $this->db->join('setores', 'setores.id = escalas.setor_id');
        $this->db->join('unidadeshospitalares', 'unidadeshospitalares.id = setores.unidadehospitalar_id');
        $this->db->order_by('dataplantao, horainicialplantao');
        $query = $this->db->get();

        return $query->result();
    }

    public function get_oportunidades($profissional_id)
    {
        $where_in = 'setores.id in ';
        $where_in .= '(select setor_id from profissionalsetor ';
        $where_in .= 'where profissional_id = ' . $profissional_id . ') ';

        $fields = 'escalas.*, ';
        $fields .= 'passagenstrocas.id as passagenstrocas_id, ';
        $fields .= 'profissional_passagem.registro as profissional_passagem_registro, ';
        $fields .= 'profissional_passagem.nome as profissional_passagem_nome, ';
        $fields .= 'setores.nome as setor_nome, ';
        $fields .= 'unidadeshospitalares.razaosocial as unidadehospitalar_razaosocial ';
        $this->db->select($fields);
        $this->db->from($this->table);
        $this->db->join(
            'passagenstrocas',
            'passagenstrocas.escala_id = escalas.id
            and passagenstrocas.statuspassagem = 0
            and passagenstrocas.profissionalsubstituto_id = 0'
        );
        $this->db->join(
            'profissionais profissional_passagem',
            'profissional_passagem.id = passagenstrocas.profissional_id'
        );
        $this->db->join('setores', 'setores.id = escalas.setor_id');
        $this->db->join('unidadeshospitalares', 'unidadeshospitalares.id = setores.unidadehospitalar_id');
        $this->db->where($where_in);
        $this->db->order_by('dataplantao, horainicialplantao');
        $query = $this->db->get();

        return $query->result();
    }

    public function get_escalas_consolidadas_por_profissional($profissional_id, $datainicial, $datafinal, $setor_id = null, $tipo_plantao = null)
    {
        $sql = 'select escalas.id, escalas.dataplantao, escalas.tipo_plantao, escalas.datafinalplantao, ';
        $sql .= 'escalas.horainicialplantao, escalas.horafinalplantao, ';
        $sql .= 'escalas.duracao, escalas.profissional_id, escalas.setor_id, ';
        $sql .= 'profissionais.registro as profissional_registro, ';
        $sql .= 'profissionais.nome as profissional_nome, ';
        $sql .= 'setores.nome as setor_nome, ';
        $sql .= 'unidadeshospitalares.razaosocial as unidadehospitalar_razaosocial, ';
        $sql .= '\'Original\' as tipoescala ';
        $sql .= 'from escalas ';
        $sql .= 'join profissionais on (escalas.profissional_id = profissionais.id) ';
        $sql .= 'join setores on (escalas.setor_id = setores.id) ';
        $sql .= 'join unidadeshospitalares on (setores.unidadehospitalar_id = unidadeshospitalares.id) ';
        $sql .= 'where escalas.publicada = 1 and profissionais.id = ? ';
        $sql .= 'and escalas.id not in ';
        $sql .= '(select escala_id ';
        $sql .= 'from passagenstrocas ';
        $sql .= 'where escala_id = escalas.id) ';
        $sql .= 'and escalas.dataplantao between \'' . $datainicial  . '\' and \'' . $datafinal . '\' ';
        if ($tipo_plantao != null && $tipo_plantao != 2){
            $sql .= 'and escalas.tipo_plantao =  '. $tipo_plantao . ' ';
        }
        if ($setor_id != null) {
            $sql .= 'and escalas.setor_id = ' . $setor_id . ' ';
        }
        $sql .= 'union ';
        $sql .= 'select escalas.id, escalas.dataplantao, escalas.tipo_plantao, escalas.datafinalplantao, ';
        $sql .= 'escalas.horainicialplantao, escalas.horafinalplantao, ';
        $sql .= 'escalas.duracao, escalas.profissional_id, escalas.setor_id, ';
        $sql .= 'profissionais.registro as profissional_registro, ';
        $sql .= 'profissionais.nome as profissional_nome, ';
        $sql .= 'setores.nome as setor_nome, ';
        $sql .= 'unidadeshospitalares.razaosocial as unidadehospitalar_razaosocial, ';
        $sql .= 'case ';
        $sql .= 'when passagenstrocas.tipopassagem=0 then \'Cessão\' ';
        $sql .= 'when passagenstrocas.tipopassagem=1 then \'Troca\' ';
        $sql .= 'end as tipoescala ';
        $sql .= 'from escalas ';
        $sql .= 'join passagenstrocas on (escalas.id = passagenstrocas.escala_id) ';
        $sql .= 'join profissionais on (passagenstrocas.profissionalsubstituto_id = profissionais.id) ';
        $sql .= 'join setores on (escalas.setor_id = setores.id) ';
        $sql .= 'join unidadeshospitalares on (setores.unidadehospitalar_id = unidadeshospitalares.id) ';
        $sql .= 'where profissionais.id = ? ';
        $sql .= 'and escalas.dataplantao between \'' . $datainicial  . '\' and \'' . $datafinal . '\' ';
        $sql .= 'and passagenstrocas.statuspassagem = 1 ';
        if ($tipo_plantao != 2){
            $sql .= 'and escalas.tipo_plantao =  '. $tipo_plantao .' ';
        }
        if ($setor_id != null) {
            $sql .= 'and escalas.setor_id = ' . $setor_id . ' ';
        }
        $sql .= 'order by dataplantao, horainicialplantao';

        $query = $this->db->query($sql, array($profissional_id, $profissional_id));

        return $query->result();
    }

    public function get_escalas_consolidadas_por_profissional_mes_setor($mes, $setor_id, $profissional_id)
    {
        $sql = 'select escalas.id, escalas.dataplantao, escalas.datafinalplantao, ';
        $sql .= 'escalas.horainicialplantao, escalas.horafinalplantao, ';
        $sql .= 'escalas.duracao, escalas.profissional_id, escalas.setor_id, ';
        $sql .= 'profissionais.registro as profissional_registro, ';
        $sql .= 'profissionais.nome as profissional_nome, ';
        $sql .= 'setores.nome as setor_nome, ';
        $sql .= 'unidadeshospitalares.razaosocial as unidadehospitalar_razaosocial, ';
        $sql .= '\'Original\' as tipoescala ';
        $sql .= 'from escalas ';
        $sql .= 'join profissionais on (escalas.profissional_id = profissionais.id) ';
        $sql .= 'join setores on (escalas.setor_id = setores.id) ';
        $sql .= 'join unidadeshospitalares on (setores.unidadehospitalar_id = unidadeshospitalares.id) ';
        $sql .= 'where profissionais.id = ' . $profissional_id . ' ';
        $sql .= 'and escalas.id not in ';
        $sql .= '(select escala_id ';
        $sql .= 'from passagenstrocas ';
        $sql .= 'where escala_id = escalas.id) ';
        $sql .= 'and month(escalas.dataplantao) = ' . $mes  . ' ';
        if ($setor_id != null) {
            $sql .= 'and escalas.setor_id = ' . $setor_id . ' ';
        }
        $sql .= 'union ';
        $sql .= 'select escalas.id, escalas.dataplantao, escalas.datafinalplantao, ';
        $sql .= 'escalas.horainicialplantao, escalas.horafinalplantao, ';
        $sql .= 'escalas.duracao, escalas.profissional_id, escalas.setor_id, ';
        $sql .= 'profissionais.registro as profissional_registro, ';
        $sql .= 'profissionais.nome as profissional_nome, ';
        $sql .= 'setores.nome as setor_nome, ';
        $sql .= 'unidadeshospitalares.razaosocial as unidadehospitalar_razaosocial, ';
        $sql .= 'case ';
        $sql .= 'when passagenstrocas.tipopassagem=0 then \'Cessão\' ';
        $sql .= 'when passagenstrocas.tipopassagem=1 then \'Troca\' ';
        $sql .= 'end as tipoescala ';
        $sql .= 'from escalas ';
        $sql .= 'join passagenstrocas on (escalas.id = passagenstrocas.escala_id) ';
        $sql .= 'join profissionais on (passagenstrocas.profissionalsubstituto_id = profissionais.id) ';
        $sql .= 'join setores on (escalas.setor_id = setores.id) ';
        $sql .= 'join unidadeshospitalares on (setores.unidadehospitalar_id = unidadeshospitalares.id) ';
        $sql .= 'where profissionais.id = ' . $profissional_id . ' ';
        $sql .= 'and month(escalas.dataplantao) = ' . $mes  . ' ';
        $sql .= 'and passagenstrocas.statuspassagem = 1 ';
        if ($setor_id != null) {
            $sql .= 'and escalas.setor_id = ' . $setor_id . ' ';
        }
        $sql .= 'order by dataplantao, horainicialplantao';

        $query = $this->db->query($sql);

        return $query->result();
    }

    public function get_escalas_consolidadas_por_setor_e_usuario($user_id = null, $datainicial, $datafinal, $setor_id)
    {
        $sql = 'select escalas.id, escalas.dataplantao, escalas.datafinalplantao, ';
        $sql .= 'escalas.horainicialplantao, escalas.horafinalplantao, ';
        $sql .= 'escalas.duracao, escalas.profissional_id, escalas.setor_id, ';
        $sql .= 'profissionais.registro as profissional_registro, ';
        $sql .= 'profissionais.nome as profissional_nome, ';
        $sql .= 'setores.nome as setor_nome, ';
        $sql .= 'unidadeshospitalares.razaosocial as unidadehospitalar_razaosocial, ';
        $sql .= '\'Original\' as tipoescala ';
        $sql .= 'from escalas ';
        $sql .= 'join profissionais on (escalas.profissional_id = profissionais.id) ';
        $sql .= 'join setores on (escalas.setor_id = setores.id) ';
        $sql .= 'join usuariosetor on (usuariosetor.setor_id = setores.id) ';
        $sql .= 'join users on (usuariosetor.user_id = users.id) ';
        $sql .= 'join unidadeshospitalares on (setores.unidadehospitalar_id = unidadeshospitalares.id) ';
        $sql .= 'where escalas.id not in ';
        $sql .= '(select escala_id ';
        $sql .= 'from passagenstrocas ';
        $sql .= 'where escala_id = escalas.id) ';
        $sql .= 'and escalas.dataplantao between \'' . $datainicial  . '\' and \'' . $datafinal . '\' ';
        if ($user_id != null) {
            $sql .= 'and users.id = ' . $user_id . ' ';
        }
        if ($setor_id != null) {
            $sql .= 'and escalas.setor_id = ' . $setor_id . ' ';
        }
        $sql .= 'union ';
        $sql .= 'select escalas.id, escalas.dataplantao, escalas.datafinalplantao, ';
        $sql .= 'escalas.horainicialplantao, escalas.horafinalplantao, ';
        $sql .= 'escalas.duracao, escalas.profissional_id, escalas.setor_id, ';
        $sql .= 'profissionais.registro as profissional_registro, ';
        $sql .= 'profissionais.nome as profissional_nome, ';
        $sql .= 'setores.nome as setor_nome, ';
        $sql .= 'unidadeshospitalares.razaosocial as unidadehospitalar_razaosocial, ';
        $sql .= 'case ';
        $sql .= 'when passagenstrocas.tipopassagem=0 then \'Cessão\' ';
        $sql .= 'when passagenstrocas.tipopassagem=1 then \'Troca\' ';
        $sql .= 'end as tipoescala ';
        $sql .= 'from escalas ';
        $sql .= 'join passagenstrocas on (escalas.id = passagenstrocas.escala_id) ';
        $sql .= 'join profissionais on (passagenstrocas.profissionalsubstituto_id = profissionais.id) ';
        $sql .= 'join setores on (escalas.setor_id = setores.id) ';
        $sql .= 'join usuariosetor on (usuariosetor.setor_id = setores.id) ';
        $sql .= 'join users on (usuariosetor.user_id = users.id) ';
        $sql .= 'join unidadeshospitalares on (setores.unidadehospitalar_id = unidadeshospitalares.id) ';
        $sql .= 'where profissionais.id = escalas.dataplantao between \'' . $datainicial  . '\' and \'' . $datafinal . '\' ';
        $sql .= 'and passagenstrocas.statuspassagem = 1 ';
        if ($user_id != null) {
            $sql .= 'and users.id = ' . $user_id . ' ';
        }
        if ($setor_id != null) {
            $sql .= 'and escalas.setor_id = ' . $setor_id . ' ';
        }
        $sql .= 'order by dataplantao, horainicialplantao';

        $query = $this->db->query($sql);

        return $query->result();
    }

    public function get_plantoes_recebidos_por_profissional($profissional_id, $datainicial, $datafinal, $setor_id = null)
    {
        $sql = 'select escalas.*, ';
        $sql .= 'passagenstrocas.id as passagenstrocas_id, ';
        $sql .= 'passagenstrocas.statuspassagem as passagenstrocas_statuspassagem, ';
        $sql .= 'passagenstrocas.tipopassagem as passagenstrocas_tipopassagem, ';
        $sql .= 'profissional_passagem.registro as profissional_passagem_registro, ';
        $sql .= 'profissional_passagem.nome as profissional_passagem_nome, ';
        $sql .= 'setores.nome as setor_nome, ';
        $sql .= 'unidadeshospitalares.razaosocial as unidadehospitalar_razaosocial ';
        $sql .= 'from escalas ';
        $sql .= 'join passagenstrocas on (passagenstrocas.escala_id = escalas.id ';
        $sql .= 'and passagenstrocas.profissionalsubstituto_id = ' . $profissional_id . ') ';
        $sql .= 'join profissionais profissional_passagem on (profissional_passagem.id = passagenstrocas.profissional_id) ';
        $sql .= 'join setores on (setores.id = escalas.setor_id) ';
        $sql .= 'join unidadeshospitalares on (unidadeshospitalares.id = setores.unidadehospitalar_id) ';
        $sql .= 'where passagenstrocas.statuspassagem in (0, 1, 2, 3) '; // Definir quais status devem aparecer
        $sql .= 'and escalas.dataplantao between \'' . $datainicial . '\' and \'' . $datafinal . '\' ';
        if ($setor_id != null) {
            $sql .= 'and escalas.setor_id = ' . $setor_id . ' ';
        }
        $sql .= 'order by dataplantao, horainicialplantao, statuspassagem';

        $query = $this->db->query($sql);

        return $query->result();
    }

    public function get_frequencia_por_profissional($profissional_id, $datainicial, $datafinal)
    {
        $sql = 'select s.nm_set as nome_setor, f.dt_frq as data_frequencia, f.tp_frq as tipo_frequencia ';
        $sql .= 'from tb_ctl_frq f ';
        $sql .= 'join profissionais p on (f.cd_pes_fis = p.cd_pes_fis) ';
        $sql .= 'join tb_set s on (f.cd_set = s.cd_set) ';
        $sql .= 'where p.id = ' . $profissional_id . ' ';
        $sql .= 'and date(f.dt_frq) between \'' . $datainicial . '\' and \'' . $datafinal . '\' ';
        $sql .= 'order by f.dt_frq ';

        $query = $this->db->query($sql);

        return $query->result();
    }

    public function get_plantoes_recebidos_por_setor($datainicial, $datafinal, $setor_id = null)
    {
        $sql = 'select escalas.*, ';
        $sql .= 'passagenstrocas.id as passagenstrocas_id, ';
        $sql .= 'passagenstrocas.statuspassagem as passagenstrocas_statuspassagem, ';
        $sql .= 'passagenstrocas.tipopassagem as passagenstrocas_tipopassagem, ';
        $sql .= 'profissional_passagem.registro as profissional_passagem_registro, ';
        $sql .= 'profissional_passagem.nome as profissional_passagem_nome, ';
        $sql .= 'profissional_substituto.registro as profissional_substituto_registro, ';
        $sql .= 'profissional_substituto.nome as profissional_substituto_nome, ';
        $sql .= 'setores.nome as setor_nome, ';
        $sql .= 'unidadeshospitalares.razaosocial as unidadehospitalar_razaosocial ';
        $sql .= 'from escalas ';
        $sql .= 'join passagenstrocas on (passagenstrocas.escala_id = escalas.id) ';
        $sql .= 'join profissionais profissional_passagem on (profissional_passagem.id = passagenstrocas.profissional_id) ';
        $sql .= 'join profissionais profissional_substituto on (profissional_substituto.id = passagenstrocas.profissionalsubstituto_id) ';
        $sql .= 'join setores on (setores.id = escalas.setor_id) ';
        $sql .= 'join unidadeshospitalares on (unidadeshospitalares.id = setores.unidadehospitalar_id) ';
        $sql .= 'where passagenstrocas.statuspassagem in (0, 1, 2, 3) ';
        $sql .= 'and escalas.dataplantao between \'' . $datainicial . '\' and \'' . $datafinal . '\' ';
        if ($setor_id != null) {
            $sql .= 'and escalas.setor_id = ' . $setor_id . ' ';
        }
        $sql .= 'order by dataplantao, horainicialplantao, statuspassagem';

        $query = $this->db->query($sql);

        return $query->result();
    }

    public function get_passagens_de_plantao_por_profissional($profissional_id, $datainicial, $datafinal, $setor_id = null)
    {
        $sql = 'select escalas.*, ';
        $sql .= 'passagenstrocas.id as passagenstrocas_id, ';
        $sql .= 'passagenstrocas.statuspassagem as passagenstrocas_statuspassagem, ';
        $sql .= 'passagenstrocas.tipopassagem as passagenstrocas_tipopassagem, ';
        $sql .= 'profissional_substituto.registro as profissional_substituto_registro, ';
        $sql .= 'profissional_substituto.nome as profissional_substituto_nome, ';
        $sql .= 'setores.nome as setor_nome, ';
        $sql .= 'unidadeshospitalares.razaosocial as unidadehospitalar_razaosocial ';
        $sql .= 'from escalas ';
        $sql .= 'join passagenstrocas on (passagenstrocas.escala_id = escalas.id ';
        $sql .= 'and passagenstrocas.profissional_id = ' . $profissional_id . ') ';
        $sql .= 'join profissionais profissional_substituto on (profissional_substituto.id = passagenstrocas.profissionalsubstituto_id) ';
        $sql .= 'join setores on (setores.id = escalas.setor_id) ';
        $sql .= 'join unidadeshospitalares on (unidadeshospitalares.id = setores.unidadehospitalar_id) ';
        $sql .= 'where passagenstrocas.statuspassagem in (0, 1, 2, 3) '; // Definir quais status devem aparecer
        $sql .= 'and escalas.dataplantao between \'' . $datainicial . '\' and \'' . $datafinal . '\' ';
        if ($setor_id != null) {
            $sql .= 'and escalas.setor_id = ' . $setor_id . ' ';
        }
        $sql .= 'order by dataplantao, horainicialplantao, statuspassagem';

        $query = $this->db->query($sql);

        return $query->result();
    }

    public function get_passagens_de_plantao_por_setor_e_usuario($user_id = null, $datainicial, $datafinal, $setor_id = null)
    {
        $sql = 'select escalas.*, ';
        $sql .= 'passagenstrocas.id as passagenstrocas_id, ';
        $sql .= 'passagenstrocas.statuspassagem as passagenstrocas_statuspassagem, ';
        $sql .= 'passagenstrocas.tipopassagem as passagenstrocas_tipopassagem, ';
        $sql .= 'profissional_substituto.registro as profissional_substituto_registro, ';
        $sql .= 'profissional_substituto.nome as profissional_substituto_nome, ';
        $sql .= 'profissional_titular.registro as profissional_titular_registro, ';
        $sql .= 'profissional_titular.nome as profissional_titular_nome, ';
        $sql .= 'setores.nome as setor_nome, ';
        $sql .= 'unidadeshospitalares.razaosocial as unidadehospitalar_razaosocial ';
        $sql .= 'from escalas ';
        $sql .= 'join passagenstrocas on (passagenstrocas.escala_id = escalas.id) ';
        $sql .= 'join profissionais profissional_substituto on (profissional_substituto.id = passagenstrocas.profissionalsubstituto_id) ';
        $sql .= 'join profissionais profissional_titular on (profissional_titular.id = passagenstrocas.profissional_id) ';
        $sql .= 'join setores on (setores.id = escalas.setor_id) ';
        $sql .= 'join usuariosetor on (usuariosetor.setor_id = setores.id) ';
        $sql .= 'join users on (usuariosetor.user_id = users.id) ';
        $sql .= 'join unidadeshospitalares on (unidadeshospitalares.id = setores.unidadehospitalar_id) ';
        $sql .= 'where passagenstrocas.statuspassagem in (0, 1, 2, 3) ';
        $sql .= 'and escalas.dataplantao between \'' . $datainicial . '\' and \'' . $datafinal . '\' ';
        if ($setor_id != null) {
            $sql .= 'and escalas.setor_id = ' . $setor_id . ' ';
        }
        if ($user_id != null) {
            $sql .= 'and users.id = ' . $user_id . ' ';
        }
        $sql .= 'order by dataplantao, horainicialplantao, statuspassagem';

        $query = $this->db->query($sql);

        return $query->result();
    }

    public function get_escala_troca($escala_id)
    {
        $fields = 'escalas.*, ';
        $fields .= 'passagenstrocas.id as passagenstrocas_id, ';
        $fields .= 'passagenstrocas.profissionalsubstituto_id as passagenstrocas_profissionalsubstituto_id, ';
        $fields .= 'passagenstrocas.tipopassagem as passagenstrocas_tipopassagem, ';
        $fields .= 'profissional_passagem.registro as profissional_passagem_registro, ';
        $fields .= 'profissional_passagem.nome as profissional_passagem_nome, ';
        $fields .= 'profissional_passagem.email as profissional_passagem_email, ';
        $fields .= 'profissional_substituto.registro as profissional_substituto_registro, ';
        $fields .= 'profissional_substituto.nome as profissional_substituto_nome, ';
        $fields .= 'profissional_substituto.email as profissional_substituto_email, ';
        $fields .= 'setores.nome as setor_nome, ';
        $fields .= 'unidadeshospitalares.razaosocial as unidadehospitalar_razaosocial ';
        $this->db->select($fields);
        $this->db->from($this->table);
        $this->db->join(
            'passagenstrocas',
            'passagenstrocas.escala_id = escalas.id
            and passagenstrocas.statuspassagem = 0
            and passagenstrocas.tipopassagem = 1'
        );
        $this->db->join('profissionais profissional_passagem', 'profissional_passagem.id = passagenstrocas.profissional_id');
        $this->db->join('profissionais profissional_substituto', 'profissional_substituto.id = passagenstrocas.profissionalsubstituto_id');
        $this->db->join('setores', 'setores.id = escalas.setor_id');
        $this->db->join('unidadeshospitalares', 'unidadeshospitalares.id = setores.unidadehospitalar_id');
        $this->db->where('escalas.id', $escala_id);
        $this->db->order_by('dataplantao, horainicialplantao');
        $query = $this->db->get();

        return $query->row();
    }

    public function get_escala_troca_a_confirmar($escala_id)
    {
        $fields = 'escalas.*, ';
        $fields .= 'passagenstrocas.id as passagenstrocas_id, ';
        $fields .= 'passagenstrocas.profissional_id as passagenstrocas_profissional_id, ';
        $fields .= 'passagenstrocas.profissionalsubstituto_id as passagenstrocas_profissionalsubstituto_id, ';
        $fields .= 'passagenstrocas.tipopassagem as passagenstrocas_tipopassagem, ';
        $fields .= 'escala_troca.id as escala_troca_id, ';
        $fields .= 'escala_troca.dataplantao as escala_troca_dataplantao, ';
        $fields .= 'escala_troca.horainicialplantao as escala_troca_horainicialplantao, ';
        $fields .= 'escala_troca.horafinalplantao as escala_troca_horafinalplantao, ';
        $fields .= 'profissional_passagem.registro as profissional_passagem_registro, ';
        $fields .= 'profissional_passagem.nome as profissional_passagem_nome, ';
        $fields .= 'profissional_passagem.email as profissional_passagem_email, ';
        $fields .= 'profissional_substituto.registro as profissional_substituto_registro, ';
        $fields .= 'profissional_substituto.nome as profissional_substituto_nome, ';
        $fields .= 'profissional_substituto.email as profissional_substituto_email, ';
        $fields .= 'setores.nome as setor_nome, ';
        $fields .= 'unidadeshospitalares.razaosocial as unidadehospitalar_razaosocial ';
        $this->db->select($fields);
        $this->db->from($this->table);
        $this->db->join(
            'passagenstrocas',
            'passagenstrocas.escala_id = escalas.id
            and passagenstrocas.statuspassagem = 2
            and passagenstrocas.tipopassagem = 1'
        );
        // Buscar a escalatroca_id
        $this->db->join('profissionais profissional_passagem', 'profissional_passagem.id = passagenstrocas.profissional_id');
        $this->db->join('profissionais profissional_substituto', 'profissional_substituto.id = passagenstrocas.profissionalsubstituto_id');
        $this->db->join('setores', 'setores.id = escalas.setor_id');
        $this->db->join('unidadeshospitalares', 'unidadeshospitalares.id = setores.unidadehospitalar_id');
        $this->db->join('escalas escala_troca', 'escala_troca.id = passagenstrocas.escalatroca_id');
        $this->db->where('escalas.id', $escala_id);
        $this->db->order_by('dataplantao, horainicialplantao');
        $query = $this->db->get();

        return $query->row();
    }


    public function get_escala_passada_a_confirmar($escala_id)
    {
        $fields = 'escalas.*, ';
        $fields .= 'passagenstrocas.id as passagenstrocas_id, ';
        $fields .= 'passagenstrocas.profissionalsubstituto_id as passagenstrocas_profissionalsubstituto_id, ';
        $fields .= 'passagenstrocas.tipopassagem as passagenstrocas_tipopassagem, passagenstrocas.escalatroca_id, ';
        $fields .= 'profissional_passagem.registro as profissional_passagem_registro, ';
        $fields .= 'profissional_passagem.nome as profissional_passagem_nome, ';
        $fields .= 'profissional_passagem.email as profissional_passagem_email, ';
        $fields .= 'profissional_substituto.registro as profissional_substituto_registro, ';
        $fields .= 'profissional_substituto.nome as profissional_substituto_nome, ';
        $fields .= 'profissional_substituto.email as profissional_substituto_email, ';
        $fields .= 'setores.nome as setor_nome, ';
        $fields .= 'unidadeshospitalares.razaosocial as unidadehospitalar_razaosocial ';
        $this->db->select($fields);
        $this->db->from($this->table);
        $this->db->join(
            'passagenstrocas',
            'passagenstrocas.escala_id = escalas.id
            and passagenstrocas.statuspassagem = 0'
        );// and passagenstrocas.tipopassagem = 0'
        $this->db->join('profissionais profissional_passagem', 'profissional_passagem.id = passagenstrocas.profissional_id');
        $this->db->join('profissionais profissional_substituto', 'profissional_substituto.id = passagenstrocas.profissionalsubstituto_id');
        $this->db->join('setores', 'setores.id = escalas.setor_id');
        $this->db->join('unidadeshospitalares', 'unidadeshospitalares.id = setores.unidadehospitalar_id');
        $this->db->where('escalas.id', $escala_id);
        $this->db->order_by('passagenstrocas.datahorapassagem');
        $this->db->limit('1');
        $query = $this->db->get();

        //var_dump($this->db->get_compiled_select());exit;

        return $query->row();
    }

    public function get_escala($where)
    {
        return $this->db->get_where($this->table, $where)->row();
    }

    public function get_escala_by_id($escala_id)
    {
        $fields = 'escalas.*, ';
        $fields .= 'profissional_passagem.registro as profissional_passagem_registro, ';
        $fields .= 'profissional_passagem.nome as profissional_passagem_nome, ';
        $fields .= 'profissional_passagem.email as profissional_passagem_email, ';
        $fields .= 'profissional_substituto.registro as profissional_substituto_registro, ';
        $fields .= 'profissional_substituto.nome as profissional_substituto_nome, ';
        $fields .= 'profissional_substituto.email as profissional_substituto_email, ';
        $fields .= 'passagenstrocas.id as passagenstrocas_id, ';
        $fields .= 'passagenstrocas.profissionalsubstituto_id as passagenstrocas_profissionalsubstituto_id, ';
        $fields .= 'passagenstrocas.tipopassagem as passagenstrocas_tipopassagem, ';
        $fields .= 'setores.id as setor_id, ';
        $fields .= 'setores.nome as setor_nome, ';
        $fields .= 'unidadeshospitalares.id as unidadehospitalar_id, ';
        $fields .= 'unidadeshospitalares.razaosocial as unidadehospitalar_razaosocial ';
        $this->db->select($fields);
        $this->db->from($this->table);
        $this->db->join(
            'passagenstrocas',
            'passagenstrocas.escala_id = escalas.id and passagenstrocas.statuspassagem = 1',
            'left'
        );
        $this->db->join('profissionais profissional_passagem', 'profissional_passagem.id = escalas.profissional_id');
        $this->db->join('profissionais profissional_substituto', 'profissional_substituto.id = passagenstrocas.profissionalsubstituto_id', 'left');
        $this->db->join('setores', 'setores.id = escalas.setor_id');
        $this->db->join('unidadeshospitalares', 'unidadeshospitalares.id = setores.unidadehospitalar_id');
        $this->db->where('escalas.id', $escala_id);
        $this->db->order_by('passagenstrocas.datahorapassagem', 'desc');
        $this->db->limit('1');
        $query = $this->db->get();

        return $query->row();
    }

    public function get_escala_consolidada_por_id($escala_id)
    {
        $sql = "
        select id, idsetor, nomesetor, idunidade, nomeunidade,
        dataplantao, datafinalplantao, horainicialplantao, horafinalplantao,
        id_profissional, cd_pes_fis_profissional, crm_profissional, nome_profissional
        from vw_escalas_consolidadas ec
        where ec.id = $escala_id ";

        $query = $this->db->query($sql);

        return $query->row();
    }


    public function get_trocas_passagens_por_escala($escala_id)
    {
        $sql = "
        select pt.id, pt.escala_id,
        p_titular.id as profissional_titular_id, p_titular.registro as profissional_titular_registro,
        p_titular.nome as profissional_titular_nome, p_titular.nomecurto as profissional_titular_nomecurto,
        p_substituto.id as profissional_substituto_id, p_substituto.registro as profissional_substituto_registro,
        p_substituto.nome as profissional_substituto_nome, p_substituto.nomecurto as profissional_substituto_nomecurto
        from passagenstrocas pt
        join profissionais p_titular on (pt.profissional_id = p_titular.id)
        join profissionais p_substituto on (pt.profissionalsubstituto_id = p_substituto.id)
        where pt.escala_id = $escala_id
        order by pt.datahorapassagem";

        $query = $this->db->query($sql);

        return $query->result();
    }

    public function get_trocas_passagens_por_setor_periodo($setor_id, $datainicial, $datafinal)
    {
        $sql = "
        select pt.id, pt.escala_id,
        p_titular.id as profissional_titular_id, p_titular.registro as profissional_titular_registro,
        p_titular.nome as profissional_titular_nome, p_titular.nomecurto as profissional_titular_nomecurto,
        p_substituto.id as profissional_substituto_id, p_substituto.registro as profissional_substituto_registro,
        p_substituto.nome as profissional_substituto_nome, p_substituto.nomecurto as profissional_substituto_nomecurto
        from passagenstrocas pt
        join escalas e on (pt.escala_id = e.id)
        join profissionais p_titular on (pt.profissional_id = p_titular.id)
        join profissionais p_substituto on (pt.profissionalsubstituto_id = p_substituto.id)
        where e.dataplantao between '$datainicial' and '$datafinal' ";
        if ($setor_id != null) {
            $sql .= "and e.setor_id = $setor_id ";
        }
        $sql .= "and pt.statuspassagem in (1,3) ";
        $sql .= "order by e.id, pt.datahorapassagem";

        $query = $this->db->query($sql);

        return $query->result();
    }
}