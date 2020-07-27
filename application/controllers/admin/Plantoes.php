<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Plantoes extends Admin_Controller {

    public function __construct()
    {
        parent::__construct();

        /* Load :: Common */
        $this->load->model('cemerge/escala_model');
        $this->load->model('cemerge/plantao_model');
        $this->lang->load('admin/plantoes');

        /* Title Page */
        $this->page_title->push(lang('menu_plantoes'));
        $this->data['pagetitle'] = $this->page_title->show();

        /* Breadcrumbs :: Common */
        $this->breadcrumbs->unshift(1, lang('menu_plantoes'), 'admin/plantoes');
    }


    public function index()
    {
        $permitted_groups = array('admin', 'profissionais');
        if (!$this->ion_auth->logged_in() or !$this->ion_auth->in_group($groups)) {
            redirect('auth/login', 'refresh');
        } else {
            /* Breadcrumbs */
            $this->data['breadcrumb'] = $this->breadcrumbs->show();

            /* Get all data */
            if ($this->ion_auth->is_admin()) {
                $this->data['plantoes'] = $this->escala_model->get_all();
                $this->data['passagens'] = $this->plantao_model->get_all();
                $this->data['recebidos'] = $this->plantao_model->get_all();
            } else {
                $this->data['plantoes'] = $this->escala_model->get_where(['profissional_id' => $this->ion_auth->user()->row()->id]);
                $this->data['passagens'] = $this->plantao_model->get_where(['profissional_id' => $this->ion_auth->user()->row()->id]);
                $this->data['recebidos'] = $this->plantao_model->get_where(['profissional_id' => $this->ion_auth->user()->row()->id]);
            }

            /* Load Template */
            $this->template->admin_render('admin/plantoes/index', $this->data);
        }
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
            && $this->plantao_model->insert($additional_data)
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
        $plantao = $this->plantao_model->get_by_id($id);
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

                if ($this->plantao_model->update($plantao->id, $data)) {
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

        $this->data['plantao'] = $this->plantao_model->get_by_id($id);
        /*
        // plantoes
        foreach ($this->data['user_info'] as $k => $user)
        {
            $this->data['user_info'][$k]->groups = $this->ion_auth->get_users_groups($user->id)->result();
        }
        */

        /* Load Template */
        $this->template->admin_render('admin/plantoes/view', $this->data);
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
