<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Especializacoes extends Admin_Controller
{
    private $_permitted_groups = array('admin', 'sac', 'profissionais');
    private $_admin_groups = array('admin', 'sac');
    function __construct()
    {
        parent::__construct($this->_permitted_groups);

       /* Load :: Common */

       $this->load->model('cemerge/Especializacao_model');
       $this->lang->load('admin/especializacoes');

       /* Title Page */
       
       $this->page_title->push(lang('menu_epecializacoes'));
       $this->data['pagetitle'] = $this->page_title->show();

       /* Breadcrumbs :: Common */
        $this->breadcrumbs->unshift(1, lang('menu_especializacoes'), 'admin/especializacoes');
    }

    public function index()
    {
        
        if (!$this->ion_auth->logged_in()) {
            $this->session->set_flashdata('message', 'Você deve estar autenticado para listar as Especializações.');
            redirect('auth/login', 'refresh');
        }
        if (!$this->ion_auth->in_group($this->_permitted_groups)) {
            $this->session->set_flashdata('message', 'O acesso &agrave; este recurso não é permitido ao seu perfil de usuário.');
            redirect('auth/login', 'refresh');
        }
        
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        $categoria_select = $this->_get_categorias();

        $this->data['categoria_select'] = array(
            'name'  => 'categoria_select',
            'id'    => 'categoria_select',
            'type'  => 'select',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('categoria_select'),
            'options' => $categoria_select,
        );

        $this->template->admin_render('admin/especializacoes/index', $this->data);
    }

    private function _get_categorias()
    {
        
        $this->load->model('cemerge/Categoria_model');

        $categorias = $this->Categoria_model->get_all();

        $categoria_select = array(
            '' => 'Selecione uma categoria',
        );
        foreach ($categorias as $categoria) {
            $categoria_select[$categoria->categoria_id] = $categoria->categoria_nome;
        }
        //var_dump($categorias); exit;
        return $categoria_select;
    }
    
    public function cadastrar_categoria(){
        
        if (!$this->ion_auth->logged_in()) {
            $this->session->set_flashdata('message', 'Você deve estar autenticado para criar uma categoria.');
            redirect('auth/login', 'refresh');
        }
        if (!$this->ion_auth->in_group($this->_permitted_groups)) {
            $this->session->set_flashdata('message', 'O acesso &agrave; este recurso não é permitido ao seu perfil de usuário.');
            redirect('admin/dashboard', 'refresh');
        }
        $this->load->model("cemerge/Categoria_model");
        /* Breadcrumbs */
        $this->breadcrumbs->unshift(2, lang('menu_categorias_create'), 'admin/especializacoes/');
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        /* Variables */
        $categoria_nome = $this->input->post('categoria_nome');

        $this->form_validation->set_rules('categoria_nome', 'lang:categoria_nome', 'required');

        if ($this->form_validation->run() == true) {
            $categoria_id = $this->Categoria_model->insert(['categoria_nome'=>$categoria_nome]);
            if ($categoria_id) {
                $this->session->set_flashdata('message', 'Categoria inserida com sucesso.');
                redirect('admin/especializacoes/', 'refresh');
            } else {
                $this->session->set_flashdata('message', 'Houve um erro ao inserir a Categoria. Tente novamente.');
                redirect('admin/especializacoes/', 'refresh');
            }
        } else {
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
            $this->data['categoria_nome'] = $categoria_nome;
            redirect('admin/especializacoes/', 'refresh');
        }
    }

    public function ajax_listar_categorias() {

    if (!$this->input->is_ajax_request()) {
        exit("Nenhum acesso de script direto permitido!");
    }
        
    $this->load->model("cemerge/Categoria_model");
    $categorias = $this->Categoria_model->get_datatable();

    $data = array();
    foreach ($categorias as $categoria) {

        $row = array();
        $row[] = $categoria->categoria_id;
        $row[] = $categoria->categoria_nome;

        $row[] = '<div style="display: inline-block;">
                    <button class="btn btn-primary btn-edit-categoria" 
                        categoria_id="'.$categoria->categoria_id.'">
                        <i class="fa fa-edit"></i>
                    </button>
                    <button class="btn btn-danger btn-del-categoria" 
                        categoria_id="'.$categoria->categoria_id.'">
                        <i class="fa fa-times"></i>
                    </button>
                </div>';

        $data[] = $row;

    }

    $json = array(
        "draw" => $this->input->post("draw"),
        "recordsTotal" => $this->Categoria_model->records_total(),
        "recordsFiltered" => $this->Categoria_model->records_filtered(),
        "data" => $data,
    );
    echo json_encode($json);
    exit;
}

public function cadastrar_especializacao(){
        
    if (!$this->ion_auth->logged_in()) {
        $this->session->set_flashdata('message', 'Você deve estar autenticado para criar uma especialização.');
        redirect('auth/login', 'refresh');
    }
    if (!$this->ion_auth->in_group($this->_permitted_groups)) {
        $this->session->set_flashdata('message', 'O acesso &agrave; este recurso não é permitido ao seu perfil de usuário.');
        redirect('admin/dashboard', 'refresh');
    }
    /* Breadcrumbs */
    $this->breadcrumbs->unshift(2, lang('menu_especializacoes_create'), 'admin/especializacoes/');
    $this->data['breadcrumb'] = $this->breadcrumbs->show();

    /* Variables */
    $especializacao_nome = $this->input->post('especializacao_nome');
    $especializacao_categoria = $this->input->post('categoria_select');

    $this->form_validation->set_rules('especializacao_nome', 'lang:especializacao_nome', 'required');
    $this->form_validation->set_rules('categoria_select', 'lang:especializacao_categoria', 'required');

    if ($this->form_validation->run() == true) {
        $especializacao_id = $this->Especializacao_model->insert(['especializacao_nome'=>$especializacao_nome, 'especializacao_categoria'=>$especializacao_categoria]);
        if ($especializacao_id) {
            $this->session->set_flashdata('message', 'Especialização inserida com sucesso.');
            redirect('admin/especializacoes/', 'refresh');
        } else {
            $this->session->set_flashdata('message', 'Houve um erro ao inserir a especialização. Tente novamente.');
            redirect('admin/especializacoes/', 'refresh');
        }
    } else {
        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
        $this->data['especializacao_nome'] = $especializacao_nome;
        redirect('admin/especializacoes/', 'refresh');
    } 
        exit;
}

    public function ajax_listar_especializacoes() {

    if (!$this->input->is_ajax_request()) {
        exit("Nenhum acesso de script direto permitido!");
    }
        
    $especializacoes = $this->Especializacao_model->get_datatable();

    $data = array();
    foreach ($especializacoes as $especializacao) {

        $row = array();
        $row[] = $especializacao->especializacao_id;
        $row[] = $especializacao->especializacao_nome;

        $row[] = '<div style="display: inline-block;">
                    <button class="btn btn-primary btn-edit-especializacao" 
                        especializacao_id="'.$especializacao->especializacao_id.'">
                        <i class="fa fa-edit"></i>
                    </button>
                    <button class="btn btn-danger btn-del-especializacao" 
                        especializacao_id="'.$especializacao->especializacao_id.'">
                        <i class="fa fa-times"></i>
                    </button>
                </div>';

        $data[] = $row;

    }

    $json = array(
        "draw" => $this->input->post("draw"),
        "recordsTotal" => $this->Especializacao_model->records_total(),
        "recordsFiltered" => $this->Especializacao_model->records_filtered(),
        "data" => $data,
    );
    echo json_encode($json);
    exit;
    }

    public function deletar_categoria() {

        if (!$this->input->is_ajax_request()) {
            exit("Nenhum acesso de script direto permitido!");
        }

    	$json = array();
		$json["status"] = 1;

		$this->load->model("Categoria_model");
		$categoria_id = $this->input->post("categoria_id");
		$this->Categoria_model->delete(['categoria_id' => $categoria_id]);

        echo json_encode($json);
	}

	public function deletar_especializacao() {

		if (!$this->input->is_ajax_request()) {
			exit("Nenhum acesso de script direto permitido!");
        }
        
		$json = array();
        $json["status"] = 1;
        
		$especializacao_id = $this->input->post("especializacao_id");
		$this->Especializacao_model->delete(['especializacao_id' => $especializacao_id]);

        echo json_encode($json);
	}

}