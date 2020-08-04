<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Plantoes extends Admin_Controller {
    private $_permitted_groups = array('admin', 'profissionais');

    private $_profissional = null;

    const TIPO_PASSAGEM_CESSAO = 0;
    const TIPO_PASSAGEM_TROCA = 1;

    const STATUS_PASSAGEM_ACONFIRMAR = 0;
    const STATUS_PASSAGEM_CONFIRMADA = 1;
    const STATUS_PASSAGEM_TROCA_PROPOSTA = 2;

    public function __construct()
    {
        parent::__construct($this->_permitted_groups);

        /* Load :: Common */
        $this->load->model('cemerge/escala_model');
        $this->load->model('cemerge/passagemtroca_model');
        $this->load->model('cemerge/profissional_model');
        $this->load->model('cemerge/usuarioprofissional_model');
        $this->load->model('cemerge/unidadehospitalar_model');
        $this->load->model('cemerge/setor_model');
        $this->lang->load('admin/plantoes');

        /* Profissional */
        $userId = $this->ion_auth->user()->row()->id;
        if ($this->ion_auth->in_group('profissionais') && $userId) {
            $usuarioProfissional = $this->usuarioprofissional_model->get_where(['user_id' => $userId]);
            if ($usuarioProfissional) {
                $this->_profissional = $this->profissional_model->get_where(['id' => $usuarioProfissional[0]->profissional_id])[0];
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
            if ($this->ion_auth->in_group($this->_permitted_groups)) {
                $this->data['meus_plantoes'] = $this->escala_model->get_escalas_consolidadas_por_profissional($this->_profissional->id);
                $this->data['recebidos'] = $this->escala_model->get_plantoes_recebidos_por_profissional($this->_profissional->id);
                $this->data['passagens'] = $this->escala_model->get_passagens_de_plantao_por_profissional($this->_profissional->id);
            } else {
                $this->data['plantoes'] = array();
                $this->data['recebidos'] = array();
                $this->data['passagens'] = array();
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
        // Removendo o profissional logado
        unset($profissionais_setor[$plantao->profissional_id]);

        /* Validate form input */
        $this->form_validation->set_rules('tipopassagem', 'lang:plantoes_tipopassagem', 'required');
        $this->form_validation->set_rules('profissionalsubstituto_id', 'lang:plantoes_profissional_substituto', 'required');

        if (isset($_POST) && ! empty($_POST)) {
            if ($this->_valid_csrf_nonce() === false or $id != $this->input->post('id')) {
                show_error($this->lang->line('error_csrf'));
            }

            if ($this->form_validation->run() == true) {
                // escala_id, profissional_id, 
                $data = array(
                    'escala_id' => $plantao->id,
                    'profissional_id' => $plantao->profissional_id,
                    'tipopassagem' => $this->input->post('tipopassagem'),
                    'profissionalsubstituto_id' => $this->input->post('profissionalsubstituto_id'),
                    'datahorapassagem' => date('Y-m-d H:i:s'),
                    'statuspassagem' => 0,
                );

                if ($this->passagemtroca_model->insert($data)) {
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

    /**
     * Realiza uma proposta à uma troca de plantão
     */
    public function propose($id)
    {
        $id = (int) $id;

        $now = date('Y-m-d H:i:s');

        if (!$this->ion_auth->logged_in() or !$this->ion_auth->in_group($this->_permitted_groups)) {
            redirect('auth', 'refresh');
        }

        /* Breadcrumbs */
        $this->breadcrumbs->unshift(2, lang('menu_plantoes_propose'), 'admin/plantoes/propose');
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        /* Load Data */
        $plantao = $this->escala_model->get_escala_troca($id);
        $this->data['tipospassagem'] = $this->_get_tipos_passagem();
        $this->data['statuspassagem'] = $this->_get_status_passagem();

        // Plantões disponíveis do profissional no mesmo setor
        $plantoes = $this->escala_model->get_escalas_consolidadas_por_profissional(
            $plantao->passagenstrocas_profissionalsubstituto_id,
            $plantao->setor_id
        );
        $plantoes_profissional = $this->_get_plantoes_profissional($plantoes);
        if (sizeof($plantoes_profissional) <= 0) {
            $this->session->set_flashdata('message', lang('plantoes_sem_plantoes_disponiveis'));
            redirect('admin/plantoes', 'refresh');
        }

        /* Validate form input */
        $this->form_validation->set_rules('escalatroca_id', 'lang:plantoes_escalatroca', 'required');

        if (isset($_POST) && !empty($_POST)) {
            if ($this->_valid_csrf_nonce() === false or $id != $this->input->post('id')) {
                show_error($this->lang->line('error_csrf'));
            }

            /* Dados da proposta de troca */
            $data_proposta = array(
                'statuspassagem' => 2, // Aguardando aceite
                'datahoraproposta' => $now,
                'escalatroca_id' => $this->input->post('escalatroca_id'),
            );

            if ($this->form_validation->run() == true
                && $this->passagemtroca_model->update($plantao->passagenstrocas_id, $data_proposta)
            ) {
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
        $this->data['escalatroca_id'] = array(
            'name'  => 'escalatroca_id',
            'id'    => 'escalatroca_id',
            'type'  => 'select',
            'class' => 'form-control',
            'options' => $plantoes_profissional
        );

        /* Load Template */
        $this->template->admin_render('admin/plantoes/propose', $this->data);
    }

    public function acceptproposal($id)
    {
        $id = (int) $id;

        if (!$this->ion_auth->logged_in() or !$this->ion_auth->in_group($this->_permitted_groups)) {
            redirect('auth', 'refresh');
        }

        /* Breadcrumbs */
        $this->breadcrumbs->unshift(2, lang('menu_plantoes_accept'), 'admin/plantoes/acceptproposal');
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        /* Load Data */
        $plantao = $this->escala_model->get_escala_troca_a_confirmar($id);
        $this->data['tipospassagem'] = $this->_get_tipos_passagem();
        $this->data['statuspassagem'] = $this->_get_status_passagem();

        if (isset($_POST) && ! empty($_POST)) {
            if ($this->_valid_csrf_nonce() === false or $id != $this->input->post('id')) {
                show_error($this->lang->line('error_csrf'));
            }

            // Dados do plantão de troca a serem atualizados
            $data_plantao_troca = array();

            $now = date('Y-m-d H:i:s');

            /* Confirmando a troca*/
            $data = array(
                'datahoraconfirmacao' => $now,
                'statuspassagem' => 1
            );

            // Adiciona dados do plantão de troca
            $data_plantao_troca = array(
                'escala_id' => $plantao->escala_troca_id,
                'profissional_id' => $plantao->passagenstrocas_profissionalsubstituto_id,
                'profissionalsubstituto_id' => $plantao->profissional_id,
                'tipopassagem' => $this::TIPO_PASSAGEM_TROCA,
                'datahorapassagem' => $now,
                'statuspassagem' => $this::STATUS_PASSAGEM_CONFIRMADA,
                'datahoraconfirmacao' => $now,
                'escalatroca_id' => $plantao->id,
            );

            if ($this->passagemtroca_model->update($plantao->passagenstrocas_id, $data)
                && $this->passagemtroca_model->insert($data_plantao_troca)
            ) {
                $this->session->set_flashdata('message', $this->ion_auth->messages());

                if ($this->ion_auth->in_group($this->_permitted_groups)) {
                    redirect('admin/plantoes', 'refresh');
                } else {
                    redirect('admin', 'refresh');
                }
            } else {
                $this->session->set_flashdata('message', 'Ocorreu um erro ao confirmar a troca de plantão.');

                if ($this->ion_auth->in_group($this->_permitted_groups)) {
                    redirect('admin/plantoes', 'refresh');
                } else {
                    redirect('admin', 'refresh');
                }
            }
        }

        // display the edit user form
        $this->data['csrf'] = $this->_get_csrf_nonce();

        // set the flash data error message if there is one
        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

        // pass the plantao to the view
        $this->data['plantao'] = $plantao;

        /* Load Template */
        $this->template->admin_render('admin/plantoes/accept', $this->data);
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

        exit;

        if (isset($_POST) && ! empty($_POST)) {
            if ($this->_valid_csrf_nonce() === false or $id != $this->input->post('id')) {
                show_error($this->lang->line('error_csrf'));
            }

            $now = date('Y-m-d H:i:s');

            $data = array(
                'datahoraconfirmacao' => $now,
                'statuspassagem' => 1
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
        // Passagem por troca
        if ($plantao->tipopassagem == 1) {
            $this->data['escalatroca_id'] = array(
                'name'  => 'escalatroca_id',
                'id'    => 'escalatroca_id',
                'type'  => 'select',
                'class' => 'form-control',
                'options' => $plantoes_profissional
            );
        }

        /* Load Template */
        $this->template->admin_render('admin/plantoes/confirm', $this->data);
    }

    public function view($id)
    {
        /* Breadcrumbs */
        $this->breadcrumbs->unshift(2, lang('menu_plantoes'), 'admin/plantoes/view');
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        /* Data */
        $id = (int) $id;

        $this->data['plantao'] = $this->escala_model->get_escala(['id' => $id]);

        /* Load Template */
        $this->template->admin_render('admin/plantoes/view', $this->data);
    }

    public function _get_plantoes_profissional($plantoes)
    {
        //var_dump($plantoes); exit;
        $plantoes_profissional = array();
        foreach ($plantoes as $plantao) {
            $desc_plantao = date('d/m/Y', strtotime($plantao->dataplantao)) . ' - ' .
                            $plantao->horainicialplantao . ' - ' .
                            $plantao->horafinalplantao . ' - ' .
                            $plantao->unidadehospitalar_razaosocial . ' - ' .
                            $plantao->setor_nome;
            $plantoes_profissional[$plantao->id] = $desc_plantao;
        }

        return $plantoes_profissional;
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
            '2' => 'Troca proposta',
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






    public function _____create()
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

    public function _____edit($id)
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
}
