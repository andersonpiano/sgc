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
            '' => 'Selecione um Nivel de Formação',
        );
        foreach ($categorias as $categoria) {
            $categoria_select[$categoria->categoria_id] = $categoria->categoria_nome;
        }
        //var_dump($categorias); exit;
        return $categoria_select;
    }

    private function _get_especializacoes()
    {
        
        $this->load->model('cemerge/Especializacao_model');

        $especializacoes = $this->Especializacao_model->get_all();

        $especializacoes_select = array(
            '' => 'Selecione uma especialidade',
        );
        foreach ($especializacoes as $especializacao) {
            $especializacoes_select[$especializacao->especializacao_id] = $especializacao->especializacao_nome;
        }
        //var_dump($categorias); exit;
        return $especializacoes_select;
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

        if (!$this->input->is_ajax_request()) {
			exit("Nenhum acesso de script direto permitido!");
        }
        
        $this->load->model("cemerge/Categoria_model");
        /* Breadcrumbs */
        $this->breadcrumbs->unshift(2, lang('menu_categorias_create'), 'admin/especializacoes/');
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        /* Variables */
            $json = array();
            $json["status"] = 1;
        
        $categoria_nome = $this->input->post('categoria_nome');

        $this->form_validation->set_rules('categoria_nome', 'lang:categoria_nome', 'required');

        if ($this->form_validation->run() == true) {
            $data = $this->input->post();
            if(empty($data['categoria_id'])) {
                $this->Categoria_model->insert(['categoria_nome'=>$categoria_nome, 'categoria_status' => 1]);
            } else {
                $categoria_id = $data["categoria_id"];
                unset($data['categoria_id']);
                $this->Categoria_model->update($categoria_id, ['categoria_nome' => $categoria_nome]);
            }
        } else {
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
            $this->data['categoria_nome'] = $categoria_nome;
        } 
        echo json_encode($json);
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
        $row[] = '<center>'.$categoria->categoria_id.'</center>';
        $row[] = $categoria->categoria_nome;

        $row[] = '<div style="display: inline-block;">
                    <button class="btn btn-primary btn-edit-categoria" 
                        categoria_id='.$categoria->categoria_id.'>
                        <i class="fa fa-edit"></i>
                    </button>
                    <button class="btn btn-danger btn-del-categoria" 
                        categoria_id='.$categoria->categoria_id.'>
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

        $json = array();

        if ($this->form_validation->run() == true) {
            $data = $this->input->post();
            if(empty($data['especializacao_id'])) {
                $this->Especializacao_model->insert(['especializacao_nome'=>$especializacao_nome, 'especializacao_categoria'=>$especializacao_categoria]);
            } else {
                $especializacao_id = $data["especializacao_id"];
                unset($data['categoria_id']);
                $this->Especializacao_model->update($especializacao_id, ['especializacao_nome' => $especializacao_nome, 'especializacao_categoria'=>$especializacao_categoria]);
            }
        } else {
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
            $this->data['especializacao_nome'] = $especializacao_nome;
        } 
            json_encode($json);
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
        $row[] = '<center>'.$especializacao->especializacao_id.'</center>';
        $row[] = $especializacao->especializacao_nome;

        $row[] = '<div style="display: inline-block;" >
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

    public function deletar_categoria($categoria_id) {

        if (!$this->input->is_ajax_request()) {
            exit("Nenhum acesso de script direto permitido!");
        }
            $this->load->model("cemerge/Categoria_model");
            $this->Categoria_model->delete(['categoria_id' => $categoria_id]);
    }

    public function deletar_especializacao($especializacao_id) {

        if (!$this->input->is_ajax_request()) {
            exit("Nenhum acesso de script direto permitido!");
        }
            $especializacao_id = $this->input->post("especializacao_id");
            $this->Especializacao_model->delete(['especializacao_id' => $especializacao_id]);

    }


    public function ajax_get_categoria_data() {

        if (!$this->input->is_ajax_request()) {
            exit("Nenhum acesso de script direto permitido!");
        }
        $json = array();
        $json["status"] = 1;
        $json["input"] = array();

        $this->load->model("cemerge/Categoria_model");
        
        $categoria_id = $this->input->post("categoria_id");
        $data = $this->Categoria_model->get_data($categoria_id)->result_array()[0];
        $json["input"]["categoria_id"] = $data["categoria_id"];
        $json["input"]["categoria_nome"] = $data["categoria_nome"];

        echo json_encode($json);
        exit;
    }

    public function ajax_get_especializacao_data() {

        if (!$this->input->is_ajax_request()) {
            exit("Nenhum acesso de script direto permitido!");
        }
        $json = array();
        $json["status"] = 1;
        $json["input"] = array();

        $this->load->model("cemerge/Especializacao_model");
        
        $especializacao_id = $this->input->post("especializacao_id");
        $data = $this->Especializacao_model->get_data($especializacao_id)->result_array()[0];
        $json["input"]["especializacao_id"] = $data["especializacao_id"];
        $json["input"]["especializacao_nome"] = $data["especializacao_nome"];
        $json["input"]["categoria_select"] =$data["especializacao_categoria"];

        echo json_encode($json);
        exit;
    }

    public function troca_categoria($id){
        if (!$this->input->is_ajax_request()) {
            exit("Nenhum acesso de script direto permitido!");
        }
        $this->load->model("cemerge/Profissional_model");
        $categoria = $this->input->get_post('nivel_especializacao');
        $this->Profissional_model->update($id, ['nivel_especializacao'=>$categoria]);
    }

    public function troca_especializacao($id){
        if (!$this->input->is_ajax_request()) {
            exit("Nenhum acesso de script direto permitido!");
        }
        $this->load->model("cemerge/Profissional_model");

        $especializacao = $this->input->get_post('especializacao');
        $this->Profissional_model->update($id, ['especializacao' => $especializacao]);
    }

    public function ajax_listar_profissionais_cadastro() {

        if (!$this->input->is_ajax_request()) {
            exit("Nenhum acesso de script direto permitido!");
        }
            
        $this->load->model("cemerge/Profissional_model");
        $profissionais = $this->Profissional_model->get_datatable();
    
        $data = array();
        foreach ($profissionais as $profissional) {
    
            $row = array();
            $row[] = '<center>'.$profissional->id.'</center>';
            $row[] = '<center>'.$profissional->nome.'</center>';
            $row[] = '<center>'.$profissional->registro.'</center>';
            $row[] = '<center>'.$profissional->email.'</center>';
    
            $row[] = '<center><div style="display: inline-block;">
                        <button class="btn btn-primary btn-profissional-edit" 
                            id='.$profissional->id.'>
                            <i class="fa fa-edit"></i>
                        </button>
                        <button class="btn btn-success btn-profissional-frequencia" 
                            id='.$profissional->id.'>
                            <i class="fa fa-check-square-o"></i>
                        </button>

                    </div></center>';
    
            $data[] = $row;
    
        }
        $json = array(
            "draw" => $this->input->post("draw"),
            "recordsTotal" => $this->Profissional_model->records_total(),
            "recordsFiltered" => $this->Profissional_model->records_filtered(),
            "data" => $data,
        );
        echo json_encode($json);
        exit;
        }

    public function ajax_listar_profissionais() {

        if (!$this->input->is_ajax_request()) {
            exit("Nenhum acesso de script direto permitido!");
        }
            
        $this->load->model("cemerge/Profissional_model");
        $profissionais = $this->Profissional_model->get_datatable();

        $especializacoes_select = $this->_get_especializacoes();
        $categoria_select = $this->_get_categorias();
    
        foreach ($profissionais as $profissional) {

            $row = array();
            $row[] = '<center>'.$profissional->id.'<center>';
            $row[] = $profissional->nome;
            $row[] = '<center>'.form_dropdown('categoria_select', $categoria_select, $profissional->nivel_especializacao, 'class="form-control" id="categoria_select" profissional_id="'.$profissional->id.'"').'</center>';
    
            //$row[] = '<span class="text-center">'.$this->Especializacao_model->get_especializacao_by_id($profissional->especializacao)->especializacao_nome.'</span>';

            $row[] = '<center>'.form_dropdown('especializacao_select', $especializacoes_select, $profissional->especializacao, 'class="form-control" id="especializacao_select" profissional_id="'.$profissional->id.'"').'</center>';
    
            $data[] = $row;
    
        }
        $json = array(
            "draw" => $this->input->post("draw"),
            "recordsTotal" => $this->Profissional_model->records_total(),
            "recordsFiltered" => $this->Profissional_model->records_filtered(),
            "data" => $data,
        );
        echo json_encode($json);
        exit;
        }
}