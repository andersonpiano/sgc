<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Unidadeshospitalares extends Admin_Controller
{
    private $_permitted_groups = array('admin', 'sac', 'sad');

    public function __construct()
    {
        parent::__construct($this->_permitted_groups);

        /* Load :: Common */
        $this->load->model('cemerge/unidadehospitalar_model');
        $this->lang->load('admin/unidadeshospitalares');

        /* Title Page */
        $this->page_title->push(lang('menu_unidadeshospitalares'));
        $this->data['pagetitle'] = $this->page_title->show();

        /* Breadcrumbs :: Common */
        $this->breadcrumbs->unshift(1, lang('menu_unidadeshospitalares'), 'admin/unidadeshospitalares');
    }

    public function index()
    {
        if (!$this->ion_auth->logged_in() or !$this->ion_auth->in_group($this->_permitted_groups)) {
            redirect('auth/login', 'refresh');
        } else {
            /* Breadcrumbs */
            $this->data['breadcrumb'] = $this->breadcrumbs->show();

            /* Get all hospitals */
            $this->data['unidadeshospitalares'] = $this->unidadehospitalar_model->get_all();

            /* Load Template */
            $this->template->admin_render('admin/unidadeshospitalares/index', $this->data);
        }
    }

    public function create()
    {
        /* Breadcrumbs */
        $this->breadcrumbs->unshift(2, lang('menu_unidadeshospitalares_create'), 'admin/unidadeshospitalares/create');
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        /* Variables */
        $tables = $this->config->item('tables', 'ion_auth');

        /* Validate form input */
        $this->form_validation->set_rules('cnpj', 'lang:unidadeshospitalares_cnpj', 'required');
        $this->form_validation->set_rules('razaosocial', 'lang:unidadeshospitalares_razaosocial', 'required');
        $this->form_validation->set_rules('nomefantasia', 'lang:unidadeshospitalares_nomefantasia', 'required');

        if ($this->form_validation->run() == true) {
            $cnpj = $this->input->post('cnpj');
            $razaosocial = $this->input->post('razaosocial');
            $nomefantasia = $this->input->post('nomefantasia');
            $active = $this->input->post('active');

            $additional_data = array(
                'cnpj' => $this->input->post('cnpj'),
                'razaosocial' => $this->input->post('razaosocial'),
                'nomefantasia' => $this->input->post('nomefantasia'),
                'active' => $this->input->post('active')
            );
        }

        // Realizar o insert no model de unidades hospitalares
        if ($this->form_validation->run() == true
            && $this->unidadehospitalar_model->insert($additional_data)
        ) {
            $this->session->set_flashdata('message', $this->ion_auth->messages());
            redirect('admin/unidadeshospitalares', 'refresh');
        } else {
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

            $this->data['cnpj'] = array(
                'name'  => 'cnpj',
                'id'    => 'cnpj',
                'type'  => 'text',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('cnpj'),
            );
            $this->data['razaosocial'] = array(
                'name'  => 'razaosocial',
                'id'    => 'razaosocial',
                'type'  => 'text',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('razaosocial'),
            );
            $this->data['nomefantasia'] = array(
                'name'  => 'nomefantasia',
                'id'    => 'nomefantasia',
                'type'  => 'text',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('nomefantasia'),
            );
            $this->data['active'] = array(
                'name'  => 'active',
                'id'    => 'active',
                'type'  => 'checkbox',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('active'),
            );

            /* Load Template */
            $this->template->admin_render('admin/unidadeshospitalares/create', $this->data);
        }
    }

    public function edit($id)
    {
        $id = (int) $id;

        if (!$this->ion_auth->logged_in() OR (!$this->ion_auth->is_admin() && ! ($this->ion_auth->user()->row()->id == $id))) {
            redirect('auth', 'refresh');
        }

        /* Breadcrumbs */
        $this->breadcrumbs->unshift(2, lang('menu_unidadeshospitalares_edit'), 'admin/unidadeshospitalares/edit');
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        /* Load Data */
        $unidadehospitalar = $this->unidadehospitalar_model->get_by_id($id);
        //$groups        = $this->ion_auth->groups()->result_array();
        //$currentGroups = $this->ion_auth->get_users_groups($id)->result();

        /* Validate form input */
        $this->form_validation->set_rules('cnpj', 'lang:unidadeshospitalares_cnpj', 'required');
        $this->form_validation->set_rules('razaosocial', 'lang:unidadeshospitalares_razaosocial', 'required');
        //$this->form_validation->set_rules('nomefantasia', 'lang:unidadeshospitalares_nomefantasia', 'required');
        //$this->form_validation->set_rules('active', 'lang:edit_user_validation_company_label', 'required');

        if (isset($_POST) && ! empty($_POST)) {
            if ($this->_valid_csrf_nonce() === false or $id != $this->input->post('id')) {
                show_error($this->lang->line('error_csrf'));
            }

            if ($this->form_validation->run() == true) {
                $data = array(
                    'cnpj' => $this->input->post('cnpj'),
                    'razaosocial' => $this->input->post('razaosocial'),
                    'nomefantasia' => $this->input->post('nomefantasia'),
                    'active' => $this->input->post('active')
                );

                if ($this->unidadehospitalar_model->update($unidadehospitalar->id, $data)) {
                    $this->session->set_flashdata('message', $this->ion_auth->messages());

                    if ($this->ion_auth->is_admin()) {
                        redirect('admin/unidadeshospitalares', 'refresh');
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

        // pass the unidadehospitalar to the view
        $this->data['unidadehospitalar'] = $unidadehospitalar;

        $this->data['cnpj'] = array(
            'name'  => 'cnpj',
            'id'    => 'cnpj',
            'type'  => 'text',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('cnpj', $unidadehospitalar->cnpj)
        );
        $this->data['razaosocial'] = array(
            'name'  => 'razaosocial',
            'id'    => 'razaosocial',
            'type'  => 'text',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('razaosocial', $unidadehospitalar->razaosocial)
        );
        $this->data['nomefantasia'] = array(
            'name'  => 'nomefantasia',
            'id'    => 'nomefantasia',
            'type'  => 'text',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('nomefantasia', $unidadehospitalar->nomefantasia)
        );
        $this->data['active'] = array(
            'name'  => 'active',
            'id'    => 'active',
            'type'  => 'checkbox',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('active', $unidadehospitalar->active)
        );

        /* Load Template */
        $this->template->admin_render('admin/unidadeshospitalares/edit', $this->data);
    }

    public function view($id)
    {
        /* Breadcrumbs */
        $this->breadcrumbs->unshift(2, lang('menu_users_profile'), 'admin/unidadeshospitalares/view');
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        /* Data */
        $id = (int) $id;

        $this->data['unidadehospitalar'] = $this->unidadehospitalar_model->get_by_id($id);

        // Setores
        $this->load->model('cemerge/setor_model');
        $this->data['setores'] = $this->setor_model->get_by_unidadehospitalar($id);

        /* Load Template */
        $this->template->admin_render('admin/unidadeshospitalares/view', $this->data);
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
