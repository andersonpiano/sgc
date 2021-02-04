<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Faltas extends Admin_Controller {

    private $_permitted_groups = array('admin', 'sac');
    private $_admin_groups = array('admin', 'sac');

    const TIPO_FALTA_JUSTIFICADA = 1;
    const TIPO_FALTA_NAO_JUSTIFICADA = 2;

    public function __construct()
    {
        parent::__construct($this->_permitted_groups);

        /* Load :: Common */
        $this->load->model('cemerge/falta_model');
        $this->load->model('cemerge/profissional_model');
        $this->load->model('cemerge/unidadehospitalar_model');
        $this->load->model('cemerge/setor_model');
        $this->lang->load('admin/faltas');

        /* Title Page */
        $this->page_title->push(lang('menu_faltas'));
        $this->data['pagetitle'] = $this->page_title->show();

        /* Breadcrumbs :: Common */
        $this->breadcrumbs->unshift(1, lang('menu_faltas'), 'admin/faltas');
    }

    public function index()
    {
        if (!$this->ion_auth->logged_in()) {
            $this->session->set_flashdata('message', 'Você deve estar autenticado para utilizar esta função.');
            redirect('auth/login', 'refresh');
        }
        if (!$this->ion_auth->in_group($this->_permitted_groups)) {
            $this->session->set_flashdata('message', 'O acesso &agrave; este recurso não é permitido ao seu perfil de usuário.');
            redirect('auth/login', 'refresh');
        }

        /* Breadcrumbs */
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        $profissionais_unidade = array(
            '' => 'Selecione um profissional',
        );

        /* Validate form input */
        $this->form_validation->set_rules('unidadehospitalar_id', 'lang:faltas_unidadehospitalar', 'required');
        $this->form_validation->set_rules('profissional_id', 'lang:faltas_profissional', 'required');
        $this->form_validation->set_rules('data_inicial', 'lang:faltas_data_inicial', 'required');
        $this->form_validation->set_rules('data_final', 'lang:faltas_data_final', 'required');

        if ($this->form_validation->run() == true) {
            $unidadehospitalar_id = $this->input->post('unidadehospitalar_id');
            $profissional_id = $this->input->post('profissional_id');
            $data_inicial = $this->input->post('data_inicial');
            $data_final = $this->input->post('data_final');
            
            /* buscando faltas */
            $this->data['faltas'] = $this->falta_model->get_faltas_por_profissional_e_periodo($profissional_id, $data_inicial, $data_final);

            /* Profissionais */
            $profissionais_unidade = $this->_get_profissionais_por_unidade_hospitalar($unidadehospitalar_id);
        } else {
            $this->data['faltas'] = array();
            $profissional_id = 0;
            $data_inicial = date('Y-m-d');
            $data_final = date('Y-m-d');
            $this->session->set_flashdata('message', validation_errors());
        }

        $unidadeshospitalares = $this->_get_unidadeshospitalares();

        $this->data['unidadehospitalar_id'] = array(
            'name'  => 'unidadehospitalar_id',
            'id'    => 'unidadehospitalar_id',
            'type'  => 'select',
            'class' => 'form-control',
            'selected' => $this->form_validation->set_value('unidadehospitalar_id'),
            'options' => $unidadeshospitalares,
        );
        $this->data['profissional_id'] = array(
            'name'  => 'profissional_id',
            'id'    => 'profissional_id',
            'type'  => 'select',
            'class' => 'form-control',
            'selected' => $this->form_validation->set_value('profissional_id', $profissional_id),
            'options' => $profissionais_unidade,
        );
        $this->data['data_inicial'] = array(
            'name'  => 'data_inicial',
            'id'    => 'data_inicial',
            'type'  => 'date',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('data_inicial', $data_inicial),
        );
        $this->data['data_final'] = array(
            'name'  => 'data_final',
            'id'    => 'data_final',
            'type'  => 'date',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('data_final', $data_final),
        );

        /* Load Template */
        $this->template->admin_render('admin/faltas/index', $this->data);
    }

    public function view($id)
    {
        if (!$this->ion_auth->logged_in()) {
            $this->session->set_flashdata('message', 'Você deve estar autenticado para utilizar esta função.');
            redirect('auth/login', 'refresh');
        }
        if (!$this->ion_auth->in_group($this->_permitted_groups)) {
            $this->session->set_flashdata('message', 'O acesso &agrave; este recurso não é permitido ao seu perfil de usuário.');
            redirect('auth/login', 'refresh');
        }

        /* Breadcrumbs */
        $this->breadcrumbs->unshift(2, lang('menu_faltas'), 'admin/faltas/view');
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        /* Data */
        $id = (int) $id;

        /* Load additional models */
        $this->load->model('cemerge/escala_model');

        $this->data['falta'] = $this->falta_model->get_by_id($id);
        $this->data['falta']->escala = $this->escala_model->get_by_id($this->data['falta']->escala_id);
        $this->data['falta']->setor = $this->setor_model->get_by_id($this->data['falta']->escala->setor_id);
        $this->data['falta']->profissional = $this->profissional_model->get_by_id($this->data['falta']->profissional_id);

        // Turno
        $hora_inicial_plantao = (int)$this->data['falta']->escala->horainicialplantao;
        $this->data['falta']->turno_plantao = '';
        if ($hora_inicial_plantao >= 5 and $hora_inicial_plantao < 13) {
            $this->data['falta']->turno_plantao = 'Manhã';
        } else if ($hora_inicial_plantao >= 13 and $hora_inicial_plantao < 19) {
            $this->data['falta']->turno_plantao = 'Tarde';
        } else if ($hora_inicial_plantao >= 19 and $hora_inicial_plantao < 23) {
            $this->data['falta']->turno_plantao = 'Noite';
        }

        // Tipo de falta
        if ($this->data['falta']->tipo_falta == $this::TIPO_FALTA_JUSTIFICADA) {
            $this->data['falta']->tipo_falta = 'Justificada';
        } else if ($this->data['falta']->tipo_falta == $this::TIPO_FALTA_NAO_JUSTIFICADA) {
            $this->data['falta']->tipo_falta = 'Não Justificada';
        }

        /* Load Template */
        $this->template->admin_render('admin/faltas/view', $this->data);
    }

    public function _get_unidadeshospitalares()
    {
        $unidades = $this->unidadehospitalar_model->get_all();

        $unidadeshospitalares = array(
            '' => 'Selecione uma unidade hospitalar',
        );
        foreach ($unidades as $unidade) {
            $unidadeshospitalares[$unidade->id] = $unidade->razaosocial;
        }

        return $unidadeshospitalares;
    }

    public function _get_profissionais_por_unidade_hospitalar($unidadehospitalar_id)
    {
        $profissionais_unidade = $this->profissional_model->get_profissionais_por_unidade_hospitalar($unidadehospitalar_id);

        $profissionais = array(
            '' => 'Selecione um profissional',
        );
        foreach ($profissionais_unidade as $profissional) {
            $profissionais[$profissional->id] = $profissional->nome;
        }

        return $profissionais;
    }
}
