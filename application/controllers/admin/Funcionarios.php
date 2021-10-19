<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Funcionarios extends Admin_Controller
{
    private $_permitted_groups = array('admin', 'coordenadorplantao', 'sac', 'funcionarios');
    private $_admin_groups = array('admin', 'coordenadorplantao', 'sac', 'funcionarios');

    public function __construct()
    {
        parent::__construct($this->_permitted_groups);

        /* Load :: Common */
        $this->load->model('cemerge/funcionario_model');
        $this->load->model('cemerge/vinculo_model');
        $this->lang->load('admin/funcionarios');

        /* Title Page */
        $this->page_title->push(lang('menu_funcionarios'));
        $this->data['pagetitle'] = $this->page_title->show();

        /* Breadcrumbs :: Common */
        $this->breadcrumbs->unshift(1, lang('menu_funcionarios'), 'admin/funcionarios/');
    }

    public function index()
    {
        if (!$this->ion_auth->logged_in()) {
            $this->session->set_flashdata('message', 'Você deve estar autenticado para listar os funcionarios.');
            redirect('auth/login', 'refresh');
        }
        if (!$this->ion_auth->in_group($this->_permitted_groups)) {
            $this->session->set_flashdata('message', 'O acesso &agrave; este recurso não é permitido ao seu perfil de usuário.');
            redirect('auth/login', 'refresh');
        }

        /* Breadcrumbs */
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        /* Validate form input */
        $this->form_validation->set_rules('nome', 'lang:funcionarios_nome', 'required');

        if ($this->form_validation->run() == true) {
            $nome = $this->input->post('nome');
            
            /* funcionarios */
            $this->data['funcionarios'] = $this->funcionario_model->get_like('nome', $nome, 'nome');
        } else {
            $this->data['funcionarios'] = array();
            $nome = '';
        }

        $this->data['nome'] = array(
            'name'  => 'nome',
            'id'    => 'nome',
            'type'  => 'text',
            'class' => 'form-control',
            'value' => $nome,
        );

        /* Load Template */
        $this->template->admin_render('admin/funcionarios/index', $this->data);
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
        $this->breadcrumbs->unshift(2, lang('menu_funcionarios_create'), 'admin/funcionarios/create');
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        /* Variables */
        $tables = $this->config->item('tables', 'ion_auth');

        // Vínculos
        $vinculos = $this->_get_vinculos();

        /* Validate form input */
        $this->form_validation->set_rules('matricula', 'lang:funcionarios_matricula', 'required');
        $this->form_validation->set_rules('nome', 'lang:funcionarios_nome', 'required');
        $this->form_validation->set_rules('nomecurto', 'lang:funcionarios_nomecurto', 'required');
        $this->form_validation->set_rules('vinculo', 'lang:funcionarios_vinculo', 'required');
        $this->form_validation->set_rules('email', 'lang:funcionarios_email', 'required|valid_email|is_unique[funcionarios.email]');
        $this->form_validation->set_rules('cpf', 'lang:funcionarios_cpf', 'required|is_unique[funcionarios.cpf]');
        $this->form_validation->set_rules('rg', 'lang:funcionarios_rg', 'required');
        $this->form_validation->set_rules('orgao_expeditor_rg', 'lang:funcionarios_orgao_expeditor_rg', 'required');

        if ($this->form_validation->run() == true) {
            $matricula = $this->input->post('matricula');
            $nome = $this->input->post('nome');
            $nomecurto = $this->input->post('nomecurto');
            $vinculo = $this->input->post('vinculo');
            $email = $this->input->post('email');
            $cpf = $this->input->post('cpf');
            $rg = $this->input->post('rg');
            $orgao_expeditor_rg = $this->input->post('orgao_expeditor_rg');
            $active = $this->input->post('active');

            $additional_data = array(
                'matricula' => $this->input->post('matricula'),
                'nome' => $this->input->post('nome'),
                'vinculo_id' => $vinculo,
                'nomecurto' => $this->input->post('nomecurto'),
                'email' => $this->input->post('email'),
                'cpf' => $this->input->post('cpf'),
                'rg' => $this->input->post('rg'),
                'orgao_expeditor_rg' => $this->input->post('orgao_expeditor_rg'),
                'active' => $this->input->post('active')
            );
        }

        // Realizar o insert no model de unidades hospitalares
        if ($this->form_validation->run() == true
            && $this->funcionario_model->insert($additional_data)
        ) {
            $this->session->set_flashdata('message', $this->ion_auth->messages());
            redirect('admin/especializacoes/', 'refresh');
        } else {
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

            $this->data['matricula'] = array(
                'name'  => 'matricula',
                'id'    => 'matricula',
                'type'  => 'text',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('matricula'),
            );
            $this->data['nome'] = array(
                'name'  => 'nome',
                'id'    => 'nome',
                'type'  => 'text',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('nome'),
            );
            $this->data['nomecurto'] = array(
                'name'  => 'nomecurto',
                'id'    => 'nomecurto',
                'type'  => 'text',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('nomecurto'),
            );
            $this->data['vinculo'] = array(
                'name'  => 'vinculo',
                'id'    => 'vinculo',
                'type'  => 'select',
                'class' => 'form-control',
                'selected' => $this->form_validation->set_value('vinculo'),
                'options' => $vinculos,
            );
            $this->data['email'] = array(
                'name'  => 'email',
                'id'    => 'email',
                'type'  => 'text',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('email'),
            );
            $this->data['cpf'] = array(
                'name'  => 'cpf',
                'id'    => 'cpf',
                'type'  => 'text',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('cpf'),
            );
            $this->data['rg'] = array(
                'name'  => 'rg',
                'id'    => 'rg',
                'type'  => 'text',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('rg'),
            );
            $this->data['orgao_expeditor_rg'] = array(
                'name'  => 'orgao_expeditor_rg',
                'id'    => 'orgao_expeditor_rg',
                'type'  => 'text',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('orgao_expeditor_rg'),
            );
            $this->data['active'] = array(
                'name'  => 'active',
                'id'    => 'active',
                'type'  => 'checkbox',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('active'),
            );

            /* Load Template */
            $this->template->admin_render('admin/funcionarios/create', $this->data);
        }
    }

    public function create_all(){
        $this->load->model('cemerge/usuariofuncionario_model');
        $this->load->model('cemerge/funcionario_model');

        $usuarios = $this->usuariofuncionario_model->funcionarios_sem_usuario();
        

        foreach ($usuarios as $usuario) {
            if ($this->createuser($usuario->id)){
                //var_dump($usuario->id); exit;
                $status = $usuario->id.' - ok';
            } else {
            }   
        }
    }

    public function createuser($id)
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

        /* Load Data */
        $funcionario = $this->funcionario_model->get_by_id($id);

        if ($funcionario and trim($funcionario->cpf) != '0' and trim($funcionario->cpf) != '') {
            $username = strtolower($funcionario->nomecurto);
            $password = "cemerge@2021";
            $email = $funcionario->email;
            $additional_data = array(
                        'first_name' => $funcionario->nome,
                        );
            $group = array('3'); // Sets user to funcionario group

            // Validate status
            if ($funcionario->active == 0) {
                $this->session->set_flashdata('message', 'O funcionario está inativo. Ative-o primeiro e depois crie seu usuário.');
               // redirect('admin/funcionarios/edit/' . $id, 'refresh');
            }

            // Validate email
            if (trim($funcionario->email) == "@") {
                $this->session->set_flashdata('message', 'O funcionario não possui um e-mail válido cadastrado.');
                //redirect('admin/funcionarios/edit/' . $id, 'refresh');
            }

            // User exists?
            if ($this->ion_auth->email_check($email)) {
                $this->session->set_flashdata('message', 'Já existe um usuário criado para este funcionario. Favor editá-lo.');
               // redirect('admin/funcionarios/edit/' . $id, 'refresh');
               exit;
            }

            $userCreated = $this->ion_auth->register($username, $password, $email, $additional_data, $group);

            if ($userCreated) {
                // Vincular usuário ao funcionario
                $this->load->model('cemerge/usuariofuncionario_model');
                $usuariofuncionario = $this->usuariofuncionario_model->insert(array('funcionario_id' => $id, 'user_id' => $userCreated));
                if ($usuariofuncionario) {
                    $this->session->set_flashdata('message', 'Usuarío criado e vinculado ao funcionario com sucesso.');
                } else {
                    $this->session->set_flashdata('message', 'Houve um erro ao vincular o usuário ao funcionario. Tente novamente.');
                }
            } else {
                $this->session->set_flashdata('message', 'Houve um erro ao criar o usuário. Tente novamente.');
            }
        } else {
            $this->session->set_flashdata('message', 'Não foi encontrado funcionario com o código informado ou o funcionario não possui CPF cadastrado.');
        }
        

        /* Redirect to edit page */
        //redirect('admin/funcionarios/edit/' . $id, 'refresh');
    }

    public function linktosector($id)
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
        $this->breadcrumbs->unshift(2, lang('menu_funcionarios_edit'), 'admin/funcionarios/edit');
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        /* Load Data */
        $funcionario = $this->funcionario_model->get_by_id($id);
        $unidadeshospitalares = $this->_get_unidadeshospitalares();
        $setores = array();

        /* Validate form input */
        //$this->form_validation->set_rules('unidadehospitalar_id', 'lang:funcionarios_unidadehospitalar', 'required');
        $this->form_validation->set_rules('setor_id', 'lang:funcionarios_setor', 'required');

        if (isset($_POST) && !empty($_POST)) {
            if ($this->form_validation->run() == true) {
                $setor_id = $this->input->post('setor_id');
                $data = array(
                    'setor_id' => $setor_id,
                    'funcionario_id' => $id,
                );

                $this->load->model('cemerge/funcionariosetor_model');

                if (empty($this->funcionariosetor_model->get_where(array('setor_id' => $setor_id, 'funcionario_id' =>$id)))) {
                    if ($this->funcionariosetor_model->insert($data)) {
                        $this->session->set_flashdata('message', 'funcionario vinculado ao setor com sucesso.');
                    } else {
                        $this->session->set_flashdata('message', 'Houve um erro ao vincular o funcionario ao setor.');
                    }
                } else {
                    $this->session->set_flashdata('message', 'O funcionario já é vinculado a este setor.');
                }
                redirect('admin/especializacoes/', 'refresh');
            }
        }

        // display the edit user form
        $this->data['csrf'] = $this->_get_csrf_nonce();

        // set the flash data error message if there is one
        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

        // pass the funcionario to the view
        $this->data['funcionario'] = $funcionario;

        $this->data['unidadehospitalar_id'] = array(
            'name'  => 'unidadehospitalar_id',
            'id'    => 'unidadehospitalar_id',
            'type'  => 'select',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('unidadehospitalar_id'),
            'options' => $unidadeshospitalares,
        );
        $this->data['setor_id'] = array(
            'name'  => 'setor_id',
            'id'    => 'setor_id',
            'type'  => 'select',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('setor_id'),
            'options' => $setores,
        );

        /* Load Template */
        $this->template->admin_render('admin/funcionarios/linktosector', $this->data);
        return true;
    }

    public function unlinkfromsector($funcionario_id, $setor_id)
    {
        if (!$this->ion_auth->logged_in()) {
            $this->session->set_flashdata('message', 'Você deve estar autenticado para listar os setores.');
            redirect('auth/login', 'refresh');
        }
        if (!$this->ion_auth->in_group($this->_admin_groups)) {
            $this->session->set_flashdata('message', 'O acesso &agrave; este recurso não é permitido ao seu perfil de usuário.');
            redirect('admin/dashboard', 'refresh');
        }

        $funcionario_id = (int) $funcionario_id;
        $setor_id = (int) $setor_id;

        // Checar se o funcionario possui plantões ativos no setor

        $this->load->model('cemerge/funcionariosetor_model');
        $success = $this->funcionariosetor_model->delete(array('funcionario_id' => $funcionario_id, 'setor_id' => $setor_id));

        if ($success != false) {
            $this->session->set_flashdata('message', 'funcionario desvinculado do setor com sucesso.');
        } else {
            $this->session->set_flashdata('message', 'Houve um problema ao desvincular o funcionario do setor.');
        }

        /* Redirect */
        //redirect('admin/setores/view/'.$setor_id, 'refresh');
        redirect($_SERVER['HTTP_REFERER'], 'refresh');
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
        $this->breadcrumbs->unshift(2, lang('menu_funcionarios_edit'), 'admin/funcionarios/edit');
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        /* Load Data */
        $funcionario = $this->funcionario_model->get_by_id($id);
        // Vínculos
        $vinculos = $this->_get_vinculos();
        //$groups        = $this->ion_auth->groups()->result_array();
        //$currentGroups = $this->ion_auth->get_users_groups($id)->result();

        /* Validate form input */
        $this->form_validation->set_rules('nome', 'lang:funcionarios_nome', 'required');
        $this->form_validation->set_rules('nomecurto', 'lang:funcionarios_nomecurto', 'required');
        $this->form_validation->set_rules('matricula', 'lang:funcionarios_matricula', 'required');
        $this->form_validation->set_rules('vinculo', 'lang:funcionarios_vinculo', 'required');
        $this->form_validation->set_rules('email', 'lang:funcionarios_email', 'required|valid_email');
        $this->form_validation->set_rules('cpf', 'lang:funcionarios_cpf', 'required');
        $this->form_validation->set_rules('rg', 'lang:funcionarios_rg', 'required');
        $this->form_validation->set_rules('orgao_expeditor_rg', 'lang:orgao_expeditor_rg', 'required');
        //$this->form_validation->set_rules('active', 'lang:edit_user_validation_company_label', 'required');

        if (isset($_POST) and !empty($_POST)) {
            /*if ($this->ion_auth->is_admin()) {
                if ($this->_valid_csrf_nonce() === false or $id != $this->input->post('id')) {
                    show_error($this->lang->line('error_csrf'));
                }*/

                if ($this->form_validation->run() == true) {
                    $data = array(
                        'matricula' => $this->input->post('matricula'),
                        'nome' => $this->input->post('nome'),
                        'nomecurto' => $this->input->post('nomecurto'),
                        'vinculo_id' => $this->input->post('vinculo'),
                        'email' => $this->input->post('email'),
                        'cpf' => $this->input->post('cpf'),
                        'rg' => $this->input->post('rg'),
                        'orgao_expeditor_rg' => $this->input->post('orgao_expeditor_rg'),
                        'active' => $this->input->post('active')
                    );

                    if ($this->funcionario_model->update($funcionario->id, $data)) {
                        $this->session->set_flashdata('message', 'funcionario atualizado com sucesso.');
                        redirect('admin/especializacoes/', 'refresh');

                    } else {
                        $this->session->set_flashdata('message', $this->ion_auth->errors());
                    }
                }
            /*} else {
                $this->session->set_flashdata('message', 'Somente administradores podem alterar dados de funcionarios.');
            }*/
        }

        // display the edit user form
        $this->data['csrf'] = $this->_get_csrf_nonce();

        // set the flash data error message if there is one
        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

        // pass the funcionario to the view
        $this->data['funcionario'] = $funcionario;

        $this->data['matricula'] = array(
            'name'  => 'matricula',
            'id'    => 'matricula',
            'type'  => 'text',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('matricula', $funcionario->matricula)
        );
        $this->data['nome'] = array(
            'name'  => 'nome',
            'id'    => 'nome',
            'type'  => 'text',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('nome', $funcionario->nome)
        );
        $this->data['nomecurto'] = array(
            'name'  => 'nomecurto',
            'id'    => 'nomecurto',
            'type'  => 'text',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('nomecurto', $funcionario->nomecurto)
        );
        $this->data['vinculo'] = array(
            'name'  => 'vinculo',
            'id'    => 'vinculo',
            'type'  => 'select',
            'class' => 'form-control',
            'selected' => $this->form_validation->set_value('vinculo', $funcionario->vinculo_id),
            'options' => $vinculos,
        );
        $this->data['email'] = array(
            'name'  => 'email',
            'id'    => 'email',
            'type'  => 'text',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('email', $funcionario->email)
        );
        $this->data['cpf'] = array(
            'name'  => 'cpf',
            'id'    => 'cpf',
            'type'  => 'text',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('cpf', $funcionario->cpf)
        );
        $this->data['rg'] = array(
            'name'  => 'rg',
            'id'    => 'rg',
            'type'  => 'text',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('rg', $funcionario->rg)
        );
        $this->data['orgao_expeditor_rg'] = array(
            'name'  => 'orgao_expeditor_rg',
            'id'    => 'orgao_expeditor_rg',
            'type'  => 'text',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('orgao_expeditor_rg', $funcionario->orgao_expeditor_rg)
        );
        $this->data['active'] = array(
            'name'  => 'active',
            'id'    => 'active',
            'type'  => 'checkbox',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('active', $funcionario->active)
        );

        /* Load Template */
        $this->template->admin_render('admin/funcionarios/edit', $this->data);
    }

    public function view($id)
    {
        if (!$this->ion_auth->logged_in()) {
            $this->session->set_flashdata('message', 'Você deve estar autenticado para listar os setores.');
            redirect('auth/login', 'refresh');
        }
        if (!$this->ion_auth->in_group($this->_permitted_groups)) {
            $this->session->set_flashdata('message', 'O acesso &agrave; este recurso não é permitido ao seu perfil de usuário.');
            redirect('admin/dashboard', 'refresh');
        }

        /* Load aditional models */
        $this->load->model('cemerge/setor_model');
        $this->load->model('cemerge/usuariofuncionario_model');

        /* Breadcrumbs */
        $this->breadcrumbs->unshift(2, lang('menu_users_profile'), 'admin/funcionarios/view');
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        /* Data */
        $id = (int) $id;

        $this->data['funcionario'] = $this->funcionario_model->get_by_id($id);
        $this->data['funcionario']->usuario = array();
        $usuario_funcionario = $this->usuariofuncionario_model->get_where(array('funcionario_id' => $id));
        if (!empty($usuario_funcionario)) {
            $this->data['funcionario']->usuario = $usuario_funcionario[0];
            if (sizeof($usuario_funcionario) > 1) {
                // O funcionario está vinculado a mais de um usuário
                $this->session->set_flashdata('message', 'O funcionario está vinculado a mais de um usuário. Isso pode causar erros. Favor solicitar a correção ao SAC.');
            }
        } else {
            // Não há vínculo entre funcionario e usuário
            $this->session->set_flashdata('message', 'O funcionario não está vinculado a nenhum usuário. Favor corrigir, caso seja necessário.');
        }
        $this->data['funcionario']->setores = $this->setor_model->get_setores_por_funcionario($id);
        $this->data['funcionario']->setorescoordena = $this->setor_model->get_setores_coordenados_por_funcionario($id);

        $this->data['coordenador'] = new stdClass();
        $this->data['coordenador']->setorescoordena = $this->setor_model->get_setores_coordenados_por_usuario($this->ion_auth->user()->row()->id);

        //var_dump($this->data['coordenador']->setorescoordena);exit;

        /* Load Template */
        $this->template->admin_render('admin/funcionarios/view', $this->data);
    }

    public function funcionariosporunidade($unidadehospitalar_id)
    {
        $unidadehospitalar_id = (int) $unidadehospitalar_id;
        
        if ($unidadehospitalar_id and $unidadehospitalar_id != 0) {
            $funcionarios = $this->funcionario_model->get_funcionarios_por_unidade_hospitalar($unidadehospitalar_id);
        }
        array_unshift($funcionarios, ['id' => '', 'nome' => 'Todos os funcionarios']);

        echo json_encode($funcionarios);
        exit;
    }

    public function _get_unidadeshospitalares()
    {
        $this->load->model('cemerge/unidadehospitalar_model');
        $unidades = $this->unidadehospitalar_model->get_all();

        $unidadeshospitalares = array(
            '' => 'Selecione uma unidade hospitalar',
        );
        foreach ($unidades as $unidade) {
            $unidadeshospitalares[$unidade->id] = $unidade->razaosocial;
        }

        return $unidadeshospitalares;
    }

    public function funcionarios_por_setor($setor, $plantao){

        if (!$this->ion_auth->logged_in()) {
            $this->session->set_flashdata('message', 'Você deve estar autenticado para listar os funcionarios.');
            redirect('auth/login', 'refresh');
        }
        if (!$this->ion_auth->in_group($this->_permitted_groups)) {
            $this->session->set_flashdata('message', 'O acesso &agrave; este recurso não é permitido ao seu perfil de usuário.');
            redirect('auth/login', 'refresh');
        }
        
        if ($setor and $setor!= 0) {
            $funcionarios = $this->funcionario_model->get_funcionarios_por_setor_disponiveis($setor, $plantao);
        }
        //var_dump($funcionarios); exit;
        echo json_encode($funcionarios);
        exit;
    }

    public function _get_vinculos()
    {
        $vinculos = $this->vinculo_model->get_all();

        $v = array();
        foreach ($vinculos as $vinculo) {
            $v[$vinculo->id] = $vinculo->nome;
        }

        return $v;
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
        if ($this->input->post($this->session->flashdata('csrfkey')) !== false
            && $this->input->post($this->session->flashdata('csrfkey')) == $this->session->flashdata('csrfvalue')
        ) {
            return true;
        } else {
            return false;
        }
    }
}
