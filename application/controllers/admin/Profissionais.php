<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profissionais extends Admin_Controller {

    public function __construct()
    {
        parent::__construct();

        /* Load :: Common */
        $this->load->model('cemerge/profissional_model');
        $this->lang->load('admin/profissionais');

        /* Title Page */
        $this->page_title->push(lang('menu_profissionais'));
        $this->data['pagetitle'] = $this->page_title->show();

        /* Breadcrumbs :: Common */
        $this->breadcrumbs->unshift(1, lang('menu_profissionais'), 'admin/profissionais');
    }

    public function index()
    {
        if (!$this->ion_auth->logged_in() OR ! $this->ion_auth->is_admin()) {
            redirect('auth/login', 'refresh');
        } else {
            /* Breadcrumbs */
            $this->data['breadcrumb'] = $this->breadcrumbs->show();

            /* Validate form input */
            $this->form_validation->set_rules('nome', 'lang:profissionais_nome', 'required');

            if ($this->form_validation->run() == true) {
                $nome = $this->input->post('nome');
                
                /* Profissionais */
                $this->data['profissionais'] = $this->profissional_model->get_like('nome', $nome, 'nome');
            } else {
                $this->data['profissionais'] = array();
                $nome = '';
            }

            $this->data['nome'] = array(
                'name'  => 'nome',
                'id'    => 'nome',
                'type'  => 'text',
                'class' => 'form-control',
                'value' => $nome,
            );

            /* Load Template */
            $this->template->admin_render('admin/profissionais/index', $this->data);
        }
    }

    public function create()
    {
        /* Breadcrumbs */
        $this->breadcrumbs->unshift(2, lang('menu_profissionais_create'), 'admin/profissionais/create');
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        /* Variables */
        $tables = $this->config->item('tables', 'ion_auth');

        /* Validate form input */
        $this->form_validation->set_rules('registro', 'lang:profissionais_registro', 'required');
        $this->form_validation->set_rules('nome', 'lang:profissionais_nome', 'required');
        $this->form_validation->set_rules('email', 'lang:profissionais_email', 'required|valid_email');

        if ($this->form_validation->run() == true) {
            $registro = $this->input->post('registro');
            $nome = $this->input->post('nome');
            $email = $this->input->post('email');
            $active = $this->input->post('active');

            $additional_data = array(
                'registro' => $this->input->post('registro'),
                'nome' => $this->input->post('nome'),
                'email' => $this->input->post('email'),
                'active' => $this->input->post('active')
            );
        }

        // Realizar o insert no model de unidades hospitalares
        if ($this->form_validation->run() == true
            && $this->profissional_model->insert($additional_data)
        ) {
            $this->session->set_flashdata('message', $this->ion_auth->messages());
            redirect('admin/profissionais', 'refresh');
        } else {
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

            $this->data['registro'] = array(
                'name'  => 'registro',
                'id'    => 'registro',
                'type'  => 'text',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('registro'),
            );
            $this->data['nome'] = array(
                'name'  => 'nome',
                'id'    => 'nome',
                'type'  => 'text',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('nome'),
            );
            $this->data['email'] = array(
                'name'  => 'email',
                'id'    => 'email',
                'type'  => 'text',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('email'),
            );
            $this->data['active'] = array(
                'name'  => 'active',
                'id'    => 'active',
                'type'  => 'checkbox',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('active'),
            );

            /* Load Template */
            $this->template->admin_render('admin/profissionais/create', $this->data);
        }
    }

    public function edit($id)
    {
        $id = (int) $id;

        if (!$this->ion_auth->logged_in()
            or (!$this->ion_auth->is_admin()
            and !($this->ion_auth->user()->row()->id == $id))
        ) {
            redirect('auth', 'refresh');
        }

        /* Breadcrumbs */
        $this->breadcrumbs->unshift(2, lang('menu_profissionais_edit'), 'admin/profissionais/edit');
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        /* Load Data */
        $profissional = $this->profissional_model->get_by_id($id);
        //$groups        = $this->ion_auth->groups()->result_array();
        //$currentGroups = $this->ion_auth->get_users_groups($id)->result();

        /* Validate form input */
        $this->form_validation->set_rules('registro', 'lang:profissionais_registro', 'required');
        $this->form_validation->set_rules('nome', 'lang:profissionais_nome', 'required');
        $this->form_validation->set_rules('email', 'lang:profissionais_email', 'required|valid_email');
        //$this->form_validation->set_rules('active', 'lang:edit_user_validation_company_label', 'required');

        if (isset($_POST) && ! empty($_POST)) {
            if ($this->_valid_csrf_nonce() === false or $id != $this->input->post('id')) {
                show_error($this->lang->line('error_csrf'));
            }

            if ($this->form_validation->run() == true) {
                $data = array(
                    'registro' => $this->input->post('registro'),
                    'nome' => $this->input->post('nome'),
                    'email' => $this->input->post('email'),
                    'active' => $this->input->post('active')
                );

                if ($this->profissional_model->update($profissional->id, $data)) {
                    $this->session->set_flashdata('message', $this->ion_auth->messages());

                    if ($this->ion_auth->is_admin()) {
                        redirect('admin/profissionais', 'refresh');
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

        // pass the profissional to the view
        $this->data['profissional'] = $profissional;

        $this->data['registro'] = array(
            'name'  => 'registro',
            'id'    => 'registro',
            'type'  => 'text',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('registro', $profissional->registro)
        );
        $this->data['nome'] = array(
            'name'  => 'nome',
            'id'    => 'nome',
            'type'  => 'text',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('nome', $profissional->nome)
        );
        $this->data['email'] = array(
            'name'  => 'email',
            'id'    => 'email',
            'type'  => 'text',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('email', $profissional->email)
        );
        $this->data['active'] = array(
            'name'  => 'active',
            'id'    => 'active',
            'type'  => 'checkbox',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('active', $profissional->active)
        );

        /* Load Template */
        $this->template->admin_render('admin/profissionais/edit', $this->data);
    }

    public function view($id)
    {
        /* Breadcrumbs */
        $this->breadcrumbs->unshift(2, lang('menu_users_profile'), 'admin/profissionais/view');
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        /* Data */
        $id = (int) $id;

        $this->data['profissional'] = $this->profissional_model->get_by_id($id);
        /*
        // Setores
        foreach ($this->data['user_info'] as $k => $user)
        {
            $this->data['user_info'][$k]->groups = $this->ion_auth->get_users_groups($user->id)->result();
        }
        */

        /* Load Template */
        $this->template->admin_render('admin/profissionais/view', $this->data);
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
