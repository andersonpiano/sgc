<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Justificativas extends Admin_Controller
{
    const STATUS_JUSTIFICATIVA_AGUARDANDO = 0;
    const STATUS_JUSTIFICATIVA_APROVADA = 1;
    const STATUS_JUSTIFICATIVA_NEGADA = 2;
    const STATUS_JUSTIFICATIVA_TODAS = 3;
    const STATUS_JUSTIFICATIVA_IGNORADA = 4;

    private $_permitted_groups = array('admin', 'profissionais', 'coordenadorplantao', 'sac');
    private $_admin_groups = array('admin', 'coordenadorplantao', 'sac');
    private $_coordenador_group = array('coordenadorplantao');

    public function __construct()
    {
        parent::__construct($this->_permitted_groups);

        /* Load :: Common */
        $this->load->model('cemerge/justificativa_model');
        $this->lang->load('admin/justificativas');
        /* Title Page */
        $this->page_title->push(lang('menu_justificativas'));
        $this->data['pagetitle'] = $this->page_title->show();

        /* Breadcrumbs :: Common */
        $this->breadcrumbs->unshift(1, lang('menu_justificativas'), 'admin/justificativas');
    }

    public function index()
    {
        if (!$this->ion_auth->logged_in()) {
            $this->session->set_flashdata('message', 'Você deve estar autenticado para listar as justificativas.');
            redirect('auth/login', 'refresh');
        }
        if (!$this->ion_auth->in_group($this->_permitted_groups)) {
            $this->session->set_flashdata('message', 'O acesso &agrave; este recurso não é permitido ao seu perfil de usuário.');
            redirect('auth/login', 'refresh');
        }
        /* Breadcrumbs */
        $this->data['breadcrumb'] = $this->breadcrumbs->show(); 
        // Modulos Carregados  
        $this->load->model('cemerge/FrequenciaAssessus_model');
            $data_plantao_inicio = $this->input->post('data_plantao_inicio');
            $data_plantao_fim = $this->input->post('data_plantao_fim');
            $status = $this->input->post('status');
            $covid = $this->input->post('covid');
            $profissional_id = $this->input->post('profissional_id');
            $unidadehospitalar_id = $this->input->post('unidadehospitalar_id');
        /* Validate form input */
        $this->form_validation->set_rules('data_plantao_inicio', 'lang:justificativas_data_inicio', 'required');
        $this->form_validation->set_rules('data_plantao_fim', 'lang:justificativas_data_fim', 'required');
        $this->form_validation->set_rules('status', 'lang:justificativas_status', 'required');

        $tipos_status = array (
            $this::STATUS_JUSTIFICATIVA_TODAS => 'Todas',
            $this::STATUS_JUSTIFICATIVA_AGUARDANDO => 'Aguardando Aprovação',
            $this::STATUS_JUSTIFICATIVA_APROVADA => 'Deferidas',
            $this::STATUS_JUSTIFICATIVA_NEGADA => 'Indeferidas',
            $this::STATUS_JUSTIFICATIVA_IGNORADA => 'Ignoradas',
            
        );

        $unidadeshospitalares = $this->_get_unidadeshospitalares();

        if ($this->form_validation->run() == true) {       
            /* Justificativas */
            //$profissional_id = $this->session->userdata('profissional_id');
            //$this->data['justificativas'] = $this->justificativa_model->get_where(array('profissional_id' => $profissional_id, 'data_plantao >='=>$data_plantao_inicio, 'data_plantao <='=>$data_plantao_fim));

            if($profissional_id == ''){
                $profissional_id = 0;
            }

            $this->data['justificativas'] = $this->justificativa_model->get_justificativas_profissional($data_plantao_inicio, $data_plantao_fim, $status, $covid, $profissional_id);
            $profissionais= $this->_get_profissionais_por_unidade_hospitalar($unidadehospitalar_id);
            //$this->data['justificativas'] = $this->justificativa_model->get_where(array('data_plantao >='=>$data_plantao_inicio, 'data_plantao <='=>$data_plantao_fim));

            foreach ($this->data['justificativas'] as $ct) {
                $ct->turno = '';
                if ((int)$ct->hora_entrada >= 5 && (int)$ct->hora_entrada < 13) {
                    $ct->turno = 'Manhã';
                } else if ((int)$ct->hora_entrada >= 13 && (int)$ct->hora_entrada < 19) {
                    $ct->turno = 'Tarde';
                } else if ((int)$ct->hora_entrada >= 19 && (int)$ct->hora_entrada <= 23) {
                    $ct->turno = 'Noite';
                }
                $ct->status_oportunidade = '';
                switch ($ct->status) {
                case $this::STATUS_JUSTIFICATIVA_APROVADA:
                    $ct->status = 'Deferida';
                    break;
                case $this::STATUS_JUSTIFICATIVA_NEGADA:
                    $ct->status = 'Indeferida';
                    break;
                case $this::STATUS_JUSTIFICATIVA_AGUARDANDO:
                    $ct->status = 'Aguardando Aprovação';
                    break;
                case $this::STATUS_JUSTIFICATIVA_IGNORADA:
                    $ct->status = 'Ignorada';
                    break;
                }

                $ct->entrada_sistema = date('H:i', strtotime($this->batida($ct->plantao, 'E')));
                $ct->saida_sistema = date('H:i', strtotime($this->batida($ct->plantao, 'S')));

                $data_plantao = $ct->data_inicial_plantao;
                $profissional_id = $ct->profissional_id;
                $plantao_entrada = $ct->hora_entrada;
                $plantao_saida = $ct->hora_saida;
        
                //$ct->entrada = $ct->hora_entrada;//$this->FrequenciaAssessus_model->get_batida_profissional_entrada($data_plantao, $profissional_id, $plantao_entrada, $plantao_saida);
                //$ct->saida = $ct->hora_saida;//$this->FrequenciaAssessus_model->get_batida_profissional_saida($data_plantao, $profissional_id, $plantao_entrada, $plantao_saida); 
            }

        } else {
            $this->data['justificativas'] = array();
            $data_plantao_inicio = date('Y-m-d');
            $data_plantao_fim = date('Y-m-d');
            $status = $this->input->post('status');
            $profissionais= $this->_get_profissionais_por_unidade_hospitalar(1);
            
        }
        $setores = array(
            0 => 'Todos',
            1 => 'COVID',
            2 => 'NÃO COVID',
        );
        $this->data['data_plantao_inicio'] = array(
            'name'  => 'data_plantao_inicio',
            'id'    => 'data_plantao_inicio',
            'type'  => 'date',
            'class' => 'form-control',
            'value' => $data_plantao_inicio,
        );

        $this->data['data_plantao_fim'] = array(
            'name'  => 'data_plantao_fim',
            'id'    => 'data_plantao_fim',
            'type'  => 'date',
            'class' => 'form-control',
            'value' => $data_plantao_fim,
        );

        $this->data['status'] = array(
            'name'  => 'status',
            'id'    => 'status',
            'type'  => 'select',
            'class' => 'form-control',
            'selected' => $status,
            'options' => $tipos_status,
        );

        $this->data['covid'] = array(
            'name'  => 'covid',
            'id'    => 'covid',
            'type'  => 'select',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('covid'),
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

        $this->data['unidadehospitalar_id'] = array(
            'name'  => 'unidadehospitalar_id',
            'id'    => 'unidadehospitalar_id',
            'type'  => 'select',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('unidadehospitalar_id'),
            'options' => $unidadeshospitalares,
        );

        // Anderson
        //var_dump($this->data['justificativas']);exit;
        //exit;

        /* Load Template */
        $this->template->admin_render('admin/justificativas/index', $this->data);
    }

    public function _get_profissionais_por_unidade_hospitalar($unidadehospitalar_id)
    {
        $this->load->model('cemerge/profissional_model');
        $profissionais_por_unidade_hospitalar = $this->profissional_model->get_profissionais_por_unidade_hospitalar($unidadehospitalar_id);

        $profissionais = array(
            '' => 'Todos',
        );
        foreach ($profissionais_por_unidade_hospitalar as $profissional) {
            $profissionais[$profissional->id] = $profissional->nome;
        }

        return $profissionais;
    }


    public function profissional()
    {
        if (!$this->ion_auth->logged_in()) {
            $this->session->set_flashdata('message', 'Você deve estar autenticado para listar as justificativas.');
            redirect('auth/login', 'refresh');
        }
        if (!$this->ion_auth->in_group($this->_permitted_groups)) {
            $this->session->set_flashdata('message', 'O acesso &agrave; este recurso não é permitido ao seu perfil de usuário.');
            redirect('auth/login', 'refresh');
        }

        $this->load->model('cemerge/usuarioprofissional_model');
        $this->load->model('cemerge/profissional_model');

            $userId = $this->ion_auth->user()->row()->id;
            $profissional = $this->usuarioprofissional_model->get_where(['user_id' => $userId]);
            if ($profissional) {
                $this->_profissional = $this->profissional_model->get_where(['id' => $profissional[0]->profissional_id])[0];
                $profissional_id = $this->_profissional->id;
            }

        /* Breadcrumbs */
        $this->data['breadcrumb'] = $this->breadcrumbs->show(); 
        // Modulos Carregados  
        $this->load->model('cemerge/FrequenciaAssessus_model');
            $data_plantao_inicio = $this->input->post('data_plantao_inicio');
            $data_plantao_fim = $this->input->post('data_plantao_fim');
            $status = $this->input->post('status');
        /* Validate form input */
        $this->form_validation->set_rules('data_plantao_inicio', 'lang:justificativas_data_inicio', 'required');
        $this->form_validation->set_rules('data_plantao_fim', 'lang:justificativas_data_fim', 'required');
        $this->form_validation->set_rules('status', 'lang:justificativas_status', 'required');

        $tipos_status = array (
            $this::STATUS_JUSTIFICATIVA_TODAS => 'Todas',
            $this::STATUS_JUSTIFICATIVA_AGUARDANDO => 'Aguardando Aprovação',
            $this::STATUS_JUSTIFICATIVA_APROVADA => 'Deferidas',
            $this::STATUS_JUSTIFICATIVA_NEGADA => 'Indeferidas',
            $this::STATUS_JUSTIFICATIVA_IGNORADA => 'Ignoradas'
            
        );

        if ($this->form_validation->run() == true) {       
            /* Justificativas */
            //$profissional_id = $this->session->userdata('profissional_id');
            //$this->data['justificativas'] = $this->justificativa_model->get_where(array('profissional_id' => $profissional_id, 'data_plantao >='=>$data_plantao_inicio, 'data_plantao <='=>$data_plantao_fim));
            $this->data['justificativas'] = $this->justificativa_model->get_justificativas_por_profissional($profissional_id, $data_plantao_inicio, $data_plantao_fim, $status);
            //$this->data['justificativas'] = $this->justificativa_model->get_where(array('data_plantao >='=>$data_plantao_inicio, 'data_plantao <='=>$data_plantao_fim));

            foreach ($this->data['justificativas'] as $ct) {
                $ct->turno = '';
                if ((int)$ct->hora_entrada >= 5 && (int)$ct->hora_entrada < 13) {
                    $ct->turno = 'Manhã';
                } else if ((int)$ct->hora_entrada >= 13 && (int)$ct->hora_entrada < 19) {
                    $ct->turno = 'Tarde';
                } else if ((int)$ct->hora_entrada >= 19 && (int)$ct->hora_entrada <= 23) {
                    $ct->turno = 'Noite';
                }
                $ct->status_oportunidade = '';
                switch ($ct->status) {
                case $this::STATUS_JUSTIFICATIVA_APROVADA:
                    $ct->status = 'Deferidas';
                    break;
                case $this::STATUS_JUSTIFICATIVA_NEGADA:
                    $ct->status = 'Indeferidas';
                    break;
                case $this::STATUS_JUSTIFICATIVA_AGUARDANDO:
                    $ct->status = 'Aguardando Aprovação';
                    break;
                case $this::STATUS_JUSTIFICATIVA_IGNORADA:
                    $ct->status = 'Ignorada';
                    break;
                }
                $ct->entrada_sistema = date('H:i', strtotime($this->batida($ct->plantao, 'E')));
                $ct->saida_sistema = date('H:i', strtotime($this->batida($ct->plantao, 'S')));

                $data_plantao = $ct->data_inicial_plantao;
                $profissional_id = $ct->profissional_id;
                $plantao_entrada = $ct->hora_entrada;
                $plantao_saida = $ct->hora_saida;
        
                //$ct->entrada = $ct->hora_entrada;//$this->FrequenciaAssessus_model->get_batida_profissional_entrada($data_plantao, $profissional_id, $plantao_entrada, $plantao_saida);
                //$ct->saida = $ct->hora_saida;//$this->FrequenciaAssessus_model->get_batida_profissional_saida($data_plantao, $profissional_id, $plantao_entrada, $plantao_saida); 
            }

        } else {
            $this->data['justificativas'] = array();
            $data_plantao_inicio = date('Y-m-d');
            $data_plantao_fim = date('Y-m-d');
            $status = $this->input->post('status');
            
        }

        $this->data['data_plantao_inicio'] = array(
            'name'  => 'data_plantao_inicio',
            'id'    => 'data_plantao_inicio',
            'type'  => 'date',
            'class' => 'form-control',
            'value' => $data_plantao_inicio,
        );

        $this->data['data_plantao_fim'] = array(
            'name'  => 'data_plantao_fim',
            'id'    => 'data_plantao_fim',
            'type'  => 'date',
            'class' => 'form-control',
            'value' => $data_plantao_fim,
        );

        $this->data['status'] = array(
            'name'  => 'status',
            'id'    => 'status',
            'type'  => 'select',
            'class' => 'form-control',
            'selected' => $status,
            'options' => $tipos_status,
        );

        // Anderson
        //var_dump($this->data['justificativas']);exit;
        //exit;

        /* Load Template */
        $this->template->admin_render('admin/justificativas/profissional', $this->data);
    }
    
    public function create($escala_id)
    {
        if (!$this->ion_auth->logged_in()) {
            $this->session->set_flashdata('message', 'Você deve estar autenticado para criar uma justificativa.');
            redirect('auth/login', 'refresh');
        }
        if (!$this->ion_auth->in_group($this->_permitted_groups)) {
            $this->session->set_flashdata('message', 'O acesso &agrave; este recurso não é permitido ao seu perfil de usuário.');
            redirect('admin/dashboard', 'refresh');
        }

        /* Breadcrumbs */
        $this->breadcrumbs->unshift(2, lang('menu_justificativas_create'), 'admin/justificativas/create');
        $this->data['breadcrumb'] = $this->breadcrumbs->show();
        $this->load->model('cemerge/FrequenciaAssessus_model');
        $this->load->model('cemerge/frequencia_model');
        $this->load->model('cemerge/escala_model');
        
        $escala_id = (int) $escala_id;
        /* Variables */
        $profissional_id = $this->session->userdata('profissional_id');
        $profissional_nome = $this->session->userdata('nome');
        $setores_profissional = $this->_get_setores_profissional($profissional_id);
        //$setor_id = $this->input->get_post('setor_id');
        $data_plantao_inicio = $this->input->get_post("data_plantao");
        $hora_entrada =  $this->input->post("hora_entrada");
        $hora_saida =  $this->input->post("hora_saida");
        $descricao =  $this->input->post("descricao");
        
            $plantao_entrada = date('H:i', strtotime($this->batida($escala_id, 'E')));
            $plantao_saida = date('H:i', strtotime($this->batida($escala_id, 'S')));

        if($plantao_entrada == '00:00'){
            $plantao_entrada = '';
        }
        if($plantao_saida == '00:00'){
            $plantao_saida = '';
        }

        $escala = $this->escala_model->get_by_id($escala_id);
        $setor_id = $escala->setor_id;
        //var_dump($setor_id); exit;
        //var_dump($plantao_entrada); exit;
        /* Validate form input */
        $this->form_validation->set_rules('descricao', 'lang:justificativas_descricao', 'required');

        // Realizar o insert no model de unidades hospitalares
        if ($this->form_validation->run() == true) {
                
            
            $this->escala_model->update($escala_id, ['justificativa' => 0]);
            $insert_data = array(
                'profissional_id' => $profissional_id,
                'escala_id' => $escala_id,
                'setor_id' => $setor_id,
                'data_plantao' => $data_plantao_inicio,
                'entrada_justificada' => $hora_entrada,
                'saida_justificada' => $hora_saida,
                'descricao' => $descricao,
                'status' => 0,
                'create_at' => date('Y-m-d H:m:i')
            );
            $this->justificativa_model->insert($insert_data);
            $this->session->set_flashdata('message', 'Justificativa inserida com sucesso.');
                redirect('admin/justificativas/profissional', 'refresh');
        } else {
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
            $this->data['profissional_id'] = $profissional_id;
            $this->data['escala_id'] = $this->input->post('plantao_id');
            $this->data['profissional_nome'] = $profissional_nome;      
            $this->data['setor_id'] = array(
                'name'  => 'setor_id',
                'id'    => 'setor_id',
                'type'  => 'select',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('setor_id'),
                'selected' => $escala->setor_id,
                'options' => $setores_profissional,
            );
            $this->data['descricao'] = array(
                'name'  => 'descricao',
                'id'    => 'descricao',
                'type'  => 'textarea',
                'class' => 'form-control',
                'rows'  => '10',
                'value' => $this->form_validation->set_value('descricao'),
            );
            $this->data['data_plantao'] = array(
                'name'  => 'data_plantao',
                'id'    => 'data_plantao',
                'type'  => 'date',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('data_plantao', $escala->dataplantao),
            );
            $this->data['hora_entrada'] = array(
                'name'  => 'hora_entrada',
                'id'    => 'hora_entrada',
                'type'  => 'time',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('hora_entrada'),
            );
            $this->data['hora_saida'] = array(
                'name'  => 'hora_saida',
                'id'    => 'hora_saida',
                'type'  => 'time',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('hora_saida'),
            );

            $this->data['batida_entrada'] = array(
                'name'  => 'batida_entrada',
                'id'    => 'batida_entrada',
                'type'  => 'time',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('batida_entrada', $plantao_entrada),
                'readonly' => true
            );
            
            $this->data['batida_saida'] = array(
                'name'  => 'batida_saida',
                'id'    => 'batida_saida',
                'type'  => 'time',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('batida_saida', $plantao_saida),
                'readonly' => true
            );

            /* Load Template */

            //var_dump($insert_data); exit;
            $this->template->admin_render('admin/justificativas/create', $this->data);
        }
    }

    public function createbyadmin($escala_id, $profissional_id)
    {
        if (!$this->ion_auth->logged_in()) {
            $this->session->set_flashdata('message', 'Você deve estar autenticado para criar uma justificativa.');
            redirect('auth/login', 'refresh');
        }
        if (!$this->ion_auth->in_group($this->_permitted_groups)) {
            $this->session->set_flashdata('message', 'O acesso &agrave; este recurso não é permitido ao seu perfil de usuário.');
            redirect('admin/dashboard', 'refresh');
        }

        /* Breadcrumbs */
        $this->breadcrumbs->unshift(2, lang('menu_justificativas_create'), 'admin/justificativas/create');
        $this->data['breadcrumb'] = $this->breadcrumbs->show();
        $this->load->model('cemerge/FrequenciaAssessus_model');
        $this->load->model('cemerge/frequencia_model');
        $this->load->model('cemerge/escala_model');

        /* Variables */

        $profissional_nome = $this->nome_profissional($profissional_id);
        $setores_profissional = $this->_get_setores_profissional($profissional_id);
        $setor_id = $this->input->post('setor_id');
        $data_plantao_inicio = $this->input->post("data_plantao");
        $hora_entrada =  $this->input->post("hora_entrada");
        $hora_saida =  $this->input->post("hora_saida");
        $descricao =  $this->input->post("descricao");

        $escala_id = (int) $escala_id;
        $plantao_entrada = date('H:i', strtotime($this->batida($escala_id, 'E')));
        $plantao_saida = date('H:i', strtotime($this->batida($escala_id, 'S')));

        if($plantao_entrada == '00:00'){
            $plantao_entrada = '';
        }
        if($plantao_saida == '00:00'){
            $plantao_saida = '';
        }

        $escala = $this->escala_model->get_by_id($escala_id);



        //Coletar dados da batida - Anderson
        //$this->data['entrada'] = $this->frequenciaassessus_model->get_batida($this->data['data_plantao'], $this->data['profissional_id'], 1, $this->data['hora_entrada'], $this->data['hora_saida' ]);
        //$this->data['saida'] = $this->frequenciaassessus_model->get_batida($this->data['data_plantao'], $this->data['profissional_id'], 1, $this->data['hora_entrada'], $this->data['hora_saida' ]);

        /* Validate form input */
        $this->form_validation->set_rules('descricao', 'lang:justificativas_descricao', 'required');


        // Realizar o insert no model de unidades hospitalares
        if ($this->form_validation->run() == true) {

            $insert_data = array(
                'profissional_id' => $profissional_id,
                'escala_id' => $escala_id,
                'setor_id' => $setor_id,
                'data_plantao' => $data_plantao_inicio,
                'entrada_justificada' => $hora_entrada,
                'saida_justificada' => $hora_saida,
                'descricao' => $descricao,
                'status' => 0
            );
            $justificativa_id = $this->justificativa_model->insert($insert_data);
            
            if ($justificativa_id) {
                $this->escala_model->update($escala_id,['justificativa' => 0]);
                if ($hora_entrada >= '18:00'){
                    if($hora_entrada != '00:00'){
                        $this->frequencia_model->insert(['unidadehospitalar_id' => 1, 'setor_id' => $setor_id, 'escala_id' => $escala_id, 'profissional_id' => $profissional_id, 'datahorabatida' => $data_plantao_inicio . ' ' . $hora_entrada, 'tipobatida' => 5]);     
                    } 
                    if($hora_saida != '00:00'){
                        $this->frequencia_model->insert(['unidadehospitalar_id' => 1, 'setor_id' => $setor_id, 'escala_id' => $escala_id, 'profissional_id' => $profissional_id, 'datahorabatida' => strtotime("+1 day", $data_plantao_inicio) . ' ' . $hora_saida, 'tipobatida' => 6]);
                    }
                } else {
                    if ($hora_entrada != '00:00'){
                        $this->frequencia_model->insert([
                            'unidadehospitalar_id' => 1, 
                            'setor_id' => $setor_id, 
                            'escala_id' => $escala_id, 
                            'profissional_id' => $profissional_id, 
                            'datahorabatida' => $data_plantao_inicio . ' ' . $hora_entrada, 
                            'tipobatida' => 5
                        ]);
                    }
                    if($hora_saida != '00:00'){
                        $this->frequencia_model->insert([
                            'unidadehospitalar_id' => 1, 
                            'setor_id' => $setor_id, 
                            'escala_id' => $escala_id, 
                            'profissional_id' => $profissional_id, 
                            'datahorabatida' => $data_plantao_inicio . ' ' . $hora_saida, 
                            'tipobatida' => 6
                        ]);
                    }
                } 
                $this->session->set_flashdata('message', 'Justificativa inserida com sucesso.');
                redirect('admin/justificativas', 'refresh');
            } else {
                $this->session->set_flashdata('message', 'Houve um erro ao inserir a justificativa. Tente novamente.');
                redirect('admin/justificativa/create', 'refresh');
            }
        } else {
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

            $this->data['profissional_id'] = $profissional_id;
            $this->data['escala_id'] = $this->input->post('plantao_id');
            $this->data['profissional_nome'] = $profissional_nome;      
            $this->data['setor_id'] = array(
                'name'  => 'setor_id',
                'id'    => 'setor_id',
                'type'  => 'select',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('setor_id'),
                'selected' => $escala->setor_id,
                'options' => $setores_profissional,
            );
            $this->data['descricao'] = array(
                'name'  => 'descricao',
                'id'    => 'descricao',
                'type'  => 'textarea',
                'class' => 'form-control',
                'rows'  => '10',
                'value' => $this->form_validation->set_value('descricao'),
            );
            $this->data['data_plantao'] = array(
                'name'  => 'data_plantao',
                'id'    => 'data_plantao',
                'type'  => 'date',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('data_plantao', $escala->dataplantao),
            );
            $this->data['hora_entrada'] = array(
                'name'  => 'hora_entrada',
                'id'    => 'hora_entrada',
                'type'  => 'time',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('hora_entrada'),
            );
            $this->data['hora_saida'] = array(
                'name'  => 'hora_saida',
                'id'    => 'hora_saida',
                'type'  => 'time',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('hora_saida'),
            );

            $this->data['batida_entrada'] = array(
                'name'  => 'batida_entrada',
                'id'    => 'batida_entrada',
                'type'  => 'time',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('batida_entrada', $plantao_entrada),
                'readonly' => true
            );
            
            $this->data['batida_saida'] = array(
                'name'  => 'batida_saida',
                'id'    => 'batida_saida',
                'type'  => 'time',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('batida_saida', $plantao_saida),
                'readonly' => true
            );

            /* Load Template */

            //var_dump($insert_data); exit;
            $this->template->admin_render('admin/justificativas/create', $this->data);
        }
    }    

    public function aprovar(){

        
        $id = $this->input->get_post('justificativa');
        $entrada_justificada = $this->input->get_post('entrada');
        $saida_justificada = $this->input->get_post('saida');

        $this->load->model('cemerge/escala_model');
        $this->load->model('cemerge/frequencia_model');
        $this->load->model('cemerge/setor_model');

        $justificativa = $this->justificativa_model->get_by_id($id);
        $escala = $this->escala_model->get_by_id($justificativa->escala_id);
        $setor = $this->setor_model->get_by_id($justificativa->setor_id);
        
        $this->justificativa_model->update($id, ['status' => 1 , 'motivo_recusa' => 'Autorizado mediante análise do coordenador']);
        if ($id) {
            $deferir = $this->escala_model->update($justificativa->escala_id, ['justificativa' => 0, 'status'=> 4]);
            if ( $deferir ){
                $this->frequencia_model->insert(['unidadehospitalar_id' => $setor->unidadehospitalar_id, 'setor_id' => $escala->setor_id, 'escala_id' => $justificativa->escala_id, 'profissional_id' => $justificativa->profissional_id, 'datahorabatida' => $escala->dataplantao . ' ' . $entrada_justificada, 'tipobatida' => 5]);
                $this->frequencia_model->insert(['unidadehospitalar_id' => $setor->unidadehospitalar_id, 'setor_id' => $escala->setor_id, 'escala_id' => $justificativa->escala_id, 'profissional_id' => $justificativa->profissional_id, 'datahorabatida' => $escala->datafinalplantao . ' ' . $saida_justificada, 'tipobatida' => 6]);
            } 
            
        }         
        echo json_encode("sucesso"); exit;
    }

    public function save(){
        
        $id = $this->input->get_post('justificativa');
        $hora_entrada = $this->input->get_post('hora_entrada');
        $hora_saida = $this->input->get_post('hora_saida');
        $this->load->model('cemerge/escala_model');
        $escala = $this->justificativa_model->get_by_id($id);
        $this->justificativa_model->update($id, ['entrada_justificada' => $hora_entrada, 'saida_justificada' => $hora_saida]);
        echo json_encode("sucesso"); exit;
    }

    public function negar($id){

        $this->justificativa_model->update($id, ['status' => 2]);
        echo json_encode("sucesso"); exit;
    }

    public function ignorar(){
        
        $id = $this->input->get_post('justificativa');

        $this->justificativa_model->update($id, ['status' => 4]);
        echo json_encode("sucesso"); exit;
    }
    
    public function validar()
    {
        $escala_id = (int)$this->uri->segment(4, false);

        if (!$this->ion_auth->logged_in()) {
            $this->session->set_flashdata('message', 'Você deve estar autenticado para validar uma justificativa.');
            redirect('auth/login', 'refresh');
        }
        if (!$this->ion_auth->in_group($this->_permitted_groups)) {
            $this->session->set_flashdata('message', 'O acesso &agrave; este recurso não é permitido ao seu perfil de usuário.');
            redirect('admin/escalas/conferencia', 'refresh');
        }
        if (!$escala_id) {
            $this->session->set_flashdata('message', 'Favor informar uma escala válida.');
            redirect('admin/escalas/conferencia', 'refresh');
        }

        /* Breadcrumbs */
        $this->breadcrumbs->unshift(2, lang('menu_justificativas_validar'), 'admin/justificativas/validar');
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        /* Buscando a escala e outros dados */
        $this->load->model('cemerge/escala_model');
        $this->load->model('cemerge/unidadehospitalar_model');
        $this->load->model('cemerge/frequenciaassessus_model');
        $this->load->model('cemerge/frequencia_model');
        $escala = $this->escala_model->get_escala_consolidada_por_id($escala_id);
        $frequencia_entrada = $this->frequenciaassessus_model->get_by_escala_id($escala_id, 1);
        $frequencia_saida = $this->frequenciaassessus_model->get_by_escala_id($escala_id, 2);
        $profissional_id = $escala->id_profissional;
        $profissional_nome = $escala->nome_profissional;
        $setor_id = $escala->idsetor;
        $setor_nome = $escala->nomesetor;
        $unidadehospitalar_id = $escala->idunidade;
        $unidadehospitalar_nome = $escala->nomeunidade;

        /* Validate form input */
        $this->form_validation->set_rules('hora_entrada', 'lang:justificativas_hora_entrada', 'required');
        $this->form_validation->set_rules('hora_saida', 'lang:justificativas_hora_saida', 'required');

        if ($this->form_validation->run() == true) {
            $setor_id = $this->input->post('setor_id');
            $data_plantao = $this->input->post('data_plantao');
            $escala_id = $this->input->post('escala_id');
            $hora_entrada = $this->input->post('hora_entrada');
            $hora_saida = $this->input->post('hora_saida');

            $insert_data = array(
                'profissional_id' => $profissional_id,
                'escala_id' => $escala_id,
                'setor_id' => $setor_id,
                'data_plantao' => $data_plantao,
                'hora_entrada' => $hora_entrada,
                'hora_saida' => $hora_saida,
                'descricao' => '',
                'status' => 1, // Aprovada pelo coordenador
            );

            // Realizar o insert
            $justificativa_id = $this->justificativa_model->insert($insert_data);

            // Inserir frequências com status 5 e 6
            if ($justificativa_id) {
                $insert_entrada = array (
                    'unidadehospitalar_id' => $unidadehospitalar_id,
                    'setor_id' => $setor_id,
                    'escala_id' => $escala->id,
                    'profissional_id' => $escala->id_profissional,
                    'datahorabatida' => $data_plantao . ' ' . $hora_entrada,
                    'tipobatida' => 5,
                );
                $frequencia_entrada_id = $this->frequencia_model->insert($insert_entrada);
                $insert_saida = array (
                    'unidadehospitalar_id' => $unidadehospitalar_id,
                    'setor_id' => $setor_id,
                    'escala_id' => $escala->id,
                    'profissional_id' => $escala->id_profissional,
                    'datahorabatida' => $data_plantao . ' ' . $hora_saida,
                    'tipobatida' => 6,
                );
                $frequencia_saida_id = $this->frequencia_model->insert($insert_saida);
                // Atualizar os horários e status da escala para OK
                $update_escala = array(
                    'frequencia_entrada_id' => $frequencia_entrada_id,
                    'frequencia_saida_id' => $frequencia_saida_id,
                    'status' => 1
                );
                $this->escala_model->update($escala_id, $update_escala);
                $this->session->set_flashdata('message', 'Justificativa inserida com sucesso.');
                redirect('admin/escalas/conferencia', 'refresh');
            } else {
                $this->session->set_flashdata('message', 'Houve um erro ao inserir a justificativa. Tente novamente.');
                redirect('admin/escalas/conferencia', 'refresh');
            }
        } else {
            $this->data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));
            $this->session->set_flashdata('message', $this->data['message']);

            $this->data['profissional_id'] = $profissional_id;
            $this->data['profissional_nome'] = $profissional_nome;
            $this->data['escala_id'] = $escala_id;
            $this->data['setor_id'] = $setor_id;
            $this->data['setor_nome'] = $setor_nome;
            $this->data['data_plantao'] = $escala->dataplantao;

            if ($frequencia_entrada) {
                $hora_entrada = date('H:i', strtotime($frequencia_entrada->DT_FRQ));
            } else {
                $hora_entrada = '';
            }
            if ($frequencia_saida) {
                $hora_saida = date('H:i', strtotime($frequencia_saida->DT_FRQ));
            } else {
                $hora_saida = '';
            }
            $this->data['hora_entrada'] = array(
                'name'  => 'hora_entrada',
                'id'    => 'hora_entrada',
                'type'  => 'time',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('hora_entrada', $hora_entrada),
            );
            $this->data['hora_saida'] = array(
                'name'  => 'hora_saida',
                'id'    => 'hora_saida',
                'type'  => 'time',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('hora_saida', $hora_saida),
            );

            /* Load Template */
            $this->template->admin_render('admin/justificativas/validar', $this->data);
        }
    }

    public function edit($id)
    {
        if (!$this->ion_auth->logged_in()) {
            $this->session->set_flashdata('message', 'Você deve estar autenticado para usar esta função.');
            redirect('auth/login', 'refresh');
        }
        if (!$this->ion_auth->in_group($this->_permitted_groups)) {
            $this->session->set_flashdata('message', 'O acesso &agrave; este recurso não é permitido ao seu perfil de usuário.');
            redirect('admin/dashboard', 'refresh');
        }

        $id = (int) $id;

        /* Breadcrumbs */
        $this->breadcrumbs->unshift(2, lang('menu_justificativas_edit'), 'admin/justificativas/edit');
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        /* Load Data */
        $justificativa = $this->justificativa_model->get_by_id($id);

        if ($justificativa->entrada_justificada == '00:00:00'){
            $justificativa->entrada_justificada = '';
        }

        if ($justificativa->saida_justificada == '00:00:00'){
            $justificativa->saida_justificada = '';
        }
        
        $this->load->model('cemerge/profissional_model');
        $profissional = $this->profissional_model->get_by_id($justificativa->profissional_id);
        $this->load->model('cemerge/setor_model');
        $setor = $this->setor_model->get_by_id($justificativa->setor_id);
        $this->load->model('cemerge/frequenciaassessus_model');
        /* Variables */
        $setores_profissional = $this->_get_setores_profissional($profissional->id);


        /* Validate form input */
        $this->form_validation->set_rules('data_plantao', 'lang:justificativas_data_plantao', 'required');
        $this->form_validation->set_rules('descricao', 'lang:justificativas_descricao', 'required');

        if (isset($_POST) and !empty($_POST)) {
            if ($this->_valid_csrf_nonce() === false or $id != $this->input->post('id')) {
                show_error($this->lang->line('error_csrf'));
            }

            if ($this->form_validation->run() == true) {
                $data = array(
                    'profissional_id' => $this->input->post('profissional_id'),
                    'setor_id' => $this->input->post('setor_id'),
                    'data_plantao' => $this->input->post('data_plantao'),
                    'entrada_justificada' => $this->input->post('hora_entrada'),
                    'saida_justificada' => $this->input->post('hora_saida'),
                    'descricao' => $this->input->post('descricao'),
                );

                if ($this->justificativa_model->update($justificativa->id, $data)) {
                    $this->session->set_flashdata('message', 'Justificativa atualizada com sucesso.');
                } else {
                    $this->session->set_flashdata('message', $this->ion_auth->errors());
                }
                redirect('admin/justificativas', 'refresh');
            }
        }

        // display the edit user form
        $this->data['csrf'] = $this->_get_csrf_nonce();

        // set the flash data error message if there is one
        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

        // pass the justificativa to the view
        $this->data['justificativa'] = $justificativa;
        $this->data['profissional_id'] = $justificativa->profissional_id;
        $this->data['profissional_nome'] = $profissional->nome;
        $this->data['setor_id'] = array(
            'name'  => 'setor_id',
            'id'    => 'setor_id',
            'type'  => 'select',
            'class' => 'form-control',
            'options' => $setores_profissional,
            'selected' => $justificativa->setor_id,
        );
        $this->data['descricao'] = array(
            'name'  => 'descricao',
            'id'    => 'descricao',
            'type'  => 'textarea',
            'class' => 'form-control',
            'rows'  => '10',
            'value' => $this->form_validation->set_value('descricao', $justificativa->descricao),
        );
        $this->data['data_plantao'] = array(
            'name'  => 'data_plantao',
            'id'    => 'data_plantao',
            'type'  => 'date',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('data_plantao', $justificativa->data_plantao),
        );
        $this->data['hora_entrada'] = array(
            'name'  => 'hora_entrada',
            'id'    => 'hora_entrada',
            'type'  => 'time',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('hora_entrada', $justificativa->entrada_justificada),
        );
        $this->data['hora_saida'] = array(
            'name'  => 'hora_saida',
            'id'    => 'hora_saida',
            'type'  => 'time',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('hora_saida', $justificativa->saida_justificada),
        );

        /* Load Template */
        $this->template->admin_render('admin/justificativas/edit', $this->data);
    }

    public function edit_ajax()
    {

        $id = (int) $this->input->get_post('justificativa');

        
        $this->load->model('cemerge/profissional_model');
        $profissional = $this->profissional_model->get_by_id($justificativa->profissional_id);
        $this->load->model('cemerge/setor_model');
        $setor = $this->setor_model->get_by_id($justificativa->setor_id);
        $this->load->model('cemerge/frequenciaassessus_model');
        /* Variables */
        $setores_profissional = $this->_get_setores_profissional($profissional->id);


        /* Validate form input */
        $this->form_validation->set_rules('data_plantao', 'lang:justificativas_data_plantao', 'required');
        $this->form_validation->set_rules('descricao', 'lang:justificativas_descricao', 'required');

        if (isset($_POST) and !empty($_POST)) {
            if ($this->_valid_csrf_nonce() === false or $id != $this->input->post('id')) {
                show_error($this->lang->line('error_csrf'));
            }

            if ($this->form_validation->run() == true) {
                $data = array(
                    'profissional_id' => $this->input->post('profissional_id'),
                    'setor_id' => $this->input->post('setor_id'),
                    'data_plantao' => $this->input->post('data_plantao'),
                    'entrada_justificada' => $this->input->post('hora_entrada'),
                    'saida_justificada' => $this->input->post('hora_saida'),
                    'descricao' => $this->input->post('descricao'),
                );

                if ($this->justificativa_model->update($justificativa->id, $data)) {
                    $this->session->set_flashdata('message', 'Justificativa atualizada com sucesso.');
                } else {
                    $this->session->set_flashdata('message', $this->ion_auth->errors());
                }
                redirect('admin/justificativas', 'refresh');
            }
        }

        // display the edit user form
        $this->data['csrf'] = $this->_get_csrf_nonce();

        // set the flash data error message if there is one
        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

        // pass the justificativa to the view
        $this->data['justificativa'] = $justificativa;
        $this->data['profissional_id'] = $justificativa->profissional_id;
        $this->data['profissional_nome'] = $profissional->nome;
        $this->data['setor_id'] = array(
            'name'  => 'setor_id',
            'id'    => 'setor_id',
            'type'  => 'select',
            'class' => 'form-control',
            'options' => $setores_profissional,
            'selected' => $justificativa->setor_id,
        );
        $this->data['descricao'] = array(
            'name'  => 'descricao',
            'id'    => 'descricao',
            'type'  => 'textarea',
            'class' => 'form-control',
            'rows'  => '10',
            'value' => $this->form_validation->set_value('descricao', $justificativa->descricao),
        );
        $this->data['data_plantao'] = array(
            'name'  => 'data_plantao',
            'id'    => 'data_plantao',
            'type'  => 'date',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('data_plantao', $justificativa->data_plantao),
        );
        $this->data['hora_entrada'] = array(
            'name'  => 'hora_entrada',
            'id'    => 'hora_entrada',
            'type'  => 'time',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('hora_entrada', $justificativa->entrada_justificada),
        );
        $this->data['hora_saida'] = array(
            'name'  => 'hora_saida',
            'id'    => 'hora_saida',
            'type'  => 'time',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('hora_saida', $justificativa->saida_justificada),
        );

        /* Load Template */
        $this->template->admin_render('admin/justificativas/edit', $this->data);
    }


    public function edit_recusa()
    {

        $id = (int) $this->input->get_post('justificativa');
        /* Load Data */
        $justificativa = $this->justificativa_model->get_by_id($id);
        $data = array(
            'motivo_recusa' => $this->input->post('motivo_recusa'),
            'status' => 2,
        );
        
        $this->justificativa_model->update($justificativa->id, $data);

        echo json_encode('sucesso'); exit;
    }

    public function view($id)
    {
        if (!$this->ion_auth->logged_in()) {
            $this->session->set_flashdata('message', 'Você deve estar autenticado para usar esta função.');
            redirect('auth/login', 'refresh');
        }
        if (!$this->ion_auth->in_group($this->_permitted_groups)) {
            $this->session->set_flashdata('message', 'O acesso &agrave; este recurso não é permitido ao seu perfil de usuário.');
            redirect('admin/dashboard', 'refresh');
        }

        /* Load aditional models */
        $this->load->model('cemerge/profissional_model');
        $this->load->model('cemerge/setor_model');
        $this->load->model('cemerge/FrequenciaAssessus_model');
        $this->load->model('cemerge/Escala_model');

        /* Breadcrumbs */
        $this->breadcrumbs->unshift(2, lang('menu_justificativas_view'), 'admin/justificativas/view');
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        /* Data */
        $id = (int) $id;

        $this->data['justificativa'] = $this->justificativa_model->get_by_id($id);
        $this->data['profissional'] = $this->profissional_model->get_by_id($this->data['justificativa']->profissional_id);
        $this->data['setor'] = $this->setor_model->get_by_id($this->data['justificativa']->setor_id);

        $data_plantao = $this->data['justificativa']->data_plantao;
        $profissional_id = $this->data['justificativa']->profissional_id;
        $plantao_entrada = date('H:i', strtotime($this->batida($this->data['justificativa']->escala_id, 'E')));
        $plantao_saida = date('H:i', strtotime($this->batida($this->data['justificativa']->escala_id, 'S')));
        $this->data['justificativa']->turno = $this->turno($this->hora_turno($this->data['justificativa']->escala_id));

        //var_dump($turno); exit;

        $this->data['batida_entrada'] = date('H:i', strtotime($this->batida($this->data['justificativa']->escala_id, 'E')));
        $this->data['batida_saida'] = date('H:i', strtotime($this->batida($this->data['justificativa']->escala_id, 'S')));

        //var_dump($this->data['batida_entrada']); exit;

        /* Load Template */
        $this->template->admin_render('admin/justificativas/view', $this->data);
    }

    public function viewp($id)
    {
        if (!$this->ion_auth->logged_in()) {
            $this->session->set_flashdata('message', 'Você deve estar autenticado para usar esta função.');
            redirect('auth/login', 'refresh');
        }
        if (!$this->ion_auth->in_group($this->_permitted_groups)) {
            $this->session->set_flashdata('message', 'O acesso &agrave; este recurso não é permitido ao seu perfil de usuário.');
            redirect('admin/dashboard', 'refresh');
        }

        /* Load aditional models */
        $this->load->model('cemerge/profissional_model');
        $this->load->model('cemerge/setor_model');
        $this->load->model('cemerge/FrequenciaAssessus_model');
        $this->load->model('cemerge/Escala_model');

        /* Breadcrumbs */
        $this->breadcrumbs->unshift(2, lang('menu_justificativas_view'), 'admin/justificativas/view');
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        /* Data */
        $id = (int) $id;

        $this->data['justificativa'] = $this->justificativa_model->get_by_id($id);
        $this->data['profissional'] = $this->profissional_model->get_by_id($this->data['justificativa']->profissional_id);
        $this->data['setor'] = $this->setor_model->get_by_id($this->data['justificativa']->setor_id);

        $data_plantao = $this->data['justificativa']->data_plantao;
        $profissional_id = $this->data['justificativa']->profissional_id;
        $plantao_entrada = $this->data['justificativa']->hora_entrada;
        $plantao_saida = $this->data['justificativa']->hora_saida;
  

        $this->data['batida_entrada'] = date('H:i', strtotime($this->batida($this->data['justificativa']->escala_id, 'E')));
        $this->data['batida_saida'] = date('H:i', strtotime($this->batida($this->data['justificativa']->escala_id, 'S')));

        //var_dump($this->data); exit;

        /* Load Template */
        $this->template->admin_render('admin/justificativas/viewp', $this->data);
    }

    public function _get_unidadeshospitalares()
    {
        $this->load->model('cemerge/unidadehospitalar_model');
        $unidades = $this->unidadehospitalar_model->get_all();

        $unidadeshospitalares = array(
            '' => 'Selecione uma unidade hospitalar',
        );
        foreach ($unidades as $unidade) {
            $unidadeshospitalares[$unidade->id] = $unidade->razaosocial;
        }

        return $unidadeshospitalares;
    }

    public function justificativa_view(){
        $this->load->model('cemerge/profissional_model');
        $this->load->model('cemerge/setor_model');
        $this->load->model('cemerge/escala_model');
        $id = $this->input->post('justificativa');

        $justificativa = $this->justificativa_model->get_by_id($id);
        $justificativa->nome_profissional = $this->profissional_model->get_by_id($justificativa->profissional_id)->nome;
        $justificativa->nome_setor = $this->setor_model->get_by_id($justificativa->setor_id)->nome;
        $justificativa->data = date('d/m/Y', strtotime($justificativa->data_plantao));
        $justificativa->hora_entrada = $this->escala_model->get_by_id($justificativa->escala_id)->horainicialplantao;
        
        $turno = '';
        if ($justificativa->hora_entrada == '07:00:00'){
            $turno = 'Manhã';
        } else if ($justificativa->hora_entrada == '13:00:00'){
            $turno = 'Tarde';
        } else {
            $turno = 'Noite';
        }
        
        $entrada_sistema = date('H:i', strtotime($this->batida($justificativa->escala_id, 'E')));
        $saida_sistema = date('H:i', strtotime($this->batida($justificativa->escala_id, 'S')));

        if ($entrada_sistema == '00:00'){
            $entrada_sistema = '-';
        }
        if ($saida_sistema == '00:00'){
            $saida_sistema = '-';
        }
        if ($justificativa->entrada_justificada == '00:00:00'){
            $justificativa->entrada_justificada = '-';
        } else {
            $justificativa->entrada_justificada = date('H:i', strtotime($justificativa->entrada_justificada));
        }
        if ($justificativa->saida_justificada == '00:00:00'){
            $justificativa->saida_justificada = '-';
        } else {
            $justificativa->saida_justificada = date('H:i',strtotime($justificativa->saida_justificada));
        }

        echo json_encode([
            'medico' => $justificativa->nome_profissional, 
            'setor' => $justificativa->nome_setor, 
            'data' => $justificativa->data, 
            'turno' => $turno,
            'entrada_sistema' => $entrada_sistema,
            'saida_sistema' => $saida_sistema,
            'entrada_justificada' => $justificativa->entrada_justificada,
            'saida_justificada' => $justificativa->saida_justificada,
            'descricao' => $justificativa->descricao,
            'status' => $justificativa->status,
            'motivo' =>$justificativa->motivo_recusa
        ]);

        exit;
    }

    public function _get_setores_profissional($profissional_id)
    {
        $this->load->model('cemerge/setor_model');
        $setores = $this->setor_model->get_setores_por_profissional($profissional_id);

        $setores_profissional = array(
            '' => 'Selecione um setor',
        );
        foreach ($setores as $setor) {
            $setores_profissional[$setor->id] = $setor->nome;
        }

        return $setores_profissional;
    }

    public function _get_vinculos()
    {
        $vinculos = $this->vinculo_model->get_all();

        $v = array();
        foreach ($vinculos as $vinculo) {
            $v[$vinculo->id] = $vinculo->nome;
        }

        return $v;
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

    public function ajax_justificativas_pendentes() {

        if (!$this->input->is_ajax_request()) {
            exit("Nenhum acesso de script direto permitido!");
        }
            
        $this->load->model("cemerge/profissional_model");
        $this->load->model("cemerge/justificativa_model");

        /* Profissional */
        $this->load->model("cemerge/profissional_model");
        $this->load->model('cemerge/usuarioprofissional_model');
        $userId = $this->ion_auth->user()->row()->id;
        if ($this->ion_auth->in_group('profissionais') && $userId) {
            $usuarioProfissional = $this->usuarioprofissional_model->get_where(['user_id' => $userId]);
            if ($usuarioProfissional) {
                $this->_profissional = $this->profissional_model->get_where(['id' => $usuarioProfissional[0]->profissional_id])[0];
            }
        } else {
            $this->_profissional = 1;
        }

        $profissionais = $this->justificativa_model->get_datatable($this->_profissional->id);
        //var_dump($this->_profissional); exit;

        $data = array();
        foreach ($profissionais as $profissional) {
    
            $row = array();
            //$row[] = '<center>'.$profissional->id.'</center>';
            $row[] = '<center>'.date('d/m/Y',strtotime($profissional->dataplantao)).'</center>';
            $row[] = '<center>'.$this->nome_setor($profissional->setor_id).'</center>';
            $row[] = '<center>'.$this->turno($profissional->horainicialplantao).'</center>';

            //var_dump($profissional);exit;
            
            $row[] = '<center><div style="display: inline-block;">
                        <button class="btn btn-link btn-add-profissional" 
                            plantao='.$profissional->escala_id.'><a style="color:green; font-size:20px;" href="/sgc/admin/justificativas/create/'.
                            $profissional->escala_id.'" 
                    <i class="fa fa-pencil-square-o">Justificar</i></a>
                            
                        </button>
                    </div></center>';
    
                    
            $data[] = $row;
    
        }
        $json = array(
            "draw" => $this->input->post("draw"),
            "recordsTotal" =>$this->justificativa_model->records_total(),
            //"recordsFiltered" => $this->justificativa_model->records_datatable_filtered($this->_profissional->id),
            "status" => 1,
            "data" => $data,
        ); 
        echo json_encode($json);
        exit;
    }


    public function ajax_escalas_consolidadas_profissional() {

        if (!$this->input->is_ajax_request()) {
            exit("Nenhum acesso de script direto permitido!");
        }

        /* Profissional */
        $this->load->model('cemerge/usuarioprofissional_model');
        $userId = $this->ion_auth->user()->row()->id;
        if ($this->ion_auth->in_group('profissionais') && $userId) {
            $usuarioProfissional = $this->usuarioprofissional_model->get_where(['user_id' => $userId]);
            if ($usuarioProfissional) {
                $this->_profissional = $this->profissional_model->get_where(['id' => $usuarioProfissional[0]->profissional_id])[0];
            }
        } else {
            $this->_profissional = 1;
        }

        $profissional_id = $this->_profissional->id;
        $datainicial = $this->input->get_post("datainicial");
        $datafinal = $this->input->get_post("datafinal");

        $plantoes = $this->escala_model->get_escalas_consolidadas_datatable($profissional_id, $datainicial, $datafinal);

        $data = array();
        foreach ($plantoes as $plantao) {
    
            $row = array();
            //$row[] = '<center>'.$profissional->id.'</center>';
            $row[] = '<center>'.date('d/m/Y',strtotime($profissional->dataplantao)).'</center>';
            $row[] = '<center>'.$this->nome_setor($profissional->setor_id).'</center>';
            $row[] = '<center>'.$this->turno($profissional->horainicialplantao).'</center>';

            //var_dump($profissional);exit;
            
            $row[] = '<center><div style="display: inline-block;">
                        <button class="btn btn-link btn-add-profissional" 
                            plantao='.$profissional->escala_id.'><a style="color:green; font-size:20px;" href="/sgc/admin/justificativas/create/'.
                            $profissional->escala_id.'" 
                    <i class="fa fa-pencil-square-o">Justificar</i></a>
                            
                        </button>
                    </div></center>';
    
                    
            $data[] = $row;
    
        }
        $json = array(
            "draw" => $this->input->post("draw"),
            "recordsTotal" =>$this->justificativa_model->records_total(),
            //"recordsFiltered" => $this->justificativa_model->records_datatable_filtered($this->_profissional->id),
            "status" => 1,
            "data" => $data,
        ); 
        echo json_encode($json);
        exit;
    }

    public function ajax_justificativas_pendentes_coordenador($data_ini, $data_fim) {

        /*if (!$this->input->is_ajax_request()) {
            exit("Nenhum acesso de script direto permitido!");
        }*/
            
        $this->load->model("cemerge/profissional_model");
        $this->load->model("cemerge/justificativa_model");


        $profissionais = $this->justificativa_model->get_justificativas_pendentes($data_ini, $data_fim);

        $data = array();
        foreach ($profissionais as $profissional) {

    
            $row = array();
            //$row[] = '<center>'.$profissional->id.'</center>';
            $row[] = '<center>'.$profissional->dataplantao.'</center>';
            $row[] = '<center>'.$profissional->setor_nome.'</center>';
            $row[] = '<center>'.$profissional->profissional_nome.'</center>';
            $row[] = '<center>'.$this->turno($profissional->horainicialplantao).'</center>';
            
            $row[] = '<center><div style="display: inline-block;">
                        <button class="btn btn-link btn-add-profissional" 
                            plantao='.$profissional->escala_id.'><a style="color:green; font-size:20px;" href="/sgc/admin/justificativas/createbyadmin/'.
                            $profissional->escala_id.'/'.$profissional->profissional_id.
                    '"<i class="fa fa-pencil-square-o">Justificar</i></a>
                        </button>
                    </div></center>';

            $data[] = $row;
        }
        
        $json = array(
            "draw" => $this->input->post("deaw"),
            "recordsTotal" =>$this->justificativa_model->records_total(),
            //"recordsFiltered" => $this->justificativa_model->records_pendentes_filtered(),
            "status" => 1,
            "data" => $data,
        ); 
        echo json_encode($json);
        exit;
    }

    public function nome_setor($id){
        $this->load->model("cemerge/setor_model");

        $setor = $this->setor_model->get_by_id($id);

        return $setor->nome;
    }

    public function nome_profissional($id){
        $this->load->model("cemerge/profissional_model");

        $profissional = $this->profissional_model->get_by_id($id);

        return $profissional->nome;
    }

    public function batida($id, $tipo){
        $this->load->model("cemerge/escala_model");

        $batida = $this->escala_model->get_by_id($id);

        if ($tipo == 'E'){
            return $this->frequencia($batida->dataplantao, $batida->frequencia_entrada_id);
        } else {
            return $this->frequencia($batida->dataplantao, $batida->frequencia_saida_id);
        }
    }

    public function hora_turno($id){
        $this->load->model("cemerge/escala_model");

        $batida = $this->escala_model->get_by_id($id);
        return date('H:i:s', strtotime($batida->horainicialplantao));

    }

    public function frequencia($dataplantao, $id){
        $this->load->model("cemerge/FrequenciaAssessus_model");
        $this->load->model("cemerge/Frequencia_model");
        if($dataplantao >= '2021-06-21'){
            $frequencia = $this->Frequencia_model->get_by_id($id);
                if (isset($frequencia->datahorabatida)){
                    return $frequencia->datahorabatida;
                } else {
                    return '1901-01-01 00:00:00';
                }
        } else {
            $frequencia = $this->FrequenciaAssessus_model->get_by_cdctlfrq($id);
                if (isset($frequencia->DT_FRQ)){
                    return $frequencia->DT_FRQ;
                } else {
                    return '1901-01-01 00:00:00';
                }
        }
    }

    public function turno($i) {
        $retorno = '';
        if ($i == '07:00:00'){
            $retorno = 'Manhã';
        } else if ($i == '13:00:00'){
            $retorno = 'Tarde';
        }
        else {
            $retorno = 'Noite';
        }

        return $retorno;
    }
}
