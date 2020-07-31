<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Escalas extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();

        /* Load :: Common */
        $this->load->model('cemerge/escala_model');
        $this->load->model('cemerge/unidadehospitalar_model');
        $this->load->model('cemerge/setor_model');
        $this->load->model('cemerge/profissional_model');
        $this->lang->load('admin/escalas');

        /* Title Page */
        $this->page_title->push(lang('menu_escalas'));
        $this->data['pagetitle'] = $this->page_title->show();

        /* Breadcrumbs :: Common */
        $this->breadcrumbs->unshift(1, lang('menu_escalas'), 'admin/escalas');
    }


    public function index()
    {
        if (!$this->ion_auth->logged_in() OR ! $this->ion_auth->is_admin()) {
            redirect('auth/login', 'refresh');
        } else {
            /* Breadcrumbs */
            $this->data['breadcrumb'] = $this->breadcrumbs->show();

            /* Get all escalas */
            $where = array();
            $this->data['escalas'] = $this->escala_model->get_escalas_originais($where, null, 'dataplantao, horainicialplantao');
            
            $this->data['escalas_consolidadas'] = $this->escala_model->get_escalas_consolidadas($where, null, 'dataplantao, horainicialplantao');

            //TODO: $this->data['passagens_trocas'] = $this->escala_model->get_passagens_trocas($where, null, 'dataplantao, horainicialplantao');
            
            //var_dump($this->data['escalas_consolidadas'][11]); exit;

            /* Load Template */
            $this->template->admin_render('admin/escalas/index', $this->data);
        }
    }

    public function create()
    {
        /* Breadcrumbs */
        $this->breadcrumbs->unshift(2, lang('menu_escalas_create'), 'admin/escalas/create');
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        /* Variables */
        $tables = $this->config->item('tables', 'ion_auth');

        /* Validate form input */
        $this->form_validation->set_rules('unidadehospitalar_id', 'lang:escalas_unidadehospitalar', 'required');
        $this->form_validation->set_rules('setor_id', 'lang:escalas_setor', 'required');
        $this->form_validation->set_rules('datainicialplantao', 'lang:escalas_datainicialplantao', 'required');
        $this->form_validation->set_rules('datafinalplantao', 'lang:escalas_datafinalplantao', 'required');
        $this->form_validation->set_rules('horainicialplantao', 'lang:escalas_horainicialplantao', 'required');
        $this->form_validation->set_rules('horafinalplantao', 'lang:escalas_horafinalplantao', 'required');

        if ($this->form_validation->run() == true) {
            $unidadehospitalar_id = $this->input->post('unidadehospitalar_id');
            $setor_id = $this->input->post('setor_id');
            $datainicialplantao = $this->input->post('datainicialplantao');
            $datafinalplantao = $this->input->post('datafinalplantao');
            $horainicialplantao = $this->input->post('horainicialplantao');
            $horafinalplantao = $this->input->post('horafinalplantao');
            //$active = $this->input->post('active');

            $additional_data = array(
                'unidadehospitalar_id' => $this->input->post('unidadehospitalar_id'),
                'setor_id' => $this->input->post('setor_id'),
                'datainicialplantao' => $this->input->post('datainicialplantao'),
                'datafinalplantao' => $this->input->post('datafinalplantao'),
                'horainicialplantao' => $this->input->post('horainicialplantao'),
                'horafinalplantao' => $this->input->post('horafinalplantao')
            );
        }

        // Realizar o insert no model
        if ($this->form_validation->run() == true) {
            $success = false;

            $datainicial = new DateTime($additional_data['datainicialplantao']);
            $datafinal = new DateTime($additional_data['datafinalplantao']);

            // Loop para inserir no período
            for ($i = $datainicial; $i <= $datafinal; $i->modify('+1 day')) {
                $hrinicialplantao = $additional_data['horainicialplantao'];
                $hrfinalplantao = $additional_data['horafinalplantao'];
                $dtinicialplantao = $i->format("Y-m-d");
                $dtfinalplantao = $dtinicialplantao;
                if ((int)$hrinicialplantao > (int)$hrfinalplantao) {
                    $dtfinalplantao = $i->modify('+1 day')->format("Y-m-d");
                }
                $insert_data = array(
                    'setor_id' => $additional_data['setor_id'],
                    'dataplantao' => $dtinicialplantao,
                    'datafinalplantao' => $dtfinalplantao,
                    'horainicialplantao' => $hrinicialplantao,
                    'horafinalplantao' => $hrfinalplantao
                );
                $success = $this->escala_model->insert($insert_data);
            }

            if ($success) {
                $this->session->set_flashdata('message', $this->ion_auth->messages());
                redirect('admin/escalas', 'refresh');
            } else {
                $this->session->set_flashdata('message', $this->ion_auth->errors());
                redirect('admin/escalas', 'refresh');
            }
        } else {
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

            $unidadeshospitalares = $this->_get_unidadeshospitalares();

            $this->data['datainicialplantao'] = array(
                'name'  => 'datainicialplantao',
                'id'    => 'datainicialplantao',
                'type'  => 'date',
                'class' => 'form-control',
                'value' => date('Y') . "-" . date('m', strtotime("next month")) . "-01",
            );
            $this->data['datafinalplantao'] = array(
                'name'  => 'datafinalplantao',
                'id'    => 'datafinalplantao',
                'type'  => 'date',
                'class' => 'form-control',
                'value' => date('Y') . "-" . date('m-t', strtotime("next month")),
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
            );

            /* Load Template */
            $this->template->admin_render('admin/escalas/create', $this->data);
        }
    }

    public function edit($id)
    {
        $id = (int) $id;

        if (!$this->ion_auth->logged_in() or !$this->ion_auth->is_admin()) {
            redirect('auth', 'refresh');
        }

        /* Breadcrumbs */
        $this->breadcrumbs->unshift(2, lang('menu_escalas_edit'), 'admin/escalas/edit');
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        /* Load Data */
        $escala = $this->escala_model->get_by_id($id);
        //$groups        = $this->ion_auth->groups()->result_array();
        //$currentGroups = $this->ion_auth->get_users_groups($id)->result();

        /* Validate form input */
        $this->form_validation->set_rules('dataplantao', 'lang:escalas_dataplantao', 'required');
        $this->form_validation->set_rules('horainicialplantao', 'lang:escalas_horainicialplantao', 'required');
        $this->form_validation->set_rules('horafinalplantao', 'lang:escalas_horafinalplantao', 'required');
        //$this->form_validation->set_rules('unidade_hospitalar', 'lang:escalas_unidade_hospitalar', 'required');
        //$this->form_validation->set_rules('nomefantasia', 'lang:escalas_nomefantasia', 'required');
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

                if ($this->escala_model->update($escala->id, $data)) {
                    $this->session->set_flashdata('message', $this->ion_auth->messages());

                    if ($this->ion_auth->is_admin()) {
                        redirect('admin/escalas', 'refresh');
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

        // pass the escala to the view
        $this->data['escala'] = $escala;

        $this->data['dataplantao'] = array(
            'name'  => 'dataplantao',
            'id'    => 'dataplantao',
            'type'  => 'date',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('dataplantao', $escala->dataplantao)
        );
        $this->data['horainicialplantao'] = array(
            'name'  => 'horainicialplantao',
            'id'    => 'horainicialplantao',
            'type'  => 'time',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('horainicialplantao', $escala->horainicialplantao)
        );
        $this->data['horafinalplantao'] = array(
            'name'  => 'horafinalplantao',
            'id'    => 'horafinalplantao',
            'type'  => 'time',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('horafinalplantao', $escala->horafinalplantao)
        );
        /*
        $this->data['active'] = array(
            'name'  => 'active',
            'id'    => 'active',
            'type'  => 'checkbox',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('active', $escala->active)
        );
        */

        /* Load Template */
        $this->template->admin_render('admin/escalas/edit', $this->data);
    }

    public function view($id)
    {
        /* Breadcrumbs */
        $this->breadcrumbs->unshift(2, lang('menu_escalas'), 'admin/escalas/view');
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        /* Data */
        $id = (int) $id;

        $this->data['escala'] = $this->escala_model->get_by_id($id);
        /*
        // escalas
        foreach ($this->data['user_info'] as $k => $user)
        {
            $this->data['user_info'][$k]->groups = $this->ion_auth->get_users_groups($user->id)->result();
        }
        */

        /* Load Template */
        $this->template->admin_render('admin/escalas/view', $this->data);
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
        if ($this->input->post($this->session->flashdata('csrfkey')) !== false &&
            $this->input->post($this->session->flashdata('csrfkey')) == $this->session->flashdata('csrfvalue')) {
            return true;
        } else {
            return false;
        }
    }

    public function _get_unidadeshospitalares()
    {
        $unidades = $this->unidadehospitalar_model->get_all();

        $unidadeshospitalares = array();
        foreach ($unidades as $unidade) {
            $unidadeshospitalares[$unidade->id] = $unidade->razaosocial;
        }

        return $unidadeshospitalares;
    }

    public function setores($id)
    {
        $id = (int) $id;

        $setores = $this->setor_model->get_where(['unidadehospitalar_id' => $id]);

        echo json_encode($setores);
        exit;
    }
}
