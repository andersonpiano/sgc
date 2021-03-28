<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Logs extends Admin_Controller
{
    private $_permitted_groups = array('admin', 'sac', 'profissionais');
    private $_admin_groups = array('admin', 'sac');
    function __construct()
    {
        parent::__construct($this->_admin_groups);

       /* Load :: Common */

       $this->load->model('cemerge/log_model');

       /* Title Page */
       
       $this->page_title->push('Logs');
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
    
    public function registro(){
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

        $id = $this->input->post('id');
        $tabela = $this->input->post('tabela');
        $usuario = $this->ion_auth->user()->row()->id;
        $motivo = $this->input->post('motivo');
        $campo_alterado = $this->input->post('campo_alterado');
        $model = $this->input->post('model');

        $sucess = false;

        /* Validate form input */
        $this->form_validation->set_rules('tabela', 'Tabela', 'required');
        date_default_timezone_set('America/Fortaleza');

        if ($this->form_validation->run() == true) {
            $insert_data = array(
                'tabela' => $tabela,
                'usuario' => $usuario,
                'data' => date('Y-m-d H:i:s'),
                'motivo' => $motivo,
                'campo_alterado' => $campo_alterado
            );
        }
        if ($this->form_validation->run() == true) {
            $log_id = $this->log_model->insert($insert_data);
            if ($log_id) {
                $sucess = true;
            }

        } 

        return $sucess;

    }
}