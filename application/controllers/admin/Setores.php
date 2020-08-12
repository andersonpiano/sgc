<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Setores extends Admin_Controller {

    public function __construct()
    {
        parent::__construct();

        /* Load :: Common */
        $this->load->model('cemerge/setor_model');
        $this->load->model('cemerge/unidadehospitalar_model');
        $this->lang->load('admin/setores');

        /* Title Page */
        $this->page_title->push(lang('menu_setores'));
        $this->data['pagetitle'] = $this->page_title->show();

        /* Breadcrumbs :: Common */
        $this->breadcrumbs->unshift(1, lang('menu_setores'), 'admin/setores');
    }


    public function index()
    {
        if (! $this->ion_auth->logged_in() OR ! $this->ion_auth->is_admin()) {
            redirect('auth/login', 'refresh');
        } else {
            /* Breadcrumbs */
            $this->data['breadcrumb'] = $this->breadcrumbs->show();

            /* Validate form input */
            $this->form_validation->set_rules('unidadehospitalar_id', 'lang:setores_unidadehospitalar', 'required');

            if ($this->form_validation->run() == true) {
                $unidadehospitalar_id = $this->input->post('unidadehospitalar_id');
                
                /* Setores */
                $this->data['setores'] = $this->setor_model->get_where(['unidadehospitalar_id' => $unidadehospitalar_id]);

                /*  Unidades hospitalares */
                // Corrigir atributo para unidadehospitalar_id
                foreach ($this->data['setores'] as $k => $setor) {
                    $this->data['setores'][$k]->unidadehospitalar = $this->unidadehospitalar_model->get_by_id($setor->unidadehospitalar_id);
                }
            } else {
                $this->data['setores'] = array();
            }

            $unidadeshospitalares = $this->_get_unidadeshospitalares();

            $this->data['unidadehospitalar_id'] = array(
                'name'  => 'unidadehospitalar_id',
                'id'    => 'unidadehospitalar_id',
                'type'  => 'select',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('unidadehospitalar_id'),
                'options' => $unidadeshospitalares,
            );

            /* Load Template */
            $this->template->admin_render('admin/setores/index', $this->data);
        }
    }

    public function create()
    {
        /* Breadcrumbs */
        $this->breadcrumbs->unshift(2, lang('menu_setores_create'), 'admin/setores/create');
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        /* Variables */
        $tables = $this->config->item('tables', 'ion_auth');

        /* Validate form input */
        $this->form_validation->set_rules('nome', 'lang:setores_nome', 'required');
        $this->form_validation->set_rules('unidadehospitalar_id', 'lang:setores_unidadehospitalar', 'required');

        if ($this->form_validation->run() == true) {
            $nome = $this->input->post('nome');
            $unidadehospitalar_id = $this->input->post('unidadehospitalar_id');
            $active = $this->input->post('active');

            $additional_data = array(
                'nome' => $this->input->post('nome'),
                'unidadehospitalar_id' => $this->input->post('unidadehospitalar_id'),
                'active' => $this->input->post('active')
            );
        }

        // Realizar o insert no model de setores
        if ($this->form_validation->run() == true
            && $this->setor_model->insert($additional_data)
        ) {
            $this->session->set_flashdata('message', $this->ion_auth->messages());
            redirect('admin/setores', 'refresh');
        } else {
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

            $unidadeshospitalares = $this->_get_unidadeshospitalares();

            $this->data['nome'] = array(
                'name'  => 'nome',
                'id'    => 'nome',
                'type'  => 'text',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('nome'),
            );
            $this->data['unidadehospitalar_id'] = array(
                'name'  => 'unidadehospitalar_id',
                'id'    => 'unidadehospitalar_id',
                'type'  => 'select',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('unidadehospitalar_id'),
                'options' => $unidadeshospitalares
            );
            $this->data['active'] = array(
                'name'  => 'active',
                'id'    => 'active',
                'type'  => 'checkbox',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('active'),
            );

            /* Load Template */
            $this->template->admin_render('admin/setores/create', $this->data);
        }
    }

    public function edit($id)
    {
        $id = (int) $id;

        if (!$this->ion_auth->logged_in() or !$this->ion_auth->is_admin()) {
            redirect('auth', 'refresh');
        }

        /* Breadcrumbs */
        $this->breadcrumbs->unshift(2, lang('menu_setores_edit'), 'admin/setores/edit');
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        /* Load Data */
        $setor = $this->setor_model->get_by_id($id);
        /*  Unidade hospitalar */
        $setor->unidadehospitalar = $this->unidadehospitalar_model->get_by_id($setor->unidadehospitalar_id);

        /* Validate form input */
        $this->form_validation->set_rules('nome', 'lang:setores_nome', 'required');
        $this->form_validation->set_rules('unidadehospitalar_id', 'lang:setores_unidadehospitalar', 'required');

        if (isset($_POST) && ! empty($_POST)) {
            if ($this->_valid_csrf_nonce() === false or $id != $this->input->post('id')) {
                show_error($this->lang->line('error_csrf'));
            }

            if ($this->form_validation->run() == true) {
                $data = array(
                    'nome' => $this->input->post('nome'),
                    'unidadehospitalar_id' => $this->input->post('unidadehospitalar_id')
                );

                if ($this->setor_model->update($setor->id, $data)) {
                    $this->session->set_flashdata('message', $this->ion_auth->messages());

                    if ($this->ion_auth->is_admin()) {
                        redirect('admin/setores', 'refresh');
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

        // pass the setor to the view
        $this->data['setor'] = $setor;

        // pass unidades to the dropdown
        $unidadeshospitalares = $this->_get_unidadeshospitalares();

        $this->data['nome'] = array(
            'name'  => 'nome',
            'id'    => 'nome',
            'type'  => 'text',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('nome', $setor->nome)
        );
        $this->data['unidadehospitalar_id'] = array(
            'name'  => 'unidadehospitalar_id',
            'id'    => 'unidadehospitalar_id',
            'type'  => 'select',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('unidadehospitalar_id', $setor->unidadehospitalar_id),
            'options' => $unidadeshospitalares,
            'selected' => $setor->unidadehospitalar_id
        );
        $this->data['active'] = array(
            'name'  => 'active',
            'id'    => 'active',
            'type'  => 'checkbox',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('active', $setor->active)
        );

        /* Load Template */
        $this->template->admin_render('admin/setores/edit', $this->data);
    }

    public function view($id)
    {
        /* Breadcrumbs */
        $this->breadcrumbs->unshift(2, lang('menu_setores'), 'admin/setores/view');
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        /* Data */
        $id = (int) $id;

        $this->data['setor'] = $this->setor_model->get_by_id($id);
        $this->data['setor']->unidadehospitalar = $this->unidadehospitalar_model->get_by_id($this->data['setor']->unidadehospitalar_id);

        /* Load Template */
        $this->template->admin_render('admin/setores/view', $this->data);
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
        if ($this->input->post($this->session->flashdata('csrfkey')) !== false && $this->input->post($this->session->flashdata('csrfkey')) == $this->session->flashdata('csrfvalue')) {
            return true;
        } else {
            return false;
        }
    }

    public function _get_unidadeshospitalares()
    {
        // pass unidades to the view
        $unidades = $this->unidadehospitalar_model->get_all();

        $unidadeshospitalares = array();
        foreach ($unidades as $unidade) {
            $unidadeshospitalares[$unidade->id] = $unidade->razaosocial;
        }

        return $unidadeshospitalares;
    }
}
