<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Escala_model extends MY_Model
{
    protected $table = 'escalas';

    public function __construct()
    {
        parent::__construct($this->table);
    }

    public function get_escalas_originais($where, $where_in_column = null, $where_in_values = null, $order_by = null)
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
        if (!is_null($where_in_column) and !empty($where_in_values)) {
            $this->db->where_in($where_in_column, $where_in_values);
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
        $sql = 'select profissionais.nome as title, ';
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
        $sql .= 'select profissionais.nome as title, ';
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
        $sql = 'select profissionais.nome as title, ';
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
        $sql = 'select profissionais.nome as title, ';
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
        $sql .= 'where escalas.id not in ';
        $sql .= '(select escala_id ';
        $sql .= 'from passagenstrocas ';
        $sql .= 'where escala_id = escalas.id ';
        $sql .= 'and passagenstrocas.statuspassagem = 1) ';
        $sql .= 'and escalas.setor_id = ' . $setor . ' ';
        $sql .= 'and month(escalas.dataplantao) = ' . $mes . ' ';
        $sql .= 'union ';
        $sql .= 'select profissionais.nome as title, ';
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

    public function get_trocas_a_confirmar($profissional_id)
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
            and passagenstrocas.statuspassagem = 2
            and passagenstrocas.tipopassagem = 1
            and passagenstrocas.profissional_id = ' . $profissional_id
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

    public function get_escalas_consolidadas_por_profissional($profissional_id, $setor_id = null)
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
        $sql .= 'where profissionais.id = ? ';
        $sql .= 'and escalas.id not in ';
        $sql .= '(select escala_id ';
        $sql .= 'from passagenstrocas ';
        $sql .= 'where escala_id = escalas.id) ';
        if ($setor_id) {
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
        $sql .= 'where profissionais.id = ? ';
        $sql .= 'and passagenstrocas.statuspassagem = 1 ';
        if ($setor_id) {
            $sql .= 'and escalas.setor_id = ' . $setor_id . ' ';
        }
        $sql .= 'order by dataplantao, horainicialplantao';

        $query = $this->db->query($sql, array($profissional_id, $profissional_id));

        return $query->result();
    }

    public function get_plantoes_recebidos_por_profissional($profissional_id)
    {
        $fields = 'escalas.*, ';
        $fields .= 'passagenstrocas.id as passagenstrocas_id, ';
        $fields .= 'passagenstrocas.statuspassagem as passagenstrocas_statuspassagem, ';
        $fields .= 'passagenstrocas.tipopassagem as passagenstrocas_tipopassagem, ';
        $fields .= 'profissional_passagem.registro as profissional_passagem_registro, ';
        $fields .= 'profissional_passagem.nome as profissional_passagem_nome, ';
        $fields .= 'setores.nome as setor_nome, ';
        $fields .= 'unidadeshospitalares.razaosocial as unidadehospitalar_razaosocial ';
        $this->db->select($fields);
        $this->db->from($this->table);
        $this->db->join(
            'passagenstrocas',
            'passagenstrocas.escala_id = escalas.id
            and passagenstrocas.profissionalsubstituto_id = ' . $profissional_id
        );
        $this->db->join('profissionais profissional_passagem', 'profissional_passagem.id = passagenstrocas.profissional_id');
        $this->db->join('setores', 'setores.id = escalas.setor_id');
        $this->db->join('unidadeshospitalares', 'unidadeshospitalares.id = setores.unidadehospitalar_id');
        $this->db->order_by('dataplantao, horainicialplantao');
        $query = $this->db->get();

        return $query->result();
    }

    public function get_passagens_de_plantao_por_profissional($profissional_id)
    {
        $fields = 'escalas.*, ';
        $fields .= 'passagenstrocas.id as passagenstrocas_id, ';
        $fields .= 'passagenstrocas.statuspassagem as passagenstrocas_statuspassagem, ';
        $fields .= 'passagenstrocas.tipopassagem as passagenstrocas_tipopassagem, ';
        $fields .= 'profissional_substituto.registro as profissional_substituto_registro, ';
        $fields .= 'profissional_substituto.nome as profissional_substituto_nome, ';
        $fields .= 'setores.nome as setor_nome, ';
        $fields .= 'unidadeshospitalares.razaosocial as unidadehospitalar_razaosocial ';
        $this->db->select($fields);
        $this->db->from($this->table);
        $this->db->join(
            'passagenstrocas',
            'passagenstrocas.escala_id = escalas.id
            and passagenstrocas.profissional_id = ' . $profissional_id
        );
        $this->db->join('profissionais profissional_substituto', 'profissional_substituto.id = passagenstrocas.profissionalsubstituto_id', 'left');
        $this->db->join('setores', 'setores.id = escalas.setor_id');
        $this->db->join('unidadeshospitalares', 'unidadeshospitalares.id = setores.unidadehospitalar_id');
        $this->db->order_by('dataplantao, horainicialplantao');
        $query = $this->db->get();

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
        $fields .= 'profissional_substituto.registro as profissional_substituto_registro, ';
        $fields .= 'profissional_substituto.nome as profissional_substituto_nome, ';
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
        $fields .= 'passagenstrocas.profissionalsubstituto_id as passagenstrocas_profissionalsubstituto_id, ';
        $fields .= 'passagenstrocas.tipopassagem as passagenstrocas_tipopassagem, ';
        $fields .= 'escala_troca.id as escala_troca_id, ';
        $fields .= 'escala_troca.dataplantao as escala_troca_dataplantao, ';
        $fields .= 'escala_troca.horainicialplantao as escala_troca_horainicialplantao, ';
        $fields .= 'escala_troca.horafinalplantao as escala_troca_horafinalplantao, ';
        $fields .= 'profissional_passagem.registro as profissional_passagem_registro, ';
        $fields .= 'profissional_passagem.nome as profissional_passagem_nome, ';
        $fields .= 'profissional_substituto.registro as profissional_substituto_registro, ';
        $fields .= 'profissional_substituto.nome as profissional_substituto_nome, ';
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
            and passagenstrocas.tipopassagem = 0'
        );
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
        $fields .= 'setores.nome as setor_nome, ';
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
        $this->db->order_by('passagenstrocas.datahorapassagem');
        $this->db->limit('1');
        $query = $this->db->get();

        return $query->row();
    }
}