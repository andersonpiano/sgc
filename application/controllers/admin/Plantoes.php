<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Plantoes extends Admin_Controller {
    private $_permitted_groups = array('admin', 'profissionais');

    private $_profissional = null;

    public function __construct()
    {
        parent::__construct($this->_permitted_groups);

        /* Load :: Common */
        $this->load->model('cemerge/escala_model');
        $this->load->model('cemerge/profissional_model');
        $this->load->model('cemerge/usuarioprofissional_model');
        $this->load->model('cemerge/unidadehospitalar_model');
        $this->load->model('cemerge/setor_model');
        $this->lang->load('admin/plantoes');

        /* Profissional */
        $userId = $this->ion_auth->user()->row()->id;
        if ($this->ion_auth->in_group('profissionais')) {
            $usuarioProfissional = $this->usuarioprofissional_model->get_where(['user_id' => $userId])[0];
            if ($usuarioProfissional) {
                $this->_profissional = $this->profissional_model->get_where(['id' => $usuarioProfissional->profissional_id])[0];
            }
        }

        /* Title Page */
        $this->page_title->push(lang('menu_plantoes'));
        $this->data['pagetitle'] = $this->page_title->show();

        /* Breadcrumbs :: Common */
        $this->breadcrumbs->unshift(1, lang('menu_plantoes'), 'admin/plantoes');
    }


    public function index()
    {
        if (!$this->ion_auth->logged_in() or !$this->ion_auth->in_group($this->_permitted_groups)) {
            redirect('auth/login', 'refresh');
        } else {
            /* Breadcrumbs */
            $this->data['breadcrumb'] = $this->breadcrumbs->show();

            // Tipos e status de passagem para exibição
            $this->data['tipospassagem'] = $this->_get_tipos_passagem();
            $this->data['statuspassagem'] = $this->_get_status_passagem();

            /* Get all data */
            if ($this->ion_auth->is_admin()) {
                $this->data['plantoes'] = array();
                $this->data['passagens'] = array();
                $this->data['recebidos'] = array();
            } else {
                $this->data['plantoes'] = $this->escala_model->get_escalas(['profissional_id' => $this->_profissional->id]);
                $this->data['passagens'] = $this->escala_model->get_escalas(['profissional_id' => $this->_profissional->id, 'profissionalsubstituto_id !=' => 0]);
                $this->data['recebidos'] = $this->escala_model->get_escalas(['profissionalsubstituto_id' => $this->_profissional->id]);
            }

            /* Load Template */
            $this->template->admin_render('admin/plantoes/index', $this->data);
        }
    }

    public function tooffer($id)
    {
        $id = (int) $id;

        if (!$this->ion_auth->logged_in() or !$this->ion_auth->in_group($this->_permitted_groups)) {
            redirect('auth', 'refresh');
        }

        /* Breadcrumbs */
        $this->breadcrumbs->unshift(2, lang('menu_plantoes_tooffer'), 'admin/plantoes/tooffer');
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        /* Load Data */
        $plantao = $this->escala_model->get_by_id($id);
        $plantao->setor = $this->setor_model->get_by_id($plantao->setor_id);
        $plantao->setor->unidadehospitalar = $this->unidadehospitalar_model->get_by_id($plantao->setor->unidadehospitalar_id);
        $plantao->profissional = $this->profissional_model->get_by_id($plantao->profissional_id);
        $tipospassagem = $this->_get_tipos_passagem();

        $profissionais = $this->profissional_model->get_profissionais_por_setor($plantao->setor_id);
        $profissionais_setor = $this->_get_profissionais_setor($profissionais);

        /* Validate form input */
        $this->form_validation->set_rules('tipopassagem', 'lang:plantoes_tipopassagem', 'required');
        $this->form_validation->set_rules('profissionalsubstituto_id', 'lang:plantoes_profissional_substituto', 'required');

        if (isset($_POST) && ! empty($_POST)) {
            if ($this->_valid_csrf_nonce() === false or $id != $this->input->post('id')) {
                show_error($this->lang->line('error_csrf'));
            }

            if ($this->form_validation->run() == true) {
                $data = array(
                    'tipopassagem' => $this->input->post('tipopassagem'),
                    'profissionalsubstituto_id' => $this->input->post('profissionalsubstituto_id'),
                    'datahorapassagem' => date('Y-m-d H:i:s'),
                    'statuspassagem' => 0,
                );

                if ($this->escala_model->update($plantao->id, $data)) {
                    $this->session->set_flashdata('message', $this->ion_auth->messages());

                    if ($this->ion_auth->in_group($this->_permitted_groups)) {
                        redirect('admin/plantoes', 'refresh');
                    } else {
                        redirect('admin', 'refresh');
                    }
                } else {
                    $this->session->set_flashdata('message', $this->ion_auth->errors());

                    if ($this->ion_auth->in_group($this->_permitted_groups)) {
                        redirect('auth', 'refresh');
                    } else {
                        redirect('/', 'refresh');
                    }
                }
            }
        }

        // display the edit user form
        $this->data['csrf'] = $this->_get_csrf_nonce();

        // set the flash data error message if there is one
        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

        // pass the plantao to the view
        $this->data['plantao'] = $plantao;

        $this->data['dataplantao'] = array(
            'name'  => 'dataplantao',
            'id'    => 'dataplantao',
            'type'  => 'date',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('dataplantao', $plantao->dataplantao)
        );
        $this->data['horainicialplantao'] = array(
            'name'  => 'horainicialplantao',
            'id'    => 'horainicialplantao',
            'type'  => 'time',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('horainicialplantao', $plantao->horainicialplantao)
        );
        $this->data['horafinalplantao'] = array(
            'name'  => 'horafinalplantao',
            'id'    => 'horafinalplantao',
            'type'  => 'time',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('horafinalplantao', $plantao->horafinalplantao)
        );
        $this->data['tipopassagem'] = array(
            'name'  => 'tipopassagem',
            'id'    => 'tipopassagem',
            'type'  => 'select',
            'class' => 'form-control',
            'options' => $tipospassagem
        );
        $this->data['profissionalsubstituto_id'] = array(
            'name'  => 'profissionalsubstituto_id',
            'id'    => 'profissionalsubstituto_id',
            'type'  => 'select',
            'class' => 'form-control',
            'options' => $profissionais_setor
        );

        /* Load Template */
        $this->template->admin_render('admin/plantoes/tooffer', $this->data);
    }

    public function confirm($id)
    {
        $id = (int) $id;

        if (!$this->ion_auth->logged_in() or !$this->ion_auth->in_group($this->_permitted_groups)) {
            redirect('auth', 'refresh');
        }

        /* Breadcrumbs */
        $this->breadcrumbs->unshift(2, lang('menu_plantoes_confirm'), 'admin/plantoes/confirm');
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        /* Load Data */
        $plantao = $this->escala_model->get_by_id($id);
        $plantao->setor = $this->setor_model->get_by_id($plantao->setor_id);
        $plantao->setor->unidadehospitalar = $this->unidadehospitalar_model->get_by_id($plantao->setor->unidadehospitalar_id);
        $plantao->profissional = $this->profissional_model->get_by_id($plantao->profissional_id);
        $plantao->profissionalsubstituto = $this->profissional_model->get_by_id($plantao->profissionalsubstituto_id);
        $this->data['tipospassagem'] = $this->_get_tipos_passagem();
        $this->data['statuspassagem'] = $this->_get_status_passagem();

        if (isset($_POST) && ! empty($_POST)) {
            if ($this->_valid_csrf_nonce() === false or $id != $this->input->post('id')) {
                show_error($this->lang->line('error_csrf'));
            }

            $data = array(
                'datahoraconfirmacao' => date('Y-m-d H:i:s'),
                'statuspassagem' => 1,
            );

            if ($this->escala_model->update($plantao->id, $data)) {
                $this->session->set_flashdata('message', $this->ion_auth->messages());

                if ($this->ion_auth->in_group($this->_permitted_groups)) {
                    redirect('admin/plantoes', 'refresh');
                } else {
                    redirect('admin', 'refresh');
                }
            } else {
                $this->session->set_flashdata('message', $this->ion_auth->errors());

                if ($this->ion_auth->in_group($this->_permitted_groups)) {
                    redirect('auth', 'refresh');
                } else {
                    redirect('/', 'refresh');
                }
            }
        }

        // display the edit user form
        $this->data['csrf'] = $this->_get_csrf_nonce();

        // set the flash data error message if there is one
        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

        // pass the plantao to the view
        $this->data['plantao'] = $plantao;

        $this->data['dataplantao'] = array(
            'name'  => 'dataplantao',
            'id'    => 'dataplantao',
            'type'  => 'date',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('dataplantao', $plantao->dataplantao)
        );
        $this->data['horainicialplantao'] = array(
            'name'  => 'horainicialplantao',
            'id'    => 'horainicialplantao',
            'type'  => 'time',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('horainicialplantao', $plantao->horainicialplantao)
        );
        $this->data['horafinalplantao'] = array(
            'name'  => 'horafinalplantao',
            'id'    => 'horafinalplantao',
            'type'  => 'time',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('horafinalplantao', $plantao->horafinalplantao)
        );

        /* Load Template */
        $this->template->admin_render('admin/plantoes/confirm', $this->data);
    }

    public function create()
    {
        /* Breadcrumbs */
        $this->breadcrumbs->unshift(2, lang('menu_plantoes_create'), 'admin/plantoes/create');
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        /* Variables */
        $tables = $this->config->item('tables', 'ion_auth');

        /* Validate form input */
        $this->form_validation->set_rules('dataplantao', 'lang:plantoes_dataplantao', 'required');
        $this->form_validation->set_rules('horainicialplantao', 'lang:plantoes_horainicialplantao', 'required');
        $this->form_validation->set_rules('horafinalplantao', 'lang:plantoes_horafinalplantao', 'required');

        if ($this->form_validation->run() == true) {
            $dataplantao = $this->input->post('dataplantao');
            $horainicialplantao = $this->input->post('horainicialplantao');
            $horafinalplantao = $this->input->post('horafinalplantao');
            //$active = $this->input->post('active');

            $additional_data = array(
                'dataplantao' => $this->input->post('dataplantao'),
                'horainicialplantao' => $this->input->post('horainicialplantao'),
                'horafinalplantao' => $this->input->post('horafinalplantao')
            );
        }

        // Realizar o insert no model de unidades hospitalares
        if ($this->form_validation->run() == true
            && $this->escala_model->insert($additional_data)
        ) {
            $this->session->set_flashdata('message', $this->ion_auth->messages());
            redirect('admin/plantoes', 'refresh');
        } else {
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

            $this->data['dataplantao'] = array(
                'name'  => 'dataplantao',
                'id'    => 'dataplantao',
                'type'  => 'date',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('dataplantao'),
            );
            $this->data['horainicialplantao'] = array(
                'name'  => 'horainicialplantao',
                'id'    => 'horainicialplantao',
                'type'  => 'time',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('horainicialplantao'),
            );
            $this->data['horafinalplantao'] = array(
                'name'  => 'horafinalplantao',
                'id'    => 'horafinalplantao',
                'type'  => 'time',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('horafinalplantao'),
            );
            /*
            $this->data['active'] = array(
                'name'  => 'active',
                'id'    => 'active',
                'type'  => 'checkbox',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('active'),
            );
            */

            /* Load Template */
            $this->template->admin_render('admin/plantoes/create', $this->data);
        }
    }

    public function edit($id)
    {
        $id = (int) $id;

        if (!$this->ion_auth->logged_in() or !$this->ion_auth->is_admin()) {
            redirect('auth', 'refresh');
        }

        /* Breadcrumbs */
        $this->breadcrumbs->unshift(2, lang('menu_plantoes_edit'), 'admin/plantoes/edit');
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        /* Load Data */
        $plantao = $this->escala_model->get_by_id($id);
        //$groups        = $this->ion_auth->groups()->result_array();
        //$currentGroups = $this->ion_auth->get_users_groups($id)->result();

        /* Validate form input */
        $this->form_validation->set_rules('dataplantao', 'lang:plantoes_dataplantao', 'required');
        $this->form_validation->set_rules('horainicialplantao', 'lang:plantoes_horainicialplantao', 'required');
        $this->form_validation->set_rules('horafinalplantao', 'lang:plantoes_horafinalplantao', 'required');
        //$this->form_validation->set_rules('unidade_hospitalar', 'lang:plantoes_unidade_hospitalar', 'required');
        //$this->form_validation->set_rules('nomefantasia', 'lang:plantoes_nomefantasia', 'required');
        //$this->form_validation->set_rules('active', 'lang:edit_user_validation_company_label', 'required');

        if (isset($_POST) && ! empty($_POST)) {
            if ($this->_valid_csrf_nonce() === false or $id != $this->input->post('id')) {
                show_error($this->lang->line('error_csrf'));
            }

            if ($this->form_validation->run() == true) {
                $data = array(
                    'dataplantao' => $this->input->post('dataplantao'),
                    'horainicialplantao' => $this->input->post('horainicialplantao'),
                    'horafinalplantao' => $this->input->post('horafinalplantao')
                );

                if ($this->escala_model->update($plantao->id, $data)) {
                    $this->session->set_flashdata('message', $this->ion_auth->messages());

                    if ($this->ion_auth->is_admin()) {
                        redirect('admin/plantoes', 'refresh');
                    } else {
                        redirect('admin', 'refresh');
                    }
                } else {
                    $this->session->set_flashdata('message', $this->ion_auth->errors());

                    if ($this->ion_auth->is_admin()) {
                        redirect('auth', 'refresh');
                    } else {
                        redirect('/', 'refresh');
                    }
                }
            }
        }

        // display the edit user form
        $this->data['csrf'] = $this->_get_csrf_nonce();

        // set the flash data error message if there is one
        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

        // pass the plantao to the view
        $this->data['plantao'] = $plantao;

        $this->data['dataplantao'] = array(
            'name'  => 'dataplantao',
            'id'    => 'dataplantao',
            'type'  => 'date',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('dataplantao', $plantao->dataplantao)
        );
        $this->data['horainicialplantao'] = array(
            'name'  => 'horainicialplantao',
            'id'    => 'horainicialplantao',
            'type'  => 'time',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('horainicialplantao', $plantao->horainicialplantao)
        );
        $this->data['horafinalplantao'] = array(
            'name'  => 'horafinalplantao',
            'id'    => 'horafinalplantao',
            'type'  => 'time',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('horafinalplantao', $plantao->horafinalplantao)
        );
        /*
        $this->data['active'] = array(
            'name'  => 'active',
            'id'    => 'active',
            'type'  => 'checkbox',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('active', $plantao->active)
        );
        */

        /* Load Template */
        $this->template->admin_render('admin/plantoes/edit', $this->data);
    }

    public function view($id)
    {
        /* Breadcrumbs */
        $this->breadcrumbs->unshift(2, lang('menu_plantoes'), 'admin/plantoes/view');
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        /* Data */
        $id = (int) $id;

        $this->data['plantao'] = $this->escala_model->get_by_id($id);

        /* Load Template */
        $this->template->admin_render('admin/plantoes/view', $this->data);
    }

    public function _get_tipos_passagem()
    {
        return array(
            '0' => 'Cessão',
            '1' => 'Troca'
        );
    }

    public function _get_status_passagem()
    {
        return array(
            '0' => 'Sem confirmação',
            '1' => 'Confirmado',
        );
    }

    public function _get_profissionais_setor($profissionais)
    {
        $profissionaissetor = array();
        foreach ($profissionais as $profissional) {
            $profissionaissetor[$profissional->id] = $profissional->nome;
        }

        return $profissionaissetor;
    }

    public function _get_csrf_nonce()
    {
        $this->load->helper('string');
        $key   = random_string('alnum', 8);
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
