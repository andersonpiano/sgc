<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Especializacao extends Admin_Controller
{
    private $_permitted_groups = array('admin', 'sac', 'profissionais');
    private $_admin_groups = array('admin', 'sac');
    function __construct()
    {
        parent::__construct($this->_permitted_groups);

         $this->load->model('cemerge/Especializacao_model');

         $this->page_title->push('Teste');
         $this->data['pagetitle'] = $this->page_title->show();
 
         /* Breadcrumbs :: Common */
         $this->breadcrumbs->unshift(1, 'Teste', 'admin/justificativas');
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

        $this->template->admin_render('admin/especializacao/index');
    }
    
    public function cadastro(){
        $this->template->admin_render('admin/especializacao/cadastro/');
    }
}