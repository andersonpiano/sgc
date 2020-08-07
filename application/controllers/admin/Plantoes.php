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

    const ACAO_NOTIFICACAO_CESSAO_PLANTAO = 0;
    const ACAO_NOTIFICACAO_TROCA_PLANTAO = 1;
    const ACAO_NOTIFICACAO_OPORTUNIDADE = 2;
    const ACAO_NOTIFICACAO_CONFIRMACAO_CESSAO = 3;
    const ACAO_NOTIFICACAO_PROPOSTA_TROCA = 4;
    const ACAO_NOTIFICACAO_ACEITE_PROPOSTA = 5;

    const TIPO_NOTIFICACAO_EMAIL = 0;

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
        $this->lang->load('admin/escalas');

        $this->load->library('email');

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

            /* Validate form input */
            $this->form_validation->set_rules('unidadehospitalar_id', 'lang:escalas_unidadehospitalar', 'required');
            $this->form_validation->set_rules('setor_id', 'lang:escalas_setor', 'required');
            $this->form_validation->set_rules('datainicial', 'lang:escalas_datainicialplantao', 'required');
            $this->form_validation->set_rules('datafinal', 'lang:escalas_datafinalplantao', 'required');
            $this->form_validation->set_rules('tipoescala', 'lang:escalas_tipoescala', 'required');
            $this->form_validation->set_rules('tipovisualizacao', 'lang:escalas_tipovisualizacao', 'required');

            if ($this->form_validation->run() == true) {
                $unidadehospitalar_id = $this->input->post('unidadehospitalar_id');
                $setor_id = $this->input->post('setor_id');
                $datainicial = $this->input->post('datainicial');
                $datafinal = $this->input->post('datafinal');
                $tipoescala = $this->input->post('tipoescala');
                $tipovisualizacao = $this->input->post('tipovisualizacao');

                $setores = $this->_get_setores($unidadehospitalar_id);

                // Realizando a busca
                $where = array(
                    'unidadehospitalar_id' => $unidadehospitalar_id,
                    'escalas.setor_id' => $setor_id,
                    'escalas.dataplantao >=' => $datainicial,
                    'escalas.dataplantao <=' => $datafinal,
                );

                // Tipos e status de passagem para exibição
                $this->data['tipospassagem'] = $this->_get_tipos_passagem();
                $this->data['statuspassagem'] = $this->_get_status_passagem();

                /* Get all data */
                // Tratar o tipo visualizacao calendário para não executar busca no banco desnecessariamente
                if ($this->ion_auth->in_group($this->_permitted_groups)) {
                    switch ($tipoescala) {
                    case 0: // Minha escala consolidada
                        $this->data['meus_plantoes'] = $this->escala_model->get_escalas_consolidadas_por_profissional($this->_profissional->id);
                        break;
                    case 1: // Minhas trocas e passagens
                        //$this->data['escalas'] = $this->escala_model->get_passagens_trocas($where, null, 'dataplantao, horainicialplantao');
                        $this->data['recebidos'] = $this->escala_model->get_plantoes_recebidos_por_profissional($this->_profissional->id);
                        $this->data['passagens'] = $this->escala_model->get_passagens_de_plantao_por_profissional($this->_profissional->id);
                        break;
                    case 2: // Consolidada do setor
                        $this->data['escalas'] = $this->escala_model->get_escalas_consolidadas($where, null, 'dataplantao, horainicialplantao');
                        break;
                    case 3:
                        // Original do setor
                        $this->data['escalas'] = $this->escala_model->get_escalas_originais($where, null, 'dataplantao, horainicialplantao');
                        break;
                    default:
                        break;

                    }
                } else {
                    $this->data['meus_plantoes'] = array();
                    $this->data['recebidos'] = array();
                    $this->data['passagens'] = array();
                    $this->data['escalas'] = array();
                }
            } else {
                $datainicial = date('Y') . "-" . date('m', strtotime("next month")) . "-01";
                $datafinal = date('Y') . "-" . date('m-t', strtotime("next month"));
                $setores = array('' => 'Selecione um setor');
                $this->data['escalas'] = array();
                $this->data['meus_plantoes'] = array();
                $this->data['recebidos'] = array();
                $this->data['passagens'] = array();
                $this->data['tipovisualizacao'] = 0;
            }

            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

            $unidadeshospitalares = $this->_get_unidadeshospitalares();

            $tiposescala = $this->_get_tipos_escala();
            $tiposvisualizacao = $this->_get_tipos_visualizacao();

            $this->data['datainicial'] = array(
                'name'  => 'datainicial',
                'id'    => 'datainicial',
                'type'  => 'date',
                'class' => 'form-control',
                'value' => $datainicial,
            );
            $this->data['datafinal'] = array(
                'name'  => 'datafinal',
                'id'    => 'datafinal',
                'type'  => 'date',
                'class' => 'form-control',
                'value' => $datafinal,
            );
            $this->data['tipoescala'] = array(
                'name'  => 'tipoescala',
                'id'    => 'tipoescala',
                'type'  => 'select',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('tipoescala'),
                'options' => $tiposescala,
            );
            $this->data['tipovisualizacao'] = array(
                'name'  => 'tipovisualizacao',
                'id'    => 'tipovisualizacao',
                'type'  => 'select',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('tipovisualizacao'),
                'options' => $tiposvisualizacao,
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
                'options' => $setores,
            );
            $this->data['profissional_id'] = array(
                'name'  => 'profissional_id',
                'id'    => 'profissional_id',
                'type'  => 'hidden',
                'value' => $this->_profissional->id
            );

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
        $plantao = $this->escala_model->get_escala_by_id($id);
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
                // No caso de plantão já passado por outro profissional
                $profissional_passagem_id = $plantao->profissional_id;
                if (isset($plantao->passagenstrocas_profissionalsubstituto_id)) {
                    $profissional_passagem_id = $plantao->passagenstrocas_profissionalsubstituto_id;
                }
                $data = array(
                    'escala_id' => $plantao->id,
                    'profissional_id' => $profissional_passagem_id,
                    'tipopassagem' => $this->input->post('tipopassagem'),
                    'profissionalsubstituto_id' => $this->input->post('profissionalsubstituto_id'),
                    'datahorapassagem' => date('Y-m-d H:i:s'),
                    'statuspassagem' => 0,
                );

                if ($this->passagemtroca_model->insert($data)) {
                    //$this->session->set_flashdata('message', $this->ion_auth->messages());
                    /* Send notifications */
                    $notification_send = $this->_sendNotifications(
                        $plantao, $this::TIPO_NOTIFICACAO_EMAIL, $this::ACAO_NOTIFICACAO_CESSAO_PLANTAO
                    );
                    if ($notification_send) {
                        $this->session->set_flashdata('message', 'E-mail enviado com sucesso.');
                    } else {
                        $this->session->set_flashdata('message', 'Ocorreu um erro ao enviar o e-mail.');
                    }

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
                //$this->session->set_flashdata('message', $this->ion_auth->messages());

                /* Send notifications */
                $notification_send = $this->_sendNotifications(
                    $plantao, $this::TIPO_NOTIFICACAO_EMAIL, $this::ACAO_NOTIFICACAO_PROPOSTA_TROCA
                );
                if ($notification_send) {
                    $this->session->set_flashdata('message', 'E-mail enviado com sucesso.');
                } else {
                    $this->session->set_flashdata('message', 'Ocorreu um erro ao enviar o e-mail.');
                }

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
                //$this->session->set_flashdata('message', $this->ion_auth->messages());

                /* Send notifications */
                $notification_send = $this->_sendNotifications(
                    $plantao, $this::TIPO_NOTIFICACAO_EMAIL, $this::ACAO_NOTIFICACAO_ACEITE_PROPOSTA
                );
                if ($notification_send) {
                    $this->session->set_flashdata('message', 'E-mail enviado com sucesso.');
                } else {
                    $this->session->set_flashdata('message', 'Ocorreu um erro ao enviar o e-mail.');
                }

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
        $plantao = $this->escala_model->get_escala_passada_a_confirmar($id);
        $this->data['tipospassagem'] = $this->_get_tipos_passagem();
        $this->data['statuspassagem'] = $this->_get_status_passagem();

        if (isset($_POST) && ! empty($_POST)) {
            if ($this->_valid_csrf_nonce() === false or $id != $this->input->post('id')) {
                show_error($this->lang->line('error_csrf'));
            }

            $now = date('Y-m-d H:i:s');

            $data = array(
                'datahoraconfirmacao' => $now,
                'statuspassagem' => 1
            );

            if ($this->passagemtroca_model->update($plantao->passagenstrocas_id, $data)) {
                $this->session->set_flashdata('message', $this->ion_auth->messages());

                /* Send notifications */
                $notification_send = $this->_sendNotifications(
                    $plantao, $this::TIPO_NOTIFICACAO_EMAIL, $this::ACAO_NOTIFICACAO_CONFIRMACAO_CESSAO
                );
                if ($notification_send) {
                    $this->session->set_flashdata('message', 'E-mail enviado com sucesso.');
                } else {
                    $this->session->set_flashdata('message', 'Ocorreu um erro ao enviar o e-mail.');
                }

                if ($this->ion_auth->in_group($this->_permitted_groups)) {
                    redirect('admin/plantoes', 'refresh');
                } else {
                    redirect('admin', 'refresh');
                }
            } else {
                $this->session->set_flashdata('message', $this->ion_auth->errors());

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

    public function _get_tipos_escala()
    {
        $tipos_escala = array(
            '0' => 'Minha escala consolidada',
            '1' => 'Minhas trocas e passagens',
            '2' => 'Consolidada do setor',
        );

        return $tipos_escala;
    }

    public function _get_tipos_visualizacao()
    {
        $tipos_visualizacao = array(
            '0' => 'Lista',
            '1' => 'Calendário',
        );

        return $tipos_visualizacao;
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

    public function setores($id)
    {
        $id = (int) $id;

        $setores = $this->setor_model->get_where(['unidadehospitalar_id' => $id]);

        echo json_encode($setores);
        exit;
    }

    public function _sendNotifications($plantao, $tipo_notificacao, $acao_notificacao)
    {
        /* Initialize email */
        $ci_mail_config = $this->config->item('mail');
        $this->email->initialize($ci_mail_config);

        $subject = 'CEMERGE - Notificação';

        if ($acao_notificacao == $this::ACAO_NOTIFICACAO_CONFIRMACAO_CESSAO) {
            $data = array(
                'profissional_passagem_nome' => $plantao->profissional_passagem_nome,
                'profissional_substituto_nome' => $plantao->profissional_substituto_nome,
                'dataplantao' => $plantao->dataplantao,
                'horainicialplantao' => $plantao->horainicialplantao,
                'horafinalplantao' => $plantao->horafinalplantao,
                'unidadehospitalar' => $plantao->unidadehospitalar_razaosocial,
                'setor' => $plantao->setor_nome,
            );
            $message = $this->load->view(
                'admin/_templates/email/confirmacao_cessao.tpl.php', $data, true
            );
            $destinatario = $plantao->profissional_passagem_email;
        }

        if ($acao_notificacao == $this::ACAO_NOTIFICACAO_CESSAO_PLANTAO) {
            $data = array(
                'profissional_passagem_nome' => $plantao->profissional_passagem_nome,
                'profissional_substituto_nome' => $plantao->profissional_substituto_nome,
                'dataplantao' => $plantao->dataplantao,
                'horainicialplantao' => $plantao->horainicialplantao,
                'horafinalplantao' => $plantao->horafinalplantao,
                'unidadehospitalar' => $plantao->unidadehospitalar_razaosocial,
                'setor' => $plantao->setor_nome,
            );
            $message = $this->load->view(
                'admin/_templates/email/aviso_cessao.tpl.php', $data, true
            );
            $destinatario = $plantao->profissional_substituto_email;
        }

        if ($acao_notificacao == $this::ACAO_NOTIFICACAO_ACEITE_PROPOSTA) {
            $data = array(
                'profissional_passagem_nome' => $plantao->profissional_passagem_nome,
                'profissional_substituto_nome' => $plantao->profissional_substituto_nome,
                'dataplantao' => $plantao->dataplantao,
                'horainicialplantao' => $plantao->horainicialplantao,
                'horafinalplantao' => $plantao->horafinalplantao,
                'unidadehospitalar' => $plantao->unidadehospitalar_razaosocial,
                'setor' => $plantao->setor_nome,
            );
            $message = $this->load->view(
                'admin/_templates/email/aceite_proposta.tpl.php', $data, true
            );
            $destinatario = $plantao->profissional_substituto_email;
        }

        if ($acao_notificacao == $this::ACAO_NOTIFICACAO_PROPOSTA_TROCA) {
            $data = array(
                'profissional_passagem_nome' => $plantao->profissional_passagem_nome,
                'profissional_substituto_nome' => $plantao->profissional_substituto_nome,
                'dataplantao' => $plantao->dataplantao,
                'horainicialplantao' => $plantao->horainicialplantao,
                'horafinalplantao' => $plantao->horafinalplantao,
                'unidadehospitalar' => $plantao->unidadehospitalar_razaosocial,
                'setor' => $plantao->setor_nome,
            );
            $message = $this->load->view(
                'admin/_templates/email/proposta_troca.tpl.php', $data, true
            );
            $destinatario = $plantao->profissional_passagem_nome;
        }

        if ($tipo_notificacao == $this::TIPO_NOTIFICACAO_EMAIL) {
            $this->email->clear();
            $this->email->from(
                $this->config->item('admin_email', 'ion_auth'),
                $this->config->item('site_title', 'ion_auth')
            );
            $this->email->to($destinatario);
            $this->email->subject($subject);
            $this->email->message($message);
            $email_enviado = $this->email->send();

            return $email_enviado;
        }
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

    /**
     * Busca os plantões para compor o calendário
     */
    public function plantoespormes($mes)
    {
        $mes = (int)$mes;

        $plantoes = $this->escala_model->get_escalas_consolidadas_calendario($mes, $this->mobile_detect->isMobile());

        echo(json_encode($plantoes));
        exit;
    }

    /**
     * Busca os plantões para compor o calendário
     */
    public function minhaescalaconsolidada()
    {
        $mes = (int)$this->uri->segment(5, 0);
        $setor = (int)$this->uri->segment(7, 0);
        $profissional = (int)$this->uri->segment(9, 0);

        $plantoes = array();

        if ($mes != 0 and $setor != 0 and $profissional != 0) {
            $plantoes = $this->escala_model->get_minha_escala_consolidada_calendario(
                $mes, $setor, $profissional, $this->mobile_detect->isMobile()
            );
        }

        echo(json_encode($plantoes));
        exit;
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
