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

       $this->load->model('cemerge/especializacao_model');
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

        $this->template->admin_render('admin/especializacoes/index', $this->data);
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

        /* Breadcrumbs */
        $this->breadcrumbs->unshift(2, lang('menu_categorias_create'), 'admin/especializacoes/cadastro/');
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        /* Variables */
        $categoria_nome = $this->input->post('categoria_nome');

        $this->form_validation->set_rules('categoria_nome', 'lang:categorias_descricao', 'required');


        if ($this->form_validation->run() == true) {
            $justificativa_id = $this->Categoria_model->insert($this->data);
            if ($justificativa_id) {
                $this->session->set_flashdata('message', 'Justificativa inserida com sucesso.');
                redirect('admin/justificativas', 'refresh');
            } else {
                $this->session->set_flashdata('message', 'Houve um erro ao inserir a justificativa. Tente novamente.');
                redirect('admin/justificativa/create', 'refresh');
            }
        } else {
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

            $this->data['categoria_nome'] = $categoria_nome;


            /* Load Template */
            $this->template->admin_render('admin/especializacoes/create', $this->data);
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

}