<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Processamento extends Admin_Controller {

    private $_permitted_groups = array('admin', 'sac');

    public function __construct()
    {
        parent::__construct($this->_permitted_groups);

        /* Load :: Common */
        $this->lang->load('admin/processamento');

        /* Title Page */
        $this->page_title->push(lang('menu_processamento'));
        $this->data['pagetitle'] = $this->page_title->show();

        /* Breadcrumbs :: Common */
        $this->breadcrumbs->unshift(1, lang('menu_processamento'), 'admin/processamento');
    }

    public function index()
    {
        if (!$this->ion_auth->logged_in()) {
            $this->session->set_flashdata('message', 'Você deve estar autenticado para acessar esta função.');
            redirect('auth/login', 'refresh');
        }
        if (!$this->ion_auth->in_group($this->_permitted_groups)) {
            $this->session->set_flashdata('message', 'O acesso &agrave; este recurso não é permitido ao seu perfil de usuário.');
            redirect('auth/login', 'refresh');
        }

        /* Breadcrumbs */
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        /* Validate form input */
        $this->form_validation->set_rules('unidadehospitalar_id', 'lang:processamento_unidadehospitalar', 'required');
        $this->form_validation->set_rules('data_escala', 'lang:processamento_data_escala', 'required');
        $this->form_validation->set_rules('processo', 'lang:processamento_processo', 'required');

        if ($this->form_validation->run() == true) {
            $unidadehospitalar_id = $this->input->post('unidadehospitalar_id');
            $data_escala = $this->input->post('data_escala');
            $processo = $this->input->post('processo');
            
            switch ($processo) {
            case 0:
                $this->processar_escala_por_demanda($unidadehospitalar_id, 0, $data_escala);
                break;
            case 1:
                $this->processar_escala_prescricao_por_demanda($unidadehospitalar_id, $data_escala);
                break;
            case 2:
                $recriar = 0;  // 0 = Não apagar os registros do dia
                $this->processar_plantoes_mt_mesmo_medico_por_demanda($unidadehospitalar_id, $data_escala, $recriar);
                break;
            case 3:
                $recriar = 1;  // 1 = apagar todos os registros do dia e criar novamente
                $this->processar_plantoes_mt_mesmo_medico_por_demanda($unidadehospitalar_id, $data_escala, $recriar);
                break;
            default:
                break;
            }
        } else {
            $unidadehospitalar_id = '';
            $data_escala = date('Y-m-d');
            $processo = '';
        }

        $processos = array(
            '' => 'Selecione um processo a ser executado',
            '0' => 'Processar escalas de plantão na data selecionada',
            '1' => 'Processar escalas de prescrição na data selecionada',
            '2' => 'Processar batidas 13h de plantões MT mesmo setor na data selecionada',
            '3' => 'Processar batidas 13h de plantões MT mesmo setor na data selecionada recriando os valores já processados',
        );

        $unidadeshospitalares = $this->_get_unidadeshospitalares();

        $this->data['unidadehospitalar_id'] = array(
            'name'  => 'unidadehospitalar_id',
            'id'    => 'unidadehospitalar_id',
            'type'  => 'select',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('unidadehospitalar_id', $unidadehospitalar_id),
            'options' => $unidadeshospitalares,
        );
        $this->data['data_escala'] = array(
            'name'  => 'data_escala',
            'id'    => 'data_escala',
            'type'  => 'date',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('data_escala', $data_escala),
        );
        $this->data['processo'] = array(
            'name'  => 'processo',
            'id'    => 'processo',
            'type'  => 'select',
            'class' => 'form-control',
            'selected' => $this->form_validation->set_value('processo', $processo),
            'options' => $processos,
        );

        /* Load Template */
        $this->template->admin_render('admin/processamento/index', $this->data);
    }

    public function processar_escala_por_demanda($unidadehospitalar_id, $setor_id, $data_escala)
    {
        if (!$this->ion_auth->logged_in()) {
            $this->session->set_flashdata('message', 'Você deve estar autenticado para usar esta função.');
            redirect('auth/login', 'refresh');
        }
        if (!$this->ion_auth->in_group($this->_permitted_groups)) {
            $this->session->set_flashdata('message', 'O acesso &agrave; este recurso não é permitido ao seu perfil de usuário.');
            redirect('admin/dashboard', 'refresh');
        }

        $hash = md5("Cmg@2020");
        $url = site_url("admin/batch/processarescala/") . $hash . "/" . $unidadehospitalar_id . "/" . $setor_id . "/" . $data_escala;

        // create curl resource
        $ch = curl_init();

        // set url
        curl_setopt($ch, CURLOPT_URL, $url);

        //return the transfer as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        // $output contains the output string
        $output = curl_exec($ch);
        $info = curl_getinfo($ch);

        if ($info['http_code'] == 200) {
            $message = 'A escala foi processada com sucesso na data selecionada.';
        } else {
            $message = 'Ocorreu um erro ao processar manualmente a escala na data selecionada.';
        }

        // close curl resource to free up system resources
        curl_close($ch);

        $this->session->set_flashdata('message', $message);
        redirect('admin/processamento', 'refresh');
    }

    public function processar_escala_prescricao_por_demanda($unidadehospitalar_id, $data_escala)
    {
        if (!$this->ion_auth->logged_in()) {
            $this->session->set_flashdata('message', 'Você deve estar autenticado para usar esta função.');
            redirect('auth/login', 'refresh');
        }
        if (!$this->ion_auth->in_group($this->_permitted_groups)) {
            $this->session->set_flashdata('message', 'O acesso &agrave; este recurso não é permitido ao seu perfil de usuário.');
            redirect('admin/dashboard', 'refresh');
        }

        $hash = md5("Cmg@2020");
        $url = site_url("admin/batch/processarescalaprescricao/") . $hash . "/" . $unidadehospitalar_id . "/" . $data_escala;

        // create curl resource
        $ch = curl_init();

        // set url
        curl_setopt($ch, CURLOPT_URL, $url);

        //return the transfer as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        // $output contains the output string
        $output = curl_exec($ch);
        $info = curl_getinfo($ch);

        if ($info['http_code'] == 200) {
            $message = 'A escala dos setores de prescrição foi processada com sucesso na data selecionada.';
        } else {
            $message = 'Ocorreu um erro ao processar manualmente a escala dos setores de prescrição na data selecionada.';
        }

        // close curl resource to free up system resources
        curl_close($ch);

        $this->session->set_flashdata('message', $message);
        redirect('admin/processamento', 'refresh');
    }

    public function processar_plantoes_mt_mesmo_medico_por_demanda($unidadehospitalar_id, $data_escala, $recriar)
    {
        if (!$this->ion_auth->logged_in()) {
            $this->session->set_flashdata('message', 'Você deve estar autenticado para usar esta função.');
            redirect('auth/login', 'refresh');
        }
        if (!$this->ion_auth->in_group($this->_permitted_groups)) {
            $this->session->set_flashdata('message', 'O acesso &agrave; este recurso não é permitido ao seu perfil de usuário.');
            redirect('admin/dashboard', 'refresh');
        }

        $hash = md5("Cmg@2020");
        $url = site_url("admin/batch/processarbatidasmtmesmomedico/") . $hash . "/" . $unidadehospitalar_id . "/" . $data_escala . "/" . $recriar;

        // create curl resource
        $ch = curl_init();

        // set url
        curl_setopt($ch, CURLOPT_URL, $url);

        //return the transfer as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        // $output contains the output string
        $output = curl_exec($ch);
        $info = curl_getinfo($ch);

        if ($info['http_code'] == 200) {
            $message = 'As batidas de 13h dos plantões MT do mesmo profissional foram processadas com sucesso na data selecionada.';
        } else {
            $message = 'Ocorreu um erro ao processar manualmente as batidas de 13h dos plantões MT do mesmo profissional na data selecionada.';
        }

        // close curl resource to free up system resources
        curl_close($ch);

        $this->session->set_flashdata('message', $message);
        redirect('admin/processamento', 'refresh');
    }

    private function _get_unidadeshospitalares()
    {
        $this->load->model('cemerge/unidadehospitalar_model');
        $unidades = $this->unidadehospitalar_model->get_all();

        $unidadeshospitalares = array(
            '' => 'Selecione uma Unidade Hospitalar'
        );
        foreach ($unidades as $unidade) {
            $unidadeshospitalares[$unidade->id] = $unidade->razaosocial;
        }

        return $unidadeshospitalares;
    }
}
