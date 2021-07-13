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
            $profissional_id = $this->input->post('profissional_id');
            $datafinal = $this->input->post('datafinal');
            $setor_id = $this->input->post('setor_id');

            $profissionais = $this->_get_profissionais_por_unidade_hospitalar($unidadehospitalar_id);
            $setores = $this->_get_setores($unidadehospitalar_id);

            $this->load->model('cemerge/escala_model');
            if ($setor_id <> '' && $profissional_id == ''){
               $frequencias = $this->escala_model->get_frequencias_escalas_nova($unidadehospitalar_id, $setor_id, $datainicial, $datafinal, null); 
            } else if ($setor_id == '' && $profissional_id <> ''){
                $frequencias = $this->escala_model->get_frequencias_escalas_nova($unidadehospitalar_id, null, $datainicial, $datafinal, $profissional_id); 
            } else if ($setor_id == '' && $profissional_id == ''){
                $frequencias = $this->escala_model->get_frequencias_escalas_nova($unidadehospitalar_id, null, $datainicial, $datafinal, null); 
            }else {
                $frequencias = $this->escala_model->get_frequencias_escalas_nova($unidadehospitalar_id, $setor_id, $datainicial, $datafinal, $profissional_id); 
            }
            $this->load->helper('group_by');
            $this->data['frequencias'] = group_by('nome_profissional_frq', $frequencias);
            
            $this->load->helper('group_by');
            $this->data['frequencias'] = group_by('nome_profissional_frq', $frequencias);
        } else {
            $datainicial = date('Y-m-01');
            $datafinal = date('Y-m-t');
            //$setores = array('0' => 'Selecione um setor');
            $profissionais = $this->_get_profissionais_por_unidade_hospitalar(1);
            $setores = $this->_get_setores(1);
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
        $this->data['profissional_id'] = array(
            'name'  => 'profissional_id',
            'id'    => 'profissional_id',
            'type'  => 'select',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('profissional_id'),
            'options' => $profissionais,
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
            if($datainicial >= '2021-06-21'){
                $frequencias = $this->escala_model->get_frequencias_por_profissional_nova($unidadehospitalar_id, $profissional_id, $datainicial, $datafinal);
            } else {
                $frequencias = $this->escala_model->get_frequencias_por_profissional($unidadehospitalar_id, $profissional_id, $datainicial, $datafinal);    
            }
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

    public function deletar_frequencia(){
        $sucess = false;

        $frequencia = $this->input->post('frequencia');

        if (!$this->input->is_ajax_request()) {
            exit("Nenhum acesso de script direto permitido!");
        } else {
            if ($this->frequencia_model->delete(['id' => $frequencia])){
                $sucess = true;
            }
        }
        echo json_encode(['sucess' => $sucess]); exit;

    }

    public function buscarfrequenciamedico()
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

        $this->load->model('cemerge/usuarioprofissional_model');
        $this->load->model('cemerge/profissional_model');
        $userId = $this->ion_auth->user()->row()->id;

        /* Validate form input */        
        $this->form_validation->set_rules('datainicial', 'lang:frequencias_datainicialplantao', 'required');
        $this->form_validation->set_rules('datafinal', 'lang:frequencias_datafinalplantao', 'required');

        if ($this->form_validation->run() == true) {
            $unidadehospitalar_id = 1;
            $profissional_id = $this->usuarioprofissional_model->get_where(['user_id' => $userId]);
            if ($profissional_id) {
                $this->_profissional = $this->profissional_model->get_where(['id' => $profissional_id[0]->profissional_id])[0];
            }
            $datainicial = $this->input->post('datainicial');
            $datafinal = $this->input->post('datafinal');

            $this->load->model('cemerge/escala_model');
            $frequencias = $this->escala_model->get_frequencias_por_profissional($unidadehospitalar_id, $this->_profissional->id, $datainicial, $datafinal);
            $this->load->helper('group_by');
            $this->data['frequencias'] = group_by('nome_profissional_frq', $frequencias);
        } else {
            $datainicial = date('Y-m-01');
            $datafinal = date('Y-m-t');
            $unidadehospitalar_id = 1;
            $profissional_id = '';
            $this->session->set_flashdata('message', validation_errors());
        }

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

        /* Load Template */
        $this->template->admin_render('admin/frequencias/listafrequenciamedico', $this->data);
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
                    'tipo_batida' =>$tipo_batida
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
            $setores = $this->get_setores_assessus_por_cd_pes_jur($frequencia->CD_PES_JUR);
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

    public function editarfrequencia_nova($frequencia_id)
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
        
        $frequencia = $this->frequencia_model->get_by_id($frequencia_id);
        $this->data['frequencia'] = $frequencia;

        /* Buscando dados do profissional */
        $this->load->model('cemerge/profissional_model');
        $this->load->model('cemerge/setor_model');
        $this->data['frequencia']->profissional = $this->profissional_model->get_by_id($frequencia->profissional_id);

        /* Validate form input */
        $this->form_validation->set_rules('setor_id', 'lang:frequencias_setor', 'required');
        $this->form_validation->set_rules('tipo_batida', 'lang:frequencias_tipobatida', 'required');

        if (isset($_POST) && !empty($_POST)) {
            if ($this->form_validation->run() == true) {
                $unidadehospitalar_id = $this->input->post('unidadehospitalar_id');
                $setor_id = $this->input->post('setor_id');
                $tipo_batida = $this->input->post('tipo_batida');
                $setor_nome = $this->setor_model->get_by_id($setor_id)->nome;
                //var_dump($setor_nome); exit;


                $data_update = array(
                    'unidadehospitalar_id' => $unidadehospitalar_id,
                    'setor_id' => $setor_id,
                    'tipobatida' => $tipo_batida,
                    'setor_nome_temp' => $setor_nome
                );

                $this->load->model('cemerge/frequenciaassessus_model');
                $sucesso = $this->frequencia_model->update($frequencia_id, $data_update);
                if ($sucesso) {
                    $this->session->set_flashdata('message', 'A frequência foi atualizada com sucesso.');
                } else {
                    $this->session->set_flashdata('message', 'Houve um erro ao atualizar a frequência.');
                }

                redirect('admin/frequencias/buscarfrequencias', 'refresh');
            }
        } else {
            $setores = $this->_get_setores($frequencia->unidadehospitalar_id);
        }

        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

        $unidadeshospitalares = $this->_get_unidadeshospitalares_assessus();

        $this->data['unidadehospitalar_id'] = array(
            'name'  => 'unidadehospitalar_id',
            'id'    => 'unidadehospitalar_id',
            'type'  => 'select',
            'class' => 'form-control',
            'selected' => $this->form_validation->set_value('unidadehospitalar_id', $frequencia->unidadehospitalar_id),
            'options' => $unidadeshospitalares,
        );
        $this->data['setor_id'] = array(
            'name'  => 'setor_id',
            'id'    => 'setor_id',
            'type'  => 'select',
            'class' => 'form-control',
            'selected' => $this->form_validation->set_value('setor_id', $frequencia->setor_id),
            'options' => $setores,
        );
        $this->data['tipo_batida'] = array(
            'name'  => 'tipo_batida',
            'id'    => 'tipo_batida',
            'type'  => 'select',
            'class' => 'form-control',
            'selected' => $this->form_validation->set_value('tipo_batida', $frequencia->tipobatida),
            'options' => $this->_get_tipos_batidas(),
        );

        /* Load Template */
        $this->template->admin_render('admin/frequencias/edit_sgc', $this->data);
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

    public function get_setores_assessus_por_cd_pes_jur($cd_pes_jur)
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

    public function _get_setores($cd_pes_jur)
    {
        $this->load->model('cemerge/setor_model');
        $setores = $this->setor_model->get_setores_por_unidade($cd_pes_jur);

        $setores_assessus = array(
            '' => 'Selecione um setor',
        );
        foreach ($setores as $setor) {
            $setores_assessus[$setor->id] = $setor->nome;
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