<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Escalas extends Admin_Controller
{
    /** Status de escalas */
    const STATUS_ESCALA_INCOMPLETA = 0;
    const STATUS_ESCALA_OK = 1;
    const STATUS_ESCALA_AGUARDANDO_JUSTIFICATIVA = 2;
    const STATUS_ESCALA_FALTA = 3;

    /** Tipo de plantão */
    const TIPO_PLANTAO_FIXO = 0;
    const TIPO_PLANTAO_VOLATIL = 1;

    /** Plantão extra */
    const PLANTAO_EXTRA_NAO = 0;
    const PLANTAO_EXTRA_SIM = 1;

    /** Tipo de passagem */
    const TIPO_PASSAGEM_CESSAO = 0;
    const TIPO_PASSAGEM_TROCA = 1;

    /** Status de passagem */
    const STATUS_PASSAGEM_ACONFIRMAR = 0;
    const STATUS_PASSAGEM_CONFIRMADA = 1;
    const STATUS_PASSAGEM_TROCA_PROPOSTA = 2;
    const STATUS_PASSAGEM_REPASSADO = 3;

    const TIPO_NOTIFICACAO_EMAIL = 0;

    private $_permitted_groups = array('admin', 'profissionais', 'coordenadorplantao', 'sac', 'sad');
    private $_admin_groups = array('admin', 'sac', 'sad', 'coordenadorplantao');
    private $_profissional = null;

    private $_diasdasemana = array('Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado');

    public function __construct()
    {
        parent::__construct($this->_permitted_groups);

        /* Load :: Common */
        $this->load->model('cemerge/escala_model');
        $this->load->model('cemerge/unidadehospitalar_model');
        $this->load->model('cemerge/setor_model');
        $this->load->model('cemerge/profissional_model');
        $this->load->model('cemerge/frequenciaassessus_model');
        $this->load->model('cemerge/frequencia_model');
        $this->lang->load('admin/escalas');
        $this->load->helper('messages');
        $this->lang->load('admin/profissionais');

        $this->load->library('email');

        /* Title Page */
        $this->page_title->push(lang('menu_escalas'));
        $this->data['pagetitle'] = $this->page_title->show();

        /* Breadcrumbs :: Common */
        $this->breadcrumbs->unshift(1, lang('menu_escalas'), 'admin/escalas');
    }


    public function ajax_escalas_consolidadas_profissional() {

        if (!$this->input->is_ajax_request()) {
            exit("Nenhum acesso de script direto permitido!");
        }

        $profissional_id = $this->input-get_post("profissional_id");
        $datainicial = $this->input-get_post("datainicial");
        $datafinal = $this->input-get_post("datafinal");

        $plantoes = $this->escala_model->get_escalas_consolidadas_datatable($profissional_id, $datainicial, $datafinal);

        $data = array();
        foreach ($plantoes as $plantao) {
    
            $row = array();
            $row[] = '<center>'.$plantao->id_evento.'</center>';
            $row[] = '<center>'.$this->Evento_model->get_evento_by_id($plantao->id_evento)->nome.'</center>';
            $row[] = '<center>'.$plantao->tp_plantao.'</center>';
            if($this->Evento_model->get_evento_by_id($plantao->id_evento)->percentual == 1){
                $row[] = '<center>'.$plantao->valor_ref.'%</center>';
            } else {
                $row[] = '<center>'.$plantao->valor_ref.'</center>';
            }
            

            if($plantao->tipo == 'C'){
                $row[] = '<center>'.($plantao->quantidade*$plantao->valor_ref).'</center>';
                $row[] = '';
            } else {
                $row[] = '';
                $row[] = '<center>'.($plantao->quantidade*$plantao->valor_ref).'</center>';
            }
    
            $row[] = '<center><div style="display: inline-block;">
                        <button class="btn btn-primary btn-edit-evento-plantao" 
                            id='.$plantao->id.'>
                            <i class="fa fa-edit"></i>
                        </button>
                        <button class="btn btn-danger btn-del-evento_plantao" 
                            id='.$plantao->id.'>
                            <i class="fa fa-times"></i>
                        </button>
                    </div></center>';
    
            $data[] = $row;
    
        }
        $json = array(
            "draw" => $this->input->post("draw"),
            "recordsTotal" =>$this->Folha_model->records_total(),
            "recordsFiltered" => $this->Folha_model->records_filtered(),
            "status" => 1,
            "data" => $data,
        ); 
        echo json_encode($json);
        exit;
    }

    public function index()
    {
        if (!$this->ion_auth->logged_in() or !$this->ion_auth->in_group($this->_permitted_groups)) {
            redirect('auth/login', 'refresh');
        } else {
            /* Breadcrumbs */
            $this->data['breadcrumb'] = $this->breadcrumbs->show();

            /* Reset */
            $this->data['escalas'] = array();
            $gerar_pdf = 0;

            /* Variables */
            $this->data['diasdasemana'] = $this->_diasdasemana;

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

                $gerar_pdf = $this->input->post('bt_gerar_pdf');

                $setores = $this->_get_setores($unidadehospitalar_id);
                
                $unidadehospitalar = array();
                $unidadehospitalar = $this->unidadehospitalar_model->get_by_id($unidadehospitalar_id);
                $this->data['unidade_nome'] = $unidadehospitalar->razaosocial;
                $this->data['setor_nome'] =  $this->setor_model->get_setor_por_id($setor_id);
                
                // Realizando a busca
                $where = array(
                    'unidadehospitalar_id' => $unidadehospitalar_id,
                    'escalas.setor_id' => $setor_id,
                    'escalas.dataplantao >=' => $datainicial,
                    'escalas.dataplantao <=' => $datafinal,
                );

                // Lista
                if ($tipoescala == 0 and $tipovisualizacao == 0) {
                    $this->data['escalas'] = $this->escala_model->get_escalas_originais($where, null, null, 'dataplantao, escalas.horainicialplantao'); // dataplantao, horainicialplantao');
                } elseif ($tipoescala == 1 and $tipovisualizacao == 0) {
                    $this->data['escalas'] = $this->escala_model->get_escalas_consolidadas($where, null, null, 'profissional_nome, dataplantao'); //, dataplantao, horainicialplantao');
                } elseif ($tipoescala == 2 and $tipovisualizacao == 0) {
                    $this->data['escalas'] = $this->escala_model->get_passagens_trocas($where, null, null, 'profissional_nome, dataplantao'); //, dataplantao, horainicialplantao');
                }

                // Calendário
                if ($tipovisualizacao == 1) {
                    if ($tipoescala == 0) {
                        $this->data['escalas'] = $this->escala_model->get_escalas_originais_cal($setor_id, $datainicial, $datafinal);
                    } elseif ($tipoescala == 1) {
                        $this->data['escalas'] = $this->escala_model->get_escalas_consolidadas_cal($setor_id, $datainicial, $datafinal);
                    } elseif ($tipoescala == 2) {
                        $this->data['escalas'] = $this->escala_model->get_passagens_trocas_cal($setor_id, $datainicial, $datafinal);
                        //$this->data['escalas'] = null;
                    }
                    $this->load->library('calendar');
                    if ($this->calendar->init($datainicial, $datafinal, $this->data['escalas'])) {
                        $this->data["calendario"] = $this->calendar->generate();
                    } else {
                        $this->data["calendario"] = "Não há dados a exibir ou este relatório não é possível de exibir no calendário. Tente o tipo de visualização lista.";
                    }
                }
                
                // Grade de frequência
                if ($tipoescala == 1 and $tipovisualizacao == 2) {
                    $this->load->helper('group_by');
                    $escalas = $this->escala_model->get_escalas_consolidadas_grade_frequencia(5, 2020, 10); //$setor_id, $ano, $mes Trocar pelo mês selecionado
                    $this->data['escalas'] = group_by('nome_profissional', $escalas);
                    $profissionais_escala = array();
                    foreach ($escalas as $escala) {
                        array_push($profissionais_escala, $escala->nome_profissional);
                    }
                    $this->data['profissionais_escala'] = array_unique($profissionais_escala);
                    $this->data['ultimo_dia_mes'] = date('t', strtotime('2020-10-01')); // Trocar pelo mês selecionado
                }
            } else {
                $datainicial = date('Y-m-01');
                $datafinal = date('Y-m-t');
                if ($this->input->post('unidadehospitalar_id')) {
                    $setores = $this->_get_setores($this->input->post('unidadehospitalar_id'));
                } else {
                    $setores = array('' => 'Selecione um setor');
                }
                $this->data['tipovisualizacao'] = 0;
            }

            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

            $unidadeshospitalares = $this->_get_unidadeshospitalares();

            $tiposescala = $this->_get_tipos_escala();
            $tiposvisualizacao = $this->_get_tipos_visualizacao();
            $tipos = array(
                '0' => 'Todos',
                '1' => 'Plantonista',
                '2' => 'Prescritor',
                '3' => 'Diarista'
            );
            $this->data['tipos'] = array(
                'name'  => 'tipos',
                'id'    => 'tipos',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('tipos'),
                'options' => $tipos,
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

            // Teste para geração de pdf
            $gerar_pdf = false;
            if ($this->form_validation->run() == true && $gerar_pdf == true) {
                $this->load->library('Pdf');
                $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

                // set document information
                $pdf->SetCreator(PDF_CREATOR);
                $pdf->SetAuthor('Nicola Asuni');
                $pdf->SetTitle('TCPDF Example 061');
                $pdf->SetSubject('TCPDF Tutorial');
                $pdf->SetKeywords('TCPDF, PDF, example, test, guide');

                // set default header data
                $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 061', PDF_HEADER_STRING);

                // set header and footer fonts
                $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
                $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

                // set default monospaced font
                $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

                // set margins
                $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
                $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
                $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

                // set auto page breaks
                $pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);

                // set image scale factor
                $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);


                // ---------------------------------------------------------

                // add a page
                $pdf->AddPage('L', 'A4');

                /* NOTE:
                * *********************************************************
                * You can load external XHTML using :
                *
                * $html = file_get_contents('/path/to/your/file.html');
                *
                * External CSS files will be automatically loaded.
                * Sometimes you need to fix the path of the external CSS.
                * *********************************************************
                */

                // define some HTML content with style
                //$template['header'] = $this->load->view('admin/_templates/header_pdf', $this->data, true);
                $template['content'] = $this->load->view('admin/escalas/index_pdf', $this->data, true);
                $html = $this->load->view('admin/_templates/template', $template, true);

                // output the HTML content
                $pdf->writeHTML($html, true, false, true, false, '');

                // reset pointer to the last page
                $pdf->lastPage();

                //Close and output PDF document
                $pdf->Output('example_061.pdf', 'I');

                /*
                $mpdf = new \Mpdf\Mpdf();
                $template['header'] = $this->load->view('admin/_templates/header_pdf', $this->$data, true);
                $template['content'] = $this->load->view('admin/escalas/index', $this->data, true);
                $html = $this->load->view('admin/_templates/template', $template, true);
                $mpdf->WriteHTML($html);
                //$mpdf->Output(); // opens in browser
                $mpdf->Output('teste.pdf', 'D'); // it downloads the file into the user system, with give name
                */
            }

            /* Load Template */
            $this->template->admin_render('admin/escalas/index', $this->data);
        }
    }

    /**
     * Get Download PDF File
     *
     * @return Response
     */
    public function gerarpdf()
    {
        $this->load->library('pdf');

        $this->pdf->load_view('mypdf');
        $this->pdf->render();

        $this->pdf->stream("welcome.pdf");
    }

    public function exportaFolha($unidadehospitalar_id, $setor_id,  $dataini, $datafinal, $profissional_id)
    {
        //var_dump($profissional_id); exit;
        $this->load->helper('file');
        header("Content-Description: File Transfer"); 
        header("Content-Disposition: attachment; filename=batidas.txt"); 
        header("Content-Type: application/octet-stream; ");
        $file = fopen('php://output', 'w');

        if($setor_id == '0'){
            $setor_id = null;
        }

        if($profissional_id == '0'){
            $profissional_id = null;
        }

        $data = $this->escala_model->get_frequencias_export_txt($unidadehospitalar_id, $setor_id,  $dataini, $datafinal, $profissional_id);//$this->_get_frequencias('1', null,  '2021-07-01', '2021-07-31', null);
        //var_dump($data); exit;
        foreach($data as $linha){
            
            if($linha->tipobatida == 1){
                $tp = 'E';
            } else if($linha->tipobatida == 2){
                $tp = 'S';
            } else if($linha->tipobatida == 3){
                $tp = 'E';
            } else if($linha->tipobatida == 4){
                $tp = 'S';
            } else if($linha->tipobatida == 5){
                $tp = 'E';
            } else if($linha->tipobatida == 6){
                $tp = 'S';
            }

            fwrite($file, (str_pad($linha->unidadehospitalar_id, 3, 0, STR_PAD_LEFT).str_pad($linha->id_assessus, 3, 0, STR_PAD_LEFT).date('Ymd', strtotime($linha->datahorabatida)). date('His', strtotime($linha->datahorabatida)) .str_pad($linha->crm, 6, 0, STR_PAD_LEFT).$tp).PHP_EOL);
        } 
        fclose($file); 
        exit; 
    }

    public function listaroportunidades()
    {
        if (!$this->ion_auth->logged_in()) {
            $this->session->set_flashdata('message', 'Você deve estar autenticado para usar esta função.');
            redirect('auth/login', 'refresh');
        }
        if (!$this->ion_auth->in_group($this->_permitted_groups)) {
            $this->session->set_flashdata('message', 'O acesso &agrave; este recurso não é permitido ao seu perfil de usuário.');
            redirect('auth/dashboard', 'refresh');
        }

        $this->page_title->push(lang('menu_oportunidades').' de ');
        $this->data['pagetitle'] = $this->page_title->show();
        /* Breadcrumbs */
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        /* Validate form input */
        $this->form_validation->set_rules('data_inicial', 'lang:escalas_datainicialplantao', 'required');
        $this->form_validation->set_rules('data_final', 'lang:escalas_datafinalplantao', 'required');
        $this->form_validation->set_rules('status', 'lang:escalas_status_passagem', 'required');

        if ($this->form_validation->run() == true) {
            $data_inicial = $this->input->post('data_inicial');
            $data_final = $this->input->post('data_final');
            $status = $this->input->post('status');

            /* Cessões e trocas */
            $this->load->model('cemerge/usuarioprofissional_model');
            $this->load->model('cemerge/passagemtroca_model');
            $userId = $this->ion_auth->user()->row()->id;
            $profissional_id = $this->usuarioprofissional_model->get_where(['user_id' => $userId]);
            if ($profissional_id) {
                $this->_profissional = $this->profissional_model->get_where(['id' => $profissional_id[0]->profissional_id])[0];
            }
            $this->data['oportunidade'] = $this->passagemtroca_model->get_sessoes_from_limbo($data_inicial, $data_final, $status, $this->_profissional->id);

            /* Turnos e status */
            foreach ($this->data['oportunidade'] as $ct) {
                $ct->turno = '';
                if ((int)$ct->horainicialplantao >= 5 && (int)$ct->horainicialplantao < 13) {
                    $ct->turno = 'Manhã';
                } else if ((int)$ct->horainicialplantao >= 13 && (int)$ct->horainicialplantao < 19) {
                    $ct->turno = 'Tarde';
                } else if ((int)$ct->horainicialplantao >= 19 && (int)$ct->horainicialplantao <= 23) {
                    $ct->turno = 'Noite';
                }
                $ct->status_oportunidade = '';
                switch ($ct->statuspassagem) {
                case $this::STATUS_PASSAGEM_ACONFIRMAR:
                    $ct->status_oportunidade = 'Disponivel';
                    break;
                case $this::STATUS_PASSAGEM_CONFIRMADA:
                    $ct->status_oportunidade = 'Confirmada';
                    break;
                case $this::STATUS_PASSAGEM_TROCA_PROPOSTA:
                    $ct->status_oportunidade = 'Troca proposta';
                    break;
                case $this::STATUS_PASSAGEM_REPASSADO:
                    $ct->status_oportunidade = 'Plantão repassado';
                    break;
                default:
                    $ct->status_oportunidade = 'Desconhecido';
                    break;
                }
            }
        } else {
            $this->data['oportunidade'] = array();
            $data_inicial = date('Y-m-d');
            $data_final = date('Y-m-d');
            $status = $this::STATUS_PASSAGEM_ACONFIRMAR;
        }

        $tipos_status = array (
            $this::STATUS_PASSAGEM_ACONFIRMAR => 'Disponivel',
            $this::STATUS_PASSAGEM_TROCA_PROPOSTA => 'Trocas ofertadas',
            $this::STATUS_PASSAGEM_CONFIRMADA => 'Trocas efetuadas',
        );

        $this->data['data_inicial'] = array(
            'name'  => 'data_inicial',
            'id'    => 'data_inicial',
            'type'  => 'date',
            'class' => 'form-control',
            'value' => $data_inicial,
        );
        $this->data['data_final'] = array(
            'name'  => 'data_final',
            'id'    => 'data_final',
            'type'  => 'date',
            'class' => 'form-control',
            'value' => $data_final,
        );
        $this->data['status'] = array(
            'name'  => 'status',
            'id'    => 'status',
            'type'  => 'select',
            'class' => 'form-control',
            'selected' => $status,
            'options' => $tipos_status,
        );

        /* Load Template */
        $this->template->admin_render('admin/escalas/listaroportunidades', $this->data);
    }
    public function listarcessoesetrocas()
    {
        if (!$this->ion_auth->logged_in()) {
            $this->session->set_flashdata('message', 'Você deve estar autenticado para usar esta função.');
            redirect('auth/login', 'refresh');
        }
        if (!$this->ion_auth->in_group($this->_permitted_groups)) {
            $this->session->set_flashdata('message', 'O acesso &agrave; este recurso não é permitido ao seu perfil de usuário.');
            redirect('auth/dashboard', 'refresh');
        }

        /* Breadcrumbs */
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        /* Validate form input */
        $this->form_validation->set_rules('data_inicial', 'lang:escalas_datainicialplantao', 'required');
        $this->form_validation->set_rules('data_final', 'lang:escalas_datafinalplantao', 'required');
        $this->form_validation->set_rules('status', 'lang:escalas_status_passagem', 'required');

        if ($this->form_validation->run() == true) {
            $data_inicial = $this->input->post('data_inicial');
            $data_final = $this->input->post('data_final');
            $status = $this->input->post('status');
            
            /* Cessões e trocas */
            $this->load->model('cemerge/passagemtroca_model');
            $this->data['cessoestrocas'] = $this->passagemtroca_model->get_cessoes_trocas_escalas($data_inicial, $data_final, $status);

            /* Turnos e status */
            foreach ($this->data['cessoestrocas'] as $ct) {
                $ct->turno = '';
                if ((int)$ct->horainicialplantao >= 5 && (int)$ct->horainicialplantao < 13) {
                    $ct->turno = 'Manhã';
                } else if ((int)$ct->horainicialplantao >= 13 && (int)$ct->horainicialplantao < 19) {
                    $ct->turno = 'Tarde';
                } else if ((int)$ct->horainicialplantao >= 19 && (int)$ct->horainicialplantao <= 23) {
                    $ct->turno = 'Noite';
                }
                $ct->status_cessao_troca = '';
                switch ($ct->statuspassagem) {
                case $this::STATUS_PASSAGEM_ACONFIRMAR:
                    $ct->status_cessao_troca = 'A confirmar';
                    break;
                case $this::STATUS_PASSAGEM_CONFIRMADA:
                    $ct->status_cessao_troca = 'Confirmada';
                    break;
                case $this::STATUS_PASSAGEM_TROCA_PROPOSTA:
                    $ct->status_cessao_troca = 'Troca proposta';
                    break;
                case $this::STATUS_PASSAGEM_REPASSADO:
                    $ct->status_cessao_troca = 'Plantão repassado';
                    break;
                default:
                    $ct->status_cessao_troca = 'Desconhecido';
                    break;
                }
            }
        } else {
            $this->data['cessoestrocas'] = array();
            $data_inicial = date('Y-m-d');
            $data_final = date('Y-m-d');
            $status = $this::STATUS_PASSAGEM_ACONFIRMAR;
        }

        $tipos_status = array (
            $this::STATUS_PASSAGEM_ACONFIRMAR => 'A confirmar',
            $this::STATUS_PASSAGEM_TROCA_PROPOSTA => 'Troca proposta',
            $this::STATUS_PASSAGEM_CONFIRMADA => 'Trocas Efetuadas',
            $this::STATUS_PASSAGEM_REPASSADO => 'Plantões Repassados',
        );

        $this->data['data_inicial'] = array(
            'name'  => 'data_inicial',
            'id'    => 'data_inicial',
            'type'  => 'date',
            'class' => 'form-control',
            'value' => $data_inicial,
        );
        $this->data['data_final'] = array(
            'name'  => 'data_final',
            'id'    => 'data_final',
            'type'  => 'date',
            'class' => 'form-control',
            'value' => $data_final,
        );
        $this->data['status'] = array(
            'name'  => 'status',
            'id'    => 'status',
            'type'  => 'select',
            'class' => 'form-control',
            'selected' => $status,
            'options' => $tipos_status,
        );

        /* Load Template */
        $this->template->admin_render('admin/escalas/listacessoesetrocas', $this->data);
    }

    public function buscarescalaporprofissional()
    {
        if (!$this->ion_auth->logged_in() or !$this->ion_auth->in_group($this->_permitted_groups)) {
            redirect('auth/login', 'refresh');
        } else {
            /* Breadcrumbs */
            $this->data['breadcrumb'] = $this->breadcrumbs->show();

            /* Reset */
            $this->data['escalas'] = array();

            /* Variables */
            $this->data['diasdasemana'] = $this->_diasdasemana;

            /* Validate form input */
            $this->form_validation->set_rules('unidadehospitalar_id', 'lang:escalas_unidadehospitalar', 'required');
            //$this->form_validation->set_rules('profissional_id', 'lang:escalas_profissional', 'required');
            $this->form_validation->set_rules('datainicial', 'lang:escalas_datainicialplantao', 'required');
            $this->form_validation->set_rules('datafinal', 'lang:escalas_datafinalplantao', 'required');
            $this->form_validation->set_rules('tipo_plantao', 'lang:escalas_tipo_plantao', 'required');
            //$this->form_validation->set_rules('tipoescala', 'lang:escalas_tipoescala', 'required');
            //$this->form_validation->set_rules('tipovisualizacao', 'lang:escalas_tipovisualizacao', 'required');

            $profissionais = array();

            if ($this->form_validation->run() == true) {
                $unidadehospitalar_id = $this->input->post('unidadehospitalar_id');
                $profissional_id = $this->input->post('profissional_id');
                $setor_id = $this->input->post('setor_id');
                $datainicial = $this->input->post('datainicial');
                $datafinal = $this->input->post('datafinal');
                $tipo_plantao = $this->input->post('tipo_plantao');
                $tipo_escala = $this->input->post('tipos');

                $setores = $this->_get_setores($unidadehospitalar_id);
                $profissionais = $this->_get_profissionais($setor_id);

                if($profissional_id == ''){
                    $profissional_id = 0;
                }

                if($setor_id == ''){
                    $setor_id = 0;
                }

                $this->data['escalas'] = $this->escala_model->get_escalas_consolidadas2($datainicial, $datafinal, $setor_id, $tipo_plantao, $tipo_escala, $profissional_id);    
            } else {
                $unidadehospitalar_id = 1;
                $setores = array('Todos os Setores');
                $profissionais = array('Todos os Profissionais');
                $datainicial = date('Y-m-01');
                $datafinal = date('Y-m-t');
                $profissionais = array('' => 'Selecione um profissional');
                $this->data['escalas'] = array();
            }        
        
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

            $unidadeshospitalares = $this->_get_unidadeshospitalares();
            $tiposescala = $this->_get_tipos_escala();
            
            $tipos_plantao = array(
                '2' => 'Todos',
                '0' => 'Fixo',
                '1' => 'Extra'
            );
            $tipos = array(
                '0' => 'Todos',
                '1' => 'Plantonista',
                '2' => 'Prescritor',
                '3' => 'Diarista'
            );
            $this->data['tipos'] = array(
                'name'  => 'tipos',
                'id'    => 'tipos',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('tipos'),
                'options' => $tipos,
            );

            $this->data['setor_id'] = array(
                'name'  => 'setor_id',
                'id'    => 'setor_id',
                'type'  => 'select',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('setor_id'),
                'options' => $setores,
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
            $this->data['profissional_id'] = array(
                'name'  => 'profissional_id',
                'id'    => 'profissional_id',
                'type'  => 'select',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('profissional_id'),
                'options' => $profissionais,
            );

            $this->data['tipo_plantao'] = array(
                'name'  => 'tipo_plantao',
                'id'    => 'tipo_plantao',
                'type'  => 'select',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('tipo_plantao'),
                'options' => $tipos_plantao,
            );

            /* Load Template */
            $this->template->admin_render('admin/escalas/listaescalaporprofissional', $this->data);
        }
    }

    public function buscarescalaprocessada()
    {
        if (!$this->ion_auth->logged_in() or !$this->ion_auth->in_group($this->_permitted_groups)) {
            redirect('auth/login', 'refresh');
        } else {
            /* Breadcrumbs */
            $this->data['breadcrumb'] = $this->breadcrumbs->show();

            /* Reset */
            $this->data['escalas'] = array();

            /* Variables */
            $this->data['diasdasemana'] = $this->_diasdasemana;

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

                $setores = $this->_get_setores($unidadehospitalar_id);

                // Realizando a busca
                $where = array(
                    'unidadehospitalar_id' => $unidadehospitalar_id,
                    'escalas.setor_id' => $setor_id,
                    'escalas.dataplantao >=' => $datainicial,
                    'escalas.dataplantao <=' => $datafinal,
                );

                if($datainicial <= '2021-06-20'){
                    $this->data['escalas'] = $this->escala_model->get_escala_processada($setor_id, $datainicial, $datafinal, 1);
                }else{
                    $this->data['escalas'] = $this->escala_model->get_escala_processada_nova($setor_id, $datainicial, $datafinal, 1);
                }                    
                /** Processando batidas de 13:00:00 de saída e entrada automática */
                $size = count($this->data['escalas']);
                for ($i = 0; $i <= $size-1; $i++) {
                    $this->data['escalas'][$i]->batidasaida_inserida = false;
                    $this->data['escalas'][$i]->batidaentrada_inserida = false;
                    if (isset($this->data['escalas'][$i+1])) {
                        if ($this->data['escalas'][$i]->horafinalplantao == '13:00:00'
                            && $this->data['escalas'][$i]->nome_profissional == $this->data['escalas'][$i+1]->nome_profissional
                            && $this->data['escalas'][$i]->dataplantao == $this->data['escalas'][$i+1]->dataplantao
                            && is_null($this->data['escalas'][$i]->batidasaida)
                            && !is_null($this->data['escalas'][$i]->batidaentrada)
                        ) {
                            $this->data['escalas'][$i]->batidasaida = '13:00:00';
                            $this->data['escalas'][$i]->batidasaida_inserida = true;
                        }
                    }
                    if (isset($this->data['escalas'][$i-1])) {
                        if ($this->data['escalas'][$i]->horainicialplantao == '13:00:00'
                            && $this->data['escalas'][$i]->nome_profissional == $this->data['escalas'][$i-1]->nome_profissional
                            && $this->data['escalas'][$i]->dataplantao == $this->data['escalas'][$i-1]->dataplantao
                            && is_null($this->data['escalas'][$i]->batidaentrada)
                            && !is_null($this->data['escalas'][$i]->batidasaida)
                        ) {
                            $this->data['escalas'][$i]->batidaentrada = '13:00:00';
                            $this->data['escalas'][$i]->batidaentrada_inserida = true;
                        }
                    }
                }
            } else {
                $datainicial = date('Y-m-01');
                $datafinal = date('Y-m-t');
                $setores = array('' => 'Selecione um setor');
            }

            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

            $unidadeshospitalares = $this->_get_unidadeshospitalares();

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

            /* Load Template */
            $this->template->admin_render('admin/escalas/listaescalaprocessada', $this->data);
        }
    }
    public function buscarfrequenciaprofissional()
    {
        if (!$this->ion_auth->logged_in() or !$this->ion_auth->in_group($this->_permitted_groups)) {
            redirect('auth/login', 'refresh');
        } else {
            /* Breadcrumbs */
            $this->data['breadcrumb'] = $this->breadcrumbs->show();

            /* Reset */
            $this->data['escalas'] = array();

            /* Variables */
            $this->data['diasdasemana'] = $this->_diasdasemana;
            $this->load->model('cemerge/usuarioprofissional_model');

            $userId = $this->ion_auth->user()->row()->id;
            $profissional = $this->usuarioprofissional_model->get_where(['user_id' => $userId]);
            if ($profissional) {
                $this->_profissional = $this->profissional_model->get_where(['id' => $profissional[0]->profissional_id])[0];
                $profissional_id = $this->_profissional->id;
            }

            
            /* Validate form input */
            $this->form_validation->set_rules('unidadehospitalar2_id', 'lang:escalas_unidadehospitalar', 'required');
            //$this->form_validation->set_rules('setor_id', 'lang:escalas_unidadehospitalar', 'required');
            $this->form_validation->set_rules('datainicial', 'lang:escalas_datainicialplantao', 'required');
            $this->form_validation->set_rules('datafinal', 'lang:escalas_datafinalplantao', 'required');

            if ($this->form_validation->run() == true) {
                $unidadehospitalar_id = $this->input->post('unidadehospitalar2_id');
                $setor_id = $this->input->post('setor_id');
                $datainicial = $this->input->post('datainicial');
                $datafinal = $this->input->post('datafinal');
                $tipo_escala = $this->input->post('tipos');
                $order = $this->input->post('ordem');

                $setores = $this->_get_setores_profissional($this->_profissional->id);

                // Realizando a busca
                $where = array(
                    'unidadehospitalar_id' => $unidadehospitalar_id,
                    'escalas.setor_id' => $setor_id,
                    'escalas.dataplantao >=' => $datainicial,
                    'escalas.dataplantao <=' => $datafinal,
                    'escalas.tipo_escala' => $tipo_escala
                );

                if($datainicial <= '2021-06-20'){
                    if ($setor_id <> '' || $setor_id <> 0){
                        $this->data['escalas'] = $this->escala_model->get_escala_processada_setor($profissional_id, $setor_id, $datainicial, $datafinal, $order);
                    } else {
                        $this->data['escalas'] = $this->escala_model->get_escala_processada_profissional($profissional_id, $datainicial, $datafinal, $order);
                    }
                } else{
                    if ($setor_id <> '' || $setor_id <> 0){
                        $this->data['escalas'] = $this->escala_model->get_escala_processada_setor_nova($profissional_id, $setor_id, $datainicial, $datafinal, $order);
                    } else {
                        $this->data['escalas'] = $this->escala_model->get_escala_processada_profissional_nova($profissional_id, $datainicial, $datafinal, $order);
                    }
                }
                


                //var_dump($this->data['escalas']); exit;
                /** Processando batidas de 13:00:00 de saída e entrada automática */
                $size = count($this->data['escalas']);
                for ($i = 0; $i <= $size-1; $i++) {
                    $this->data['escalas'][$i]->batidasaida_inserida = false;
                    $this->data['escalas'][$i]->batidaentrada_inserida = false;
                    if (isset($this->data['escalas'][$i+1])) {
                        if ($this->data['escalas'][$i]->horafinalplantao == '13:00:00'
                            && $this->data['escalas'][$i]->nome_profissional == $this->data['escalas'][$i+1]->nome_profissional
                            && $this->data['escalas'][$i]->dataplantao == $this->data['escalas'][$i+1]->dataplantao
                            && is_null($this->data['escalas'][$i]->batidasaida)
                            && !is_null($this->data['escalas'][$i]->batidaentrada)
                        ) {
                            $this->data['escalas'][$i]->batidasaida = '13:00:00';
                            $this->data['escalas'][$i]->batidasaida_inserida = true;
                        }
                    }
                    if (isset($this->data['escalas'][$i-1])) {
                        if ($this->data['escalas'][$i]->horainicialplantao == '13:00:00'
                            && $this->data['escalas'][$i]->nome_profissional == $this->data['escalas'][$i-1]->nome_profissional
                            && $this->data['escalas'][$i]->dataplantao == $this->data['escalas'][$i-1]->dataplantao
                            && is_null($this->data['escalas'][$i]->batidaentrada)
                            && !is_null($this->data['escalas'][$i]->batidasaida)
                        ) {
                            $this->data['escalas'][$i]->batidaentrada = '13:00:00';
                            $this->data['escalas'][$i]->batidaentrada_inserida = true;
                        }
                    }
                }
            } else {
                $datainicial = date('Y-m-01');
                $datafinal = date('Y-m-t');
                $unidadehospitalar_id = '';
                $profissional_id = $this->_profissional->id;
                $setores = $this->_get_setores_profissional($this->_profissional->id);
            }

            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

            $unidadeshospitalares = $this->_get_unidadeshospitalares();
            
            $tipos = array(
                '0' => 'Todos',
                '1' => 'Plantonista',
                '2' => 'Prescritor',
                '3' => 'Diarista'
            );

            $this->data['tipos'] = array(
                'name'  => 'tipos',
                'id'    => 'tipos',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('tipos'),
                'options' => $tipos,
            );

            $ordem = array(
                '1' => 'Normal',
                '2' => 'Setor',
                '3' => 'Médico',
                '4' => 'Dia da Semana'
            );

            $this->data['ordem'] = array(
                'name'  => 'ordem',
                'id'    => 'ordem',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('ordem'),
                'options' => $ordem,
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
            $this->data['unidadehospitalar2_id'] = array(
                'name'  => 'unidadehospitalar2_id',
                'id'    => 'unidadehospitalar2_id',
                'type'  => 'select',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('unidadehospitalar2_id'),
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
            $this->template->admin_render('admin/escalas/listafrequenciaprofissional', $this->data);
        }
    }

    public function justificativa_automatica(){

        //$escalas = $this->escala_model->
    }

    public function buscarfrequenciaprocessada()
    {
        if (!$this->ion_auth->logged_in() or !$this->ion_auth->in_group($this->_permitted_groups)) {
            redirect('auth/login', 'refresh');
        } else {
            /* Breadcrumbs */
            $this->data['breadcrumb'] = $this->breadcrumbs->show();

            /* Reset */
            $this->data['escalas'] = array();

            /* Variables */
            $this->data['diasdasemana'] = $this->_diasdasemana;

            /* Validate form input */
            $this->form_validation->set_rules('unidadehospitalar_id', 'lang:escalas_unidadehospitalar', 'required');
            $this->form_validation->set_rules('datainicial', 'lang:escalas_datainicialplantao', 'required');
            $this->form_validation->set_rules('datafinal', 'lang:escalas_datafinalplantao', 'required');

            if ($this->form_validation->run() == true) {
                $unidadehospitalar_id = $this->input->post('unidadehospitalar_id');
                $setor_id = $this->input->post('setor_id');
                $profissional_id = $this->input->post('profissional_id');
                $datainicial = $this->input->post('datainicial');
                $datafinal = $this->input->post('datafinal');
                $tipo_escala = $this->input->post('tipos');
                $order = $this->input->post('ordem');

                $setores = $this->_get_setores($unidadehospitalar_id);

                // Realizando a busca
                $where = array(
                    'unidadehospitalar_id' => $unidadehospitalar_id,
                    'escalas.setor_id' => $setor_id,
                    'escalas.dataplantao >=' => $datainicial,
                    'escalas.dataplantao <=' => $datafinal,
                    'escalas.tipo_escala' => $tipo_escala
                );

                if($datainicial <= '2021-06-20'){
                    if ($profissional_id <> '' || $profissional_id <> 0){
                        $this->data['escalas'] = $this->escala_model->get_escala_processada_profissional($profissional_id, $datainicial, $datafinal, $order);
                    } else {
                        $this->data['escalas'] = $this->escala_model->get_escala_processada($setor_id, $datainicial, $datafinal, $order);
                    }
                    //var_dump($datainicial); exit;
                }else{
                    if ($profissional_id <> '' || $profissional_id <> 0){
                        $this->data['escalas'] = $this->escala_model->get_escala_processada_profissional_nova($profissional_id, $datainicial, $datafinal, $order);
                    } else {
                        $this->data['escalas'] = $this->escala_model->get_escala_processada_nova($setor_id, $datainicial, $datafinal, $order);
                    }
                }    
                //var_dump($this->data['escalas']); exit;
                /** Processando batidas de 13:00:00 de saída e entrada automática */
                $size = count($this->data['escalas']);
                for ($i = 0; $i <= $size-1; $i++) {
                    $this->data['escalas'][$i]->batidasaida_inserida = false;
                    $this->data['escalas'][$i]->batidaentrada_inserida = false;
                    if (isset($this->data['escalas'][$i+1])) {
                        if ($this->data['escalas'][$i]->horafinalplantao == '13:00:00'
                            && $this->data['escalas'][$i]->nome_profissional == $this->data['escalas'][$i+1]->nome_profissional
                            && $this->data['escalas'][$i]->dataplantao == $this->data['escalas'][$i+1]->dataplantao
                            && is_null($this->data['escalas'][$i]->batidasaida)
                            && !is_null($this->data['escalas'][$i]->batidaentrada)
                        ) {
                            $this->data['escalas'][$i]->batidasaida = '13:00:00';
                            $this->data['escalas'][$i]->batidasaida_inserida = true;
                        }
                    }
                    if (isset($this->data['escalas'][$i-1])) {
                        if ($this->data['escalas'][$i]->horainicialplantao == '13:00:00'
                            && $this->data['escalas'][$i]->nome_profissional == $this->data['escalas'][$i-1]->nome_profissional
                            && $this->data['escalas'][$i]->dataplantao == $this->data['escalas'][$i-1]->dataplantao
                            && is_null($this->data['escalas'][$i]->batidaentrada)
                            && !is_null($this->data['escalas'][$i]->batidasaida)
                        ) {
                            $this->data['escalas'][$i]->batidaentrada = '13:00:00';
                            $this->data['escalas'][$i]->batidaentrada_inserida = true;
                        }
                    }
                }
            } else {
                $datainicial = date('Y-m-01');
                $datafinal = date('Y-m-t');
                $unidadehospitalar_id = '';
                $profissional_id = '';
                $setores = array('' => 'Todos os Setores');
            }

            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

            $unidadeshospitalares = $this->_get_unidadeshospitalares();
            $profissionais = $this->_get_profissionais_por_unidade_hospitalar_frequencia($unidadehospitalar_id);
            $tipos = array(
                '0' => 'Todos',
                '1' => 'Plantonista',
                '2' => 'Prescritor',
                '3' => 'Diarista'
            );

            $this->data['tipos'] = array(
                'name'  => 'tipos',
                'id'    => 'tipos',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('tipos'),
                'options' => $tipos,
            );

            $ordem = array(
                '1' => 'Normal',
                '2' => 'Setor',
                '3' => 'Médico',
                '4' => 'Dia da Semana'
            );

            $this->data['ordem'] = array(
                'name'  => 'ordem',
                'id'    => 'ordem',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('ordem'),
                'options' => $ordem,
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

            /* Load Template */
            $this->template->admin_render('admin/escalas/listafrequenciaprocessada', $this->data);
        }
    }

    public function validar()
    {
        if (!$this->ion_auth->logged_in()) {
            $this->session->set_flashdata('message', 'Você deve estar autenticado para validar uma escala.');
            redirect('auth/login', 'refresh');
        }
        if (!$this->ion_auth->in_group('admin') && !$this->ion_auth->in_group('coordenadorplantao')) {
            $this->session->set_flashdata('message', 'Somente administradores e coordenadores de plantão podem validar escalas.');
            redirect('admin/dashboard', 'refresh');
        }

        /* Breadcrumbs */
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        /* Reset */
        $this->data['escalas'] = array();

        /* Variables */
        $this->data['diasdasemana'] = $this->_diasdasemana;

        /* Validate form input */
        $this->form_validation->set_rules('unidadehospitalar_id', 'lang:escalas_unidadehospitalar', 'required');
        $this->form_validation->set_rules('setor_id', 'lang:escalas_setor', 'required');
        $this->form_validation->set_rules('ano', 'lang:escalas_ano', 'required');
        $this->form_validation->set_rules('mes', 'lang:escalas_mes', 'required');

        if ($this->form_validation->run() == true) {
            $unidadehospitalar_id = $this->input->post('unidadehospitalar_id');
            $setor_id = $this->input->post('setor_id');
            $ano = $this->input->post('ano');
            $mes = $this->input->post('mes');

            $setores = $this->_get_setores($unidadehospitalar_id);

            // Realizando a busca (período de 21/Mês Anterior a 20/Mês selecionado)
            $this->data['escalas'] = $this->escala_model->get_escalas_consolidadas_validacao($setor_id, $ano, $mes);
        } else {
            $setores = array('' => 'Selecione um setor');
        }

        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

        $unidadeshospitalares = $this->_get_unidadeshospitalares();

        $tiposescala = $this->_get_tipos_escala();
        $tiposvisualizacao = $this->_get_tipos_visualizacao();

        $this->load->helper('dates');
        $meses = meses();
        $anos = anos();

        /* Campos para formulário de validação e envio */
        $this->data['p_unidadehospitalar_id'] = $this->input->post('unidadehospitalar_id');
        $this->data['p_setor_id'] = $this->input->post('setor_id');
        $this->data['p_ano'] = $this->input->post('ano');
        $this->data['p_mes'] = $this->input->post('mes');

        $this->data['ano'] = array(
            'name'  => 'ano',
            'id'    => 'ano',
            'type'  => 'select',
            'class' => 'form-control',
            'options' => $anos,
            'selected' => date('Y'),
        );
        $this->data['mes'] = array(
            'name'  => 'mes',
            'id'    => 'mes',
            'type'  => 'select',
            'class' => 'form-control',
            'options' => $meses,
            'selected' => date('m'),
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
        $this->template->admin_render('admin/escalas/validar', $this->data);
    }

    public function validarenviar()
    {
        $this->form_validation->set_rules('p_unidadehospitalar_id', 'lang:escalas_unidadehospitalar', 'required');
        $this->form_validation->set_rules('p_setor_id', 'lang:escalas_setor', 'required');
        $this->form_validation->set_rules('p_ano', 'lang:escalas_ano', 'required');
        $this->form_validation->set_rules('p_mes', 'lang:escalas_mes', 'required');

        if (!$this->ion_auth->logged_in()) {
            $this->session->set_flashdata('message', 'Você deve estar autenticado para validar uma escala.');
            redirect('auth/login', 'refresh');
        }
        if (!$this->ion_auth->in_group('admin') && !$this->ion_auth->in_group('coordenadorplantao')) {
            $this->session->set_flashdata('message', 'Somente administradores e coordenadores de plantão podem validar escalas.');
            redirect('admin/dashboard', 'refresh');
        }

        if ($this->form_validation->run() == true) {
            $p_unidadehospitalar_id = $this->input->post('p_unidadehospitalar_id');
            $p_setor_id = $this->input->post('p_setor_id');
            $p_ano = $this->input->post('p_ano');
            $p_mes = $this->input->post('p_mes');
            $usuario_validacao_id = $this->ion_auth->user()->row()->id;
        } else {
            $this->session->set_flashdata('message', 'Favor preencher todos os parâmetros da validação.');
            redirect('admin/escalas/validar', 'refresh');
        }

        /* Breadcrumbs */
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        if ($p_unidadehospitalar_id && $p_setor_id && $p_ano && $p_mes) {
            // de 21 do mês anterior a 20 do mês selecionado
            if ($p_mes == 1) {
                $mesanterior = 12;
                $anoanterior = $p_ano - 1;
                $datainicial = $anoanterior . '-' . $mesanterior . '-' . '21';
                $datafinal = $p_ano . '-' . $p_mes . '-' . '20';
                $where = 'escalas.setor_id = ' . $p_setor_id . ' ';
                $where .= 'and escalas.dataplantao between \'' . $datainicial . '\' and \'' . $datafinal . '\' ';
            } else {
                $mesanterior = $p_mes - 1;
                $datainicial = $p_ano . '-' . $mesanterior . '-' . '21';
                $datafinal = $p_ano . '-' . $p_mes . '-' . '20';
                $where = 'escalas.setor_id = ' . $p_setor_id . ' ';
                $where .= 'and escalas.dataplantao between \'' . $datainicial . '\' and \'' . $datafinal . '\' ';
            }
            $data = array(
                'validada' => 1,
                'datahora_validacao' => date('Y-m-d H:i:s'),
                'usuario_validacao_id' => $usuario_validacao_id
            );

            if ($this->escala_model->update_where($where, $data)) {
                $this->session->set_flashdata('message', 'Escala validada e enviada com sucesso.');
            } else {
                $this->session->set_flashdata('message', 'Houve um problema ao validar e enviar a escala.');
            }
        } else {
            $this->session->set_flashdata('message', 'Favor preencher todos os parâmetros da validação.');
        }

        /* Redirect */
        redirect('admin/escalas/validar', 'refresh');
    }

    public function processarescala($unidadehospitalar_id, $setor_id, $datainicial = null)
    {
        $unidadehospitalar_id = (int)$unidadehospitalar_id;
        $setor_id = (int)$setor_id;
        $datainicial = $datainicial ? $datainicial : date('Y-m-d');
        if ($setor_id == 0) {
            $setor_id = null;
        }

        if (!$this->ion_auth->logged_in()) {
            $this->session->set_flashdata('message', 'Você deve estar autenticado para processar uma escala.');
            redirect('auth/login', 'refresh');
        }
        if (!$this->ion_auth->in_group('admin') && !$this->ion_auth->in_group('sac')) {
            $this->session->set_flashdata('message', 'Somente administradores e o SAC podem validar escalas.');
            redirect('admin/dashboard', 'refresh');
        }

        if ($datainicial && $unidadehospitalar_id) {
            $data_inicial_escala = date('Y-m-d', strtotime($datainicial . ' -1 day'));
            $data_final_escala = date('Y-m-d', strtotime($datainicial));

            $escalas = $this->escala_model->get_escala_consolidada_a_processar($unidadehospitalar_id, $setor_id, $data_inicial_escala, $data_final_escala);

            foreach ($escalas as $escala) {
                // Plantões manhã e tarde do dia solicitado
                if ($escala->dataplantao == $data_final_escala and $escala->dataplantao == $escala->datafinalplantao) {
                    if (is_null($escala->frequencia_entrada_id)) {
                        $entradas = $this->escala_model->get_frequencia_por_escala($escala->dataplantao, $escala->horainicialplantao, $escala->id_profissional, $unidadehospitalar_id);
                        if ($entradas) {
                            if (sizeof($entradas) > 1) {
                                $entrada = $entradas[1]; // Acho que no caso de prescrição deve pegar a primeira[0] para entrada e a segunda[1] para saída
                            } else {
                                $entrada = $entradas[0];
                            }
                            $this->escala_model->update($escala->escala_id, ['frequencia_entrada_id' => $entrada->cd_ctl_frq]);
                            $this->frequenciaassessus_model->update($entrada->cd_ctl_frq, ['escala_id' => $escala->escala_id, 'tipo_batida' => 1]);
                            echo('Frequencia de entrada atualizada<br>');
                        }
                    }
                    if (is_null($escala->frequencia_saida_id)) {
                        $saidas = $this->escala_model->get_frequencia_por_escala($escala->dataplantao, $escala->horafinalplantao, $escala->id_profissional, $unidadehospitalar_id);
                        if ($saidas) {
                            if (sizeof($saidas) > 1) {
                                $saida = $saidas[0]; // Acho que no caso de prescrição deve pegar a primeira[0] para entrada e a segunda[1] para saída
                            } else {
                                $saida = $saidas[0];
                            }
                            $this->escala_model->update($escala->escala_id, ['frequencia_saida_id' => $saida->cd_ctl_frq]);
                            $this->frequenciaassessus_model->update($saida->cd_ctl_frq, ['escala_id' => $escala->escala_id, 'tipo_batida' => 2]);
                            echo('Frequencia de saída atualizada<br>');
                        }
                    }
                }
                // Plantões noite do dia anterior
                if ($escala->dataplantao == $data_inicial_escala and $escala->horainicialplantao > $escala->horafinalplantao) {
                    if (is_null($escala->frequencia_saida_id)) {
                        $saidas = $this->escala_model->get_frequencia_por_escala($escala->datafinalplantao, $escala->horafinalplantao, $escala->id_profissional, $unidadehospitalar_id);
                        if ($saidas) {
                            if (sizeof($saidas) > 1) {
                                $saida = $saidas[0];
                            } else {
                                $saida = $saidas[0];
                            }
                            $this->escala_model->update($escala->escala_id, ['frequencia_saida_id' => $saida->cd_ctl_frq]);
                            $this->frequenciaassessus_model->update($saida->cd_ctl_frq, ['escala_id' => $escala->escala_id, 'tipo_batida' => 2]);
                            echo('Frequencia de saída atualizada<br>');
                        }
                    }
                }
                // Plantões noite do dia solicitado
                if ($escala->dataplantao == $data_final_escala and $escala->datafinalplantao > $escala->dataplantao and $escala->horainicialplantao > $escala->horafinalplantao) {
                    if (is_null($escala->frequencia_entrada_id)) {
                        $entradas = $this->escala_model->get_frequencia_por_escala($escala->dataplantao, $escala->horainicialplantao, $escala->id_profissional, $unidadehospitalar_id);
                        if ($entradas) {
                            if (sizeof($entradas) > 1) {
                                $entrada = $entradas[1];
                            } else {
                                $entrada = $entradas[0];
                            }
                            $this->escala_model->update($escala->escala_id, ['frequencia_entrada_id' => $entrada->cd_ctl_frq]);
                            $this->frequenciaassessus_model->update($entrada->cd_ctl_frq, ['escala_id' => $escala->escala_id, 'tipo_batida' => 1]);
                            echo('Frequencia de entrada atualizada<br>');
                        }
                    }
                }
            }
        } else {
            echo('Favor preencher todos os parâmetros da validação.');
            $this->session->set_flashdata('message', 'Favor preencher todos os parâmetros da validação.');
        }

        echo('Escalas processadas com sucesso.');

        /* Redirect */
        //redirect('admin/escalas/processarescala', 'refresh');
    }

    public function remover_medico(){
        if (!$this->input->is_ajax_request()) {
            exit("Nenhum acesso de script direto permitido!");
        }
        $escala_id = $this->input->post('escala');
        $sucess = false;

        $this->load->model('cemerge/passagemtroca_model');

        $sessoes = $this->passagemtroca_model->get_where(['escala_id' => $escala_id], null);
        if (empty($sessoes)){
            $this->escala_model->update($escala_id, ['profissional_id' => 0, 'tipo_plantao' => 1]);
            $sucess = true;
        };
        //var_dump($sessoes);exit;
        echo json_encode(['sucess'=>$sucess]);
        exit;      
    }

    public function processarescalaprescricao($unidadehospitalar_id, $datainicial = null)
    {
        $unidadehospitalar_id = (int)$unidadehospitalar_id;
        $datainicial = $datainicial ? $datainicial : date('Y-m-d');

        if (!$this->ion_auth->logged_in()) {
            $this->session->set_flashdata('message', 'Você deve estar autenticado para processar uma escala.');
            redirect('auth/login', 'refresh');
        }
        if (!$this->ion_auth->in_group('admin') && !$this->ion_auth->in_group('sac')) {
            $this->session->set_flashdata('message', 'Somente administradores e o SAC podem validar escalas.');
            redirect('admin/dashboard', 'refresh');
        }

        if ($datainicial && $unidadehospitalar_id) {
            $data_inicial_escala = date('Y-m-d', strtotime($datainicial));
            $data_final_escala = $data_inicial_escala;

            $escalas = $this->escala_model->get_escala_prescricao_a_processar($unidadehospitalar_id, $data_inicial_escala, $data_final_escala);

            foreach ($escalas as $escala) {
                // Plantões manhã e tarde do dia solicitado
                if ($escala->dataplantao == $data_final_escala and $escala->dataplantao == $escala->datafinalplantao) {
                    if (is_null($escala->frequencia_entrada_id)) {
                        $batidas = $this->escala_model->get_frequencia_por_escala_prescricao($escala->dataplantao, $escala->id_profissional, $unidadehospitalar_id, $escala->setor_id);
                        if ($batidas) {
                            $entrada = $batidas[0];
                            $this->escala_model->update($escala->escala_id, ['frequencia_entrada_id' => $entrada->cd_ctl_frq]);
                            $this->frequenciaassessus_model->update($entrada->cd_ctl_frq, ['escala_id' => $escala->escala_id, 'tipo_batida' => 1]);
                        }
                    }
                    if (is_null($escala->frequencia_saida_id)) {
                        $batidas = $this->escala_model->get_frequencia_por_escala_prescricao($escala->dataplantao, $escala->id_profissional, $unidadehospitalar_id, $escala->setor_id);
                        if ($batidas) {
                            if (sizeof($batidas) > 1) {
                                $saida = $batidas[1];
                            } else {
                                $saida = $batidas[0];
                            }
                            $this->escala_model->update($escala->escala_id, ['frequencia_saida_id' => $saida->cd_ctl_frq]);
                            $this->frequenciaassessus_model->update($saida->cd_ctl_frq, ['escala_id' => $escala->escala_id, 'tipo_batida' => 2]);
                        }
                    }
                }
            }
        } else {
            echo('Favor preencher todos os parâmetros da validação.');
            $this->session->set_flashdata('message', 'Favor preencher todos os parâmetros da validação.');
        }

        echo('Escalas processadas com sucesso.');

        /* Redirect */
        //redirect('admin/escalas/processarescala', 'refresh');
    }

    public function createExcel($filename = null, $title = null, $headers = null, $data = null)
    {
        $fileName = 'employee.xlsx';
        $employeeData = $this->profissional_model->get_all();
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'Título');
        $sheet->mergeCells('A1:F1');
        $sheet->getStyle('A1:F1')->getAlignment()->setHorizontal('center');
        $sheet->setCellValue('A2', 'Id');
        $sheet->setCellValue('B2', 'Name');
        $sheet->setCellValue('C2', 'Skills');
        $sheet->setCellValue('D2', 'Address');
        $sheet->setCellValue('E2', 'Age');
        $sheet->setCellValue('F2', 'Designation');
        $sheet->getStyle('A2:F2')->getAlignment()->setHorizontal('center');
        $rows = 3;
        foreach ($employeeData as $val) {
            $sheet->setCellValue('A' . $rows, $val->id);
            $sheet->setCellValue('B' . $rows, $val->registro);
            $sheet->setCellValue('C' . $rows, $val->nome);
            $sheet->setCellValue('D' . $rows, $val->nomecurto);
            $sheet->setCellValue('E' . $rows, $val->email);
            $sheet->setCellValue('F' . $rows, $val->cpf);
            $rows++;
        } 
        $writer = new Xlsx($spreadsheet);
        //$this->load->helper('download');
        //force_download($path_fisico . $filename, null);
        $writer->save("upload/".$fileName);
        header("Content-Type: application/vnd.ms-excel");
        redirect(base_url()."/upload/".$fileName);
    }

    public function atribuir()
    {
        if (!$this->ion_auth->logged_in() OR !$this->ion_auth->in_group($this->_permitted_groups)) {
            redirect('auth/login', 'refresh');
        } else {
            /* Breadcrumbs */
            $this->data['breadcrumb'] = $this->breadcrumbs->show();

            /* Variables */
            $this->data['data_minima'] = date('Y-m-d', strtotime(date('Y-m-d') . ' - 365 days'));
            $this->data['data_maxima'] = date('Y-m-d', strtotime(date('Y-m-d') . ' + 90 days'));
            $this->data['diasdasemana'] = $this->_diasdasemana;

            /* Reset */
            $this->data['escalas'] = array();

            /* Validate form input */
            $this->form_validation->set_rules('unidadehospitalar_id', 'lang:escalas_unidadehospitalar', 'required');
            $this->form_validation->set_rules('setor_id', 'lang:escalas_setor', 'required');
            $this->form_validation->set_rules('vinculo', 'lang:profissionais_vinculo', 'required');
            $this->form_validation->set_rules('datainicial', 'lang:escalas_datainicialplantao', 'required');
            $this->form_validation->set_rules('datafinal', 'lang:escalas_datafinalplantao', 'required');

            if ($this->form_validation->run() == true) {
                $unidadehospitalar_id = $this->input->post('unidadehospitalar_id');
                $setor_id = $this->input->post('setor_id');
                $vinculo_id = $this->input->post('vinculo');
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
                $tipovisualizacao = $this->input->post('tipovisualizacao');

                // Realizando a busca

                $tipo_escala = $this->input->post('tipos');
                $tipos_plantao_post = $this->input->post('tipo_plantao');

                     if ($vinculo_id == 1 || $vinculo_id == 2) {
                        $where = array(
                            'unidadehospitalar_id' => $unidadehospitalar_id,
                            'escalas.setor_id' => $setor_id,
                            'escalas.dataplantao >=' => $datainicial,
                            'escalas.dataplantao <=' => $datafinal,
                            'escalas.vinculo_id' => $vinculo_id
                        );

                        } else if ($vinculo_id == 4){
                            $where = array(
                                'unidadehospitalar_id' => $unidadehospitalar_id,
                                'escalas.setor_id' => $setor_id,
                                'escalas.dataplantao >=' => $datainicial,
                                'escalas.dataplantao <=' => $datafinal,
                                'profissionais.vinculo_id' => null
                            );

                        } else {
                            $where = array(
                                'unidadehospitalar_id' => $unidadehospitalar_id,
                                'escalas.setor_id' => $setor_id,
                                'escalas.dataplantao >=' => $datainicial,
                                'escalas.dataplantao <=' => $datafinal
                            );

                        };

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


                if ($tipos_plantao_post != 2){
                    $where['escalas.tipo_plantao'] = $tipos_plantao_post;
                };

                if ($tipo_escala != 0){
                    $where['escalas.tipo_escala'] = $tipo_escala;
                };

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

                //$this->data['escalas'] = $this->escala_model->get_escalas_originais($where, $where_in_column, $dias_semana, 'dataplantao, horainicialplantao');

                // Lista
                if ($tipovisualizacao == 0) {
                    $this->data['escalas'] = $this->escala_model->get_escalas_originais($where, $where_in_column, $dias_semana, 'dataplantao, horainicialplantao'); // dataplantao, horainicialplantao');
                } /*elseif ($tipoescala == 1 and $tipovisualizacao == 0) {
                    $this->data['escalas'] = $this->escala_model->get_escalas_consolidadas($where, null, null, 'profissional_nome, dataplantao'); //, dataplantao, horainicialplantao');
                } elseif ($tipoescala == 2 and $tipovisualizacao == 0) {
                    $this->data['escalas'] = $this->escala_model->get_passagens_trocas($where, null, null, 'profissional_nome, dataplantao'); //, dataplantao, horainicialplantao');
                }*/

                // Calendário
                else {
                    //if ($tipoescala == 0) {
                        $this->data['escalas'] = $this->escala_model->get_escalas_originais_cal($setor_id, $datainicial, $datafinal);
                    /*} elseif ($tipoescala == 1) {
                        $this->data['escalas'] = $this->escala_model->get_escalas_consolidadas_cal($setor_id, $datainicial, $datafinal);
                    } elseif ($tipoescala == 2) {
                        $this->data['escalas'] = $this->escala_model->get_passagens_trocas_cal($setor_id, $datainicial, $datafinal);
                        //$this->data['escalas'] = null;
                    }*/
                    $this->load->library('calendar');
                    if ($this->calendar->init($datainicial, $datafinal, $this->data['escalas'])) {
                        $this->data["calendario"] = $this->calendar->generate();
                    } else {
                        $this->data["calendario"] = "Não há dados a exibir ou este relatório não é possível de exibir no calendário. Tente o tipo de visualização lista.";
                    }
                }
            } else {
                $datainicial = date('Y-m-d', strtotime('-1 day'));
                $datafinal = date('Y-m-d', strtotime('+1 day'));
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
            $tiposvisualizacao = $this->_get_tipos_visualizacao();
            $this->data['tipovisualizacao'] = array(
                'name'  => 'tipovisualizacao',
                'id'    => 'tipovisualizacao',
                'type'  => 'select',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('tipovisualizacao'),
                'options' => $tiposvisualizacao,
            );
            $tipos = array(
                '0' => 'Todos',
                '1' => 'Plantonista',
                '2' => 'Prescritor',
                '3' => 'Diarista'
            );
            $this->data['tipos'] = array(
                'name'  => 'tipos',
                'id'    => 'tipos',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('tipos'),
                'options' => $tipos,
            );
            $vinculos = array(
                '3' => 'Todos',
                '1' => 'CEMERGE',
                '2' => 'SESA',
                '4' => 'Vagos'
            );
            $vinculos_atribuir = $this->_get_vinculos_atribuir();
            $turnos = array(
                '0' => 'Todos',
                '1' => 'Manhã',
                '2' => 'Tarde',
                '3' => 'Noite',
            );
            $tipos_plantao = array(
                '2' => 'Todos',
                '0' => 'Fixo',
                '1' => 'Extra',
            );
            $tipo_plantao = array(
                '0' => 'Fixo',
                '1' => 'Extra',
            );
            $this->data['datainicial'] = array(
                'name'  => 'datainicial',
                'id'    => 'datainicial',
                'type'  => 'date',
                'class' => 'form-control',
                'min' => $this->data['data_minima'],
                'max' => $this->data['data_maxima'],
                'value' => $datainicial,
            );
            $this->data['datafinal'] = array(
                'name'  => 'datafinal',
                'id'    => 'datafinal',
                'type'  => 'date',
                'class' => 'form-control',
                'min' => $this->data['data_minima'],
                'max' => $this->data['data_maxima'],
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
            $this->data['tipo_plantao'] = array(
                'name'  => 'tipo_plantao',
                'id'    => 'tipo_plantao',
                'type'  => 'select',
                'class' => 'form-control',
                'options' => $tipos_plantao,
            );
            $this->data['tipos_plantao'] = array(
                'name'  => 'tipos_plantao',
                'id'    => 'tipos_plantao',
                'type'  => 'select',
                //'escala' => $escala->id,
                'class' => 'form-control',
                'options' => $tipo_plantao,
            );
            $this->data['vinculo'] = array(
                'name'  => 'vinculo',
                'id'    => 'vinculo',
                'type'  => 'select',
                'class' => 'form-control',
                'selected' => $this->form_validation->set_value('vinculo'),
                'options' => $vinculos,
            );

            $this->data['vinculos_atribuir'] = array(
                'name'  => 'vinculos_atribuir',
                'id'    => 'vinculos_atribuir',
                'type'  => 'select',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('vinculos_atibuir'),
                'options' => $vinculos_atribuir,
            );
            
            //$this->data['profissionais'] = $profissionais;

            $this->data['is_mobile'] = $this->mobile_detect->isMobile();

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

    public function _get_vinculos()
    {
        $this->load->model('cemerge/vinculo_model');
        $vinculos = $this->vinculo_model->get_all();
        $v = array();
        foreach ($vinculos as $vinculo) {
            $v[$vinculo->id] = $vinculo->nome;
        }
        return $v;
    }

    public function _sendEscala($data, $profissional_nome, $destinatario)
    {
        /* Initialize email */
        $ci_mail_config = $this->config->item('mail');
        $this->email->initialize($ci_mail_config);
        $subject = 'CEMERGE - Nova Escala Publicada';
        $message = $this->load->view(
            'admin/_templates/email/escala-publicada.tpl.php', $data, true
        );
            $this->email->to($destinatario);
            $this->email->subject($subject);
            $this->email->message($message);
            $email_enviado = $this->email->send();
            return $email_enviado;
    }

    public function publicar_escala($on_off)
    {
        $unidade = $this->input->post('unidade');
        $data_ini = $this->input->post('data_ini');
        $data_fim = $this->input->post('data_fim');
        $turno = $this->input->post('turno');
        $setor = $this->input->post('setor');
        $vinculo = $this->input->post('vinculo');

            $where  = "dataplantao between '".$data_ini."' and '".$data_fim."' ";
            $where .= "and setor_id = ".$setor.' ';

        if ($turno == 1){
            $where .= "and horainicialplantao = '07:00:00' ";
        } else if ($turno == 2){
            $where .= "and horainicialplantao = '13:00:00' ";
        } else if ($turno == 3){
            $where .= "and horainicialplantao = '19:00:00' ";
        } 

        if ($vinculo != 3){
            $where .= "and vinculo_id = ".$vinculo.' ';
        } 
        $sucess = false;
        if ($this->escala_model->update_where($where, ['publicada' => $on_off])) {
            $this->session->set_flashdata('message', 'Escala validada e enviada com sucesso.');
            $this->_sendEscala('Tabela', 'Anderson', 'anderson@archlinux.com.br');
            $sucess = true;
        } else {
            $this->session->set_flashdata('message', 'Houve um problema ao validar e enviar a escala.');
        }

        echo json_encode($sucess);
        exit;
    }

    public function conferencia()
    {
        if (!$this->ion_auth->logged_in() OR !$this->ion_auth->in_group($this->_permitted_groups)) {
            redirect('auth/login', 'refresh');
        } else {
            /* Breadcrumbs */
            $this->data['breadcrumb'] = $this->breadcrumbs->show();

            /* Variables */
            $this->data['diasdasemana'] = $this->_diasdasemana;

            /* Reset */
            $this->data['frequencias'] = array();

            /* Validate form input */
            $this->form_validation->set_rules('unidadehospitalar_id', 'lang:escalas_unidadehospitalar', 'required');
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
                //$turno_id = $this->input->post('turno_id');
                $manha = $this->input->post('manha');
                $tarde = $this->input->post('tarde');
                $noite = $this->input->post('noite');

                $setores = $this->_get_setores($unidadehospitalar_id);
                $profissionais = $this->_get_profissionais($setor_id);

                // Turnos
                $turnos = array();
                if ($manha == 1) {
                    array_push($turnos, '\'07:00:00\'');
                }
                if ($tarde == 1) {
                    array_push($turnos, '\'13:00:00\'');
                }
                if ($noite == 1) {
                    array_push($turnos, '\'19:00:00\'');
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

                // Obtendo as escalas e suas frequências
                if ($datainicial >= '2021-06-21'){
                    $frequencias = $this->escala_model->get_escalas_frequencias_nova($unidadehospitalar_id, $setor_id, $datainicial, $datafinal, $turnos, $dias_semana);
                    $frequencias_sem_escala = $this->escala_model->get_frequencia_sem_escala_nova($unidadehospitalar_id, $setor_id, $datainicial, $datafinal);
                } else{
                    $frequencias = $this->escala_model->get_escalas_frequencias($unidadehospitalar_id, $setor_id, $datainicial, $datafinal, $turnos, $dias_semana);
                    // Obtendo as frequências sem escala para exibição
                    $frequencias_sem_escala = $this->escala_model->get_frequencia_sem_escala($unidadehospitalar_id, $setor_id, $datainicial, $datafinal);
                }

                // Obtendo as trocas e passagens para exibição
                $trocas_passagens = $this->escala_model->get_trocas_passagens_por_setor_periodo($setor_id, $datainicial, $datafinal);

                // Processando as frequências biométricas, frequências sem escala e frequências inseridas pelo sistema (MT mesmo setor e profissional)
                foreach ($frequencias as $freq) {
                    $data_plantao = date('Y-m-d', strtotime($freq->dataplantao));
                    $data_final_plantao = date('Y-m-d', strtotime($freq->datafinalplantao));
                    $hora_inicial_plantao = date('H:i:s', strtotime($freq->horainicialplantao . ' - 120 minutes'));
                    $hora_final_plantao = date('H:i:s', strtotime($freq->horafinalplantao . ' + 120 minutes'));
                    
                    // Obtendo e vinculando as frequências MT inseridas pelo sistema para exibição
                    $frequencias_mt = $this->frequencia_model->get_frequencias_mt($freq->idunidade, $freq->idsetor, $freq->dataplantao);
                    $freq->frequencias_inseridas_mt = array();
                    foreach ($frequencias_mt as $fmt) {
                        if ($freq->id == $fmt->escala_id && $freq->id_profissional == $fmt->profissional_id) {
                            array_push($freq->frequencias_inseridas_mt, $fmt);
                        }
                    }
                    
                    // Obtendo e vinculando as faltas para exibição
                    $freq->falta = null;
                    if ($freq->status == $this::STATUS_ESCALA_FALTA) {
                        $this->load->model('cemerge/falta_model');
                        $faltas = $this->falta_model->get_where(['escala_id' => $freq->id]);
                        if (!empty($faltas)) {
                            $freq->falta = $faltas[0];
                        }
                    }
                    
                    // Obtendo e vinculando as frequências inseridas mediante justificativa
                    $frequencias_justificadas = $this->frequencia_model->get_frequencias_justificadas($freq->idunidade, $freq->idsetor, $freq->dataplantao);
                    $freq->frequencias_justificadas = array();
                    foreach ($frequencias_justificadas as $fj) {
                        if ($freq->id == $fj->escala_id && $freq->id_profissional == $fj->profissional_id) {
                            array_push($freq->frequencias_justificadas, $fj);
                        }
                    }
                    
                    // Vinculando as frequências sem escala às escalas, por dia e setor
                    $freq->frequencias_sem_escala = array();

                    if ($datainicial <= '2021-06-20'){
                        foreach ($frequencias_sem_escala as $fse) {
                            $data_fse = date('Y-m-d', strtotime($fse->dt_frq));
                            $hora_fse = date('H:i:s', strtotime($fse->dt_frq));
                            if ($freq->prescricao == 0) {
                                if ((is_null($freq->dt_frq_entrada) or is_null($freq->dt_frq_saida))
                                    && $data_fse == $data_plantao
                                    && ($hora_fse >= $hora_inicial_plantao && $hora_fse <= $hora_final_plantao)
                                    && $freq->nomesetor == $fse->nome_setor_sgc
                                ) {
                                    array_push($freq->frequencias_sem_escala, $fse);
                                } else if ($freq->id_profissional == $fse->id_profissional
                                    && ($data_fse == $data_plantao or ($data_fse == $data_final_plantao && $hora_fse <= $hora_final_plantao))
                                    && $freq->nomesetor == $fse->nome_setor_sgc
                                ) {
                                    array_push($freq->frequencias_sem_escala, $fse);
                                }
                            } else if ($freq->prescricao == 1) {
                                if ((is_null($freq->dt_frq_entrada) or is_null($freq->dt_frq_saida))
                                    && $data_fse == $data_plantao
                                    && $freq->nomesetor == $fse->nome_setor_sgc
                                ) {
                                    array_push($freq->frequencias_sem_escala, $fse);
                                } else if ($freq->id_profissional == $fse->id_profissional
                                    && ($data_fse == $data_plantao or ($data_fse == $data_final_plantao && $hora_fse <= $hora_final_plantao))
                                    && $freq->nomesetor == $fse->nome_setor_sgc
                                ) {
                                    array_push($freq->frequencias_sem_escala, $fse);
                                }
                            }
                        }
                    } else {
                        foreach ($frequencias_sem_escala as $fse) {
                            $data_fse = date('Y-m-d', strtotime($fse->datahorabatida));
                            $hora_fse = date('H:i:s', strtotime($fse->datahorabatida));
                            if ($freq->prescricao == 0) {
                                if ((is_null($freq->dt_frq_entrada) or is_null($freq->dt_frq_saida))
                                    && $data_fse == $data_plantao
                                    && ($hora_fse >= $hora_inicial_plantao && $hora_fse <= $hora_final_plantao)
                                    && $freq->idsetor == $fse->setor_id
                                ) {
                                    array_push($freq->frequencias_sem_escala, $fse);
                                } else if ($freq->id_profissional == $fse->id_profissional
                                    && ($data_fse == $data_plantao or ($data_fse == $data_final_plantao && $hora_fse <= $hora_final_plantao))
                                    && $freq->idsetor == $fse->setor_id
                                ) {
                                    array_push($freq->frequencias_sem_escala, $fse);
                                }
                            } else if ($freq->prescricao == 1) {
                                if ((is_null($freq->dt_frq_entrada) or is_null($freq->dt_frq_saida))
                                    && $data_fse == $data_plantao
                                    && $freq->idsetor == $fse->setor_id
                                ) {
                                    array_push($freq->frequencias_sem_escala, $fse);
                                } else if ($freq->id_profissional == $fse->id_profissional
                                    && ($data_fse == $data_plantao or ($data_fse == $data_final_plantao && $hora_fse <= $hora_final_plantao))
                                    && $freq->idsetor == $fse->setor_id
                                ) {
                                    array_push($freq->frequencias_sem_escala, $fse);
                                }
                            }
                        }
                    }

                    // Vinculando as trocas e passagens à escala
                    $freq->trocas_passagens = array();
                    foreach ($trocas_passagens as $tp) {
                        if ($freq->id == $tp->escala_id) {
                            array_push($freq->trocas_passagens, $tp);
                        }
                    }

                    $freq->tipo_escala = $this->tipo_escala($freq->id);
                }

                /*
                echo('<pre>');
                var_dump($frequencias);
                echo('</pre>');
                exit;
                */
                
                $this->load->helper('group_by');
                $this->data['frequencias'] = group_by('nomesetor', $frequencias);
            } else {
                $datainicial = date('Y-m-d');
                $datafinal = date('Y-m-d');
                if ($this->input->post('unidadehospitalar_id')) {
                    $setores = $this->_get_setores($this->input->post('unidadehospitalar_id'));
                } else {
                    $setores = array('' => 'Selecione um setor');
                }
                $profissionais = array('' => 'Selecione um profissional');
                $domingo = 1;
                $segunda = 2;
                $terca = 3;
                $quarta = 4;
                $quinta = 5;
                $sexta = 6;
                $sabado = 7;
                
                $manha = 0;
                $tarde = 0;
                $noite = 0;

                $hora_atual = (int)date('H');
                if ($hora_atual > 4 && $hora_atual < 13) {
                    $manha = 1;
                } else if ($hora_atual > 12 && $hora_atual < 19) {
                    $tarde = 1;
                } else {
                    $noite = 1;
                }
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

            $this->data['domingo'] = $domingo;
            $this->data['segunda'] = $segunda;
            $this->data['terca'] = $terca;
            $this->data['quarta'] = $quarta;
            $this->data['quinta'] = $quinta;
            $this->data['sexta'] = $sexta;
            $this->data['sabado'] = $sabado;

            $this->data['manha'] = $manha;
            $this->data['tarde'] = $tarde;
            $this->data['noite'] = $noite;

            /* Load Template */
            $this->template->admin_render('admin/escalas/listafrequenciasconferencia', $this->data);
        }
    }

    public function tipo_escala($id){

        $escala = $this->escala_model->get_by_id($id);

        return $escala->tipo_escala;
    }

    public function corrigirfrequenciaescala($escala_id, $frequencia_id)
    {
        if (!$this->ion_auth->logged_in() OR !$this->ion_auth->in_group($this->_permitted_groups)) {
            redirect('auth/login', 'refresh');
        } else {
            $escala_id = (int)$escala_id;
            $frequencia_id = (int)$frequencia_id;

            /* Breadcrumbs */
            $this->data['breadcrumb'] = $this->breadcrumbs->show();

            $escala = $this->escala_model->get_by_id($escala_id);
            $frequencia = $this->frequenciaassessus_model->get_by_cdctlfrq($frequencia_id);
            $frequencias_mt_entrada = $this->frequencia_model->get_where(array('escala_id' => $escala_id, 'tipobatida' => 3));
            $frequencias_mt_saida = $this->frequencia_model->get_where(array('escala_id' => $escala_id, 'tipobatida' => 4));
            
            $frequencia_mt_entrada = null;
            if (!empty($frequencias_mt_entrada)) {
                $frequencia_mt_entrada = $frequencias_mt_entrada[0];
            }
            
            $frequencia_mt_saida = null;
            if (!empty($frequencias_mt_saida)) {
                $frequencia_mt_saida = $frequencias_mt_saida[0];
            }

            if (is_null($escala->frequencia_entrada_id) && (!is_null($escala->frequencia_saida_id) or !is_null($frequencia_mt_saida))) {
                $this->escala_model->update($escala_id, ['frequencia_entrada_id' => $frequencia_id]);
                $this->frequenciaassessus_model->update($frequencia_id, ['escala_id' => $escala_id, 'tipo_batida' => 1]);
                $this->session->set_flashdata('message', 'A frequência foi vinculada &agrave; escala com sucesso. Feche esta janela e volte para a janela anterior.');
                //redirect('admin/escalas/conferencia', 'refresh');
            } else if (is_null($escala->frequencia_saida_id) && (!is_null($escala->frequencia_entrada_id) or !is_null($frequencia_mt_entrada))) {
                $this->escala_model->update($escala_id, ['frequencia_saida_id' => $frequencia_id]);
                $this->frequenciaassessus_model->update($frequencia_id, ['escala_id' => $escala_id, 'tipo_batida' => 2]);
                $this->session->set_flashdata('message', 'A frequência foi vinculada &agrave; escala com sucesso. Feche esta janela e volte para a janela anterior.');
                //redirect('admin/escalas/conferencia', 'refresh');
            } else {
                $this->data['escala'] = $escala;
                $this->data['frequencia'] = $frequencia;
                $this->data['form_action'] = site_url('/admin/escalas/corrigirfrequenciaescalatipobatida');
                $this->session->set_flashdata('message', 'O sistema não pôde definir se era uma entrada ou saída a ser vinculada. Informe o tipo de batida e clique em salvar.');
                $this->template->admin_render('admin/escalas/corrigirfrequenciaescala', $this->data);
            }
        }
    }

    public function corrigirfrequenciaescalatipobatida()
    {
        if (!$this->ion_auth->logged_in() OR !$this->ion_auth->in_group($this->_permitted_groups)) {
            redirect('auth/login', 'refresh');
        } else {
            /* Breadcrumbs */
            $this->data['breadcrumb'] = $this->breadcrumbs->show();

            /* Validate form input */
            $this->form_validation->set_rules('escala_id', 'lang:escalas_escala', 'required');
            $this->form_validation->set_rules('frequencia_id', 'lang:escalas_frequencia', 'required');
            $this->form_validation->set_rules('tipobatida', 'lang:escalas_tipobatida', 'required');
            $this->form_validation->set_rules('profissional_id', 'lang:escalas_tipobatida', 'required');

            if ($this->form_validation->run() == true) {
                $escala_id = $this->input->post('escala_id');
                $frequencia_id = $this->input->post('frequencia_id');
                $tipobatida = $this->input->post('tipobatida');
                $profissional = $this->input->post('profissional_id');

                // Atualizar escala e frequência
                if ($tipobatida == 1) {
                    $this->frequencia_model->update($frequencia_id, ['escala_id' => $escala_id, 'tipobatida' => $tipobatida]);
                    $this->escala_model->update($escala_id, ['frequencia_entrada_id' => $frequencia_id, 'profissional_id' => $profissional]);
                } else {
                    $this->frequencia_model->update($frequencia_id, ['escala_id' => $escala_id, 'tipobatida' => $tipobatida]);
                    $this->escala_model->update($escala_id, ['frequencia_saida_id' => $frequencia_id, 'profissional_id' => $profissional]);
                }
                //$this->frequenciaassessus_model->update($frequencia_id, ['escala_id' => $escala_id, 'tipo_batida' => $tipobatida, 'profissional_id' => $profissional]);

                $this->session->set_flashdata('message', 'A frequência foi vinculada &agrave; escala com sucesso. Feche esta janela e volte para a janela anterior.');
                //redirect('admin/escalas/conferencia', 'refresh');
            } else {
                $this->data['message'] = validation_errors() ? validation_errors() : $this->session->flashdata('message');
                //redirect('admin/escalas/corrigirfrequenciaescala/' . $escala_id . '/' . $frequencia_id, 'refresh');
            }
            echo json_encode('sucess');exit;
        }
    }

    /**
     * Cria um plantão extra a partir da conferência de escalas
     */
    public function criarplantaoextra($escala_id, $frequencia_id, $profissional_id)
    {
        if (!$this->ion_auth->logged_in()) {
            $this->session->set_flashdata('message', 'Você deve estar autenticado para usar esta função.');
            redirect('auth/login', 'refresh');
        }
        if (!$this->ion_auth->in_group($this->_permitted_groups)) {
            $this->session->set_flashdata('message', 'O acesso &agrave; este recurso não é permitido ao seu perfil de usuário.');
            redirect('admin/dashboard', 'refresh');
        }

        $escala_id = (int)$escala_id;
        $frequencia_id = (int)$frequencia_id;
        $profissional_id = (int)$profissional_id;

        /* Breadcrumbs */
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        $escala = $this->escala_model->get_by_id($escala_id);
        $profissional = $this->profissional_model->get_by_id($profissional_id);
        $frequencia = $this->frequenciaassessus_model->get_by_cdctlfrq($frequencia_id);
        
        // Inserindo a escala extra
        $insert_plantao_extra = array (
            'dataplantao' => $escala->dataplantao,
            'datafinalplantao' => $escala->datafinalplantao,
            'horainicialplantao' => $escala->horainicialplantao,
            'horafinalplantao' => $escala->horafinalplantao,
            'duracao' => $escala->duracao,
            'profissional_id' => $profissional_id,
            'setor_id' => $escala->setor_id,
            'validada' => null,
            'datahora_validacao' => null,
            'usuario_validacao_id' => null,
            'frequencia_entrada_id' => null,
            'frequencia_saida_id' => null,
            'status' => $this::STATUS_ESCALA_INCOMPLETA,
            'tipo_plantao' => $this::TIPO_PLANTAO_VOLATIL,
            'extra' => $this::PLANTAO_EXTRA_SIM,
        );

        $id_escala_extra = $this->escala_model->insert($insert_plantao_extra);

        // Vinculando a frequência informada ao plantão extra criado
        
        if ($id_escala_extra) {
            $this->session->set_flashdata('message', 'A escala extra foi criada com sucesso. Feche esta janela e volte para a janela anterior.');
            redirect('admin/escalas/conferencia', 'refresh');
        } else {
            $this->session->set_flashdata('message', 'Houve um problema ao criar a escala extra. Feche esta janela e volte para a janela anterior.');
            redirect('admin/escalas/conferencia', 'refresh');
        }
    }

    public function ignorarbatida($frequencia_id)
    {
        if (!$this->ion_auth->logged_in()) {
            $this->session->set_flashdata('message', 'Você deve estar autenticado para usar esta função.');
            redirect('auth/login', 'refresh');
        }
        if (!$this->ion_auth->in_group($this->_permitted_groups)) {
            $this->session->set_flashdata('message', 'O acesso &agrave; este recurso não é permitido ao seu perfil de usuário.');
            redirect('admin/dashboard', 'refresh');
        }

        $frequencia_id = (int)$frequencia_id;

        /* Breadcrumbs */
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        $frequencia = $this->frequenciaassessus_model->get_by_cdctlfrq($frequencia_id);

        if ($frequencia) {
            $this->frequenciaassessus_model->update($frequencia_id, ['ignorar' => 1]);
            $this->session->set_flashdata('message', 'A frequência foi ignorada com sucesso. Feche esta janela e volte para a janela anterior.');
            redirect('admin/escalas/conferencia', 'refresh');
        } else {
            $this->session->set_flashdata('message', 'Não foi encontrada uma frequência com o código informado. Favor selecionar uma frequência válida. Feche esta janela e volte para a janela anterior.');
            redirect('admin/escalas/conferencia', 'refresh');
        }
    }

    public function desfazerignorarbatida($frequencia_id)
    {
        if (!$this->input->is_ajax_request()) {
            exit("Nenhum acesso de script direto permitido!");
        }  
        $frequencia_id = (int)$frequencia_id;

        /* Breadcrumbs */
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        $frequencia = $this->frequenciaassessus_model->get_by_cdctlfrq($frequencia_id);

        if ($frequencia) {
            $this->frequenciaassessus_model->update($frequencia_id, ['ignorar' => 0]);
        }

        return $json['status'] = 1;
        exit;
    }

    public function ajax_listar_batidas_ignoradas() {

        if (!$this->input->is_ajax_request()) {
            exit("Nenhum acesso de script direto permitido!");
        }  
        $this->load->model("cemerge/FrequenciaAssessus_model");
        $this->load->model("cemerge/Setor_model");
        $batidas = $this->FrequenciaAssessus_model->get_batidas();
        

        $data = array();
        foreach ($batidas as $batida) {
            $setor_sgc = $this->setor_model->sgc_x_assessus($batida->CD_SET)->setor_id;
            //ar_dump($setor_sgc); exit;
            $profissional_sgc = $this->profissional_model->get_by_cd_pes_fis($batida->CD_PES_FIS);
            //var_dump($setor_sgc); exit;
            $row = array();
            $row[] = '<center>'.date('d/m/Y',strtotime($batida->DT_FRQ)).'</center>';
            $row[] = '<center>'.date('H:i',strtotime($batida->DT_FRQ)).'</center>';
            $row[] = '<center>'.$this->setor_model->get_setor_por_id($setor_sgc)->nome.'</center>';
            $row[] = '<center>'.$profissional_sgc->nome.'</center>';
    
            $row[] = '<center><div style="display: inline-block;">
                        <button class="btn btn-link btn-reverter-batida-ignorada" 
                            id='.$batida->CD_CTL_FRQ.'>
                            <i class="fa fa-refresh"> Restaurar Batida</i>
                        </button>
                    </div></center>';
    
            $data[] = $row;
    
        }
        $json = array(
            "draw" => $this->input->post("draw"),
            "recordsTotal" => $this->FrequenciaAssessus_model->records_total(),
            "recordsFiltered" => $this->FrequenciaAssessus_model->records_filtered(),
            "status" => 1,
            "data" => $data,
        );
        echo json_encode($json);
        exit;
    }
    
    public function aguardarjustificativa($escala_id)
    {
        if (!$this->ion_auth->logged_in()) {
            $this->session->set_flashdata('message', 'Você deve estar autenticado para usar esta função.');
            redirect('auth/login', 'refresh');
            
        }
        if (!$this->ion_auth->in_group($this->_permitted_groups)) {
            $this->session->set_flashdata('message', 'O acesso &agrave; este recurso não é permitido ao seu perfil de usuário.');
            redirect('admin/dashboard', 'refresh');
        }

        $escala_id = (int)$escala_id;

        /* Breadcrumbs */
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        $sucesso = $this->escala_model->update($escala_id, ['status' => $this::STATUS_ESCALA_AGUARDANDO_JUSTIFICATIVA]);

        if ($sucesso) {
            $this->session->set_flashdata('message', 'O status da escala foi mudado para "Aguardando justificativa". Feche esta janela e volte para a janela anterior.');
            $this->escala_model->update($escala_id, ['justificativa' => 1]);
        } else {
            $this->session->set_flashdata('message', 'Houve um erro ao alterar o status da escala. Tente novamente.');
            $this->escala_model->update($escala_id, ['justificativa' => 0]);
        }
        redirect('admin/escalas/conferencia', 'refresh');
    }
    public function retirarFalta($escala_id) {
        if (empty($frequencias_mt_entrada) || (empty($frequencias_mt_saida))) {
            $this->escala_model->update($escala_id, ['status'  => $this::STATUS_ESCALA_INCOMPLETA]);
        } else {
            $this->escala_model->update($escala_id, ['status'  => $this::STATUS_ESCALA_OK]);
        }
        redirect('admin/escalas/conferencia', 'refresh');
    }

    public function escala_por_profissional($data, $profissional_id, $setor_id, $tipo_plantao){

        $escalas = $this->escala_model->get_escalas_consolidadas_por_profissional($profissional_id, $data, date('Y-m-d', strtotime($data . ' + 20 days')), $setor_id, $tipo_plantao);
        if (empty($escalas)){
            $plantoes = array(
                 0 => 'Profissional não possui plantões para troca',
             );
        } else {
            $plantoes = array(
                // '' => 'Selecione o Plantão para Troca',
             );
        }
        
        $turno = '';
        foreach ($escalas as $escala) {
            if ($escala->horainicialplantao == '07:00:00'){
                $turno = 'Manhã';
            } else if ($escala->horainicialplantao == '13:00:00'){
                $turno = 'Tarde';
            } else {
                $turno = 'Noite';
            }
            $plantoes[$escala->id] = date('d/m/Y', strtotime($escala->dataplantao)) . ' - '.$turno;
        }

        echo json_encode($plantoes);
        exit;

    }


    public function registrarfalta($escala_id)
    {
        if (!$this->ion_auth->logged_in()) {
            $this->session->set_flashdata('message', 'Você deve estar autenticado para usar esta função.');
            redirect('auth/login', 'refresh');
        }
        if (!$this->ion_auth->in_group($this->_permitted_groups)) {
            $this->session->set_flashdata('message', 'O acesso &agrave; este recurso não é permitido ao seu perfil de usuário.');
            redirect('admin/dashboard', 'refresh');
        }

        $this->load->model('cemerge/falta_model');
        
        /* Breadcrumbs */
        $this->data['breadcrumb'] = $this->breadcrumbs->show();
        
        // Obtendo a escala
        $escala_id = (int)$escala_id;
        $escala = $this->escala_model->get_escala_consolidada_por_id($escala_id);

        // Testando se a escala possui profissional
        if (is_null($escala->id_profissional) or $escala->id_profissional == 0) {
            $this->session->set_flashdata('message', 'Não é possível registrar falta em um plantão que não possui profissional alocado.');
            redirect('admin/escalas/conferencia', 'refresh');
        }

        $this->data['escala'] = $escala;
        $falta = $this->falta_model->get_where(['escala_id' => $escala_id]);
        $justificativa = '';
        $tipo_falta = 1;
        if (!empty($falta)) {
            $justificativa = $falta[0]->justificativa;
            $tipo_falta = $falta[0]->tipo_falta;
        }

        $this->form_validation->set_rules('tipofalta', 'lang:escalas_tipofalta', 'required');
        $this->form_validation->set_rules('justificativa', 'lang:escalas_justificativa', 'callback_justificativa_check');

        if ($this->form_validation->run() == true) {
            $tipo_falta = $this->input->post('tipofalta');
            $justificativa = $this->input->post('justificativa');

            $user_id = $this->ion_auth->user()->row()->id;

            if (!empty($falta)) {
                $data_update = array (
                    'tipo_falta' => $tipo_falta,
                    'justificativa' => $justificativa,
                    'user_id' => $user_id,
                );

                $sucesso = $this->falta_model->update($falta[0]->id, $data_update);
            } else {
                $data_insert = array (
                    'escala_id' => $escala_id,
                    'profissional_id' => $escala->id_profissional,
                    'tipo_falta' => $tipo_falta,
                    'justificativa' => $justificativa,
                    'user_id' => $user_id,
                );

                $sucesso = $this->falta_model->insert($data_insert);
            }
            $sucesso_update = $this->escala_model->update($escala_id, ['status' => $this::STATUS_ESCALA_FALTA]);
            $this->session->set_flashdata('message', 'A falta foi registrada com sucesso.');

            redirect('admin/escalas/conferencia', 'refresh');
        } else {
            $this->session->set_flashdata('message', validation_errors());
        }

        $this->data['tipofalta'] = array(
            'name'  => 'tipofalta',
            'id'    => 'tipofalta',
            'type'  => 'select',
            'class' => 'form-control',
            'selected' => $this->form_validation->set_value('tipofalta', $tipo_falta),
            'options' => array(
                '1' => 'Justificada',
                '2' => 'Não justificada',
            ),
        );
        $this->data['justificativa'] = array(
            'name'  => 'justificativa',
            'id'    => 'justificativa',
            'type'  => 'textarea',
            'class' => 'form-control',
            'rows'  => '10',
            'value' => $this->form_validation->set_value('justificativa', $justificativa),
        );

        $this->template->admin_render('admin/escalas/registrarfalta', $this->data);
    }

    /** Callback para checagem da justificativa no método registrarfalta */
    public function justificativa_check($str)
    {
        $tipo_falta = $this->input->post('tipofalta');

        if (strlen(trim($str)) <= 0 && $tipo_falta == 1) {
            $this->form_validation->set_message('justificativa_check', 'A {field} não pode ser vazia');
            return false;
        } else {
            return true;
        }
    }

    public function desvincularescalaefrequencias($escala_id)
    {
        $escala_id = (int)$escala_id;

        if (!$this->ion_auth->logged_in()) {
            $this->session->set_flashdata('message', 'Você deve estar autenticado para usar esta função.');
            redirect('auth/login', 'refresh');
        }
        if (!$this->ion_auth->in_group($this->_admin_groups)) {
            $this->session->set_flashdata('message', 'O acesso &agrave; este recurso não é permitido ao seu perfil de usuário.');
            redirect('admin/dashboard', 'refresh');
        }

        /* Breadcrumbs */
        $this->breadcrumbs->unshift(2, lang('menu_plantoes_tooffer'), 'admin/escalas/desvincularescalaefrequencias');
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        /* Load Data */
        $escala = $this->escala_model->get_escala_by_id($escala_id);
        $frequencia_entrada = null;
        $frequencia_saida = null;
        if ($escala && $escala->frequencia_entrada_id) {
            $frequencia_entrada = $this->frequenciaassessus_model->get_by_cdctlfrq($escala->frequencia_entrada_id);
        }
        if ($escala && $escala->frequencia_saida_id) {
            $frequencia_saida = $this->frequenciaassessus_model->get_by_cdctlfrq($escala->frequencia_saida_id);
        }

        if ($escala) {
            try {
                if ($frequencia_entrada) {
                    $this->frequenciaassessus_model->update($frequencia_entrada->CD_CTL_FRQ, ['escala_id' => null, 'tipo_batida' => null]);
                    $this->escala_model->update($escala->id, ['frequencia_entrada_id' => null, 'status' => 0]);
                }
                if ($frequencia_saida) {
                    $this->frequenciaassessus_model->update($frequencia_saida->CD_CTL_FRQ, ['escala_id' => null, 'tipo_batida' => null]);
                    $this->escala_model->update($escala->id, ['frequencia_saida_id' => null, 'status' => 0]);
                }
                $this->session->set_flashdata('message', 'A escala foi desvinculada das frequências com sucesso. Feche esta janela e volte para a janela anterior.');
            } catch (Exception $e) {
                $this->session->set_flashdata('message', 'Ocorreu um erro ao tentar desvincular a escala das frequências. Feche esta janela e tente novamente.');
            }
        } else {
            $this->session->set_flashdata('message', 'Não foi possível realizar a operação. A escala informada é inválida.');
        }

        redirect('admin/escalas/conferencia', 'refresh');
    }

    public function desvincularescalaefrequencias_nova($escala_id)
    {
        $escala_id = (int)$escala_id;

        if (!$this->ion_auth->logged_in()) {
            $this->session->set_flashdata('message', 'Você deve estar autenticado para usar esta função.');
            redirect('auth/login', 'refresh');
        }
        if (!$this->ion_auth->in_group($this->_admin_groups)) {
            $this->session->set_flashdata('message', 'O acesso &agrave; este recurso não é permitido ao seu perfil de usuário.');
            redirect('admin/dashboard', 'refresh');
        }

        /* Breadcrumbs */
        $this->breadcrumbs->unshift(2, lang('menu_plantoes_tooffer'), 'admin/escalas/desvincularescalaefrequencias');
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        /* Load Data */
        $escala = $this->escala_model->get_escala_by_id($escala_id);
        $frequencia_entrada = null;
        $frequencia_saida = null;
        if ($escala && $escala->frequencia_entrada_id) {
            $frequencia_entrada = $this->frequencia_model->get_by_id($escala->frequencia_entrada_id);
        }
        if ($escala && $escala->frequencia_saida_id) {
            $frequencia_saida = $this->frequencia_model->get_by_id($escala->frequencia_saida_id);
        }

        if ($escala) {
            try {
                if ($frequencia_entrada) {
                    $this->frequencia_model->update($frequencia_entrada->id, ['escala_id' => null, 'tipobatida' => null]);
                    $this->escala_model->update($escala->id, ['frequencia_entrada_id' => null, 'status' => 0]);
                }
                if ($frequencia_saida) {
                    $this->frequencia_model->update($frequencia_saida->id, ['escala_id' => null, 'tipobatida' => null]);
                    $this->escala_model->update($escala->id, ['frequencia_saida_id' => null, 'status' => 0]);
                }
                $this->session->set_flashdata('message', 'A escala foi desvinculada das frequências com sucesso. Feche esta janela e volte para a janela anterior.');
            } catch (Exception $e) {
                $this->session->set_flashdata('message', 'Ocorreu um erro ao tentar desvincular a escala das frequências. Feche esta janela e tente novamente.');
            }
        } else {
            $this->session->set_flashdata('message', 'Não foi possível realizar a operação. A escala informada é inválida.');
        }

        redirect('admin/escalas/conferencia', 'refresh');
    }

    public function confirmarcessaotroca($cessaotroca_id)
    {
        $cessaotroca_id = (int)$cessaotroca_id;

        if (!$this->ion_auth->logged_in()) {
            $this->session->set_flashdata('message', 'Você deve estar autenticado para usar esta função.');
            redirect('auth/login', 'refresh');
        }
        if (!$this->ion_auth->in_group($this->_admin_groups)) {
            $this->session->set_flashdata('message', 'O acesso &agrave; este recurso não é permitido ao seu perfil de usuário.');
            redirect('admin/dashboard', 'refresh');
        }

        /* Breadcrumbs */
        $this->breadcrumbs->unshift(2, lang('menu_escalas_confirmar_cessaotroca'), 'admin/escalas/confirmarcessaotroca');
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        /* Load Data */
        $this->load->model('cemerge/passagemtroca_model');
        $cessaotroca = $this->passagemtroca_model->get_by_id($cessaotroca_id);

        if ($cessaotroca) {
            try {
                if ($cessaotroca->tipopassagem == $this::TIPO_PASSAGEM_CESSAO) {
                    $this->passagemtroca_model->update($cessaotroca_id, ['datahoraconfirmacao' => date('Y-m-d H:i:s'), 'statuspassagem' => $this::STATUS_PASSAGEM_CONFIRMADA]);
                }
                if ($cessaotroca->tipopassagem == $this::TIPO_PASSAGEM_TROCA) {
                    $this->passagemtroca_model->update($cessaotroca_id, ['datahoraconfirmacao' => date('Y-m-d H:i:s'), 'statuspassagem' => $this::STATUS_PASSAGEM_CONFIRMADA]);

                    /** Finalizando a troca, inserindo o outro registro */
                    $troca_insert = array(
                        'escala_id' => $cessaotroca->escalatroca_id,
                        'profissional_id' => $cessaotroca->profissionalsubstituto_id,
                        'profissionalsubstituto_id' => $cessaotroca->profissional_id,
                        'tipopassagem' => $cessaotroca->tipopassagem,
                        'datahorapassagem' => date('Y-m-d H:i:s'),
                        'datahoraproposta' => date('Y-m-d H:i:s'),
                        'datahoraconfirmacao' => date('Y-m-d H:i:s'),
                        'statuspassagem' => $this::STATUS_PASSAGEM_CONFIRMADA,
                        'escalatroca_id' => $cessaotroca->escala_id
                    );
                    $sucesso = $this->passagemtroca_model->insert($troca_insert);
                }
                $this->session->set_flashdata('message', 'A cessão ou troca foi confirmada com sucesso. Feche esta janela e volte para a janela anterior.');
            } catch (Exception $e) {
                $this->session->set_flashdata('message', 'Ocorreu um erro ao tentar confirmar a cessão ou troca. Feche esta janela e tente novamente.');
            }
        } else {
            $this->session->set_flashdata('message', 'Não foi possível realizar a operação. A cessão ou troca informada é inválida.');
        }

        redirect('admin/escalas/listarcessoesetrocas', 'refresh');
    }

    public function confirmaroportunidade($cessaotroca_id)
    {
        $cessaotroca_id = (int)$cessaotroca_id;

        if (!$this->ion_auth->logged_in()) {
            $this->session->set_flashdata('message', 'Você deve estar autenticado para usar esta função.');
            redirect('auth/login', 'refresh');
        }
        if (!$this->ion_auth->in_group($this->_permitted_groups)) {
            $this->session->set_flashdata('message', 'O acesso &agrave; este recurso não é permitido ao seu perfil de usuário.');
            redirect('admin/dashboard', 'refresh');
        }

        /* Breadcrumbs */
        $this->breadcrumbs->unshift(2, lang('menu_escalas_confirmar_oportunidade'), 'admin/escalas/confirmaroportunidade');
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        /* Load Data */
        $this->load->model('cemerge/passagemtroca_model');
        $this->load->model('cemerge/usuarioprofissional_model');

        $cessaotroca = $this->passagemtroca_model->get_by_id($cessaotroca_id);
        $userId = $this->ion_auth->user()->row()->id;
        $profissional_id = $this->usuarioprofissional_model->get_where(['user_id' => $userId]);
        if ($profissional_id) {
            $profissional_id = $this->profissional_model->get_where(['id' => $profissional_id[0]->profissional_id])[0];
        }
        if ($cessaotroca) {
            try {
                if ($cessaotroca->tipopassagem == $this::TIPO_PASSAGEM_CESSAO) {
                    $this->passagemtroca_model->update($cessaotroca_id, ['datahoraconfirmacao' => date('Y-m-d H:i:s'), 'statuspassagem' => $this::STATUS_PASSAGEM_CONFIRMADA]);
                    $this->passagemtroca_model->update($cessaotroca_id, ['profissionalsubstituto_id' => $profissional_id->id]);
                }
                if ($cessaotroca->tipopassagem == $this::TIPO_PASSAGEM_TROCA) {
                    $this->passagemtroca_model->update($cessaotroca_id, ['datahoraconfirmacao' => date('Y-m-d H:i:s'), 'statuspassagem' => $this::STATUS_PASSAGEM_CONFIRMADA]);
                    $this->passagemtroca_model->update($cessaotroca_id, ['profissionalsubstituto_id' => $profissional_id->id]);

                    /** Finalizando a troca, inserindo o outro registro */
                    $troca_insert = array(
                        'escala_id' => $cessaotroca->escalatroca_id,
                        'profissional_id' => $profissional_id->id,
                        'profissionalsubstituto_id' =>  $cessaotroca->profissional_id,
                        'tipopassagem' => $cessaotroca->tipopassagem,
                        'datahorapassagem' => date('Y-m-d H:i:s'),
                        'datahoraproposta' => date('Y-m-d H:i:s'),
                        'datahoraconfirmacao' => date('Y-m-d H:i:s'),
                        'statuspassagem' => $this::STATUS_PASSAGEM_CONFIRMADA,
                        'escalatroca_id' => $cessaotroca->escala_id
                    );
                    $sucesso = $this->passagemtroca_model->insert($troca_insert);
                }
                $this->session->set_flashdata('message', 'Você aceitou essa oportunidade. Feche esta janela e volte para a janela anterior.');
            } catch (Exception $e) {
                $this->session->set_flashdata('message', 'Ocorreu um erro ao tentar confirmar a oportunidade. Feche esta janela e tente novamente.');
            }
        } else {
            $this->session->set_flashdata('message', 'Não foi possível realizar a operação. A oportunidade selecionada, não esta mais disponivel.');
        }

        redirect('admin/escalas/listaroportunidades', 'refresh');
    }

    public function cancelarcessaotroca($cessaotroca_id)
    {
        $cessaotroca_id = (int)$cessaotroca_id;

        if (!$this->ion_auth->logged_in()) {
            $this->session->set_flashdata('message', 'Você deve estar autenticado para usar esta função.');
            redirect('auth/login', 'refresh');
        }
        if (!$this->ion_auth->in_group($this->_admin_groups)) {
            $this->session->set_flashdata('message', 'O acesso &agrave; este recurso não é permitido ao seu perfil de usuário.');
            redirect('admin/dashboard', 'refresh');
        }

        /* Breadcrumbs */
        $this->breadcrumbs->unshift(2, lang('menu_escalas_cancelar_cessaotroca'), 'admin/escalas/cancelarcessaotroca');
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        /* Load Data */
        $this->load->model('cemerge/passagemtroca_model');
        $cessaotroca = $this->passagemtroca_model->get_by_id($cessaotroca_id);

        if ($cessaotroca) {
            try {
                $this->passagemtroca_model->delete(['id' => $cessaotroca_id]);
                $this->session->set_flashdata('message', 'A cessão ou troca foi excluída com sucesso. Feche esta janela e volte para a janela anterior.');
            } catch (Exception $e) {
                $this->session->set_flashdata('message', 'Ocorreu um erro ao tentar excluir a cessão ou troca. Feche esta janela e tente novamente.');
            }
        } else {
            $this->session->set_flashdata('message', 'Não foi possível realizar a operação. A cessão ou troca informada é inválida.');
        }

        redirect('admin/escalas/listarcessoesetrocas', 'refresh');
    }

    public function create()
    {
        /* Breadcrumbs */
        $this->breadcrumbs->unshift(2, lang('menu_escalas_create'), 'admin/escalas/create');
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        /* Variables */
        $this->data['data_minima'] = date('Y-m-d', strtotime(date('Y-m-d') . ' - 365 days')); // deixar somente data atual date('Y-m-d')
        $this->data['data_maxima'] = date('Y-m-d', strtotime(date('Y-m-d') . ' + 90 days'));


        /* Validate form input */
        $this->form_validation->set_rules('unidadehospitalar_id', 'lang:escalas_unidadehospitalar', 'required');
        $this->form_validation->set_rules('setor_id', 'lang:escalas_setor', 'required');
        $this->form_validation->set_rules('datainicialplantao', 'lang:escalas_datainicialplantao', 'required');
        /*
        $this->form_validation->set_rules('datafinalplantao', 'lang:escalas_datafinalplantao', 'required');
        $this->form_validation->set_rules('horainicialplantao', 'lang:escalas_horainicialplantao', 'required');
        $this->form_validation->set_rules('horafinalplantao', 'lang:escalas_horafinalplantao', 'required');
        */
        $this->form_validation->set_rules('tipos', 'lang:escalas_tipos', 'required');

        if ($this->form_validation->run() == true) {
            $unidadehospitalar_id = $this->input->post('unidadehospitalar_id');
            $setor_id = $this->input->post('setor_id');
            $datainicialplantao = $this->input->post('datainicialplantao');
            $datafinalplantao = $this->input->post('datafinalplantao');
            $diaria = $this->input->post('diaria');
            $manha = $this->input->post('manha');
            $tarde = $this->input->post('tarde');
            $noite = $this->input->post('noite');
            $tipo = $this->input->post('tipos');
            //$horainicialplantao = $this->input->post('horainicialplantao');
            //$horafinalplantao = $this->input->post('horafinalplantao');
            //$active = $this->input->post('active');

            //var_dump($diaria); exit;
            $additional_dataM = array(
                'unidadehospitalar_id' => $this->input->post('unidadehospitalar_id'),
                'setor_id' => $this->input->post('setor_id'),
                'datainicialplantao' => $datainicialplantao,
                'datafinalplantao' => $datainicialplantao,
                'horainicialplantao' => '07:00:00',
                'horafinalplantao' => '13:00:00',
                'tipo_escala' => $tipo
            );

            $additional_dataT = array(
                'unidadehospitalar_id' => $this->input->post('unidadehospitalar_id'),
                'setor_id' => $this->input->post('setor_id'),
                'datainicialplantao' => $datainicialplantao,
                'datafinalplantao' => $datainicialplantao,
                'horainicialplantao' => '13:00:00',
                'horafinalplantao' => '19:00:00',
                'tipo_escala' => $tipo
            );

            $additional_dataN = array(
                'unidadehospitalar_id' => $this->input->post('unidadehospitalar_id'),
                'setor_id' => $this->input->post('setor_id'),
                'datainicialplantao' => $datainicialplantao,
                'datafinalplantao' => date('Y-m-d', strtotime($datainicialplantao . ' +1 day')),
                'horainicialplantao' => '19:00:00',
                'horafinalplantao' => '07:00:00',
                'tipo_escala' => $tipo
            );
            
            $datainicial = new DateTime($datainicialplantao);
            $datafinal = new DateTime($datafinalplantao);
            //var_dump($datafinal); exit;

            if ($datainicial > $datafinal) {
                $this->session->set_flashdata('message', 'A data final do plantão deve ser maior ou igual &agrave; data inicial.');
                //redirect('admin/escalas/create', 'refresh');
            }

            /*
            if ($additional_dataM['horainicialplantao'] == $additional_dataM['horafinalplantao']) {
                $this->session->set_flashdata('message', 'A hora inicial do plantão Manhã não pode ser igual &agrave; data final.');
                //redirect('admin/escalas/create', 'refresh');
            }

            if ($additional_dataT['horainicialplantao'] == $additional_dataT['horafinalplantao']) {
                $this->session->set_flashdata('message', 'A hora inicial do plantão Tarde não pode ser igual &agrave; data final.');
                //redirect('admin/escalas/create', 'refresh');
            }
            if ($additional_dataN['horainicialplantao'] == $additional_dataN['horafinalplantao']) {
                $this->session->set_flashdata('message', 'A hora inicial do plantão Noite não pode ser igual &agrave; data final.');
                //redirect('admin/escalas/create', 'refresh');
            }
            */

        }

        // Realizar o insert no model
        if ($this->form_validation->run() == true) {
            $success = false;

            $setor = $this->setor_model->get_by_id($setor_id);
            if ($setor->maximoprofissionais < 1) {
                $this->session->set_flashdata('message', 'O número máximo de profissionais por plantão no setor não foi informado. Corrija esta informação no cadastro de setores.');
                //redirect('admin/escalas/create', 'refresh');
            }
            
            $datainicialM = new DateTime($additional_dataM['datainicialplantao']);
            $datafinalM = new DateTime($additional_dataM['datafinalplantao']);
            $horainicialM = new DateTime($additional_dataM['horainicialplantao']);
            $horafinalM = new DateTime($additional_dataM['horafinalplantao']);
            $datainicialT = new DateTime($additional_dataT['datainicialplantao']);
            $datafinalT = new DateTime($additional_dataT['datafinalplantao']);
            $horainicialT = new DateTime($additional_dataT['horainicialplantao']);
            $horafinalT = new DateTime($additional_dataT['horafinalplantao']);
            $datainicialN = new DateTime($additional_dataN['datainicialplantao']);
            $datafinalN = new DateTime($additional_dataN['datafinalplantao']);
            $horainicialN = new DateTime($additional_dataN['horainicialplantao']);
            $horafinalN = new DateTime($additional_dataN['horafinalplantao']);

            // Apagar escalas do período no mesmo setor e horários
            $delete = false;

            if ($tipo == 1){

                $whereMTN = array(
                    'setor_id' => $setor_id,
                    'dataplantao >=' => $datainicialplantao,
                    'dataplantao <=' => $datafinalplantao,
                    'tipo_escala' => $tipo
                    /*'horainicialplantao' => $horainicialM->format('H:i:s'),
                    'horafinalplantao' => $horafinalM->format('H:i:s'),*/
                );/*
                $whereT = array(
                    'setor_id' => $setor_id,
                    'dataplantao >=' => $datainicialT->format('Y-m-d'),
                    'dataplantao <=' => $datafinalT->format('Y-m-d'),
                    'horainicialplantao' => $horainicialT->format('H:i:s'),
                    'horafinalplantao' => $horafinalT->format('H:i:s'),
                );
                $whereN = array(
                    'setor_id' => $setor_id,
                    'dataplantao >=' => $datainicialN->format('Y-m-d'),
                    'dataplantao <=' => $datafinalN->format('Y-m-d'),
                    'horainicialplantao' => $horainicialN->format('H:i:s'),
                    'horafinalplantao' => $horafinalN->format('H:i:s'),
                );*/
                if ($this->escala_model->delete($whereMTN)){
                    $delete = true;
                }              
            } else {
                $whereMT = array(   
                    'setor_id' => $setor_id,
                    'dataplantao >=' => $datainicialplantao,
                    'dataplantao <=' => $datafinalplantao,
                    'tipo_escala' => $tipo
                    /*'horainicialplantao' => $horainicialM->format('H:i:s'),
                    'horafinalplantao' => $horafinalM->format('H:i:s'),*/
                );/*
                $whereT = array(
                    'setor_id' => $setor_id,
                    'dataplantao >=' => $datainicialT->format('Y-m-d'),
                    'dataplantao <=' => $datafinalT->format('Y-m-d'),
                    'horainicialplantao' => $horainicialT->format('H:i:s'),
                    'horafinalplantao' => $horafinalT->format('H:i:s'),
                );*/
                if ($this->escala_model->delete($whereMT)){
                    $delete = true;
                }
            }
            //var_dump($manha); exit;            
            // Loop para inserir no período
            if ($tipo == 1){
                //Manhã
                for ($i = $datainicial; $i <= $datafinal; $i->modify('+1 day')) {
                    $hrinicialplantaoM = $horainicialM->format('H:i:s');
                    $hrfinalplantaoM = $horafinalM->format('H:i:s');
                    $dtinicialplantaoM = $i->format('Y-m-d');
                    $dtfinalplantaoM = $dtinicialplantaoM;
                    $duracaoM = abs((int)$hrinicialplantaoM - (int)$hrfinalplantaoM);
                    if ((int)$hrinicialplantaoM > (int)$hrfinalplantaoM) {
                        $dtfinalplantaoM = date('Y-m-d', strtotime($dtinicialplantaoM . ' +1 day'));
                    }
                    for ($j= 1; $j <= $manha; $j++) { 
                        $insert_dataM = array(
                            'setor_id' => $additional_dataM['setor_id'],
                            'dataplantao' => $dtinicialplantaoM,
                            'datafinalplantao' => $dtfinalplantaoM,
                            'horainicialplantao' => $hrinicialplantaoM,
                            'horafinalplantao' => $hrfinalplantaoM,
                            'duracao' => $duracaoM,
                            'tipo_escala' => $tipo
                        );
                        $success = $this->escala_model->insert($insert_dataM);
                    }
                    $hrinicialplantaoT = $horainicialT->format('H:i:s');
                    $hrfinalplantaoT = $horafinalT->format('H:i:s');
                    $dtinicialplantaoT = $i->format('Y-m-d');
                    $dtfinalplantaoT = $dtinicialplantaoT;
                    $duracaoT = abs((int)$hrinicialplantaoT - (int)$hrfinalplantaoT);
                    if ((int)$hrinicialplantaoT > (int)$hrfinalplantaoT) {
                        $dtfinalplantaoT = date('Y-m-d', strtotime($dtinicialplantaoT . ' +1 day'));
                    }
                    for ($j=1; $j <= $tarde; $j++) { 
                        $insert_dataT = array(
                            'setor_id' => $additional_dataT['setor_id'],
                            'dataplantao' => $dtinicialplantaoT,
                            'datafinalplantao' => $dtfinalplantaoT,
                            'horainicialplantao' => $hrinicialplantaoT,
                            'horafinalplantao' => $hrfinalplantaoT,
                            'duracao' => $duracaoT,
                            'tipo_escala' => $tipo
                        );
                        $success = $this->escala_model->insert($insert_dataT);
                    }
                    $hrinicialplantaoN = $horainicialN->format('H:i:s');
                    $hrfinalplantaoN = $horafinalN->format('H:i:s');
                    $dtinicialplantaoN = $i->format('Y-m-d');
                    $dtfinalplantaoN = $dtinicialplantaoN;
                    $duracaoN = abs((int)$hrinicialplantaoN - (int)$hrfinalplantaoN);
                    if ((int)$hrinicialplantaoN > (int)$hrfinalplantaoN) {
                        $dtfinalplantaoN = date('Y-m-d', strtotime($dtinicialplantaoN . ' +1 day'));
                    }
                    for ($j=1; $j <= $noite; $j++) { 
                        $insert_dataN = array(
                            'setor_id' => $additional_dataN['setor_id'],
                            'dataplantao' => $dtinicialplantaoN,
                            'datafinalplantao' => $dtfinalplantaoN,
                            'horainicialplantao' => $hrinicialplantaoN,
                            'horafinalplantao' => $hrfinalplantaoN,
                            'duracao' => $duracaoN,
                            'tipo_escala' => $tipo
                        );
                    
                        $success = $this->escala_model->insert($insert_dataN);
                        //var_dump($insert_dataM); exit;
                    }
                };
            } else {
                //Manhã/Tarde
                for ($i = $datainicial; $i <= $datafinal; $i->modify('+1 day')) {
                    $hrinicialplantaoM = $horainicialM->format('H:i:s');
                    $hrfinalplantaoM = $horafinalM->format('H:i:s');
                    $dtinicialplantaoM = $i->format('Y-m-d');
                    $dtfinalplantaoM = $dtinicialplantaoM;
                    $duracaoM = abs((int)$hrinicialplantaoM - (int)$hrfinalplantaoM);
                    if ((int)$hrinicialplantaoM > (int)$hrfinalplantaoM) {
                        $dtfinalplantaoM = date('Y-m-d', strtotime($dtinicialplantao . ' +1 day'));
                    }
                    for ($j=1; $j <= $diaria; $j++) { 
                        $insert_dataM = array(
                            'setor_id' => $additional_dataM['setor_id'],
                            'dataplantao' => $dtinicialplantaoM,
                            'datafinalplantao' => $dtfinalplantaoM,
                            'horainicialplantao' => $hrinicialplantaoM,
                            'horafinalplantao' => '19:00:00',
                            'duracao' => $duracaoM,
                            'tipo_escala' => $tipo
                        );
                        $success = $this->escala_model->insert($insert_dataM);
                        //var_dump($insert_dataM); exit; 
                    }
                    /*
                    $hrinicialplantaoT = $horainicialT->format('H:i:s');
                    $hrfinalplantaoT = $horafinalT->format('H:i:s');
                    $dtinicialplantaoT = $i->format('Y-m-d');
                    $dtfinalplantaoT = $dtinicialplantaoT;
                    $duracaoT = abs((int)$hrinicialplantaoT - (int)$hrfinalplantaoT);
                    for ($j=1; $j <= $diaria; $j++) { 
                        $insert_dataT = array(
                            'setor_id' => $additional_dataT['setor_id'],
                            'dataplantao' => $dtinicialplantaoT,
                            'datafinalplantao' => $dtfinalplantaoT,
                            'horainicialplantao' => $hrinicialplantaoT,
                            'horafinalplantao' => $hrfinalplantaoT,
                            'duracao' => $duracaoT,
                            'tipo_escala' => $tipo
                        );
                        $success = $this->escala_model->insert($insert_dataT);
                        //var_dump($insert_dataT); exit;
                    }*/
                };
            }
            //var_dump($success); exit;
            //var_dump($success1); exit;
            //var_dump($success2); exit;
            //var_dump($success3); exit;
            if ($success) {
                $this->session->set_flashdata('message', 'Escala criada com sucesso.');
                redirect('admin/escalas/create', 'refresh');
            } else {
                $this->session->set_flashdata('message', 'Houve um erro ao criar a escala. Por favor, tente novamente.');
                redirect('admin/escalas/create', 'refresh');
            }
        } else {
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

            $unidadeshospitalares = $this->_get_unidadeshospitalares();
            $tipos = array(
                '1' => 'Plantonista',
                '2' => 'Prescritor',
                '3' => 'Diarista'
            );

            $this->data['tipos'] = array(
                'name'  => 'tipos',
                'id'    => 'tipos',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('tipos'),
                'options' => $tipos,
            );

            $this->data['datainicialplantao'] = array(
                'name'  => 'datainicialplantao',
                'id'    => 'datainicialplantao',
                'type'  => 'date',
                'class' => 'form-control',
                'min' => $this->data['data_minima'],
                'max' => $this->data['data_maxima'],
                'value' => date('Y-m-01', strtotime('+1 month')),
            );
            $this->data['datafinalplantao'] = array(
                'name'  => 'datafinalplantao',
                'id'    => 'datafinalplantao',
                'type'  => 'date',
                'class' => 'form-control',
                'min' => $this->data['data_minima'],
                'max' => $this->data['data_maxima'],
                'value' => date('Y-m-t', strtotime('+1 month')),
            );
            $this->data['horainicialplantao'] = array(
                'name'  => 'horainicialplantao',
                'id'    => 'horainicialplantao',
                'type'  => 'time',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('horainicialplantao'),
            );

            
            $this->data['diaria'] = array(
                'name'  => 'diaria',
                'id'    => 'diaria',
                'type'  => 'number',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('diaria'),
            );
            $this->data['manha'] = array(
                'name'  => 'manha',
                'id'    => 'manha',
                'type'  => 'number',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('manha'),
            );
            $this->data['tarde'] = array(
                'name'  => 'tarde',
                'id'    => 'tarde',
                'type'  => 'number',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('tarde'),
            );
            $this->data['noite'] = array(
                'name'  => 'noite',
                'id'    => 'noite',
                'type'  => 'number',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('noite'),
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

    public function createextra()
    {
        /* Breadcrumbs */
        $this->breadcrumbs->unshift(2, lang('menu_escalas_create'), 'admin/escalas/createextra');
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        /* Variables */
        $this->data['data_minima'] = date('Y-m-d', strtotime(date('Y-m-d') . ' - 60 days')); // deixar somente data atual date('Y-m-d')
        $this->data['data_maxima'] = date('Y-m-d', strtotime(date('Y-m-d') . ' + 90 days'));

        /* Validate form input */
        $this->form_validation->set_rules('unidadehospitalar_id', 'lang:escalas_unidadehospitalar', 'required');
        $this->form_validation->set_rules('setor_id', 'lang:escalas_setor', 'required');
        $this->form_validation->set_rules('datainicialplantao', 'lang:escalas_datainicialplantao', 'required');
        $this->form_validation->set_rules('datafinalplantao', 'lang:escalas_datafinalplantao', 'required');
        $this->form_validation->set_rules('quantidade', 'Quantidade', 'required');


        if ($this->form_validation->run() == true) {
            $unidadehospitalar_id = $this->input->post('unidadehospitalar_id');
            $setor_id = $this->input->post('setor_id');
            $datainicialplantao = $this->input->post('datainicialplantao');
            $datafinalplantao = $this->input->post('datafinalplantao');
            $quantidade = $this->input->post('quantidade');
            
            $tipo = $this->input->post('tipos');
            $turno = $this->input->post('turno_id');
            //$active = $this->input->post('active');
            $horainicialplantao = '07:00:00';
            $horafinalplantao = '13:00:00';
            if ($turno == 1)
            {
                $horainicialplantao = '07:00:00';
                $horafinalplantao = '13:00:00';
            } else if ($turno == 2)
            {
                $horainicialplantao = '13:00:00';
                $horafinalplantao = '19:00:00';
            } else {
                $horainicialplantao = '19:00:00';
                $horafinalplantao = '07:00:00';
            }

            $additional_data = array(
                'unidadehospitalar_id' => $unidadehospitalar_id,
                'setor_id' => $setor_id ,
                'datainicialplantao' => $datainicialplantao,
                'datafinalplantao' => $datafinalplantao,
                'horainicialplantao' => $horainicialplantao,
                'horafinalplantao' => $horafinalplantao,
                'tipo_escala' => $tipo,
                'tipo_plantao' => 1,
                'extra' => 1
            );

            $datainicial = new DateTime($datainicialplantao);
            $datafinal = new DateTime($datafinalplantao);
            if ($datainicial > $datafinal) {
                $this->session->set_flashdata('message', 'A data final do plantão deve ser maior ou igual &agrave; data inicial.');
                redirect('admin/escalas/createextra', 'refresh');
            }

            if ($horainicialplantao == $horafinalplantao) {
                $this->session->set_flashdata('message', 'A hora inicial do plantão não pode ser igual &agrave; hora final.');
                redirect('admin/escalas/createextra', 'refresh');
            }
        }

        // Realizar o insert no model
        if ($this->form_validation->run() == true) {
            $success = false;

            $datainicial = new DateTime($additional_data['datainicialplantao']);
            $datafinal = new DateTime($additional_data['datafinalplantao']);

            // Testar se já chegou ao limite máximo de profissionais pro turno selecionado

            // Loop para inserir no período
            for ($i = $datainicial; $i <= $datafinal; $i->modify('+1 day')) {
                $hrinicialplantao = $additional_data['horainicialplantao'];
                $hrfinalplantao = $additional_data['horafinalplantao'];
                $dtinicialplantao = $i->format("Y-m-d");
                $dtfinalplantao = $dtinicialplantao;
                $duracao = abs((int)$hrinicialplantao - (int)$hrfinalplantao);
                if ((int)$hrinicialplantao > (int)$hrfinalplantao) {
                    $dtfinalplantao = date('Y-m-d', strtotime($dtinicialplantao . ' +1 day'));
                }

                for ($j=1; $j <= $quantidade; $j++) { 
                $insert_data = array(
                    'setor_id' => $additional_data['setor_id'],
                    'dataplantao' => $dtinicialplantao,
                    'datafinalplantao' => $dtfinalplantao,
                    'horainicialplantao' => $hrinicialplantao,
                    'horafinalplantao' => $hrfinalplantao,
                    'duracao' => $duracao,
                    'tipo_escala' => $tipo,
                    'tipo_plantao' => 1,
                    'extra' => 1
                );
                $success = $this->escala_model->insert($insert_data);
            }
                
            }

            if ($success) {
                $this->session->set_flashdata('message', 'Escala criada com sucesso.');
                redirect('admin/escalas/createextra', 'refresh');
            } else {
                $this->session->set_flashdata('message', 'Houve um erro ao criar a escala. Por favor, tente novamente.');
                redirect('admin/escalas/createextra', 'refresh');
            }
        } else {
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

            $unidadeshospitalares = $this->_get_unidadeshospitalares();
            $tipos = array(
                '1' => 'Plantonista',
                '2' => 'Prescritor',
                '3' => 'Diarista'
            );

            $turnos = array(
                '1' => 'Manhã',
                '2' => 'Tarde',
                '3' => 'Noite',
            );

            $this->data['tipos'] = array(
                'name'  => 'tipos',
                'id'    => 'tipos',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('tipos'),
                'options' => $tipos,
            );

            $this->data['turno_id'] = array(
                'name'  => 'turno_id',
                'id'    => 'turno_id',
                'type'  => 'select',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('turno_id'),
                'options' => $turnos,
            );

            $this->data['datainicialplantao'] = array(
                'name'  => 'datainicialplantao',
                'id'    => 'datainicialplantao',
                'type'  => 'date',
                'class' => 'form-control',
                'min' => $this->data['data_minima'],
                'max' => $this->data['data_maxima'],
                'value' => date('Y-m-01', strtotime('+1 month')),
            );
            $this->data['datafinalplantao'] = array(
                'name'  => 'datafinalplantao',
                'id'    => 'datafinalplantao',
                'type'  => 'date',
                'class' => 'form-control',
                'min' => $this->data['data_minima'],
                'max' => $this->data['data_maxima'],
                'value' => date('Y-m-t', strtotime('+1 month')),
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
            $this->template->admin_render('admin/escalas/createextra', $this->data);
        }
    }

    public function createfixed()
    {
        /* Breadcrumbs */
        $this->breadcrumbs->unshift(2, lang('menu_escalas_create'), 'admin/escalas/create');
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        /* Variables */
        $this->data['data_minima'] = date('Y-m-d', strtotime(date('Y-m-d') . ' - 365 days'));
        $this->data['data_maxima'] = date('Y-m-d', strtotime(date('Y-m-d') . ' + 90 days'));

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
            $tipo = $this->input->post('tipos');
            //$active = $this->input->post('active');

            $additional_data = array(
                'unidadehospitalar_id' => $this->input->post('unidadehospitalar_id'),
                'setor_id' => $this->input->post('setor_id'),
                'datainicialplantao' => $this->input->post('datainicialplantao'),
                'datafinalplantao' => $this->input->post('datafinalplantao'),
                'tipo_escala' => $tipo
            );
        }

        // Realizar o insert no model
        if ($this->form_validation->run() == true) {
            $success = false;

            // Apagar escalas do período no mesmo setor e horários
            $where = array(
                'setor_id' => $setor_id,
                'dataplantao >=' => $datainicialplantao,
                'dataplantao <=' => $datafinalplantao,
                'tipo_escala' => $tipo
            );
            $this->escala_model->delete($where);

            $datainicial = new DateTime($datainicialplantao);
            $datafinal = new DateTime($datafinalplantao);
            $dias = $datainicial->diff($datafinal)->format('%a') + 1;
            $setor = $this->setor_model->get_by_id($setor_id);
            $limite = $setor->maximoprofissionais * 3; // * 3 turnos
            // Estudar remover o limite, copiando tudo do dia
            // Caso o máximo de profissionais cadastrados seja zero ou nulo, informar e não realizar a operação

            if ($datafinal >= $datainicial) {
                $success = array();
                for ($data = $datainicial; $data <= $datafinal; $data->modify('+1 day')) {
                    $escala_referencia = $this->escala_model->get_escala_referencia($setor_id, $data->format('Y-m-d'));
                    foreach ($escala_referencia as $indice => $escala) {
                        $dtfinalplantao = $data->format('Y-m-d');
                        if ((int)$escala->horainicialplantao > (int)$escala->horafinalplantao) {
                            $dtfinalplantao = date('Y-m-d', strtotime($dtfinalplantao . ' +1 day'));
                        }
                        // Testando se é um plantão fixo ou Extra
                        // Plantões fixos são copiados, plantões voláteis são copiados sem profissional definido
                        if ($escala->tipo_plantao == 1) {
                            $escala->profissional_id = 0;
                        }

                        $insert_data = array(
                            'setor_id' => $setor_id,
                            'dataplantao' => $data->format('Y-m-d'),
                            'datafinalplantao' => $dtfinalplantao,
                            'horainicialplantao' => $escala->horainicialplantao,
                            'horafinalplantao' => $escala->horafinalplantao,
                            'duracao' => $escala->duracao,
                            'profissional_id' => $escala->profissional_id,
                            'tipo_plantao' => 0,
                            'extra' => 0,
                            'tipo_escala' => $tipo
                        );

                        $insert_id = $this->escala_model->insert($insert_data);

                        if ($insert_id) {
                            array_push($success, true);
                        } else {
                            array_push($success, false);
                        }
                    }
                }
            } else {
                $this->session->set_flashdata('message', 'A data final deve ser maior que a data inicial');
                redirect('admin/escalas/createfixed', 'refresh');
            }

            if (count(array_keys($success, true)) == count($success)) {
                $this->session->set_flashdata('message', 'Escala criada com sucesso.');
            } else {
                echo(count(array_keys($success, false)) . ' falharam.');
                $this->session->set_flashdata('message', 'Houve um erro ao criar a escala. ' . count(array_keys($success, false)) . ' registros falharam. Favor entrar em contato com o administrador.');
            }
            redirect('admin/escalas/createfixed', 'refresh');
        } else {
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

            $unidadeshospitalares = $this->_get_unidadeshospitalares();
            $tipos = array(
                '1' => 'Plantonista',
                '2' => 'Prescritor',
                '3' => 'Diarista'
            );

            $this->data['tipos'] = array(
                'name'  => 'tipos',
                'id'    => 'tipos',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('tipos'),
                'options' => $tipos,
            );

            $this->data['datainicialplantao'] = array(
                'name'  => 'datainicialplantao',
                'id'    => 'datainicialplantao',
                'type'  => 'date',
                'class' => 'form-control',
                'min' => $this->data['data_minima'],
                'max' => $this->data['data_maxima'],
                'value' => date('Y-m-01', strtotime('+1 month')),
            );
            $this->data['datafinalplantao'] = array(
                'name'  => 'datafinalplantao',
                'id'    => 'datafinalplantao',
                'type'  => 'date',
                'class' => 'form-control',
                'min' => $this->data['data_minima'],
                'max' => $this->data['data_maxima'],
                'value' => date('Y-m-t', strtotime('+1 month')),
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

    public function deletar_plantao(){
        $sucess = false;

        $id = $this->input->post('escala');
        if (!$this->input->is_ajax_request()) {
            exit("Nenhum acesso de script direto permitido!");
        } else {
            if ($this->escala_model->delete(['id' => $id])){
                $sucess = true;
            }
        }
        echo json_encode(['sucess' => $sucess]);

    }

    public function calendario()
    {
        $setor_id = 2;
        $data_inicial = '2020-10-21';
        $data_final = '2020-12-20';
        $plantoes = $this->escala_model->get_escalas_consolidadas_cal($setor_id, $data_inicial, $data_final);

        $this->load->library('calendar');
        $this->calendar->init($data_inicial, $data_final, $plantoes);

        $this->data["calendario"] = $this->calendar->generate();

        /* Load Template */
        $this->template->admin_render('admin/escalas/index', $this->data);
    }

    public function edit($id)
    {
        $id = (int) $id;

        if (!$this->ion_auth->logged_in() or !$this->ion_auth->in_group($this->_permitted_groups)) {
            redirect('auth', 'refresh');
        }

        /* Breadcrumbs */
        $this->breadcrumbs->unshift(2, lang('menu_escalas_edit'), 'admin/escalas/edit');
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        /* Load Data */
        $escala = $this->escala_model->get_by_id($id);
        $escala->profissional = $this->profissional_model->get_by_id($escala->profissional_id);
        $escala->setor = $this->setor_model->get_by_id($escala->setor_id);
        $escala->unidadehospitalar = $this->unidadehospitalar_model->get_by_id($escala->setor->unidadehospitalar_id);

        /* Validate form input */
        $this->form_validation->set_rules('profissional_id', 'lang:escalas_profissional', 'required');

        if (isset($_POST) && !empty($_POST)) {
            if ($this->_valid_csrf_nonce() === false or $id != $this->input->post('id')) {
                show_error($this->lang->line('error_csrf'));
            }

            if ($this->form_validation->run() == true) {
                $profissional_id = $this->input->post('profissional_id');
                $escalado = $this->escala_model->get_profissional_escalado($escala->dataplantao, $escala->horainicialplantao, $profissional_id);

                $data = array(
                    'profissional_id' => $this->input->post('profissional_id'),
                );

                //if ($escalado < 1) {
                    $this->escala_model->update($escala->id, $data);
                    $this->session->set_flashdata('message', 'Escala atualizada com sucesso.');
                    if ($this->ion_auth->is_admin()) {
                        redirect('admin/escalas', 'refresh');
                    } else {
                        redirect('admin/escalas', 'refresh');
                    }
                //} else {
                    //$this->session->set_flashdata('message', 'Profissional ja Possui escala neste periodo.');
                    //if ($this->ion_auth->is_admin()) {
                        //redirect('admin/escalas', 'refresh');
                    //} else {
                        //redirect('admin/escalas', 'refresh');
                    //}
                //}
            }
        }

        // display the edit user form
        $this->data['csrf'] = $this->_get_csrf_nonce();

        // set the flash data error message if there is one
        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

        // pass the escala to the view
        $this->data['escala'] = $escala;
        $this->data['escala']->setor = $escala->setor;
        $this->data['escala']->unidadehospitalar = $escala->unidadehospitalar;
        $profissionais = $this->_get_profissionais($escala->setor_id);
        //$setores = $this-> _get_setores(1);

        $this->data['dataplantao'] = array(
            'name'  => 'dataplantao',
            'id'    => 'dataplantao',
            'type'  => 'date',
            'class' => 'form-control',
            'readonly' => 'readonly',
            'value' => $this->form_validation->set_value('dataplantao', $escala->dataplantao)
        );
        $this->data['horainicialplantao'] = array(
            'name'  => 'horainicialplantao',
            'id'    => 'horainicialplantao',
            'type'  => 'time',
            'class' => 'form-control',
            'readonly' => 'readonly',
            'value' => $this->form_validation->set_value('horainicialplantao', $escala->horainicialplantao)
        );
        $this->data['horafinalplantao'] = array(
            'name'  => 'horafinalplantao',
            'id'    => 'horafinalplantao',
            'type'  => 'time',
            'class' => 'form-control',
            'readonly' => 'readonly',
            'value' => $this->form_validation->set_value('horafinalplantao', $escala->horafinalplantao)
        );
        $this->data['profissional_id'] = array(
            'name'  => 'profissional_id',
            'id'    => 'profissional_id',
            'type'  => 'select',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('profissional_id'),
            'selected' => $escala->profissional_id,
            'options' => $profissionais,
        );

        /*$this->data['setor_id'] = array(
            'name'  => 'setor_id',
            'id'    => 'setor_id',
            'type'  => 'select',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('setor_id'),
            'selected' => $escala->setor_id,
            'options' => $setores,
        );*/
        /*
        $this->data['active'] = array(
            'name'  => 'active',
            'id'    => 'active',
            'type'  => 'checkbox',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('active', $escala->active)
        );
        */

        //exit;

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

    public function checkExists($datainicial, $datafinal, $setor_id, $hora_inicial, $hora_final)
    {
        /* Breadcrumbs */
        $this->breadcrumbs->unshift(2, lang('menu_escalas'), 'admin/escalas/view');
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        /* Data */
        $id = (int) $id;

        $this->data['escala'] = $this->escala_model->get_by_id($id);
    }

    public function _get_csrf_nonce()
    {
        $this->load->helper('string');
        $key = random_string('alnum', 8);
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

    public function _get_setores_profissional($profissional_id)
    {
        $setores_por_unidade = $this->setor_model->get_setores_por_profissional($profissional_id);

        $setores = array(
            '' => 'Todos',
        );
        foreach ($setores_por_unidade as $setor) {
            $setores[$setor->id] = $setor->nome;
        }

        return $setores;
    }

    public function _get_profissionais_por_unidade_hospitalar($unidadehospitalar_id)
    {
        $profissionais_por_unidade_hospitalar = $this->profissional_model->get_profissionais_por_unidade_hospitalar($unidadehospitalar_id);

        $profissionais = array(
            '' => 'Selecione um profissional',
        );
        foreach ($profissionais_por_unidade_hospitalar as $profissional) {
            $profissionais[$profissional->id] = $profissional->nome;
        }

        return $profissionais;
    }

    public function _get_profissionais_por_unidade_hospitalar_frequencia($unidadehospitalar_id)
    {
        $profissionais_por_unidade_hospitalar = $this->profissional_model->get_profissionais_por_unidade_hospitalar($unidadehospitalar_id);

        $profissionais = array(
            '' => 'Todos os Profissionais',
        );
        foreach ($profissionais_por_unidade_hospitalar as $profissional) {
            $profissionais[$profissional->id] = $profissional->nome;
        }

        return $profissionais;
    }

    public function _get_frequencias($unidadehospitalar_id, $setor_id, $datainicial, $datafinal, $profissional_id)
    {
        $profissionais_por_unidade_hospitalar = $this->escala_model->get_frequencias_export_txt($unidadehospitalar_id, $setor_id, $datainicial, $datafinal, $profissional_id);
        //var_dump($profissionais_por_unidade_hospitalar); exit;
        $profissionais = array();
        foreach ($profissionais_por_unidade_hospitalar as $profissional) {
            $profissionais[$profissional->id] = $profissional->nome;
        }
        //Anderson
        return $profissionais;
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

    public function get_profissionais($setor_id)
    {
        $profissionais = $this->profissional_model->get_profissionais_por_setor($setor_id);

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

        $setores = $this->setor_model->get_where(['unidadehospitalar_id' => $id], null, 'nome');

        echo json_encode($setores);
        exit;
    }

    public function novo_escala()
    {
        $profissional_id = $this->input->post('profissional', 0);
        $escala_id = $this->input->post('escala', 0);
        $data_ini = $this->input->post('data_ini', 0);
        $hora_ini = $this->input->post('hora_ini', 0);

        $vinculo_atribuir = $this->input->post('vinculos_atribuir');

        $escala = $this->escala_model->get_by_id($escala_id);
        $profissional_ant = $escala->profissional_id;

        $this->load->model('cemerge/passagemtroca_model');

        $sessoes = $this->passagemtroca_model->get_where(['escala_id' => $escala_id, 'statuspassagem' => 1], null);
        $sucess = false;
        $escalado = $this->escala_model->get_profissional_escalado($data_ini, $hora_ini, $profissional_id);
        $vinculo = $this->profissional_model->get_vinculo_por_profissional($profissional_id);
        //var_dump($escalado); exit;

        if (empty($sessoes) && $escalado < 1){
            try {
                $this->escala_model->update($escala_id, ['profissional_id' => $profissional_id]);
                $sucess = true;
            } catch (Exception $ex) {
                echo(json_encode($ex));
            }
        }

        //echo json_encode($profissional);
        //echo json_encode($escala);
        echo json_encode(['sucess' => $sucess, 'profissional' => $profissional_id, 'vinculo_atribuir' => $vinculo_atribuir, 'vinculo'=> $vinculo[0]->vinculo_id, 'profissional_ant'=> $profissional_ant]);
        exit;
    }

    public function troca_vinculo_escala(){

        $escala_id = $this->input->post('escala');
        $vinculo_id = $this->input->post('vinculo_id');
        $success = 'errado';
        if($this->escala_model->update($escala_id, ['vinculo_id' => $vinculo_id])){
            $success = 'certo';
        };

        return $success;
    }

    public function troca_tipo_plantao(){

        $escala_id = $this->input->post('escala');
        $tipo = $this->input->post('tipo');
        $success = 'errado';
        if($this->escala_model->update($escala_id, ['tipo_plantao' => $tipo])){
            $success = 'certo';
        };

        echo json_encode(['sucesso' => $success]);
    }

    private function _get_vinculos_atribuir()
    {

        $vinculos = array(
            '1' => 'CEMERGE',
            '2' => 'SESA',
        );

        return $vinculos;
    }

    public function alterartipoplantao()
    {
        $tipo_plantao = $this->input->post('tipo_plantao', 0);
        $escala = $this->input->post('escala', 0);

        try {
            $this->escala_model->update($escala, ['tipo_plantao' => $tipo_plantao]);
        } catch (Exception $ex) {
            echo(json_encode($ex));
        }

        //echo json_encode($profissional);
        //echo json_encode($escala);
        exit;
    }

    public function _get_tipos_visualizacao()
    {
        $tipos_visualizacao = array(
            '0' => 'Lista',
            '1' => 'Calendário',
        );
        // '2' => 'Grade de Frequência',

        return $tipos_visualizacao;
    }

    /**
     * Busca os plantões do setor para compor o calendário
     */
    public function trocasepassagensdosetor()
    {
        $mes = (int)$this->uri->segment(5, 0);
        $setor = (int)$this->uri->segment(7, 0);

        $escala = array();

        if ($mes != 0 and $setor != 0) {
            $escala = $this->escala_model->get_trocas_passagens_setor_calendario(
                $mes, $setor, $this->mobile_detect->isMobile()
            );
        }

        echo(json_encode($escala));
        exit;
    }

    /**
     * Busca os plantões do setor para compor o calendário
     */
    public function escalaoriginaldosetor()
    {
        $mes = (int)$this->uri->segment(5, 0);
        $setor = (int)$this->uri->segment(7, 0);

        $escala = array();

        if ($mes != 0 and $setor != 0) {
            $escala = $this->escala_model->get_escala_original_setor_calendario(
                $mes, $setor, $this->mobile_detect->isMobile()
            );
        }

        echo(json_encode($escala));
        exit;
    }

    /**
     * Busca os plantões do setor para compor o calendário
     */
    public function escalaconsolidadadosetor()
    {
        $mes = (int)$this->uri->segment(5, 0);
        $setor = (int)$this->uri->segment(7, 0);

        $escala = array();

        if ($mes != 0 and $setor != 0) {
            $escala = $this->escala_model->get_escala_consolidada_setor_calendario(
                $mes, $setor, $this->mobile_detect->isMobile()
            );
        }

        echo(json_encode($escala));
        exit;
    }
}
