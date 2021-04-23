<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Setores extends Admin_Controller {

    private $_permitted_groups = array('admin', 'profissionais', 'coordenadorplantao', 'sac');
    private $_admin_groups = array('admin');

    public function __construct()
    {
        parent::__construct($this->_permitted_groups);

        /* Load :: Common */
        $this->load->model('cemerge/setor_model');
        $this->load->model('cemerge/unidadehospitalar_model');
        $this->lang->load('admin/setores');

        /* Title Page */
        $this->page_title->push(lang('menu_setores'));
        $this->data['pagetitle'] = $this->page_title->show();

        /* Breadcrumbs :: Common */
        $this->breadcrumbs->unshift(1, lang('menu_setores'), 'admin/setores');
    }

    public function index()
    {
        if (!$this->ion_auth->logged_in()) {
            $this->session->set_flashdata('message', 'Você deve estar autenticado para listar os setores.');
            redirect('auth/login', 'refresh');
        }
        if (!$this->ion_auth->in_group($this->_permitted_groups)) {
            $this->session->set_flashdata('message', 'O acesso &agrave; este recurso não é permitido ao seu perfil de usuário.');
            redirect('auth/login', 'refresh');
        }

        /* Breadcrumbs */
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        /* Validate form input */
        $this->form_validation->set_rules('unidadehospitalar_id', 'lang:setores_unidadehospitalar', 'required');

        if ($this->form_validation->run() == true) {
            $unidadehospitalar_id = $this->input->post('unidadehospitalar_id');
            
            /* Setores */
            $this->data['setores'] = $this->setor_model->get_where(['unidadehospitalar_id' => $unidadehospitalar_id]);

            /*  Unidades hospitalares */
            // Corrigir atributo para unidadehospitalar_id
            foreach ($this->data['setores'] as $k => $setor) {
                $this->data['setores'][$k]->unidadehospitalar = $this->unidadehospitalar_model->get_by_id($setor->unidadehospitalar_id);
                $this->data['setores'][$k]->vagas = $this->setor_model->vagas($setor->id);//$this->unidadehospitalar_model->get_by_id($setor->unidadehospitalar_id);
            }
        } else {
            $this->data['setores'] = array();
        }

        $unidadeshospitalares = $this->_get_unidadeshospitalares();

        $this->data['unidadehospitalar_id'] = array(
            'name'  => 'unidadehospitalar_id',
            'id'    => 'unidadehospitalar_id',
            'type'  => 'select',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('unidadehospitalar_id'),
            'options' => $unidadeshospitalares,
        );

        /* Load Template */
        $this->template->admin_render('admin/setores/index', $this->data);
    }

    public function create()
    {
        if (!$this->ion_auth->logged_in()) {
            $this->session->set_flashdata('message', 'Você deve estar autenticado para listar os setores.');
            redirect('auth/login', 'refresh');
        }
        if (!$this->ion_auth->in_group($this->_admin_groups)) {
            $this->session->set_flashdata('message', 'O acesso &agrave; este recurso não é permitido ao seu perfil de usuário.');
            redirect('admin/dashboard', 'refresh');
        }

        /* Breadcrumbs */
        $this->breadcrumbs->unshift(2, lang('menu_setores_create'), 'admin/setores/create');
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        /* Variables */
        $tables = $this->config->item('tables', 'ion_auth');

        /* Validate form input */
        $this->form_validation->set_rules('nome', 'lang:setores_nome', 'required');
        $this->form_validation->set_rules('unidadehospitalar_id', 'lang:setores_unidadehospitalar', 'required');
        $this->form_validation->set_rules('maximoprofissionais', 'lang:setores_maximoprofissionais', 'required|greater_than[0]|integer');

        if ($this->form_validation->run() == true) {
            $nome = $this->input->post('nome');
            $unidadehospitalar_id = $this->input->post('unidadehospitalar_id');
            $maximoprofissionais = $this->input->post('maximoprofissionais');
            $active = $this->input->post('active');

            $additional_data = array(
                'nome' => $this->input->post('nome'),
                'unidadehospitalar_id' => $this->input->post('unidadehospitalar_id'),
                'maximoprofissionais' => $this->input->post('maximoprofissionais'),
                'active' => $this->input->post('active')
            );
        }

        // Realizar o insert no model de setores
        if ($this->form_validation->run() == true
            && $this->setor_model->insert($additional_data)
        ) {
            $this->session->set_flashdata('message', $this->ion_auth->messages());
            redirect('admin/setores', 'refresh');
        } else {
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

            $unidadeshospitalares = $this->_get_unidadeshospitalares();

            $this->data['nome'] = array(
                'name'  => 'nome',
                'id'    => 'nome',
                'type'  => 'text',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('nome'),
            );
            $this->data['unidadehospitalar_id'] = array(
                'name'  => 'unidadehospitalar_id',
                'id'    => 'unidadehospitalar_id',
                'type'  => 'select',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('unidadehospitalar_id'),
                'options' => $unidadeshospitalares
            );
            $this->data['maximoprofissionais'] = array(
                'name'  => 'maximoprofissionais',
                'id'    => 'maximoprofissionais',
                'type'  => 'number',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('maximoprofissionais'),
            );
            $this->data['active'] = array(
                'name'  => 'active',
                'id'    => 'active',
                'type'  => 'checkbox',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('active'),
            );

            /* Load Template */
            $this->template->admin_render('admin/setores/create', $this->data);
        }
    }

    public function edit($id)
    {
        if (!$this->ion_auth->logged_in()) {
            $this->session->set_flashdata('message', 'Você deve estar autenticado para listar os setores.');
            redirect('auth/login', 'refresh');
        }
        if (!$this->ion_auth->in_group($this->_admin_groups)) {
            $this->session->set_flashdata('message', 'O acesso &agrave; este recurso não é permitido ao seu perfil de usuário.');
            redirect('admin/dashboard', 'refresh');
        }

        $id = (int) $id;

        /* Breadcrumbs */
        $this->breadcrumbs->unshift(2, lang('menu_setores_edit'), 'admin/setores/edit');
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        /* Load Data */
        $setor = $this->setor_model->get_by_id($id);
        /*  Unidade hospitalar */
        $setor->unidadehospitalar = $this->unidadehospitalar_model->get_by_id($setor->unidadehospitalar_id);

        /* Validate form input */
        $this->form_validation->set_rules('nome', 'lang:setores_nome', 'required');
        $this->form_validation->set_rules('unidadehospitalar_id', 'lang:setores_unidadehospitalar', 'required');
        $this->form_validation->set_rules('maximoprofissionais', 'lang:setores_maximoprofissionais', 'required|greater_than[0]|integer');

        if (isset($_POST) && ! empty($_POST)) {
            if ($this->_valid_csrf_nonce() === false or $id != $this->input->post('id')) {
                show_error($this->lang->line('error_csrf'));
            }

            if ($this->form_validation->run() == true) {
                $data = array(
                    'nome' => $this->input->post('nome'),
                    'unidadehospitalar_id' => $this->input->post('unidadehospitalar_id'),
                    'maximoprofissionais' => $this->input->post('maximoprofissionais'),
                    'id_assessus' => $this->input->post('setores_assessus')
                );

                if ($this->setor_model->update($setor->id, $data)) {
                    $this->session->set_flashdata('message', $this->ion_auth->messages());

                    if ($this->ion_auth->is_admin()) {
                        redirect('admin/setores', 'refresh');
                    } else {
                        redirect('admin', 'refresh');
                    }
                } else {
                    $this->session->set_flashdata('message', $this->ion_auth->errors());

                    if ($this->ion_auth->is_admin()) {
                        redirect('admin/setores', 'refresh');
                    } else {
                        redirect('admin', 'refresh');
                    }
                }
            }
        }

        // display the edit user form
        $this->data['csrf'] = $this->_get_csrf_nonce();

        // set the flash data error message if there is one
        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

        // pass the setor to the view
        $this->data['setor'] = $setor;

        // pass unidades to the dropdown
        $unidadeshospitalares = $this->_get_unidadeshospitalares();
        $setores = $this->_get_setores_assessus();
        $profissionais = $this->_get_responsavel($setor->id);

        $this->data['nome'] = array(
            'name'  => 'nome',
            'id'    => 'nome',
            'type'  => 'text',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('nome', $setor->nome)
        );
        
        $this->data['nome'] = array(
            'name'  => 'nome',
            'id'    => 'nome',
            'type'  => 'text',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('nome', $setor->nome)
        );
        $this->data['unidadehospitalar_id'] = array(
            'name'  => 'unidadehospitalar_id',
            'id'    => 'unidadehospitalar_id',
            'type'  => 'select',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('unidadehospitalar_id', $setor->unidadehospitalar_id),
            'options' => $unidadeshospitalares,
            'selected' => $setor->unidadehospitalar_id
        );
        $this->data['maximoprofissionais'] = array(
            'name'  => 'maximoprofissionais',
            'id'    => 'maximoprofissionais',
            'type'  => 'number',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('maximoprofissionais', $setor->maximoprofissionais),
        );
        $this->data['active'] = array(
            'name'  => 'active',
            'id'    => 'active',
            'type'  => 'checkbox',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('active', $setor->active)
        );

        $this->data['setores_assessus'] = array(
            'name'  => 'setores_assessus',
            'id'    => 'setores_assessus',
            'type'  => 'select',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('setor_id'),
            'selected' => $setor->id_assessus,
            'options' => $setores,
        );

        $this->data['responsavel_id'] = array(
            'name'  => 'responsavel_id',
            'id'    => 'responsavel_id',
            'type'  => 'select',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('setor_id'),
            'selected' => $setor->id_responsavel,
            'options' => $profissionais,
        );

        /* Load Template */
        $this->template->admin_render('admin/setores/edit', $this->data);
    }

    public function _get_setores_sgc()
    {
        $setores_por_unidade = $this->setor_model->get_setores_por_unidade(1);

        $setores = array(
            '' => 'Selecione um setor',
        );
        foreach ($setores_por_unidade as $setor) {
            $setores[$setor->cd_set] = $setor->nm_set;
        }
        return $setores;
    }

    public function _get_setores_assessus()
    {
        $setores_por_unidade = $this->setor_model->get_setores_assessus_por_cd_pes_jur(1);

        $setores = array(
            '' => 'Selecione um setor',
        );
        foreach ($setores_por_unidade as $setor) {
            $setores[$setor->cd_set] = $setor->nm_set;
        }
        return $setores;
    }

    public function _get_responsavel($setor)
    {
        $this->load->model('cemerge/Profissional_model');
        $profissionais_por_setor = $this->Profissional_model->get_profissionais_por_setor($setor);

        $profissionais = array(
            '' => 'Selecione um Profissional',
        );
        foreach ($profissionais_por_setor as $profissional) {
            $profissionais[$profissional->id] = $profissional->nome;
        }
        return $profissionais;
    }

    public function view($id)
    {
        if (!$this->ion_auth->logged_in()) {
            $this->session->set_flashdata('message', 'Você deve estar autenticado para listar os setores.');
            redirect('auth/login', 'refresh');
        }

        /* Load aditional models */
        $this->load->model('cemerge/profissional_model');
        $this->load->model('cemerge/usuariosetor_model');

        /* Breadcrumbs */
        $this->breadcrumbs->unshift(2, lang('menu_setores'), 'admin/setores/view');
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        /* Data */
        $id = (int) $id;

        $this->data['setor'] = $this->setor_model->get_by_id($id);
        $this->data['setor']->unidadehospitalar = $this->unidadehospitalar_model->get_by_id($this->data['setor']->unidadehospitalar_id);
        $this->data['setor']->profissionais = $this->profissional_model->get_profissionais_por_setor($id);
        $this->data['setor']->usuarios = $this->usuariosetor_model->get_usuarios_por_setor($id);

        $profissionais = $this->data['setor']->profissionais;
        $usuarios_dropdown = $this->get_usuarios_dropdown($id);
        //var_dump($usuarios);exit;
        $this->data['profissional_id'] = array(
            'name'  => 'profissional_id',
            'id'    => 'profissional_id',
            'type'  => 'select',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('profissional_id'),
            'options' => $usuarios_dropdown,
        );

        /* Load Template */
        $this->template->admin_render('admin/setores/view', $this->data);
    }

    public function trocar_coordenador(){
        
        $this->load->model('cemerge/usuarioprofissional_model');
        $this->load->model('cemerge/usuariosetor_model');
        
        $setor = $this->input->post('setor');
        $profissional = $this->input->post('profissional');
        $user_id = $this->usuarioprofissional_model->get_usuario_por_profissional($profissional)[0];
        //var_dump($user_id->user_id);exit;

        $this->form_validation->set_rules('profissional', 'profissional', 'required|greater_than[0]|integer');
        $this->data['sucess'] = false;

            if ($this->form_validation->run() == true) {

                $usuariosetor = $this->usuariosetor_model->setor_usuario($setor);

                if ($usuariosetor >= 1){
                    if ($this->usuariosetor_model->trocar_coordenador($setor, $user_id->user_id)) {
                        $this->data['sucess'] = true;
                    }
                } else {
                    $this->usuariosetor_model->insert(['setor_id' => $setor, 'user_id' => $user_id->user_id, 'coordenador' => 1]);
                    if ($this->usuariosetor_model->trocar_coordenador($setor, $user_id->user_id)) {
                        $this->data['sucess'] = true;
                    }
                }
            }

        echo json_encode($this->data['sucess']); exit;
    }

    public function ajax_profissionais() {

        if (!$this->input->is_ajax_request()) {
            exit("Nenhum acesso de script direto permitido!");
        }
            
        $this->load->model("cemerge/profissional_model");

        $profissionais = $this->profissional_model->get_datatable();

        $data = array();
        foreach ($profissionais as $profissional) {
    
            $row = array();
            $row[] = '<center>'.$profissional->id.'</center>';
            $row[] = '<center>'.$profissional->nome.'</center>';
            
            $row[] = '<center><div style="display: inline-block;">
                        <button style="color:green; font-size:20px;" class="btn btn-link btn-add-profissional" 
                            id='.$profissional->id.'>
                            <i class="fa fa-check-square-o"></i>
                        </button>
                    </div></center>';
    
            $data[] = $row;
    
        }
        $json = array(
            "draw" => $this->input->post("draw"),
            "recordsTotal" =>$this->profissional_model->records_total(),
            "recordsFiltered" => $this->profissional_model->records_filtered(),
            "status" => 1,
            "data" => $data,
        ); 
        echo json_encode($json);
        exit;
    }
    

    public function ajax_setores_profissional($profissional_id) {

        $profissional_id = (int) $profissional_id;

        if (!$this->input->is_ajax_request()) {
            exit("Nenhum acesso de script direto permitido!");
        }
            
        $this->load->model("cemerge/setor_model");

        $setores = $this->setor_model->get_datatable_setor_profissional($profissional_id);

        $data = array();
        foreach ($setores as $setor) {
            $setor_sgc = $this->setor_model->get_setor_por_id($setor->setor_id);
            $row = array();
            $row[] = '<center>'.$setor_sgc->nome.'</center>';
            
            $row[] = '<center><div style="display: inline-block;">
                        <button onclick="window.location.href='."'/sgc/admin/profissionais/unlinkfromsector/".$profissional_id.'/'.$setor_sgc->id."'".'" style="color:red; font-size:20px;" class="btn btn-link btn-remover-profissional" 
                            id='.$profissional_id.'>
                            <i class="fa fa-times">&nbsp; Remover</i>
                        </button>
                    </div></center>';
    
            $data[] = $row;
    
        }
        $json = array(
            "draw" => $this->input->post("draw"),
            "status" => 1,
            "data" => $data,
        ); 
        echo json_encode($json);
        exit;
    }

    public function ajax_setores() {


        if (!$this->input->is_ajax_request()) {
            exit("Nenhum acesso de script direto permitido!");
        }
            
        $this->load->model("cemerge/setor_model");

        $setores = $this->setor_model->get_datatable();

        $data = array();
        foreach ($setores as $setor) {
            $row = array();
            $row[] = '<center>'.$setor->nome.'</center>';
            
            $row[] = '<center><div style="display: inline-block;">
                        <button style="color:green; font-size:20px;" class="btn btn-link btn-add-profissional" 
                            id='.$setor->id.'>
                            <i class="fa fa-plus">&nbsp; Adicionar</i>
                        </button>
                    </div></center>';
    
            $data[] = $row;
    
        }
        $json = array(
            "draw" => $this->input->post("draw"),
            "recordsTotal" =>$this->setor_model->records_total(),
            "recordsFiltered" => $this->setor_model->records_filtered(),
            "status" => 1,
            "data" => $data,
        ); 
        echo json_encode($json);
        exit;
    }

    public function get_usuarios_dropdown($setor)
    {   
        $this->load->model('cemerge/profissional_model');
        $usuarios = $this->profissional_model->get_profissionais_por_setor($setor);

        $coordenadores = array(
            '' => 'Selecione o novo coordenador',
        );
        foreach ($usuarios as $usuario) {
            $coordenadores[$usuario->id] = $usuario->nome;
        }
        //echo json_encode(['coordenadores' => $coordenadores]);
        return $coordenadores;
    }

    public function get_usuarios_dropdown_encode()
    {   

        $setor = $this->input->post('setor');
        $this->load->model('cemerge/profissional_model');
        $usuarios = $this->profissional_model->get_profissionais_por_setor($setor);

        $coordenadores = array(
        );
        foreach ($usuarios as $usuario) {
            $coordenadores[$usuario->id] = $usuario->nomecurto;
        }
        echo json_encode($coordenadores);
        exit;
        //return $coordenadores;
    }

    public function user_by_prof($profissional_id){
        
        $this->db->select('*');
        $this->db->from('usuariosprofissionais');
        $this->db->join('profissionalsetor', 'setores.id = profissionalsetor.setor_id', 'left');
        $this->db->join('profissionais', 'profissionais.id = profissionalsetor.profissional_id', 'left');
        $this->db->where('profissional_id', $profissional_id);
        $query = $this->db->get();

        return $query->result();
    }

    public function _get_csrf_nonce()
    {
        $this->load->helper('string');
        $key   = random_string('alnum', 8);
        $value = random_string('alnum', 20);
        $this->session->set_flashdata('csrfkey', $key);
        $this->session->set_flashdata('csrfvalue', $value);

        return array($key => $value);
    }

    public function _valid_csrf_nonce()
    {
        if ($this->input->post($this->session->flashdata('csrfkey')) !== false && $this->input->post($this->session->flashdata('csrfkey')) == $this->session->flashdata('csrfvalue')) {
            return true;
        } else {
            return false;
        }
    }

    public function _get_unidadeshospitalares()
    {
        // pass unidades to the view
        $unidades = $this->unidadehospitalar_model->get_all();

        $unidadeshospitalares = array();
        foreach ($unidades as $unidade) {
            $unidadeshospitalares[$unidade->id] = $unidade->razaosocial;
        }

        return $unidadeshospitalares;
    }

    public function setores($id, $profissional_id = null, $user_id = null)
    {
        $id = (int) $id;
        
        if ($profissional_id and $profissional_id != 0) {
            $setores = $this->setor_model->get_by_unidade_profissional($id, $profissional_id);
        } elseif ($user_id and $user_id != 0) {
            $setores = $this->setor_model->get_by_unidade_usuario($id, $user_id);
        } else {
            $setores = $this->setor_model->get_where(['unidadehospitalar_id' => $id], null, 'nome');
        }
        array_unshift($setores, ['id' => '', 'nome' => 'Todos os Setores']);

        echo json_encode($setores);
        exit;
    }

    public function setor_por_profissional($profissional_id)
    {
        $this->load->model('cemerge/setor_model');
        $setores = $this->setor_model->get_setores_por_profissional($profissional_id);

        $opcoes = array(
            0 => 'Todos',
        );
        foreach ($setores as $setor) {
            $opcoes[$setor->id] = $setor->nome;
        }

        echo json_encode($opcoes);
        exit;
    }

    public function setores_assessus($cd_pes_jur)
    {
        $cd_pes_jur = (int) $cd_pes_jur;

        $this->load->model('cemerge/setor_model');
        $setores = $this->setor_model->get_setores_assessus_por_cd_pes_jur($cd_pes_jur);
        array_unshift($setores, ['cd_set' => '0', 'nm_set' => 'Selecione um setor']);

        echo json_encode($setores);
        exit;
    }

    public function assessus_x_sgc($setor_id)
    {
        $setor_id = (int) $setor_id;
        $this->load->model('cemerge/setor_model');
        $setor = $this->setor_model->assessus_x_sgc($setor_id);

        echo json_encode($setor);
        exit;
    }
}
