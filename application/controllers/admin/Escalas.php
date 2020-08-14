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
        if (!$this->ion_auth->logged_in() or !$this->ion_auth->is_admin()) {
            redirect('auth/login', 'refresh');
        } else {
            /* Breadcrumbs */
            $this->data['breadcrumb'] = $this->breadcrumbs->show();

            /* Reset */
            $this->data['escalas'] = array();

            /* Validate form input */
            $this->form_validation->set_rules('unidadehospitalar_id', 'lang:escalas_unidadehospitalar', 'required');
            $this->form_validation->set_rules('setor_id', 'lang:escalas_setor', 'required');
            $this->form_validation->set_rules('datainicial', 'lang:escalas_datainicialplantao', 'required');
            $this->form_validation->set_rules('datafinal', 'lang:escalas_datafinalplantao', 'required');
            $this->form_validation->set_rules('tipoescala', 'lang:escalas_tipoescala', 'required');

            if ($this->form_validation->run() == true) {
                $unidadehospitalar_id = $this->input->post('unidadehospitalar_id');
                $setor_id = $this->input->post('setor_id');
                $datainicial = $this->input->post('datainicial');
                $datafinal = $this->input->post('datafinal');
                $tipoescala = $this->input->post('tipoescala');

                $setores = $this->_get_setores($unidadehospitalar_id);

                // Realizando a busca
                $where = array(
                    'unidadehospitalar_id' => $unidadehospitalar_id,
                    'escalas.setor_id' => $setor_id,
                    'escalas.dataplantao >=' => $datainicial,
                    'escalas.dataplantao <=' => $datafinal,
                );

                if ($tipoescala == 0) {
                    $this->data['escalas'] = $this->escala_model->get_escalas_originais($where, null, null, 'dataplantao, horainicialplantao');
                } elseif ($tipoescala == 1) {
                    $this->data['escalas'] = $this->escala_model->get_escalas_consolidadas($where, null, 'dataplantao, horainicialplantao');
                } elseif ($tipoescala == 2) {
                    $this->data['escalas'] = $this->escala_model->get_passagens_trocas($where, null, 'dataplantao, horainicialplantao');
                }
            } else {
                $datainicial = date('Y') . "-" . date('m', strtotime("next month")) . "-01";
                $datafinal = date('Y') . "-" . date('m-t', strtotime("next month"));
                $setores = array('' => 'Selecione um setor');
            }

            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

            $unidadeshospitalares = $this->_get_unidadeshospitalares();

            $tiposescala = $this->_get_tipos_escala();

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
            $this->template->admin_render('admin/escalas/index', $this->data);
        }
    }

    public function atribuir()
    {
        if (!$this->ion_auth->logged_in() OR !$this->ion_auth->is_admin()) {
            redirect('auth/login', 'refresh');
        } else {
            /* Breadcrumbs */
            $this->data['breadcrumb'] = $this->breadcrumbs->show();

            /* Reset */
            $this->data['escalas'] = array();

            /* Validate form input */
            $this->form_validation->set_rules('unidadehospitalar_id', 'lang:escalas_unidadehospitalar', 'required');
            $this->form_validation->set_rules('setor_id', 'lang:escalas_setor', 'required');
            $this->form_validation->set_rules('datainicial', 'lang:escalas_datainicialplantao', 'required');
            $this->form_validation->set_rules('datafinal', 'lang:escalas_datafinalplantao', 'required');

            if ($this->form_validation->run() == true) {
                $unidadehospitalar_id = $this->input->post('unidadehospitalar_id');
                $setor_id = $this->input->post('setor_id');
                $datainicial = $this->input->post('datainicial');
                $datafinal = $this->input->post('datafinal');
                $domingo = $this->input->post('domingo');
                $segunda = $this->input->post('segunda');
                $terca = $this->input->post('terca');
                $quarta = $this->input->post('quarta');
                $quinta = $this->input->post('quinta');
                $sexta = $this->input->post('sexta');
                $sabado = $this->input->post('sabado');
                $turno_id = $this->input->post('turno_id');

                $setores = $this->_get_setores($unidadehospitalar_id);
                $profissionais = $this->_get_profissionais($setor_id);

                // Realizando a busca
                $where = array(
                    'unidadehospitalar_id' => $unidadehospitalar_id,
                    'escalas.setor_id' => $setor_id,
                    'escalas.dataplantao >=' => $datainicial,
                    'escalas.dataplantao <=' => $datafinal
                );

                // Se escolhido o turno
                $turno = null;
                if ($turno_id == 1) {
                    $turno = '07:00:00';
                } elseif ($turno_id == 2) {
                    $turno = '13:00:00';
                } elseif ($turno_id == 3) {
                    $turno = '19:00:00';
                }
                if (!is_null($turno)) {
                    $where['escalas.horainicialplantao'] = $turno;
                }

                // Dias da semana filtrados
                $dias_semana = array();
                if ($domingo == 1) {
                    array_push($dias_semana, $domingo);
                }
                if ($segunda == 2) {
                    array_push($dias_semana, $segunda);
                }
                if ($terca == 3) {
                    array_push($dias_semana, $terca);
                }
                if ($quarta == 4) {
                    array_push($dias_semana, $quarta);
                }
                if ($quinta == 5) {
                    array_push($dias_semana, $quinta);
                }
                if ($sexta == 6) {
                    array_push($dias_semana, $sexta);
                }
                if ($sabado == 7) {
                    array_push($dias_semana, $sabado);
                }
                $where_in_column = null;
                if (!empty($dias_semana)) {
                    $where_in_column = 'dayofweek(escalas.dataplantao)';
                }

                $this->data['escalas'] = $this->escala_model->get_escalas_originais($where, $where_in_column, $dias_semana, 'dataplantao, horainicialplantao');
            } else {
                $datainicial = date('Y') . "-" . date('m', strtotime("next month")) . "-01";
                $datafinal = date('Y') . "-" . date('m-t', strtotime("next month"));
                $setores = array('' => 'Selecione um setor');
                $profissionais = array('' => 'Selecione um profissional');
                $domingo = 1;
                $segunda = 2;
                $terca = 3;
                $quarta = 4;
                $quinta = 5;
                $sexta = 6;
                $sabado = 7;
                $turno_id = 0;
            }

            $this->data['message'] = (
                validation_errors() ? validation_errors() : (
                    $this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')
                )
            );

            $unidadeshospitalares = $this->_get_unidadeshospitalares();
            $turnos = array(
                '0' => 'Todos',
                '1' => 'Manhã',
                '2' => 'Tarde',
                '3' => 'Noite',
            );

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
                'type'  => 'select',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('profissional_id'),
                'options' => $profissionais,
            );
            $this->data['turno_id'] = array(
                'name'  => 'turno_id',
                'id'    => 'turno_id',
                'type'  => 'select',
                'class' => 'form-control',
                'value' => $turno_id,
                'options' => $turnos,
            );
            $this->data['domingo'] = $domingo;
            $this->data['segunda'] = $segunda;
            $this->data['terca'] = $terca;
            $this->data['quarta'] = $quarta;
            $this->data['quinta'] = $quinta;
            $this->data['sexta'] = $sexta;
            $this->data['sabado'] = $sabado;

            /* Load Template */
            $this->template->admin_render('admin/escalas/atribuir', $this->data);
        }
    }

    public function create()
    {
        /* Breadcrumbs */
        $this->breadcrumbs->unshift(2, lang('menu_escalas_create'), 'admin/escalas/create');
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        /* Variables */
        //$tables = $this->config->item('tables', 'ion_auth');

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

    public function createfixed()
    {
        /* Breadcrumbs */
        $this->breadcrumbs->unshift(2, lang('menu_escalas_create'), 'admin/escalas/create');
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        /* Validate form input */
        $this->form_validation->set_rules('unidadehospitalar_id', 'lang:escalas_unidadehospitalar', 'required');
        $this->form_validation->set_rules('setor_id', 'lang:escalas_setor', 'required');
        $this->form_validation->set_rules('datainicialplantao', 'lang:escalas_datainicialplantao', 'required');
        $this->form_validation->set_rules('datafinalplantao', 'lang:escalas_datafinalplantao', 'required');

        if ($this->form_validation->run() == true) {
            $unidadehospitalar_id = $this->input->post('unidadehospitalar_id');
            $setor_id = $this->input->post('setor_id');
            $datainicialplantao = $this->input->post('datainicialplantao');
            $datafinalplantao = $this->input->post('datafinalplantao');
            //$active = $this->input->post('active');

            $additional_data = array(
                'unidadehospitalar_id' => $this->input->post('unidadehospitalar_id'),
                'setor_id' => $this->input->post('setor_id'),
                'datainicialplantao' => $this->input->post('datainicialplantao'),
                'datafinalplantao' => $this->input->post('datafinalplantao'),
            );
        }

        // Realizar o insert no model
        if ($this->form_validation->run() == true) {
            $success = false;

            $datainicial = new DateTime($datainicialplantao);
            $datafinal = new DateTime($datafinalplantao);
            $dias = $datainicial->diff($datafinal)->format('%a') + 1;
            $indice = 0;

            if ($datafinal >= $datainicial) {
                $escala_referencia = $this->escala_model->get_escala_referencia($setor_id, $datainicialplantao);

                for ($data = $datainicial; $data <= $datafinal; ) {
                    $ref = $escala_referencia[$indice];
                    
                    $horaInicialReferencia = $ref->horainicialplantao;
                    $horaFinalReferencia = $ref->horafinalplantao;
                    $duracaoReferencia = $ref->duracao;
                    $profissionalIdReferencia = $ref->profissional_id;
                    
                    $duracao = 6;
                    $dtinicialplantao = $data->format("Y-m-d");
                    $dtfinalplantao = $dtinicialplantao;
                    if ((int)$horaInicialReferencia > (int)$horaFinalReferencia) {
                        $dtfinalplantao = date('Y-m-d', strtotime($dtfinalplantao . ' +1 day'));
                        $duracao = 12;
                    }
                    $insert_data = array(
                        'setor_id' => $setor_id,
                        'dataplantao' => $dtinicialplantao,
                        'datafinalplantao' => $dtfinalplantao,
                        'horainicialplantao' => $horaInicialReferencia,
                        'horafinalplantao' => $horaFinalReferencia,
                        'duracao' => $duracao,
                        'profissional_id' => $profissionalIdReferencia,
                    );

                    echo($data->format('d/m/Y'));
                    echo(" => " . $indice);
                    echo("<br>");
                    //var_dump($escala_referencia[$indice]);
                    var_dump($insert_data);
                    echo("<br>");

                    //$success = $this->escala_model->insertfixed($insert_data);

                    if ($escala_referencia[$indice]->horafinalplantao == '07:00:00') {
                        $data->modify('+1 day');
                    }

                    if ($indice == sizeof($escala_referencia)-1) {
                        $indice = -1;
                    }

                    $indice++;
                }
                exit;
            } else {
                $this->session->set_flashdata('message', 'A data final deve ser menor que a data inicial');
                redirect('admin/escalas/createfixed', 'refresh');
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
            $this->template->admin_render('admin/escalas/createfixed', $this->data);
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
            '0' => 'Original',
            '1' => 'Consolidada',
            '2' => 'Trocas e Passagens',
        );

        return $tipos_escala;
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

    public function _get_profissionais($setor_id)
    {
        $profissionais_por_setor = $this->profissional_model->get_profissionais_por_setor($setor_id);

        $profissionais = array(
            '' => 'Selecione um profissional',
        );
        foreach ($profissionais_por_setor as $profissional) {
            $profissionais[$profissional->id] = $profissional->nome;
        }

        return $profissionais;
    }

    public function profissionais($setor)
    {
        $setor = (int)$setor;

        $profissionais = $this->profissional_model->get_profissionais_por_setor($setor);

        echo json_encode($profissionais);
        exit;
    }

    public function setores($id)
    {
        $id = (int) $id;

        $setores = $this->setor_model->get_where(['unidadehospitalar_id' => $id]);

        echo json_encode($setores);
        exit;
    }

    public function atribuirescala()
    {
        $profissional = $this->input->post('profissional', 0);
        $escala = $this->input->post('escala', 0);

        try {
            $this->escala_model->update($escala, ['profissional_id' => $profissional]);
        } catch (Exception $ex) {
            echo(json_encode($ex));
        }

        echo json_encode($profissional);
        echo json_encode($escala);
        exit;
    }
}
