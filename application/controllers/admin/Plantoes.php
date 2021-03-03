<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Plantoes extends Admin_Controller
{
    private $_permitted_groups = array('admin', 'profissionais', 'coordenadorplantao');

    private $_diasdasemana = array('Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado');

    private $_profissional = null;

    const TIPO_PASSAGEM_CESSAO = 0;
    const TIPO_PASSAGEM_TROCA = 1;

    const STATUS_PASSAGEM_ACONFIRMAR = 0;
    const STATUS_PASSAGEM_CONFIRMADA = 1;
    const STATUS_PASSAGEM_TROCA_PROPOSTA = 2;
    const STATUS_PASSAGEM_REPASSADO = 3;

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
        $this->load->model('cemerge/frequenciaassessus_model');
        $this->load->model('cemerge/profissional_model');
        $this->load->model('cemerge/usuarioprofissional_model');
        $this->load->model('cemerge/unidadehospitalar_model');
        $this->load->model('cemerge/setor_model');

        $this->lang->load('admin/plantoes');
        $this->lang->load('admin/escalas');

        $this->load->library('email');

        $this->load->helper('messages');

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
        if (!$this->ion_auth->logged_in()) {
            $this->session->set_flashdata('message', 'Você deve estar autenticado para utilizar esta funcionalidade.');
            redirect('auth/login', 'refresh');
        }
        if (!$this->ion_auth->in_group($this->_permitted_groups)) {
            $this->session->set_flashdata('message', 'O acesso &agrave; este recurso não é permitido ao seu perfil de usuário.');
            redirect('admin/dashboard', 'refresh');
        }

        /* Breadcrumbs */
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        $this->data = array_merge($this->data, $this->checkUserType());

        /* Validate form input */
        $this->form_validation->set_rules('unidadehospitalar_id', 'lang:escalas_unidadehospitalar', 'required');
        $this->form_validation->set_rules('setor_id', 'lang:escalas_setor', 'required');
        $this->form_validation->set_rules('datainicial', 'lang:escalas_datainicialplantao', 'required');
        $this->form_validation->set_rules('datafinal', 'lang:escalas_datafinalplantao', 'required');
        $this->form_validation->set_rules('tipoescala', 'lang:escalas_tipoescala', 'required');
        $this->form_validation->set_rules('tipovisualizacao', 'lang:escalas_tipovisualizacao', 'required');

        if (isset($_POST) && !empty($_POST)) {
            if ($this->form_validation->run() == true) {
                $unidadehospitalar_id = $this->input->post('unidadehospitalar_id');
                $setor_id = $this->input->post('setor_id');
                $datainicial = $this->input->post('datainicial');
                $datafinal = $this->input->post('datafinal');
                $tipoescala = $this->input->post('tipoescala');
                $tipovisualizacao = $this->input->post('tipovisualizacao');

                if ($this->data['user_type'] == 0) {
                    $setores = $this->_get_setores($unidadehospitalar_id, $this->_profissional, null);
                } elseif ($this->data['user_type'] == 1) {
                    $setores = $this->_get_setores($unidadehospitalar_id, null, $this->data['user_id']);
                } elseif ($this->data['user_type'] == 2) {
                    $setores = $this->_get_setores($unidadehospitalar_id, null, null);
                } else {
                    $setores = array();
                }

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

                // Lista
                if ($tipovisualizacao == 0) {
                    switch ($tipoescala) {
                    case 0: // Minha escala consolidada
                        $this->data['meus_plantoes'] = $this->escala_model->get_escalas_frequencias_plantao($unidadehospitalar_id, $setor_id, $datainicial, $datafinal, $this->_profissional->id);
                        /*get_escalas_consolidadas_por_profissional(
                            $this->_profissional->id, $datainicial, $datafinal, $setor_id
                        );*/
                        break;
                    case 1: // Minhas trocas e passagens
                        //$this->data['escalas'] = $this->escala_model->get_passagens_trocas($where, null, 'dataplantao, horainicialplantao');
                        $this->data['recebidos'] = $this->escala_model->get_plantoes_recebidos_por_profissional($this->_profissional->id, $datainicial, $datafinal, $setor_id);
                        $this->data['passagens'] = $this->escala_model->get_passagens_de_plantao_por_profissional($this->_profissional->id, $datainicial, $datafinal, $setor_id);
                        break;
                    case 2: // Consolidada do setor para profissional
                        $this->data['escalas'] = $this->escala_model->get_escalas_consolidadas($where, null, 'dataplantao, horainicialplantao');                
                        break;
                    case 3:
                        // Original do setor
                        $this->data['escalas'] = $this->escala_model->get_escalas_originais($where, null, 'dataplantao, horainicialplantao');
                        break;
                    case 4:
                        // Consolidadas do setor para coordenador
                        $this->data['escalas'] = $this->escala_model->get_escalas_consolidadas($where, null, 'dataplantao, horainicialplantao');
                        break;
                    case 5:
                        // Trocas e passagens do setor
                        //$this->data['recebidos'] = $this->escala_model->get_plantoes_recebidos_por_setor($datainicial, $datafinal, $setor_id);
                        $this->data['passagens'] = $this->escala_model->get_passagens_de_plantao_por_setor_e_usuario($this->data['user_id'], $datainicial, $datafinal, $setor_id);
                        break;
                    default:
                        break;
                    }
                }
                
                // Calendário
                if ($tipovisualizacao == 1) {
                    switch ($tipoescala) {
                    case 0: // Minha escala consolidada
                        $this->data['escalas'] = $this->escala_model->get_escalas_consolidadas_por_profissional_cal(
                            $this->_profissional->id, $setor_id, $datainicial, $datafinal
                        );
                        break;
                    case 1: // Minhas trocas e passagens
                        $this->data['escalas'] = null;
                        break;
                    case 2: // Consolidada do setor para profissional
                        $this->data['escalas'] = $this->escala_model->get_escalas_consolidadas_cal($setor_id, $datainicial, $datafinal);
                        break;
                    case 3:
                        // Original do setor
                        $this->data['escalas'] = $this->escala_model->get_escalas_originais_cal($setor_id, $datainicial, $datafinal);
                        break;
                    case 4:
                        // Consolidadas do setor para coordenador
                        $this->data['escalas'] = $this->escala_model->get_escalas_consolidadas_cal($setor_id, $datainicial, $datafinal);
                        break;
                    case 5:
                        // Trocas e passagens do setor
                        $this->data['escalas'] = null;
                        break;
                    default:
                        break;
                    }

                    $this->load->library('calendar');
                    if ($this->calendar->init($datainicial, $datafinal, $this->data['escalas'])) {
                        $this->data["calendario"] = $this->calendar->generate();
                    } else {
                        $this->data["calendario"] = "Não há dados a exibir ou não é possível exibir esta listagem no calendário. Tente o tipo de visualização lista.";
                    }
                }
            } else {
                // Mês seguinte
                //$datainicial = date('Y') . "-" . date('m', strtotime("next month")) . "-01";
                //$datafinal = date('Y') . "-" . date('m-t', strtotime("next month"));
                $datainicial = date('Y-m-01');
                $datafinal = date('Y-m-t');
                $setores = array('' => 'Selecione um setor');
                $this->data['escalas'] = array();
                $this->data['meus_plantoes'] = array();
                $this->data['recebidos'] = array();
                $this->data['passagens'] = array();
                $this->data['tipovisualizacao'] = 0;

                $this->session->set_flashdata('message', validation_errors());
            }
        } else {
            $datainicial = date('Y-m-01');
            $datafinal = date('Y-m-t');
            $setores = array('' => 'Selecione um setor');
            $this->data['escalas'] = array();
            $this->data['meus_plantoes'] = array();
            $this->data['recebidos'] = array();
            $this->data['passagens'] = array();
            $this->data['tipovisualizacao'] = 0;
        }

        if ($this->data['user_type'] == 0) {
            $unidadeshospitalares = $this->_get_unidadeshospitalares($this->_profissional, null);
        } elseif ($this->data['user_type'] == 1) {
            $unidadeshospitalares = $this->_get_unidadeshospitalares(null, $this->data['user_id']);
        } elseif ($this->data['user_type'] == 2) {
            $unidadeshospitalares = $this->_get_unidadeshospitalares(null);
        } else {
            $unidadeshospitalares = array();
        }

        $tiposescala = $this->_get_tipos_escala($this->data['user_type']);
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
        if ($this->ion_auth->in_group('profissionais')) {
            $this->data['profissional_id'] = array(
                'name'  => 'profissional_id',
                'id'    => 'profissional_id',
                'type'  => 'hidden',
                'value' => $this->_profissional->id
            );
        } else {
            $this->data['profissional_id'] = array(
                'name'  => 'profissional_id',
                'id'    => 'profissional_id',
                'type'  => 'hidden',
                'value' => 0
            );
        }
        $this->data['user_type'] = array(
            'name'  => 'user_type',
            'id'    => 'user_type',
            'type'  => 'hidden',
            'value' => $this->data['user_type']
        );
        $this->data['user_id'] = array(
            'name'  => 'user_id',
            'id'    => 'user_id',
            'type'  => 'hidden',
            'value' => $this->data['user_id']
        );

        /* Load Template */
        $this->template->admin_render('admin/plantoes/index', $this->data);
    }

    /**
     * Exibe os plantões consolidades do profissional de hoje até 30 dias
     */
    public function proximosplantoes()
    {
        if (!$this->ion_auth->is_admin()) {
            if (!$this->ion_auth->logged_in() or !$this->ion_auth->in_group($this->_permitted_groups)) {
                redirect('auth/login', 'refresh');
            } else {
                /* Breadcrumbs */
                $this->data['breadcrumb'] = $this->breadcrumbs->show();

                $datainicial = date('Y-m-d');
                $datafinal = date('Y-m-d', strtotime("+40 days"));
                $setor_id = null;

                $this->data = array_merge($this->data, $this->checkUserType());

                //var_dump($this->data); exit;

                $this->data['proximosplantoes'] = array();
                if ($this->data['user_type'] == 0) {
                    $this->data['proximosplantoes'] = $this->escala_model->get_escalas_consolidadas_por_profissional(
                        $this->_profissional->id, $datainicial, $datafinal, $setor_id
                    );
                } elseif ($this->data['user_type'] == 1 or $this->data['user_type'] == 2) {
                    $this->data['proximosplantoes'] = $this->escala_model->get_escalas_consolidadas_por_setor_e_usuario(
                        $this->data['user_id'], $datainicial, $datafinal, $setor_id
                    );
                }

                $this->load->helper('group_by');
                $this->data['proximosplantoes'] = group_by("unidadehospitalar_razaosocial", $this->data['proximosplantoes']);

                /* Load Template */
                if ($this->mobile_detect->isMobile()) {
                    $this->template->admin_render('admin/plantoes/proximosplantoesmobile', $this->data);
                } else {
                    $this->template->admin_render('admin/plantoes/proximosplantoes', $this->data);
                }
            }
        } else {
            $this->session->set_flashdata('message', 'Esta função não é permitida para o perfil administrador.');
            redirect('admin/dashboard', 'refresh');
        }
    }

    /**
     * Exibe as cessões pendentes feitas ou recebidas pelo profissional
     */
    public function cessoestrocas()
    {
        if (!$this->ion_auth->is_admin()) {
            if (!$this->ion_auth->logged_in() or !$this->ion_auth->in_group($this->_permitted_groups)) {
                redirect('auth/login', 'refresh');
            } else {
                /* Breadcrumbs */
                $this->data['breadcrumb'] = $this->breadcrumbs->show();

                $datainicial = date('Y-m-d', strtotime("-10 days"));
                $datafinal = date('Y-m-d', strtotime("+35 days"));
                $setor_id = null;

                $this->data = array_merge($this->data, $this->checkUserType());

                $this->data['recebidos'] = array();
                $this->data['passagens'] = array();
                if ($this->data['user_type'] == 0) {
                    $this->data['recebidos'] = $this->escala_model->get_plantoes_recebidos_por_profissional($this->_profissional->id, $datainicial, $datafinal, $setor_id);
                    $this->data['passagens'] = $this->escala_model->get_passagens_de_plantao_por_profissional($this->_profissional->id, $datainicial, $datafinal, $setor_id);
                } elseif ($this->data['user_type'] == 1 or $this->data['user_type'] == 2) {
                    $this->data['passagens'] = $this->escala_model->get_passagens_de_plantao_por_setor_e_usuario(
                        $this->data['user_id'], $datainicial, $datafinal, $setor_id
                    );
                }
                $this->load->helper('group_by');
                $this->data['recebidos'] = group_by("unidadehospitalar_razaosocial", $this->data['recebidos']);
                $this->data['passagens'] = group_by("unidadehospitalar_razaosocial", $this->data['passagens']);

                // Tipos e status de passagem para exibição
                $this->data['tipospassagem'] = $this->_get_tipos_passagem();
                $this->data['statuspassagem'] = $this->_get_status_passagem();

                /* Load Template */
                if ($this->mobile_detect->isMobile()) {
                    $this->template->admin_render('admin/plantoes/cessoestrocasmobile', $this->data);
                } else {
                    $this->template->admin_render('admin/plantoes/cessoestrocas', $this->data);
                }
            }
        } else {
            $this->session->set_flashdata('message', 'Esta função não é permitida para o perfil administrador.');
            redirect('admin/dashboard', 'refresh');
        }
    }

    /**
     * Exibe a frequência do profissional
     */
    public function frequencia()
    {
        if (!$this->ion_auth->is_admin()) {
            if (!$this->ion_auth->logged_in() or !$this->ion_auth->in_group($this->_permitted_groups)) {
                redirect('auth/login', 'refresh');
            } else {
                /* Breadcrumbs */
                $this->data['breadcrumb'] = $this->breadcrumbs->show();

                $datainicial = date('Y-m-d', strtotime("-31 days"));
                $datafinal = date('Y-m-d');

                $this->data = array_merge($this->data, $this->checkUserType());

                $this->data['frequencias'] = $this->escala_model->get_frequencia_por_profissional($this->_profissional->id, $datainicial, $datafinal);

                $this->load->helper('group_by');
                $this->data['frequencias'] = group_by("nome_setor", $this->data['frequencias']);

                $this->template->admin_render('admin/plantoes/frequencia', $this->data);
            }
        } else {
            $this->session->set_flashdata('message', 'Esta função não é permitida para o perfil administrador.');
            redirect('admin/dashboard', 'refresh');
        }
    }

    /**
     * Exibe as oportunidades que combinam com o perfil do profissional
     */
    public function oportunidades()
    {
        if (!$this->ion_auth->logged_in() or !$this->ion_auth->in_group($this->_permitted_groups)) {
            redirect('auth/login', 'refresh');
        } else {
            /* Breadcrumbs */
            $this->data['breadcrumb'] = $this->breadcrumbs->show();

            $datainicial = date('Y-m-d');
            $datafinal = date('Y-m-d', strtotime("+30 days"));
            $setor_id = null;

            $this->data['proximosplantoes'] = $this->escala_model->get_escalas_consolidadas_por_profissional(
                $this->_profissional->id, $datainicial, $datafinal, $setor_id
            );
            $this->load->helper('group_by');
            $this->data['proximosplantoes'] = group_by("unidadehospitalar_razaosocial", $this->data['proximosplantoes']);

            /* Load Template */
            $this->template->admin_render('admin/plantoes/proximosplantoes', $this->data);
        }
    }

    /** Check user type by url */
    public function checkUserType()
    {
        $current_url = current_url();
        $admin_url = 'admin/plantoes';
        $coordenador_url = 'coordenador/plantoes';
        $profissional_url = 'profissional/plantoes';

        $data['user_type'] = -1;
        $data['user_id'] = 0;
        if (strpos($current_url, $profissional_url)) {
            $data['user_type'] = 0; // Profissional
            $data['user_id'] = $this->_profissional->id;
        } elseif (strpos($current_url, $coordenador_url)) {
            $data['user_type'] = 1; // Coordenador
            $data['user_id'] = $this->ion_auth->user()->row()->id;
        } elseif (strpos($current_url, $admin_url)) {
            $data['user_type'] = 2; // Administrador
            $data['user_id'] = $this->ion_auth->user()->row()->id;
        }

        return $data;
    }

    public function tooffer($id, $url_origem)
    {
        $id = (int) $id;

        if ($url_origem == 'index') {
            $url_origem = '';
        }

        if (!$this->ion_auth->logged_in()) {
            $this->session->set_flashdata('message', 'Você deve estar autenticado para utilizar esta funcionalidade.');
            redirect('auth/login', 'refresh');
        }
        if (!$this->ion_auth->in_group($this->_permitted_groups)) {
            $this->session->set_flashdata('message', 'O acesso &agrave; este recurso não é permitido ao seu perfil de usuário.');
            redirect('admin/profissional/plantoes/' . $url_origem, 'refresh');
        }

        /* Breadcrumbs */
        $this->breadcrumbs->unshift(2, lang('menu_plantoes_tooffer'), 'admin/plantoes/tooffer');
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        /* Load Data */
        $plantao = $this->escala_model->get_escala_by_id($id);

        // Testando se o plantão ainda pode ser cedido ou trocado
        $data_hora_atual = date('Y-m-d H:i:s');
        $data_hora_plantao = $plantao->dataplantao . ' ' . $plantao->horainicialplantao;
        if ($data_hora_atual > $data_hora_plantao) {
            $this->session->set_flashdata('message', 'O plantão não pode mais ser cedido ou trocado. As cessões ou trocas devem ser feitas até o horário do plantão.');
            redirect('admin/profissional/plantoes/' . $url_origem, 'refresh');
        }

        // Verificando se o plantão já foi passado e está pendente de confirmação
        $passagens_pendentes = $this->escala_model->get_passagens_trocas_pendentes($plantao->id);
        if (sizeof($passagens_pendentes) > 0) {
            $this->session->set_flashdata('message', 'Já existe uma cessão ou troca pendente para este plantão.');
            redirect('admin/profissional/plantoes/cessoestrocas', 'refresh');
        }

        $this->data = array_merge($this->data, $this->checkUserType());

        /* Checando se o plantão pertence ao profissional */
        if ($this->data['user_type'] == 0) {
            if (!($plantao->profissional_id == $this->_profissional->id) and !($plantao->passagenstrocas_profissionalsubstituto_id == $this->_profissional->id)) {
                $this->session->set_flashdata('message', 'Este plantão não pertence ao profissional logado. Favor acessar o sistema com o profissional do plantão.');
                redirect('admin/plantoes', 'refresh');
            }
        }

        $tipospassagem = $this->_get_tipos_passagem();

        $profissionais = $this->profissional_model->get_profissionais_por_setor($plantao->setor_id);
        $profissionais_setor = $this->_get_profissionais_setor($profissionais);

        // Removendo o profissional logado
        unset($profissionais_setor[$plantao->passagenstrocas_profissionalsubstituto_id]);

        /* Validate form input */
        $this->form_validation->set_rules('tipopassagem', 'lang:plantoes_tipopassagem', 'required');
        $this->form_validation->set_rules('profissionalsubstituto_id', 'lang:plantoes_profissional_substituto', 'required');

        if (isset($_POST) && ! empty($_POST)) {
            if ($this->_valid_csrf_nonce() === false or $id != $this->input->post('id')) {
                show_error($this->lang->line('error_csrf'));
            }

            if ($this->form_validation->run() == true) {
                $profissionaltroca_id = $this->input->post('profissionalsubstituto_id');
                $plantao_conflito = $this->escala_model->get_plantao_conflito($profissionaltroca_id, $plantao->dataplantao, $plantao->horainicialplantao);
                if (!empty($plantao_conflito)) {
                    $detalhe_plantao_conflito = date('d/m/Y', strtotime($plantao_conflito->dataplantao)) . " de " . date('H:i', strtotime($plantao_conflito->horainicialplantao));
                    $detalhe_plantao_conflito .= " às " . date('H:i', strtotime($plantao_conflito->horafinalplantao)) . " no setor " . $plantao_conflito->nomesetor;
                    $this->session->set_flashdata('message', 'O profissional selecionado já possui plantão neste dia e turno. Detalhes: ' . $detalhe_plantao_conflito);
                    redirect('admin/plantoes/tooffer/' . $id . '/index', 'refresh');
                }

                // Caso seja uma troca, teste se o profissional substituto tem plantões neste setor para trocar
                if ($this->input->post('tipopassagem') == 1) {
                    $primeirodiames = date('Y-m-01', strtotime($plantao->dataplantao)); //$primeirodiames = date('Y-m-d');
                    $ultimodiames = date('Y-m-t', strtotime($plantao->dataplantao)); // Correção do problema que informava que não haviam plantões disponíveis
                    $plantoes = $this->get_escalas_consolidadas_por_profissional(
                        $profissionaltroca_id,
                        $primeirodiames,
                        $ultimodiames,
                        $plantao->setor_id
                    );
                    $plantoes_profissional = $this->_get_plantoes_profissional($plantoes);
                    if (sizeof($plantoes_profissional) <= 0) {
                        $this->session->set_flashdata('message', lang('plantoes_profissional_sem_plantoes_disponiveis'));
                        redirect('admin/plantoes/tooffer/' . $id . '/index', 'refresh');
                    }
                }

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
        array_push($profissionais_setor, $profissionais_setor[9999] = 'LISTA DE OPORTUNIDADES');
        unset($profissionais_setor[10000]);

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

    public function cederplantaoeprocessar($id)
    {
        $id = (int) $id;

        if (!$this->ion_auth->logged_in() or !$this->ion_auth->in_group($this->_permitted_groups)) {
            redirect('auth', 'refresh');
        }

        /* Breadcrumbs */
        $this->breadcrumbs->unshift(2, lang('menu_plantoes_tooffer'), 'admin/plantoes/cederplantaoeprocessar');
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        /* Load Data */
        $plantao = $this->escala_model->get_escala_by_id($id);

        // Verificando se é plantão sem profissional
        if (is_null($plantao) or $plantao->profissional_id == 0) {
            $this->session->set_flashdata('message', 'Este plantão não pode ser cedido, pois não há profissional definido.');
            redirect('admin/escalas/conferencia', 'refresh');
        }

        // Verificando se o plantão já foi passado e está pendente de confirmação
        $passagens_pendentes = $this->escala_model->get_passagens_trocas_pendentes($plantao->id);
        if (sizeof($passagens_pendentes) > 0) {
            $this->session->set_flashdata('message', 'Já existe uma cessão ou troca pendente para este plantão.');
            redirect('admin/escalas/conferencia', 'refresh');
        }

        $this->data = array_merge($this->data, $this->checkUserType());

        $tipospassagem = array('0' => 'Cessão');

        $profissionais = $this->profissional_model->get_profissionais_por_setor($plantao->setor_id);
        $profissionais_setor = $this->_get_profissionais_setor($profissionais);
        $frequencias_disponiveis = array();

        /* Validate form input */
        $this->form_validation->set_rules('tipopassagem', 'lang:plantoes_tipopassagem', 'required');
        $this->form_validation->set_rules('profissionalsubstituto_id', 'lang:plantoes_profissional_substituto', 'required');
        //$this->form_validation->set_rules('frequencias_disponiveis', 'lang:plantoes_frequencias_disponiveis', 'callback_tipo_profissional_check');

        if (isset($_POST) && ! empty($_POST)) {
            if ($id != $this->input->post('id')) {
                show_error($this->lang->line('error_csrf'));
            }

            if ($this->form_validation->run() == true) {
                $profissionaltroca_id = $this->input->post('profissionalsubstituto_id');
                $frequencia_id = $this->input->post('frequencias_disponiveis');
                $frequencia = null;
                if ($frequencia_id) {
                    $frequencia = $this->frequenciaassessus_model->get_by_cdctlfrq($frequencia_id);
                }

                // No caso de plantão já passado por outro profissional
                $profissional_passagem_id = $plantao->profissional_id;
                if (isset($plantao->passagenstrocas_profissionalsubstituto_id)) {
                    $profissional_passagem_id = $plantao->passagenstrocas_profissionalsubstituto_id;
                }

                // Dados da passagem a inserir
                $data = array(
                    'escala_id' => $plantao->id,
                    'profissional_id' => $profissional_passagem_id,
                    'tipopassagem' => $this->input->post('tipopassagem'),
                    'profissionalsubstituto_id' => $this->input->post('profissionalsubstituto_id'),
                    'datahorapassagem' => date('Y-m-d H:i:s'),
                    'datahoraconfirmacao' => date('Y-m-d H:i:s'),
                    'statuspassagem' => 1,
                );

                $passagemtroca_id = $this->passagemtroca_model->insert($data);
                if ($passagemtroca_id) {
                    // Atualizando a cessão / troca atual como a única válida para esta escala
                    $this->passagemtroca_model->set_passagem_troca_valida($plantao->id, $passagemtroca_id);
                    // Atualizando a escala com a frequência
                    if ($frequencia) {
                        if ($frequencia->TP_FRQ == 1) {
                            $this->escala_model->update($plantao->id, ['frequencia_entrada_id' => $frequencia_id]);
                        } elseif ($frequencia->TP_FRQ == 2) {
                            $this->escala_model->update($plantao->id, ['frequencia_saida_id' => $frequencia_id]);
                        }
                        // Atualizando a frequência com a escala
                        $this->frequenciaassessus_model->update($frequencia_id, ['escala_id' => $plantao->id, 'tipo_batida' => $frequencia->TP_FRQ]);
                        $this->session->set_flashdata('message', 'Plantão cedido e processado com sucesso.');
                    } else {
                        $this->session->set_flashdata('message', 'Plantão cedido com sucesso.');
                    }
                } else {
                    $this->session->set_flashdata('message', 'Ocorreu um erro ao realizar a cessão do plantão.');
                }

                redirect('admin/escalas/conferencia', 'refresh');
            } else {
                $this->session->set_flashdata('message', validation_errors());
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
        $this->data['frequencias_disponiveis'] = array(
            'name'  => 'frequencias_disponiveis',
            'id'    => 'frequencias_disponiveis',
            'type'  => 'select',
            'class' => 'form-control',
            'options' => $frequencias_disponiveis
        );

        /* Load Template */
        $this->template->admin_render('admin/plantoes/cedereprocessar', $this->data);
    }

    /**
     * Testa o tipo do profissional para permitir que profissionais que
     * não são da Cemerge possam ter plantões cedidos à eles
     * Exemplo: Um profissional da Sesa não possui frequência em nosso sistema,
     * então a função de ceder impediria ele de receber um plantão por cessão
     */
    public function tipo_profissional_check($str)
    {
        $profissionalsubstituto_id = $this->input->post('profissionalsubstituto_id');
        $this->load->model('cemerge/profissional_model');
        $profissionalsubstituto = $this->profissional_model->get_by_id($profissionalsubstituto_id);

        if ($profissionalsubstituto) {
            if ($profissionalsubstituto->vinculo_id != 1) { // 1 - Cooperado Cemerge
                return true;
            } else {
                if (strlen(trim($str)) > 0) {
                    return true;
                } else {
                    $this->form_validation->set_message('tipo_profissional_check', 'A {field} não pode ser vazia');
                    return false;
                }
            }
        } else {
            $this->form_validation->set_message('tipo_profissional_check', 'Informe um profissional válido');
            return false;
        }
    }

    public function toofferbyadmin($id)
    {
        $id = (int) $id;

        if (!$this->ion_auth->logged_in() or !$this->ion_auth->in_group($this->_permitted_groups)) {
            redirect('auth', 'refresh');
        }

        /* Breadcrumbs */
        $this->breadcrumbs->unshift(2, lang('menu_plantoes_tooffer'), 'admin/plantoes/toofferbyadmin');
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        /* Load Data */
        $plantao = $this->escala_model->get_escala_by_id($id);

        // Verificando se o plantão já foi passado e está pendente de confirmação
        $passagens_pendentes = $this->escala_model->get_passagens_trocas_pendentes($plantao->id);
        if (sizeof($passagens_pendentes) > 0) {
            $this->session->set_flashdata('message', 'Já existe uma cessão ou troca pendente para este plantão.');
            redirect('admin/coordenador/plantoes/cessoestrocas', 'refresh');
        }

        $this->data = array_merge($this->data, $this->checkUserType());

        $tipospassagem = array('0' => 'Cessão');

        $profissionais = $this->profissional_model->get_profissionais_por_setor($plantao->setor_id);
        $profissionais_setor = $this->_get_profissionais_setor($profissionais);

        /* Validate form input */
        $this->form_validation->set_rules('tipopassagem', 'lang:plantoes_tipopassagem', 'required');
        $this->form_validation->set_rules('profissionalsubstituto_id', 'lang:plantoes_profissional_substituto', 'required');

        if (isset($_POST) && ! empty($_POST)) {
            if ($this->_valid_csrf_nonce() === false or $id != $this->input->post('id')) {
                show_error($this->lang->line('error_csrf'));
            }

            if ($this->form_validation->run() == true) {
                $profissionaltroca_id = $this->input->post('profissionalsubstituto_id');

                // Caso seja uma troca, teste se o profissional substituto tem plantões neste setor para trocar
                if ($this->input->post('tipopassagem') == 1) {
                    $primeirodiames = date('Y-m-01', strtotime($plantao->dataplantao)); //$primeirodiames = date('Y-m-d');
                    $ultimodiames = date('Y-m-t', strtotime($plantao->dataplantao)); // Correção do problema que informava que não haviam plantões disponíveis
                    $plantoes = $this->escala_model->get_escalas_consolidadas_por_profissional(
                        $profissionaltroca_id,
                        $primeirodiames,
                        $ultimodiames,
                        $plantao->setor_id
                    );
                    $plantoes_profissional = $this->_get_plantoes_profissional($plantoes);
                    if (sizeof($plantoes_profissional) <= 0) {
                        $this->session->set_flashdata('message', lang('plantoes_profissional_sem_plantoes_disponiveis'));
                        redirect('admin/coordenador/plantoes/tooffer/' . $id . '/index', 'refresh');
                    }
                }

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
                    'datahoraconfirmacao' => date('Y-m-d H:i:s'),
                    'statuspassagem' => 1,
                );

                $passagemtroca_id = $this->passagemtroca_model->insert($data);
                if ($passagemtroca_id) {
                    $this->passagemtroca_model->set_passagem_troca_valida($plantao->id, $passagemtroca_id);
                    /* Send notifications */
                    $notification_send = $this->_sendNotifications(
                        $plantao, $this::TIPO_NOTIFICACAO_EMAIL, $this::ACAO_NOTIFICACAO_CESSAO_PLANTAO
                    );
                    if ($notification_send) {
                        $this->session->set_flashdata('message', 'E-mail enviado com sucesso.');
                    } else {
                        $this->session->set_flashdata('message', 'Ocorreu um erro ao enviar o e-mail.');
                    }
                } else {
                    $this->session->set_flashdata('message', 'Ocorreu um erro ao realizar a cessão do plantão.');
                }

                redirect('admin/plantoes', 'refresh');
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
     * Troca realizada pelo Coordenador de Setor
     */
    public function exchangebyadmin($id)
    {
        $id = (int) $id;

        if (!$this->ion_auth->logged_in() or !$this->ion_auth->in_group($this->_permitted_groups)) {
            redirect('auth', 'refresh');
        }

        /* Breadcrumbs */
        $this->breadcrumbs->unshift(2, lang('menu_plantoes_toexchange'), 'admin/coordenador/plantoes/exchangebyadmin');
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        /* Load Data */
        $plantao = $this->escala_model->get_escala_by_id($id);

        // Verificando se o plantão já foi passado e está pendente de confirmação
        $passagens_pendentes = $this->escala_model->get_passagens_trocas_pendentes($plantao->id);
        if (sizeof($passagens_pendentes) > 0) {
            $this->session->set_flashdata('message', 'Já existe uma cessão ou troca pendente para este plantão.');
            redirect('admin/coordenador/plantoes/', 'refresh');
        }

        $this->data = array_merge($this->data, $this->checkUserType());

        $tipospassagem = array('1' => 'Troca');
        $escalas_troca = array();

        $profissionais = $this->profissional_model->get_profissionais_por_setor($plantao->setor_id);
        $profissionais_setor = $this->_get_profissionais_setor($profissionais);
        $profissionais_setor = array('' => 'Selecione um profissional') + $profissionais_setor;

        /* Validate form input */
        $this->form_validation->set_rules('tipopassagem', 'lang:plantoes_tipopassagem', 'required');
        $this->form_validation->set_rules('profissionalsubstituto_id', 'lang:plantoes_profissional_substituto', 'required');
        $this->form_validation->set_rules('escalatroca_id', 'lang:plantoes_escalatroca_id', 'required');

        if (isset($_POST) && !empty($_POST)) {
            /*
            if ($this->_valid_csrf_nonce() === false or $id != $this->input->post('id')) {
                show_error($this->lang->line('error_csrf'));
            }
            */
            if ($id != $this->input->post('id')) {
                $this->session->set_flashdata('message', 'O identificador do plantão enviado é diferente do identificador de plantão acessado.');
                redirect('admin/coordenador/plantoes/exchangebyadmin/' . $id, 'refresh');
            }

            if ($this->form_validation->run() == true) {
                $profissionaltroca_id = $this->input->post('profissionalsubstituto_id');
                $escalatroca_id = $this->input->post('escalatroca_id');

                if (!$profissionaltroca_id or !$escalatroca_id) {
                    $this->session->set_flashdata('message', 'Escola um profissional e uma escala disponível para troca.');
                    redirect('admin/coordenador/plantoes/exchangebyadmin/' . $id, 'refresh');
                }

                $plantao_troca = $this->escala_model->get_escala_by_id($escalatroca_id);

                // No caso de plantão já passado por outro profissional
                $profissional_passagem_id = $plantao->profissional_id;
                if (isset($plantao->passagenstrocas_profissionalsubstituto_id)) {
                    $profissional_passagem_id = $plantao->passagenstrocas_profissionalsubstituto_id;
                }
                $data_escala = array(
                    'escala_id' => $plantao->id,
                    'profissional_id' => $profissional_passagem_id,
                    'tipopassagem' => $this::TIPO_PASSAGEM_TROCA,
                    'profissionalsubstituto_id' => $profissionaltroca_id,
                    'datahorapassagem' => date('Y-m-d H:i:s'),
                    'datahoraconfirmacao' => date('Y-m-d H:i:s'),
                    'statuspassagem' => 1,
                    'escalatroca_id' => $plantao_troca->id,
                );
                $data_escalatroca = array(
                    'escala_id' => $plantao_troca->id,
                    'profissional_id' => $profissionaltroca_id,
                    'tipopassagem' => $this::TIPO_PASSAGEM_TROCA,
                    'profissionalsubstituto_id' => $profissional_passagem_id,
                    'datahorapassagem' => date('Y-m-d H:i:s'),
                    'datahoraconfirmacao' => date('Y-m-d H:i:s'),
                    'statuspassagem' => 1,
                    'escalatroca_id' => $plantao->id,
                );

                // TODO: Realizar update de todas outras trocas para status 3
                // $this->passagemtroca_model->set_passagem_troca_valida($plantao->id, $plantao->passagenstrocas_id
                $passagemtrocaorigem_id = $this->passagemtroca_model->insert($data_escala);
                $passagemtrocadestino_id = $this->passagemtroca_model->insert($data_escalatroca);

                if ($passagemtrocaorigem_id
                    && $passagemtrocadestino_id
                    && $this->passagemtroca_model->set_passagem_troca_valida($plantao->id, $passagemtrocaorigem_id)
                    && $this->passagemtroca_model->set_passagem_troca_valida($plantao_troca->id, $passagemtrocadestino_id)
                ) {
                    /* Send notifications */
                    /*
                    $notification_send = $this->_sendNotifications(
                        $plantao, $this::TIPO_NOTIFICACAO_EMAIL, $this::ACAO_NOTIFICACAO_CESSAO_PLANTAO
                    );
                    if ($notification_send) {
                        $this->session->set_flashdata('message', 'E-mail enviado com sucesso.');
                    } else {
                        $this->session->set_flashdata('message', 'Ocorreu um erro ao enviar o e-mail.');
                    }
                    */
                    $this->session->set_flashdata('message', 'Os plantões foram trocados com êxito.');
                } else {
                    $this->session->set_flashdata('message', 'Ocorreu um erro ao realizar a cessão do plantão.');
                }

                redirect('admin/coordenador/plantoes', 'refresh');
            }
        }

        // display the edit user form
        //$this->data['csrf'] = $this->_get_csrf_nonce();

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
            'selected' => '',
            'options' => $profissionais_setor
        );
        $this->data['escalatroca_id'] = array(
            'name'  => 'escalatroca_id',
            'id'    => 'escalatroca_id',
            'type'  => 'select',
            'class' => 'form-control',
            'options' => $escalas_troca
        );

        /* Load Template */
        $this->template->admin_render('admin/plantoes/exchangebyadmin', $this->data);
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

        $this->data = array_merge($this->data, $this->checkUserType());

        /* Checando se o plantão pertence ao profissional */
        if ($this->data['user_type'] == 0) {
            if (!($plantao->passagenstrocas_profissionalsubstituto_id == $this->_profissional->id)) {
                $this->session->set_flashdata('message', 'Esta troca de plantão não foi oferecido ao profissional logado. Favor acessar o sistema com o profissional do plantão.');
                redirect('admin/plantoes', 'refresh');
            }
        }

        // Plantões disponíveis do profissional no mesmo setor a partir do primeiro dia do mês do plantão ao final do mês
        $primeirodiames = date('Y-m-01', strtotime($plantao->dataplantao));
        $ultimodiames = date('Y-m-t', strtotime($plantao->dataplantao)); // Correção do erro que informava não haver plantões disponíveis
        $plantoes = $this->escala_model->get_escalas_consolidadas_por_profissional(
            $plantao->passagenstrocas_profissionalsubstituto_id,
            $primeirodiames,
            $ultimodiames,
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

        /* Variables */
        $this->data['diasdasemana'] = $this->_diasdasemana;

        /* Load Data */
        $plantao = $this->escala_model->get_escala_troca_a_confirmar($id);
        $this->data['tipospassagem'] = $this->_get_tipos_passagem();
        $this->data['statuspassagem'] = $this->_get_status_passagem();

        $this->data = array_merge($this->data, $this->checkUserType());

        /* Checando se o plantão pertence ao profissional */
        if ($this->data['user_type'] == 0) {
            if (!($plantao->profissional_id == $this->_profissional->id) and !($plantao->passagenstrocas_profissional_id == $this->_profissional->id)) {
                $this->session->set_flashdata('message', 'Este plantão não pertence ao profissional logado. Favor acessar o sistema com o profissional do plantão.');
                redirect('admin/plantoes', 'refresh');
            }
        }

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
                && $this->passagemtroca_model->set_passagem_troca_valida($plantao->id, $plantao->passagenstrocas_id)
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

        /* Checando se o plantão pertence ao profissional */
        $this->data = array_merge($this->data, $this->checkUserType());

        if ($this->data['user_type'] == 0) {
            if (!($plantao->passagenstrocas_profissionalsubstituto_id == $this->_profissional->id)) {
                $this->session->set_flashdata('message', 'Este plantão não foi passado ao profissional logado. Favor acessar o sistema com o profissional do plantão.');
                redirect('admin/plantoes', 'refresh');
            }
        }

        if (isset($_POST) && ! empty($_POST)) {
            if ($this->_valid_csrf_nonce() === false or $id != $this->input->post('id')) {
                show_error($this->lang->line('error_csrf'));
            }

            $now = date('Y-m-d H:i:s');

            $data = array(
                'datahoraconfirmacao' => $now,
                'statuspassagem' => 1
            );

            if ($this->passagemtroca_model->update($plantao->passagenstrocas_id, $data)
                && $this->passagemtroca_model->set_passagem_troca_valida($plantao->id, $plantao->passagenstrocas_id)
            ) {
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
            '3' => 'Repassado'
        );
    }

    public function _get_profissionais_setor($profissionais)
    {
        $profissionaissetor = array();
        $profissionaissetor[''] = 'Selecione um profissional';
        foreach ($profissionais as $profissional) {
            $profissionaissetor[$profissional->id] = $profissional->nome;
        }

        return $profissionaissetor;
    }

    public function _get_unidadeshospitalares($profissional = null, $user_id = null)
    {
        if ($profissional != null) {
            $unidades = $this->unidadehospitalar_model->get_by_profissional($profissional->id);
        } else if ($user_id != null) {
            $unidades = $this->unidadehospitalar_model->get_by_user($user_id);
        } else {
            $unidades = $this->unidadehospitalar_model->get_all();
        }

        $unidadeshospitalares = array(
            '' => 'Selecione uma unidade hospitalar',
        );
        foreach ($unidades as $unidade) {
            $unidadeshospitalares[$unidade->id] = $unidade->razaosocial;
        }

        return $unidadeshospitalares;
    }

    public function _get_tipos_escala($user_type)
    {
        if ($user_type == 0) {
            $tipos_escala = array(
                '0' => 'Minha escala consolidada',
                '1' => 'Minhas trocas e passagens',
                '2' => 'Consolidada do setor',
            );
        } elseif ($user_type == 1 or $user_type == 2) {
            $tipos_escala = array(
                '4' => 'Consolidada do setor',
                '5' => 'Trocas e passagens do setor',
                '3' => 'Original do setor',
            );
        }

        return $tipos_escala;
    }

    public function _get_tipos_visualizacao()
    {
        $tipos_visualizacao = array(
            '1' => 'Calendário',
            '0' => 'Lista',
        );

        return $tipos_visualizacao;
    }

    public function _get_setores($unidadehospitalar_id, $profissional, $user_id = null)
    {
        if ($profissional != null) {
            $setores_por_unidade = $this->setor_model->get_by_unidade_profissional($unidadehospitalar_id, $profissional->id);
        } elseif ($user_id != null) {
            $setores_por_unidade = $this->setor_model->get_by_unidade_usuario($unidadehospitalar_id, $user_id, true);
        } else {
            $setores_por_unidade = $this->setor_model->get_where(['unidadehospitalar_id' => $unidadehospitalar_id]);
        }

        $setores = array(
            '' => 'Selecione um setor',
        );
        foreach ($setores_por_unidade as $setor) {
            $setores[$setor->id] = $setor->nome;
        }

        return $setores;
    }

    /** Buscar apenas os setores do profissional */
    /** DESUSO: Usar o método no controller Setores */
    public function setores($id)
    {
        $id = (int) $id;

        if ($this->ion_auth->is_admin()) {
            $setores = $this->setor_model->get_where(['unidadehospitalar_id' => $id]);
        } else {
            $setores = $this->setor_model->get_by_unidade_profissional($unidadehospitalar_id, $this->_profissional->id);
        }

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
            $destinatario = $plantao->profissional_passagem_email;
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
     * Cancela uma cessão
     */
    public function cancelarcessao($cessao_id)
    {
        $cessao_id = (int)$cessao_id;

        $sucesso = false;
        $sucesso = $this->passagemtroca_model->delete(['id' => $cessao_id]);

        echo(json_encode("{ sucesso: $sucesso }"));
        exit;
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
     * Busca os plantões do setor para compor o calendário
     */
    public function escalaconsolidadadosetor()
    {
        $mes = (int)$this->uri->segment(5, 0);
        $setor = (int)$this->uri->segment(7, 0);

        $plantoes = array();

        if ($mes != 0 and $setor != 0) {
            $plantoes = $this->escala_model->get_escala_consolidada_setor_calendario(
                $mes, $setor, $this->mobile_detect->isMobile()
            );
        }

        echo(json_encode($plantoes));
        exit;
    }

    /**
     * Busca os plantões originais do setor para compor o calendário
     */
    public function escalaoriginaldosetor()
    {
        $mes = (int)$this->uri->segment(5, 0);
        $setor = (int)$this->uri->segment(7, 0);

        $plantoes = array();

        if ($mes != 0 and $setor != 0) {
            $plantoes = $this->escala_model->get_escala_original_setor_calendario(
                $mes, $setor, $this->mobile_detect->isMobile()
            );
        }

        echo(json_encode($plantoes));
        exit;
    }

    /**
     * Busca os plantões do profissional escolhido para a lista de troca
     */
    public function plantoesdisponiveisporprofissionalmesesetor()
    {
        $mes = (int)$this->uri->segment(4, 0);
        $setor = (int)$this->uri->segment(5, 0);
        $profissional = (int)$this->uri->segment(6, 0);

        $plantoes = array();

        if ($mes != 0 and $setor != 0 and $profissional != 0) {
            $plantoes = $this->escala_model->get_escalas_consolidadas_por_profissional_mes_setor(
                $mes, $setor, $profissional
            );
        }
        echo(json_encode($plantoes));
        exit;
    }

    /**
     * Busca as frequências não processadas de um profissional em um dia e setor
     */
    public function frequenciasdisponiveisporprofissional()
    {
        $data_plantao = $this->uri->segment(4, 0);
        $hora_inicial_plantao = $this->uri->segment(5, 0);
        $profissional_id = $this->uri->segment(6, 0);
        $unidadehospitalar_id = $this->uri->segment(7, 0);

        $frequencias = array();

        if ($data_plantao and $hora_inicial_plantao and $profissional_id != 0 and $unidadehospitalar_id) {
            $frequencias = $this->escala_model->get_frequencia_por_escala_dia($data_plantao, $profissional_id, $unidadehospitalar_id);
        }
        echo(json_encode($frequencias));
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

    /**
     * Busca as trocas e passagens para compor o calendário
     */
    public function minhastrocasepassagens()
    {
        $mes = (int)$this->uri->segment(5, 0);
        $setor = (int)$this->uri->segment(7, 0);
        $profissional = (int)$this->uri->segment(9, 0);

        $plantoes = array();

        if ($mes != 0 and $setor != 0 and $profissional != 0) {
            $plantoes = $this->escala_model->get_minhas_trocas_passagens_calendario(
                $mes, $setor, $profissional, $this->mobile_detect->isMobile()
            );
        }

        echo(json_encode($plantoes));
        exit;
    }

    /**
     * Busca as trocas e passagens do setor para compor o calendário
     */
    public function trocasepassagensdosetor()
    {
        $mes = (int)$this->uri->segment(5, 0);
        $setor = (int)$this->uri->segment(7, 0);

        $plantoes = array();

        if ($mes != 0 and $setor != 0) {
            $plantoes = $this->escala_model->get_trocas_passagens_setor_calendario(
                $mes, $setor, $this->mobile_detect->isMobile()
            );
        }

        echo(json_encode($plantoes));
        exit;
    }
}
