<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class estoque extends Admin_Controller
{
    private $_permitted_groups = array('admin', 'sac', 'profissionais');
    private $_admin_groups = array('admin', 'sac');
    function __construct()
    {
        parent::__construct($this->_admin_groups);

       /* Load :: Common */

       $this->load->model('cemerge/Estoque_model');
       $this->lang->load('admin/estoque');

       /* Title Page */
       
       $this->page_title->push(lang('menu_estoque'));
       $this->data['pagetitle'] = $this->page_title->show();

       /* Breadcrumbs :: Common */
        $this->breadcrumbs->unshift(1, lang('menu_estoque'), 'admin/estoque');
    }

    public function index()
    {
        
        if (!$this->ion_auth->logged_in()) {
            $this->session->set_flashdata('message', 'Você deve estar autenticado para listar as estoque.');
            redirect('auth/login', 'refresh');
        }
        if (!$this->ion_auth->in_group($this->_admin_groups)) {
            $this->session->set_flashdata('message', 'O acesso &agrave; este recurso não é permitido ao seu perfil de usuário.');
            redirect('auth/login', 'refresh');
        }
        
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        $this->template->admin_render('admin/estoque/index', $this->data);
    }
    
    public function cadastro(){
        if (!$this->ion_auth->logged_in()) {
            $this->session->set_flashdata('message', 'Você deve estar autenticado para criar uma estoque.');
            redirect('auth/login', 'refresh');
        }
        if (!$this->ion_auth->in_group($this->_admin_groups)) {
            $this->session->set_flashdata('message', 'O acesso &agrave; este recurso não é permitido ao seu perfil de usuário.');
            redirect('admin/dashboard', 'refresh');
        }

        /* Breadcrumbs */
        $this->breadcrumbs->unshift(2, lang('menu_estoque_create'), 'admin/estoque/cadastro');
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        /* Validate form input */
        $this->form_validation->set_rules('estoque_nome', 'lang:estoque_descricao', 'required');

        if ($this->form_validation->run() == true) {
            $estoque_nome = $this->input->post('estoque_nome');
            $insert_data = array(
                'estoque_nome' => $estoque_nome,
            );
        }

        if ($this->form_validation->run() == true) {
            $estoque_id = $this->estoque_model->insert($insert_data);
            if ($estoque_id) {
                $this->session->sweetalert2('message', 'estoque inserido com sucesso.');
                redirect('admin/estoque/', 'refresh');
            } else {
                $this->session->sweetalert2('message', 'Houve um erro ao inserir o estoque. Tente novamente.');
                redirect('admin/estoque/', 'refresh');
            }

        } else {
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->swal('message')));
            $this->data['estoque_nome'] = $estoque_nome;
        }

        $this->template->admin_render('admin/estoque/cadastro', $this->data);

    }
}