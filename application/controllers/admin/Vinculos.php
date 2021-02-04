<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vinculos extends Admin_Controller
{
    private $_permitted_groups = array('admin', 'sad', 'sac');
    private $_admin_groups = array('admin', 'sad', 'sac');

    public function __construct()
    {
        parent::__construct($this->_permitted_groups);

        /* Load :: Common */
        $this->load->model('cemerge/vinculo_model');
        $this->lang->load('admin/vinculos');

        /* Title Page */
        $this->page_title->push(lang('menu_vinculos'));
        $this->data['pagetitle'] = $this->page_title->show();

        /* Breadcrumbs :: Common */
        $this->breadcrumbs->unshift(1, lang('menu_vinculos'), 'admin/vinculos');
    }

    public function index()
    {
        if (!$this->ion_auth->logged_in()) {
            $this->session->set_flashdata('message', 'Para listar vinculos, você precisa estar logado no sistema.');
            redirect('auth/login', 'refresh');
        }
        if (!$this->ion_auth->in_group($this->_permitted_groups)) {
            $this->session->set_flashdata('message', 'O acesso &agrave; este recurso não é permitido ao seu perfil de usuário.');
            redirect('admin/dashboard', 'refresh');
        }

        /* Breadcrumbs */
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        /* Variables */
        $nome = '';

        /* Validate form input */
        $this->form_validation->set_rules('nome', 'lang:vinculos_nome', 'required');

        if ($this->form_validation->run() == true) {
            $nome = $this->input->post('nome');

            $this->data['vinculos'] = $this->vinculo_model->get_like('nome', $nome, 'nome');
        } else {
            $this->data['vinculos'] = $this->vinculo_model->get_all();
        }

        $this->data['nome'] = array(
            'name'  => 'nome',
            'id'    => 'nome',
            'type'  => 'text',
            'class' => 'form-control',
            'value' => $nome
        );

        /* Load Template */
        $this->template->admin_render('admin/vinculos/index', $this->data);
    }

    public function create()
    {
        if (!$this->ion_auth->logged_in()) {
            $this->session->set_flashdata('message', 'Você deve estar autenticado para utilizar esta funcionalidade.');
            redirect('auth/login', 'refresh');
        }
        if (!$this->ion_auth->in_group($this->_admin_groups)) {
            $this->session->set_flashdata('message', 'O acesso &agrave; este recurso não é permitido ao seu perfil de usuário.');
            redirect('admin/dashboard', 'refresh');
        }

        /* Breadcrumbs */
        $this->breadcrumbs->unshift(2, lang('menu_vinculos_create'), 'admin/vinculos/create');
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        /* Validate form input */
        $this->form_validation->set_rules('nome', 'lang:vinculos_nome', 'required');

        if ($this->form_validation->run() == true) {
            $nome = $this->input->post('nome');

            $additional_data = array(
                'nome' => $nome,
            );
        }

        // Realizar o insert no model de unidades hospitalares
        if ($this->form_validation->run() == true and $this->vinculo_model->insert($additional_data)) {
            $this->session->set_flashdata('message', lang('vinculos_insert_success'));
            redirect('admin/vinculos', 'refresh');
        } else {
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

            $this->data['nome'] = array(
                'name'  => 'nome',
                'id'    => 'nome',
                'type'  => 'text',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('nome'),
            );

            /* Load Template */
            $this->template->admin_render('admin/vinculos/create', $this->data);
        }
    }

    public function edit($id)
    {
        if (!$this->ion_auth->logged_in()) {
            $this->session->set_flashdata('message', 'Você deve estar autenticado para utilizar esta funcionalidade.');
            redirect('auth/login', 'refresh');
        }
        if (!$this->ion_auth->in_group($this->_admin_groups)) {
            $this->session->set_flashdata('message', 'O acesso &agrave; este recurso não é permitido ao seu perfil de usuário.');
            redirect('admin/dashboard', 'refresh');
        }

        $id = (int) $id;

        /* Breadcrumbs */
        $this->breadcrumbs->unshift(2, lang('menu_vinculos_edit'), 'admin/vinculos/edit');
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        /* Load Data */
        $vinculo = $this->vinculo_model->get_by_id($id);

        /* Validate form input */
        $this->form_validation->set_rules('nome', 'lang:vinculos_nome', 'required');

        if (isset($_POST) and !empty($_POST)) {
            if ($this->_valid_csrf_nonce() === false or $id != $this->input->post('id')) {
                show_error($this->lang->line('error_csrf'));
            }

            if ($this->form_validation->run() == true) {
                $data = array(
                    'nome' => $this->input->post('nome'),
                );

                if ($this->vinculo_model->update($vinculo->id, $data)) {
                    $this->session->set_flashdata('message', lang('vinculos_edit_success'));
                } else {
                    $this->session->set_flashdata('message', lang('vinculos_edit_error'));
                }
                redirect('admin/vinculos', 'refresh');
            }
        }

        // display the edit user form
        $this->data['csrf'] = $this->_get_csrf_nonce();

        // set the flash data error message if there is one
        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

        // pass the valor plantao to the view
        $this->data['vinculo'] = $vinculo;

        $this->data['nome'] = array(
            'name'  => 'nome',
            'id'    => 'nome',
            'type'  => 'text',
            'class' => 'form-control',
            'value' => $vinculo->nome,
        );

        /* Load Template */
        $this->template->admin_render('admin/vinculos/edit', $this->data);
    }

    public function view($id)
    {
        if (!$this->ion_auth->logged_in()) {
            $this->session->set_flashdata('message', 'Você deve estar autenticado para utilizar esta funcionalidade.');
            redirect('auth/login', 'refresh');
        }
        if (!$this->ion_auth->in_group($this->_permitted_groups)) {
            $this->session->set_flashdata('message', 'O acesso &agrave; este recurso não é permitido ao seu perfil de usuário.');
            redirect('admin/dashboard', 'refresh');
        }

        /* Breadcrumbs */
        $this->breadcrumbs->unshift(2, lang('menu_vinculos'), 'admin/vinculos/view');
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        /* Data */
        $id = (int) $id;

        $this->data['vinculo'] = $this->vinculo_model->get_by_id($id);

        /* Load Template */
        $this->template->admin_render('admin/vinculos/view', $this->data);
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
