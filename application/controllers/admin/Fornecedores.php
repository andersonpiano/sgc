<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Fornecedores extends Admin_Controller
{
    private $_permitted_groups = array('admin', 'sac', 'profissionais');
    private $_admin_groups = array('admin', 'sac');
    function __construct()
    {
        parent::__construct($this->_admin_groups);

       /* Load :: Common */

       $this->load->model('cemerge/Fornecedor_model');
       $this->lang->load('admin/fornecedores');

       /* Title Page */
       
       $this->page_title->push(lang('menu_fornecedores'));
       $this->data['pagetitle'] = $this->page_title->show();

       /* Breadcrumbs :: Common */
        $this->breadcrumbs->unshift(1, lang('menu_fornecedores'), 'admin/fornecedores');
    }

    public function index()
    {
        
        if (!$this->ion_auth->logged_in()) {
            $this->session->set_flashdata('message', 'Você deve estar autenticado para listar as fornecedores.');
            redirect('auth/login', 'refresh');
        }
        if (!$this->ion_auth->in_group($this->_admin_groups)) {
            $this->session->set_flashdata('message', 'O acesso &agrave; este recurso não é permitido ao seu perfil de usuário.');
            redirect('auth/login', 'refresh');
        }
        
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        $this->template->admin_render('admin/fornecedores/index', $this->data);
    }
    
    public function cadastro(){
        if (!$this->ion_auth->logged_in()) {
            $this->session->set_flashdata('message', 'Você deve estar autenticado para criar uma fornecedor.');
            redirect('auth/login', 'refresh');
        }
        if (!$this->ion_auth->in_group($this->_admin_groups)) {
            $this->session->set_flashdata('message', 'O acesso &agrave; este recurso não é permitido ao seu perfil de usuário.');
            redirect('admin/dashboard', 'refresh');
        }

        /* Breadcrumbs */
        $this->breadcrumbs->unshift(2, lang('menu_fornecedores_create'), 'admin/fornecedores/cadastro');
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        /* Validate form input */
        $this->form_validation->set_rules('fornecedor_nome', 'lang:fornecedores_descricao', 'required');

        if ($this->form_validation->run() == true) {
            $fornecedor_nome = $this->input->post('fornecedor_nome');
            $insert_data = array(
                'fornecedor_nome' => $fornecedor_nome,
            );
        }

        if ($this->form_validation->run() == true) {
            $fornecedor_id = $this->Fornecedor_model->insert($insert_data);
            if ($fornecedor_id) {
                $this->session->sweetalert2('message', 'fornecedor inserido com sucesso.');
                redirect('admin/fornecedores/', 'refresh');
            } else {
                $this->session->sweetalert2('message', 'Houve um erro ao inserir o fornecedor. Tente novamente.');
                redirect('admin/fornecedores/', 'refresh');
            }

        } else {
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->swal('message')));
            $this->data['fornecedor_nome'] = $fornecedor_nome;
        }

        $this->template->admin_render('admin/fornecedores/cadastro', $this->data);

    }
}