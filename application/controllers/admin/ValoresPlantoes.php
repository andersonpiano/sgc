<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ValoresPlantoes extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();

        /* Load :: Common */
        $this->load->model('cemerge/valorplantao_model');
        $this->load->model('cemerge/unidadehospitalar_model');
        $this->load->model('cemerge/setor_model');
        $this->lang->load('admin/valoresplantoes');

        /* Title Page */
        $this->page_title->push(lang('menu_valoresplantoes'));
        $this->data['pagetitle'] = $this->page_title->show();

        /* Breadcrumbs :: Common */
        $this->breadcrumbs->unshift(1, lang('menu_valoresplantoes'), 'admin/valoresplantoes');
    }

    public function index()
    {
        if (!$this->ion_auth->logged_in() OR ! $this->ion_auth->is_admin()) {
            redirect('auth/login', 'refresh');
        } else {
            /* Breadcrumbs */
            $this->data['breadcrumb'] = $this->breadcrumbs->show();

            /* Validate form input */
            $this->form_validation->set_rules('unidadehospitalar_id', 'lang:valoresplantoes_unidadehospitalar', 'required');
            $this->form_validation->set_rules('setor_id', 'lang:valoresplantoes_setor', 'required');

            if ($this->form_validation->run() == true) {
                $unidadehospitalar_id = $this->input->post('unidadehospitalar_id');
                $setor_id = $this->input->post('setor_id');

                $setores = $this->_get_setores($unidadehospitalar_id);
                
                /* Valores */
                $where = array(
                    'unidadehospitalar_id' => $unidadehospitalar_id,
                    'setor_id' => $setor_id,
                );
                $this->data['valoresplantoes'] = $this->valorplantao_model->get_where($where);
            } else {
                $this->data['valoresplantoes'] = array();
                $setores = array('' => 'Selecione um setor');
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
            $this->data['setor_id'] = array(
                'name'  => 'setor_id',
                'id'    => 'setor_id',
                'type'  => 'select',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('setor_id'),
                'options' => $setores,
            );

            /* Load Template */
            $this->template->admin_render('admin/valoresplantoes/index', $this->data);
        }
    }

    public function create()
    {
        /* Breadcrumbs */
        $this->breadcrumbs->unshift(2, lang('menu_valoresplantoes_create'), 'admin/valoresplantoes/create');
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        /* Variables */
        //$tables = $this->config->item('tables', 'ion_auth');

        /* Validate form input */
        $this->form_validation->set_rules('unidadehospitalar_id', 'lang:valoresplantoes_unidadehospitalar', 'required');
        $this->form_validation->set_rules('setor_id', 'lang:valoresplantoes_setor', 'required');
        $this->form_validation->set_rules('semanadia', 'lang:valoresplantoes_semanadia', 'required');
        $this->form_validation->set_rules('semananoite', 'lang:valoresplantoes_semananoite', 'required');
        $this->form_validation->set_rules('sextanoite', 'lang:valoresplantoes_sextanoite', 'required');
        $this->form_validation->set_rules('sabadodomingo', 'lang:valoresplantoes_sabadodomingo', 'required');
        $this->form_validation->set_rules('feriados', 'lang:valoresplantoes_feriados', 'required');
        $this->form_validation->set_rules('datasespeciais', 'lang:valoresplantoes_datasespeciais', 'required');

        if ($this->form_validation->run() == true) {
            $unidadehospitalar_id = $this->input->post('unidadehospitalar_id');
            $setor_id = $this->input->post('setor_id');
            $semanadia = $this->input->post('semanadia');
            $semananoite = $this->input->post('semananoite');
            $sextanoite = $this->input->post('sextanoite');
            $sabadodomingo = $this->input->post('sabadodomingo');
            $feriados = $this->input->post('feriados');
            $datasespeciais = $this->input->post('datasespeciais');

            $additional_data = array(
                'unidadehospitalar_id' => $unidadehospitalar_id,
                'setor_id' => $setor_id,
                'semanadia' => $semanadia,
                'semananoite' => $semananoite,
                'sextanoite' => $sextanoite,
                'sabadodomingo' => $sabadodomingo,
                'feriados' => $feriados,
                'datasespeciais' => $datasespeciais,
            );
        }

        // Realizar o insert no model de unidades hospitalares
        if ($this->form_validation->run() == true
            && $this->valorplantao_model->insert($additional_data)
        ) {
            $this->session->set_flashdata('message', lang('valoresplantoes_insert_success'));
            redirect('admin/valoresplantoes', 'refresh');
        } else {
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

            $unidadeshospitalares = $this->_get_unidadeshospitalares();

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
            //<input type="number" min="0.00" max="10000.00" step="0.01" />
            $this->data['semanadia'] = array(
                'name'  => 'semanadia',
                'id'    => 'semanadia',
                'type'  => 'number',
                'min'   => '0.00',
                'max'   => '10000.00',
                'step'   => '0.01',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('semanadia')
            );
            $this->data['semananoite'] = array(
                'name'  => 'semananoite',
                'id'    => 'semananoite',
                'type'  => 'number',
                'min'   => '0.00',
                'max'   => '10000.00',
                'step'   => '0.01',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('semananoite')
            );
            $this->data['sextanoite'] = array(
                'name'  => 'sextanoite',
                'id'    => 'sextanoite',
                'type'  => 'number',
                'min'   => '0.00',
                'max'   => '10000.00',
                'step'   => '0.01',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('sextanoite')
            );
            $this->data['sabadodomingo'] = array(
                'name'  => 'sabadodomingo',
                'id'    => 'sabadodomingo',
                'type'  => 'number',
                'min'   => '0.00',
                'max'   => '10000.00',
                'step'   => '0.01',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('sabadodomingo')
            );
            $this->data['feriados'] = array(
                'name'  => 'feriados',
                'id'    => 'feriados',
                'type'  => 'number',
                'min'   => '0.00',
                'max'   => '10000.00',
                'step'   => '0.01',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('feriados')
            );
            $this->data['datasespeciais'] = array(
                'name'  => 'datasespeciais',
                'id'    => 'datasespeciais',
                'type'  => 'number',
                'min'   => '0.00',
                'max'   => '10000.00',
                'step'   => '0.01',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('datasespeciais')
            );

            /* Load Template */
            $this->template->admin_render('admin/valoresplantoes/create', $this->data);
        }
    }

    public function edit($id)
    {
        $id = (int) $id;

        if (!$this->ion_auth->logged_in() or !$this->ion_auth->is_admin()) {
            redirect('auth', 'refresh');
        }

        /* Breadcrumbs */
        $this->breadcrumbs->unshift(2, lang('menu_valoresplantoes_edit'), 'admin/valoresplantoes/edit');
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        /* Load Data */
        $valorplantao = $this->valorplantao_model->get_by_id($id);

        /* Validate form input */
        $this->form_validation->set_rules('semanadia', 'lang:valoresplantoes_semanadia', 'required');
        $this->form_validation->set_rules('semananoite', 'lang:valoresplantoes_semananoite', 'required');
        $this->form_validation->set_rules('sextanoite', 'lang:valoresplantoes_sextanoite', 'required');
        $this->form_validation->set_rules('sabadodomingo', 'lang:valoresplantoes_sabadodomingo', 'required');
        $this->form_validation->set_rules('feriados', 'lang:valoresplantoes_feriados', 'required');
        $this->form_validation->set_rules('datasespeciais', 'lang:valoresplantoes_datasespeciais', 'required');

        if (isset($_POST) and !empty($_POST)) {
            if ($this->_valid_csrf_nonce() === false or $id != $this->input->post('id')) {
                show_error($this->lang->line('error_csrf'));
            }

            if ($this->form_validation->run() == true) {
                $data = array(
                    'semanadia' => $this->input->post('semanadia'),
                    'semananoite' => $this->input->post('semananoite'),
                    'sextanoite' => $this->input->post('sextanoite'),
                    'sabadodomingo' => $this->input->post('sabadodomingo'),
                    'feriados' => $this->input->post('feriados'),
                    'datasespeciais' => $this->input->post('datasespeciais'),
                );

                if ($this->valorplantao_model->update($valorplantao->id, $data)) {
                    $this->session->set_flashdata('message', lang('valoresplantoes_edit_success'));
                } else {
                    $this->session->set_flashdata('message', lang('valoresplantoes_edit_error'));
                }
                redirect('admin/valoresplantoes', 'refresh');
            }
        }

        // display the edit user form
        $this->data['csrf'] = $this->_get_csrf_nonce();

        // set the flash data error message if there is one
        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

        // pass the valor plantao to the view
        $this->data['valorplantao'] = $valorplantao;

        $this->data['unidadehospitalar_id'] = $valorplantao->unidadehospitalar_id;
        $this->data['setor_id'] = $valorplantao->setor_id;
        $this->data['semanadia'] = array(
            'name'  => 'semanadia',
            'id'    => 'semanadia',
            'type'  => 'number',
            'min'   => '0.00',
            'max'   => '10000.00',
            'step'   => '0.01',
            'class' => 'form-control',
            'value' => $valorplantao->semanadia
        );
        $this->data['semananoite'] = array(
            'name'  => 'semananoite',
            'id'    => 'semananoite',
            'type'  => 'number',
            'min'   => '0.00',
            'max'   => '10000.00',
            'step'   => '0.01',
            'class' => 'form-control',
            'value' => $valorplantao->semananoite
        );
        $this->data['sextanoite'] = array(
            'name'  => 'sextanoite',
            'id'    => 'sextanoite',
            'type'  => 'number',
            'min'   => '0.00',
            'max'   => '10000.00',
            'step'   => '0.01',
            'class' => 'form-control',
            'value' => $valorplantao->sextanoite
        );
        $this->data['sabadodomingo'] = array(
            'name'  => 'sabadodomingo',
            'id'    => 'sabadodomingo',
            'type'  => 'number',
            'min'   => '0.00',
            'max'   => '10000.00',
            'step'   => '0.01',
            'class' => 'form-control',
            'value' => $valorplantao->sabadodomingo
        );
        $this->data['feriados'] = array(
            'name'  => 'feriados',
            'id'    => 'feriados',
            'type'  => 'number',
            'min'   => '0.00',
            'max'   => '10000.00',
            'step'   => '0.01',
            'class' => 'form-control',
            'value' => $valorplantao->feriados
        );
        $this->data['datasespeciais'] = array(
            'name'  => 'datasespeciais',
            'id'    => 'datasespeciais',
            'type'  => 'number',
            'min'   => '0.00',
            'max'   => '10000.00',
            'step'   => '0.01',
            'class' => 'form-control',
            'value' => $valorplantao->datasespeciais
        );

        /* Load Template */
        $this->template->admin_render('admin/valoresplantoes/edit', $this->data);
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

    public function _get_setores($unidadehospitalar_id)
    {
        $setores_por_unidade = $this->setor_model->get_where(['unidadehospitalar_id' => $unidadehospitalar_id]);

        $setores = array(
            '' => 'Selecione um setor',
        );
        foreach ($setores_por_unidade as $setor) {
            $setores[$setor->id] = $setor->nome;
        }

        return $setores;
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
