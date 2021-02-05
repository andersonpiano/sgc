<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Frequencias extends Admin_Controller
{
    private $_permitted_groups = array('admin', 'profissionais', 'coordenadorplantao', 'sac');
    private $_admin_groups = array('admin', 'sac', 'sad');

    private $_diasdasemana = array('Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado');

    public function __construct()
    {
        parent::__construct($this->_permitted_groups);

        /* Load :: Common */
        $this->load->model('cemerge/frequencia_model');
        $this->load->model('cemerge/frequenciaassessus_model');
        $this->lang->load('admin/frequencias');

        /* Title Page */
        $this->page_title->push(lang('menu_frequencias'));
        $this->data['pagetitle'] = $this->page_title->show();

        /* Breadcrumbs :: Common */
        $this->breadcrumbs->unshift(1, lang('menu_frequencias'), 'admin/frequencias');
    }

    public function buscarfrequencias()
    {
        if (!$this->ion_auth->logged_in()) {
            $this->session->set_flashdata('message', 'Você deve estar autenticado para usar esta função.');
            redirect('auth/login', 'refresh');
        }

        if (!$this->ion_auth->in_group($this->_permitted_groups)) {
            $this->session->set_flashdata('message', 'O acesso &agrave; este recurso não é permitido ao seu perfil de usuário.');
            redirect('admin/dashboard', 'refresh');
        }

        /* Breadcrumbs */
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        /* Reset */
        $this->data['frequencias'] = array();

        /* Variables */
        $this->data['diasdasemana'] = $this->_diasdasemana;

        /* Validate form input */
        $this->form_validation->set_rules('unidadehospitalar_id', 'lang:escalas_unidadehospitalar', 'required');
        $this->form_validation->set_rules('datainicial', 'lang:escalas_datainicialplantao', 'required');
        $this->form_validation->set_rules('datafinal', 'lang:escalas_datafinalplantao', 'required');

        if ($this->form_validation->run() == true) {
            $unidadehospitalar_id = $this->input->post('unidadehospitalar_id');
            $datainicial = $this->input->post('datainicial');
            $datafinal = $this->input->post('datafinal');
            $setor_id = $this->input->post('setor_id');

            $setores = $this->_get_setores_assessus($unidadehospitalar_id);

            $this->load->model('cemerge/escala_model');
            $frequencias = $this->escala_model->get_frequencias_escalas($unidadehospitalar_id, $setor_id, $datainicial, $datafinal);
            $this->load->helper('group_by');
            $this->data['frequencias'] = group_by('nome_profissional_frq', $frequencias);
        } else {
            $datainicial = date('Y-m-01');
            $datafinal = date('Y-m-t');
            $setores = array('0' => 'Selecione um setor');
        }

        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

        $unidadeshospitalares = $this->_get_unidadeshospitalares_assessus();

        $this->data['datainicial'] = array(
            'name'  => 'datainicial',
            'id'    => 'datainicial',
            'type'  => 'date',
            'class' => 'form-control',
            'value' => $datainicial,
        );
        $this->data['datafinal'] = array(
            'name'  => 'datafinal',
            'id'    => 'datafinal',
            'type'  => 'date',
            'class' => 'form-control',
            'value' => $datafinal,
        );
        $this->data['unidadehospitalar_id'] = array(
            'name'  => 'unidadehospitalar_id',
            'id'    => 'unidadehospitalar_id',
            'type'  => 'select',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('unidadehospitalar_id'),
            'options' => $unidadeshospitalares,
        );
        $this->data['setor_id'] = array(
            'name'  => 'setor_id',
            'id'    => 'setor_id',
            'type'  => 'select',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('setor_id'),
            'options' => $setores,
        );

        /* Load Template */
        $this->template->admin_render('admin/frequencias/listafrequencias', $this->data);
    }

    public function buscarfrequenciaporprofissional()
    {
        if (!$this->ion_auth->logged_in()) {
            $this->session->set_flashdata('message', 'Você deve estar autenticado para usar esta função.');
            redirect('auth/login', 'refresh');
        }

        if (!$this->ion_auth->in_group($this->_permitted_groups)) {
            $this->session->set_flashdata('message', 'O acesso &agrave; este recurso não é permitido ao seu perfil de usuário.');
            redirect('admin/dashboard', 'refresh');
        }

        /* Breadcrumbs */
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        /* Reset */
        $this->data['frequencias'] = array();

        /* Variables */
        $this->data['diasdasemana'] = $this->_diasdasemana;

        /* Validate form input */
        $this->form_validation->set_rules('unidadehospitalar_id', 'lang:frequencias_unidadehospitalar', 'required');
        $this->form_validation->set_rules('profissional_id', 'lang:frequencias_profissional', 'required');        
        $this->form_validation->set_rules('datainicial', 'lang:frequencias_datainicialplantao', 'required');
        $this->form_validation->set_rules('datafinal', 'lang:frequencias_datafinalplantao', 'required');

        if ($this->form_validation->run() == true) {
            $unidadehospitalar_id = $this->input->post('unidadehospitalar_id');
            $profissional_id = $this->input->post('profissional_id');
            $datainicial = $this->input->post('datainicial');
            $datafinal = $this->input->post('datafinal');

            $this->load->model('cemerge/escala_model');
            $frequencias = $this->escala_model->get_frequencias_por_profissional($unidadehospitalar_id, $profissional_id, $datainicial, $datafinal);
            $this->load->helper('group_by');
            $this->data['frequencias'] = group_by('nome_profissional_frq', $frequencias);
        } else {
            $datainicial = date('Y-m-01');
            $datafinal = date('Y-m-t');
            $unidadehospitalar_id = '';
            $profissional_id = '';
            $this->session->set_flashdata('message', validation_errors());
        }

        $unidadeshospitalares = $this->_get_unidadeshospitalares_assessus();
        $profissionais = $this->_get_profissionais_por_unidade_hospitalar($unidadehospitalar_id);

        $this->data['datainicial'] = array(
            'name'  => 'datainicial',
            'id'    => 'datainicial',
            'type'  => 'date',
            'class' => 'form-control',
            'value' => $datainicial,
        );
        $this->data['datafinal'] = array(
            'name'  => 'datafinal',
            'id'    => 'datafinal',
            'type'  => 'date',
            'class' => 'form-control',
            'value' => $datafinal,
        );
        $this->data['unidadehospitalar_id'] = array(
            'name'  => 'unidadehospitalar_id',
            'id'    => 'unidadehospitalar_id',
            'type'  => 'select',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('unidadehospitalar_id'),
            'options' => $unidadeshospitalares,
        );
        $this->data['profissional_id'] = array(
            'name'  => 'profissional_id',
            'id'    => 'profissional_id',
            'type'  => 'select',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('profissional_id'),
            'options' => $profissionais,
        );

        /* Load Template */
        $this->template->admin_render('admin/frequencias/listafrequenciaporprofissional', $this->data);
    }

    public function buscarfrequenciasemescala()
    {
        if (!$this->ion_auth->logged_in() or !$this->ion_auth->in_group($this->_permitted_groups)) {
            redirect('auth/login', 'refresh');
        } else {
            /* Breadcrumbs */
            $this->data['breadcrumb'] = $this->breadcrumbs->show();

            /* Reset */
            $this->data['frequencias'] = array();

            /* Variables */
            $this->data['diasdasemana'] = $this->_diasdasemana;

            /* Validate form input */
            $this->form_validation->set_rules('unidadehospitalar_id', 'lang:escalas_unidadehospitalar', 'required');
            $this->form_validation->set_rules('setor_id', 'lang:escalas_setor', 'required');
            $this->form_validation->set_rules('datainicial', 'lang:escalas_datainicialplantao', 'required');
            $this->form_validation->set_rules('datafinal', 'lang:escalas_datafinalplantao', 'required');

            if ($this->form_validation->run() == true) {
                $unidadehospitalar_id = $this->input->post('unidadehospitalar_id');
                $setor_id = $this->input->post('setor_id');
                $datainicial = $this->input->post('datainicial');
                $datafinal = $this->input->post('datafinal');

                $setores = $this->_get_setores($unidadehospitalar_id);

                $this->load->model('cemerge/escala_model');
                $this->data['frequencias'] = $this->escala_model->get_frequencia_sem_escala($unidadehospitalar_id, $setor_id, $datainicial, $datafinal);
            } else {
                $datainicial = date('Y-m-01');
                $datafinal = date('Y-m-t');
                $setores = array('' => 'Selecione um setor');
            }

            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

            $unidadeshospitalares = $this->_get_unidadeshospitalares_assessus();

            $this->data['datainicial'] = array(
                'name'  => 'datainicial',
                'id'    => 'datainicial',
                'type'  => 'date',
                'class' => 'form-control',
                'value' => $datainicial,
            );
            $this->data['datafinal'] = array(
                'name'  => 'datafinal',
                'id'    => 'datafinal',
                'type'  => 'date',
                'class' => 'form-control',
                'value' => $datafinal,
            );
            $this->data['unidadehospitalar_id'] = array(
                'name'  => 'unidadehospitalar_id',
                'id'    => 'unidadehospitalar_id',
                'type'  => 'select',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('unidadehospitalar_id'),
                'options' => $unidadeshospitalares,
            );
            $this->data['setor_id'] = array(
                'name'  => 'setor_id',
                'id'    => 'setor_id',
                'type'  => 'select',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('setor_id'),
                'options' => $setores,
            );

            /* Load Template */
            $this->template->admin_render('admin/frequencias/listafrequenciasemescala', $this->data);
        }
    }

    public function editarfrequencia($frequencia_id)
    {
        if (!$this->ion_auth->logged_in()) {
            $this->session->set_flashdata('message', 'Você deve estar autenticado para usar esta função.');
            redirect('auth/login', 'refresh');
        }

        if (!$this->ion_auth->in_group($this->_permitted_groups)) {
            $this->session->set_flashdata('message', 'O acesso &agrave; este recurso não é permitido ao seu perfil de usuário.');
            redirect('admin/dashboard', 'refresh');
        }

        /* Breadcrumbs */
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        /* Buscando frequência */
        $frequencia_id = (int)$frequencia_id;
        $frequencia = $this->frequenciaassessus_model->get_by_cdctlfrq($frequencia_id);
        $this->data['frequencia'] = $frequencia;

        /* Buscando dados do profissional */
        $this->load->model('cemerge/profissional_model');
        $this->data['frequencia']->profissional = $this->profissional_model->get_by_cd_pes_fis($frequencia->CD_PES_FIS);

        /* Validate form input */
        $this->form_validation->set_rules('setor_id', 'lang:frequencias_setor', 'required');
        $this->form_validation->set_rules('tipo_batida', 'lang:frequencias_tipobatida', 'required');

        if (isset($_POST) && !empty($_POST)) {
            if ($this->form_validation->run() == true) {
                $unidadehospitalar_id = $this->input->post('unidadehospitalar_id');
                $setor_id = $this->input->post('setor_id');
                $tipo_batida = $this->input->post('tipo_batida');

                $data_update = array(
                    'CD_PES_JUR' => $unidadehospitalar_id,
                    'CD_SET' => $setor_id,
                    'TP_FRQ' => $tipo_batida,
                );

                $this->load->model('cemerge/frequenciaassessus_model');
                $sucesso = $this->frequenciaassessus_model->update($frequencia_id, $data_update);
                if ($sucesso) {
                    $this->session->set_flashdata('message', 'A frequência foi atualizada com sucesso.');
                } else {
                    $this->session->set_flashdata('message', 'Houve um erro ao atualizar a frequência.');
                }

                redirect('admin/frequencias/buscarfrequencias', 'refresh');
            }
        } else {
            $setores = $this->_get_setores_assessus($frequencia->CD_PES_JUR);
        }

        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

        $unidadeshospitalares = $this->_get_unidadeshospitalares_assessus();

        $this->data['unidadehospitalar_id'] = array(
            'name'  => 'unidadehospitalar_id',
            'id'    => 'unidadehospitalar_id',
            'type'  => 'select',
            'class' => 'form-control',
            'selected' => $this->form_validation->set_value('unidadehospitalar_id', $frequencia->CD_PES_JUR),
            'options' => $unidadeshospitalares,
        );
        $this->data['setor_id'] = array(
            'name'  => 'setor_id',
            'id'    => 'setor_id',
            'type'  => 'select',
            'class' => 'form-control',
            'selected' => $this->form_validation->set_value('setor_id', $frequencia->CD_SET),
            'options' => $setores,
        );
        $this->data['tipo_batida'] = array(
            'name'  => 'tipo_batida',
            'id'    => 'tipo_batida',
            'type'  => 'select',
            'class' => 'form-control',
            'selected' => $this->form_validation->set_value('tipo_batida', $frequencia->TP_FRQ),
            'options' => $this->_get_tipos_batidas(),
        );

        /* Load Template */
        $this->template->admin_render('admin/frequencias/edit', $this->data);
    }

    public function _get_tipos_batidas()
    {
        $tipos_batidas = array(
            '1' => 'Entrada',
            '2' => 'Saída',
        );

        return $tipos_batidas;
    }

    public function _get_unidadeshospitalares()
    {
        $this->load->model('cemerge/unidadehospitalar_model');
        $unidades = $this->unidadehospitalar_model->get_all();

        $unidadeshospitalares = array(
            '' => 'Selecione uma unidade hospitalar',
        );
        foreach ($unidades as $unidade) {
            $unidadeshospitalares[$unidade->id] = $unidade->razaosocial;
        }

        return $unidadeshospitalares;
    }

    public function _get_unidadeshospitalares_assessus()
    {
        $this->load->model('cemerge/unidadehospitalar_model');
        $unidades = $this->unidadehospitalar_model->get_unidadeshospitalares_assessus();

        $unidadeshospitalares = array(
            '' => 'Selecione uma unidade hospitalar',
        );
        foreach ($unidades as $unidade) {
            $unidadeshospitalares[$unidade->cd_pes_jur] = $unidade->nm_pes_jur;
        }

        return $unidadeshospitalares;
    }

    public function _get_profissionais_por_unidade_hospitalar($unidadehospitalar_id)
    {
        $this->load->model('cemerge/profissional_model');
        $profissionais_por_unidade_hospitalar = $this->profissional_model->get_profissionais_por_unidade_hospitalar($unidadehospitalar_id);

        $profissionais = array(
            '' => 'Selecione um profissional',
        );
        foreach ($profissionais_por_unidade_hospitalar as $profissional) {
            $profissionais[$profissional->id] = $profissional->nome;
        }

        return $profissionais;
    }

    public function _get_setores_assessus($cd_pes_jur)
    {
        $this->load->model('cemerge/setor_model');
        $setores = $this->setor_model->get_setores_assessus_por_cd_pes_jur($cd_pes_jur);

        $setores_assessus = array(
            '' => 'Selecione um setor',
        );
        foreach ($setores as $setor) {
            $setores_assessus[$setor->cd_set] = $setor->nm_set;
        }

        return $setores_assessus;
    }

    public function _get_csrf_nonce()
    {
        $this->load->helper('string');
        $key = random_string('alnum', 8);
        $value = random_string('alnum', 20);
        $this->session->set_flashdata('csrfkey', $key);
        $this->session->set_flashdata('csrfvalue', $value);

        return array($key => $value);
    }

    public function _valid_csrf_nonce()
    {
        if ($this->input->post($this->session->flashdata('csrfkey')) !== false
            && $this->input->post($this->session->flashdata('csrfkey')) == $this->session->flashdata('csrfvalue')
        ) {
            return true;
        } else {
            return false;
        }
    }
}