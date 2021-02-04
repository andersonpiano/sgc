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
    
    public function cadastro(){
        $this->template->admin_render('admin/especializacao/cadastro/', $this->data);
    }
}