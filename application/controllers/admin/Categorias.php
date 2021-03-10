<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Categorias extends Admin_Controller
{
    private $_permitted_groups = array('admin', 'sac', 'profissionais');
    private $_admin_groups = array('admin', 'sac');
    function __construct()
    {
        parent::__construct($this->_admin_groups);

       /* Load :: Common */

       $this->load->model('cemerge/categoria_model');
       $this->lang->load('admin/categorias');

       /* Title Page */
       
       $this->page_title->push(lang('menu_epecializacoes'));
       $this->data['pagetitle'] = $this->page_title->show();

       /* Breadcrumbs :: Common */
        $this->breadcrumbs->unshift(1, lang('menu_categorias'), 'admin/categorias');
    }

    public function index()
    {
        
        if (!$this->ion_auth->logged_in()) {
            $this->session->set_flashdata('message', 'Você deve estar autenticado para listar as Categorias.');
            redirect('auth/login', 'refresh');
        }
        if (!$this->ion_auth->in_group($this->_admin_groups)) {
            $this->session->set_flashdata('message', 'O acesso &agrave; este recurso não é permitido ao seu perfil de usuário.');
            redirect('auth/login', 'refresh');
        }
        
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        $this->template->admin_render('admin/categorias/index', $this->data);
    }
    
    public function cadastro(){
        if (!$this->ion_auth->logged_in()) {
            $this->session->set_flashdata('message', 'Você deve estar autenticado para criar uma categoria.');
            redirect('auth/login', 'refresh');
        }
        if (!$this->ion_auth->in_group($this->_admin_groups)) {
            $this->session->set_flashdata('message', 'O acesso &agrave; este recurso não é permitido ao seu perfil de usuário.');
            redirect('admin/dashboard', 'refresh');
        }

        /* Breadcrumbs */
        $this->breadcrumbs->unshift(2, lang('menu_categorias_create'), 'admin/categorias/cadastro');
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        /* Validate form input */
        $this->form_validation->set_rules('categoria_nome', 'lang:categorias_descricao', 'required');

        if ($this->form_validation->run() == true) {
            $categoria_nome = $this->input->post('categoria_nome');
            $insert_data = array(
                'categoria_nome' => $categoria_nome,
            );
        }

        if ($this->form_validation->run() == true) {
            $categoria_id = $this->categoria_model->insert($insert_data);
            if ($categoria_id) {
                $this->session->sweetalert2('message', 'Categoria inserida com sucesso.');
                redirect('admin/categorias/', 'refresh');
            } else {
                $this->session->sweetalert2('message', 'Houve um erro ao inserir a categoria. Tente novamente.');
                redirect('admin/categorias/', 'refresh');
            }

        } else {
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->swal('message')));
            $this->data['categoria_nome'] = $categoria_nome;
        }

        $this->template->admin_render('admin/categorias/cadastro', $this->data);

    }
}