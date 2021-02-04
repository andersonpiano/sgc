<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Feriados extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();

        /* Load :: Common */
        $this->load->model('cemerge/feriado_model');
        $this->lang->load('admin/feriados');

        /* Title Page */
        $this->page_title->push(lang('menu_feriados'));
        $this->data['pagetitle'] = $this->page_title->show();

        /* Breadcrumbs :: Common */
        $this->breadcrumbs->unshift(1, lang('menu_feriados'), 'admin/feriados');
    }

    public function index()
    {
        if (!$this->ion_auth->logged_in() OR ! $this->ion_auth->is_admin()) {
            redirect('auth/login', 'refresh');
        } else {
            /* Breadcrumbs */
            $this->data['breadcrumb'] = $this->breadcrumbs->show();

            /* Default values */
            $datainicial = date('Y-m-d');
            $datafinal = date('Y-m-d');

            /* Validate form input */
            $this->form_validation->set_rules('datainicial', 'lang:feriados_datainicial', 'required');
            $this->form_validation->set_rules('datafinal', 'lang:feriados_datafinal', 'required');

            if ($this->form_validation->run() == true) {
                $datainicial = $this->input->post('datainicial');
                $datafinal = $this->input->post('datafinal');

                /* Valores */
                $where = array(
                    'data >=' => $datainicial,
                    'data <=' => $datafinal,
                );
                $this->data['feriados'] = $this->feriado_model->get_where($where, null, null, 'data');
            } else {
                $this->data['feriados'] = array();
            }

            $this->data['datainicial'] = array(
                'name'  => 'datainicial',
                'id'    => 'datainicial',
                'type'  => 'date',
                'class' => 'form-control',
                'value' => $datainicial
            );
            $this->data['datafinal'] = array(
                'name'  => 'datafinal',
                'id'    => 'datafinal',
                'type'  => 'date',
                'class' => 'form-control',
                'value' => $datafinal
            );

            /* Load Template */
            $this->template->admin_render('admin/feriados/index', $this->data);
        }
    }

    public function create()
    {
        /* Breadcrumbs */
        $this->breadcrumbs->unshift(2, lang('menu_feriados_create'), 'admin/feriados/create');
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        /* Variables */
        //$tables = $this->config->item('tables', 'ion_auth');

        /* Validate form input */
        $this->form_validation->set_rules('data', 'lang:feriados_data', 'required');
        $this->form_validation->set_rules('descricao', 'lang:feriados_descricao', 'required');

        if ($this->form_validation->run() == true) {
            $data = $this->input->post('data');
            $descricao = $this->input->post('descricao');
            $especial = $this->input->post('especial');
            if (is_null($especial)) {
                $especial = 0;
            }

            $additional_data = array(
                'data' => $data,
                'descricao' => $descricao,
                'especial' => $especial,
            );
        } else {
            $data = date('Y-m-d');
        }

        // Realizar o insert no model de unidades hospitalares
        if ($this->form_validation->run() == true and $this->feriado_model->insert($additional_data)) {
            $this->session->set_flashdata('message', lang('feriados_insert_success'));
            redirect('admin/feriados', 'refresh');
        } else {
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

            $this->data['data'] = array(
                'name'  => 'data',
                'id'    => 'data',
                'type'  => 'date',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('data'),
            );
            $this->data['descricao'] = array(
                'name'  => 'descricao',
                'id'    => 'descricao',
                'type'  => 'text',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('descricao'),
            );
            $this->data['especial'] = array(
                'name'  => 'especial',
                'id'    => 'especial',
                'type'  => 'checkbox',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('especial'),
            );

            /* Load Template */
            $this->template->admin_render('admin/feriados/create', $this->data);
        }
    }

    public function edit($id)
    {
        $id = (int) $id;

        if (!$this->ion_auth->logged_in() or !$this->ion_auth->is_admin()) {
            redirect('auth', 'refresh');
        }

        /* Breadcrumbs */
        $this->breadcrumbs->unshift(2, lang('menu_feriados_edit'), 'admin/feriados/edit');
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        /* Load Data */
        $feriado = $this->feriado_model->get_by_id($id);

        /* Validate form input */
        $this->form_validation->set_rules('data', 'lang:feriados_data', 'required');
        $this->form_validation->set_rules('descricao', 'lang:feriados_descricao', 'required');

        if (isset($_POST) and !empty($_POST)) {
            if ($this->_valid_csrf_nonce() === false or $id != $this->input->post('id')) {
                show_error($this->lang->line('error_csrf'));
            }

            if ($this->form_validation->run() == true) {
                $data = array(
                    'data' => $this->input->post('data'),
                    'descricao' => $this->input->post('descricao'),
                    'especial' => $this->input->post('especial'),
                );

                if ($this->feriado_model->update($feriado->id, $data)) {
                    $this->session->set_flashdata('message', lang('feriados_edit_success'));
                } else {
                    $this->session->set_flashdata('message', lang('feriados_edit_error'));
                }
                redirect('admin/feriados', 'refresh');
            }
        }

        // display the edit user form
        $this->data['csrf'] = $this->_get_csrf_nonce();

        // set the flash data error message if there is one
        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

        // pass the valor plantao to the view
        $this->data['feriado'] = $feriado;

        $this->data['data'] = array(
            'name'  => 'data',
            'id'    => 'data',
            'type'  => 'date',
            'class' => 'form-control',
            'value' => $feriado->data,
        );
        $this->data['descricao'] = array(
            'name'  => 'descricao',
            'id'    => 'descricao',
            'type'  => 'text',
            'class' => 'form-control',
            'value' => $feriado->descricao,
        );
        $this->data['especial'] = array(
            'name'  => 'especial',
            'id'    => 'especial',
            'type'  => 'checkbox',
            'class' => 'form-control',
            'value' => $feriado->especial,
        );

        /* Load Template */
        $this->template->admin_render('admin/feriados/edit', $this->data);
    }

    public function view($id)
    {
        /* Breadcrumbs */
        $this->breadcrumbs->unshift(2, lang('menu_feriados'), 'admin/feriados/view');
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        /* Data */
        $id = (int) $id;

        $this->data['feriado'] = $this->feriado_model->get_by_id($id);

        /* Load Template */
        $this->template->admin_render('admin/feriados/view', $this->data);
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
