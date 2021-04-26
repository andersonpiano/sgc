<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profissionais extends Admin_Controller
{
    private $_permitted_groups = array('admin', 'coordenadorplantao', 'sac');
    private $_admin_groups = array('admin', 'coordenadorplantao', 'sac');

    public function __construct()
    {
        parent::__construct($this->_permitted_groups);

        /* Load :: Common */
        $this->load->model('cemerge/profissional_model');
        $this->load->model('cemerge/vinculo_model');
        $this->lang->load('admin/profissionais');

        /* Title Page */
        $this->page_title->push(lang('menu_profissionais'));
        $this->data['pagetitle'] = $this->page_title->show();

        /* Breadcrumbs :: Common */
        $this->breadcrumbs->unshift(1, lang('menu_profissionais'), 'admin/profissionais/');
    }

    public function index()
    {
        if (!$this->ion_auth->logged_in()) {
            $this->session->set_flashdata('message', 'Você deve estar autenticado para listar os profissionais.');
            redirect('auth/login', 'refresh');
        }
        if (!$this->ion_auth->in_group($this->_permitted_groups)) {
            $this->session->set_flashdata('message', 'O acesso &agrave; este recurso não é permitido ao seu perfil de usuário.');
            redirect('auth/login', 'refresh');
        }

        /* Breadcrumbs */
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        /* Validate form input */
        $this->form_validation->set_rules('nome', 'lang:profissionais_nome', 'required');

        if ($this->form_validation->run() == true) {
            $nome = $this->input->post('nome');
            
            /* Profissionais */
            $this->data['profissionais'] = $this->profissional_model->get_like('nome', $nome, 'nome');
        } else {
            $this->data['profissionais'] = array();
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
        $this->template->admin_render('admin/profissionais/index', $this->data);
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
        $this->breadcrumbs->unshift(2, lang('menu_profissionais_create'), 'admin/profissionais/create');
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        /* Variables */
        $tables = $this->config->item('tables', 'ion_auth');

        // Vínculos
        $vinculos = $this->_get_vinculos();

        /* Validate form input */
        $this->form_validation->set_rules('registro', 'lang:profissionais_registro', 'required|is_unique[profissionais.registro]');
        $this->form_validation->set_rules('matricula', 'lang:profissionais_matricula', 'required');
        $this->form_validation->set_rules('nome', 'lang:profissionais_nome', 'required');
        $this->form_validation->set_rules('nomecurto', 'lang:profissionais_nomecurto', 'required');
        $this->form_validation->set_rules('vinculo', 'lang:profissionais_vinculo', 'required');
        $this->form_validation->set_rules('email', 'lang:profissionais_email', 'required|valid_email|is_unique[profissionais.email]');
        $this->form_validation->set_rules('cpf', 'lang:profissionais_cpf', 'required|is_unique[profissionais.cpf]');
        $this->form_validation->set_rules('rg', 'lang:profissionais_rg', 'required');
        $this->form_validation->set_rules('orgao_expeditor_rg', 'lang:profissionais_orgao_expeditor_rg', 'required');

        if ($this->form_validation->run() == true) {
            $registro = $this->input->post('registro');
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
                'registro' => $this->input->post('registro'),
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
            && $this->profissional_model->insert($additional_data)
        ) {
            $this->session->set_flashdata('message', $this->ion_auth->messages());
            redirect('admin/especializacoes/', 'refresh');
        } else {
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

            $this->data['registro'] = array(
                'name'  => 'registro',
                'id'    => 'registro',
                'type'  => 'text',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('registro'),
            );
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
            $this->template->admin_render('admin/profissionais/create', $this->data);
        }
    }

    public function create_all(){
        $this->load->model('cemerge/usuarioprofissional_model');
        $this->load->model('cemerge/profissional_model');

        $usuarios = $this->usuarioprofissional_model->profissionais_sem_usuario();
        

        foreach ($usuarios as $usuario) {
            if ($this->createuser($usuario->id)){
                var_dump($usuario->id); exit;
            } else {
            }   
        }
    }

    public function createuser($id)
    {
        /*if (!$this->ion_auth->logged_in()) {
            $this->session->set_flashdata('message', 'Você deve estar autenticado para listar os setores.');
            redirect('auth/login', 'refresh');
        }
        if (!$this->ion_auth->in_group($this->_admin_groups)) {
            $this->session->set_flashdata('message', 'O acesso &agrave; este recurso não é permitido ao seu perfil de usuário.');
            redirect('admin/dashboard', 'refresh');
        }*/

        $id = (int) $id;

        /* Load Data */
        $profissional = $this->profissional_model->get_by_id($id);

        if ($profissional and trim($profissional->cpf) != '0' and trim($profissional->cpf) != '') {
            $username = strtolower($profissional->nomecurto);
            $password = "cemerge@2021";
            $email = $profissional->email;
            $additional_data = array(
                        'first_name' => $profissional->nome,
                        );
            $group = array('3'); // Sets user to profissional group

            // Validate status
            /*if ($profissional->active == 0) {
                $this->session->set_flashdata('message', 'O profissional está inativo. Ative-o primeiro e depois crie seu usuário.');
                redirect('admin/profissionais/edit/' . $id, 'refresh');
            }*/

            // Validate email
            /*if (trim($profissional->email) == "@") {
                $this->session->set_flashdata('message', 'O profissional não possui um e-mail válido cadastrado.');
                redirect('admin/profissionais/edit/' . $id, 'refresh');
            }

            // User exists?
            if ($this->ion_auth->email_check($email)) {
                $this->session->set_flashdata('message', 'Já existe um usuário criado para este profissional. Favor editá-lo.');
                redirect('admin/profissionais/edit/' . $id, 'refresh');
            }*/

            $userCreated = $this->ion_auth->register($username, $password, $email, $additional_data, $group);

            if ($userCreated) {
                // Vincular usuário ao profissional
                $this->load->model('cemerge/usuarioprofissional_model');
                $usuarioprofissional = $this->usuarioprofissional_model->insert(array('profissional_id' => $id, 'user_id' => $userCreated));
                /*if ($usuarioprofissional) {
                    $this->session->set_flashdata('message', 'Usuarío criado e vinculado ao profissional com sucesso.');
                } else {
                    $this->session->set_flashdata('message', 'Houve um erro ao vincular o usuário ao profissional. Tente novamente.');
                }*/
            } else {
                //$this->session->set_flashdata('message', 'Houve um erro ao criar o usuário. Tente novamente.');
            }
        } else {
            //$this->session->set_flashdata('message', 'Não foi encontrado profissional com o código informado ou o profissional não possui CPF cadastrado.');
        }

        /* Redirect to edit page */
        //redirect('admin/profissionais/edit/' . $id, 'refresh');
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
        $this->breadcrumbs->unshift(2, lang('menu_profissionais_edit'), 'admin/profissionais/edit');
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        /* Load Data */
        $profissional = $this->profissional_model->get_by_id($id);
        $unidadeshospitalares = $this->_get_unidadeshospitalares();
        $setores = array();

        /* Validate form input */
        //$this->form_validation->set_rules('unidadehospitalar_id', 'lang:profissionais_unidadehospitalar', 'required');
        $this->form_validation->set_rules('setor_id', 'lang:profissionais_setor', 'required');

        if (isset($_POST) && !empty($_POST)) {
            if ($this->form_validation->run() == true) {
                $setor_id = $this->input->post('setor_id');
                $data = array(
                    'setor_id' => $setor_id,
                    'profissional_id' => $id,
                );

                $this->load->model('cemerge/profissionalsetor_model');

                if (empty($this->profissionalsetor_model->get_where(array('setor_id' => $setor_id, 'profissional_id' =>$id)))) {
                    if ($this->profissionalsetor_model->insert($data)) {
                        $this->session->set_flashdata('message', 'Profissional vinculado ao setor com sucesso.');
                    } else {
                        $this->session->set_flashdata('message', 'Houve um erro ao vincular o profissional ao setor.');
                    }
                } else {
                    $this->session->set_flashdata('message', 'O profissional já é vinculado a este setor.');
                }
                redirect('admin/especializacoes/', 'refresh');
            }
        }

        // display the edit user form
        $this->data['csrf'] = $this->_get_csrf_nonce();

        // set the flash data error message if there is one
        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

        // pass the profissional to the view
        $this->data['profissional'] = $profissional;

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
        $this->template->admin_render('admin/profissionais/linktosector', $this->data);
        return true;
    }

    public function unlinkfromsector($profissional_id, $setor_id)
    {
        if (!$this->ion_auth->logged_in()) {
            $this->session->set_flashdata('message', 'Você deve estar autenticado para listar os setores.');
            redirect('auth/login', 'refresh');
        }
        if (!$this->ion_auth->in_group($this->_admin_groups)) {
            $this->session->set_flashdata('message', 'O acesso &agrave; este recurso não é permitido ao seu perfil de usuário.');
            redirect('admin/dashboard', 'refresh');
        }

        $profissional_id = (int) $profissional_id;
        $setor_id = (int) $setor_id;

        // Checar se o profissional possui plantões ativos no setor

        $this->load->model('cemerge/profissionalsetor_model');
        $success = $this->profissionalsetor_model->delete(array('profissional_id' => $profissional_id, 'setor_id' => $setor_id));

        if ($success != false) {
            $this->session->set_flashdata('message', 'Profissional desvinculado do setor com sucesso.');
        } else {
            $this->session->set_flashdata('message', 'Houve um problema ao desvincular o profissional do setor.');
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
        $this->breadcrumbs->unshift(2, lang('menu_profissionais_edit'), 'admin/profissionais/edit');
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        /* Load Data */
        $profissional = $this->profissional_model->get_by_id($id);
        // Vínculos
        $vinculos = $this->_get_vinculos();
        //$groups        = $this->ion_auth->groups()->result_array();
        //$currentGroups = $this->ion_auth->get_users_groups($id)->result();

        /* Validate form input */
        $this->form_validation->set_rules('registro', 'lang:profissionais_registro', 'required');
        $this->form_validation->set_rules('nome', 'lang:profissionais_nome', 'required');
        $this->form_validation->set_rules('nomecurto', 'lang:profissionais_nomecurto', 'required');
        $this->form_validation->set_rules('matricula', 'lang:profissionais_matricula', 'required');
        $this->form_validation->set_rules('vinculo', 'lang:profissionais_vinculo', 'required');
        $this->form_validation->set_rules('email', 'lang:profissionais_email', 'required|valid_email');
        $this->form_validation->set_rules('cpf', 'lang:profissionais_cpf', 'required');
        $this->form_validation->set_rules('rg', 'lang:profissionais_rg', 'required');
        $this->form_validation->set_rules('orgao_expeditor_rg', 'lang:orgao_expeditor_rg', 'required');
        //$this->form_validation->set_rules('active', 'lang:edit_user_validation_company_label', 'required');

        if (isset($_POST) and !empty($_POST)) {
            /*if ($this->ion_auth->is_admin()) {
                if ($this->_valid_csrf_nonce() === false or $id != $this->input->post('id')) {
                    show_error($this->lang->line('error_csrf'));
                }*/

                if ($this->form_validation->run() == true) {
                    $data = array(
                        'registro' => $this->input->post('registro'),
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

                    if ($this->profissional_model->update($profissional->id, $data)) {
                        $this->session->set_flashdata('message', 'Profissional atualizado com sucesso.');
                        redirect('admin/especializacoes/', 'refresh');

                    } else {
                        $this->session->set_flashdata('message', $this->ion_auth->errors());
                    }
                }
            /*} else {
                $this->session->set_flashdata('message', 'Somente administradores podem alterar dados de profissionais.');
            }*/
        }

        // display the edit user form
        $this->data['csrf'] = $this->_get_csrf_nonce();

        // set the flash data error message if there is one
        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

        // pass the profissional to the view
        $this->data['profissional'] = $profissional;

        $this->data['registro'] = array(
            'name'  => 'registro',
            'id'    => 'registro',
            'type'  => 'text',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('registro', $profissional->registro)
        );
        $this->data['matricula'] = array(
            'name'  => 'matricula',
            'id'    => 'matricula',
            'type'  => 'text',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('matricula', $profissional->matricula)
        );
        $this->data['nome'] = array(
            'name'  => 'nome',
            'id'    => 'nome',
            'type'  => 'text',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('nome', $profissional->nome)
        );
        $this->data['nomecurto'] = array(
            'name'  => 'nomecurto',
            'id'    => 'nomecurto',
            'type'  => 'text',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('nomecurto', $profissional->nomecurto)
        );
        $this->data['vinculo'] = array(
            'name'  => 'vinculo',
            'id'    => 'vinculo',
            'type'  => 'select',
            'class' => 'form-control',
            'selected' => $this->form_validation->set_value('vinculo', $profissional->vinculo_id),
            'options' => $vinculos,
        );
        $this->data['email'] = array(
            'name'  => 'email',
            'id'    => 'email',
            'type'  => 'text',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('email', $profissional->email)
        );
        $this->data['cpf'] = array(
            'name'  => 'cpf',
            'id'    => 'cpf',
            'type'  => 'text',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('cpf', $profissional->cpf)
        );
        $this->data['rg'] = array(
            'name'  => 'rg',
            'id'    => 'rg',
            'type'  => 'text',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('rg', $profissional->rg)
        );
        $this->data['orgao_expeditor_rg'] = array(
            'name'  => 'orgao_expeditor_rg',
            'id'    => 'orgao_expeditor_rg',
            'type'  => 'text',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('orgao_expeditor_rg', $profissional->orgao_expeditor_rg)
        );
        $this->data['active'] = array(
            'name'  => 'active',
            'id'    => 'active',
            'type'  => 'checkbox',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('active', $profissional->active)
        );

        /* Load Template */
        $this->template->admin_render('admin/profissionais/edit', $this->data);
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
        $this->load->model('cemerge/usuarioprofissional_model');

        /* Breadcrumbs */
        $this->breadcrumbs->unshift(2, lang('menu_users_profile'), 'admin/profissionais/view');
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        /* Data */
        $id = (int) $id;

        $this->data['profissional'] = $this->profissional_model->get_by_id($id);
        $this->data['profissional']->usuario = array();
        $usuario_profissional = $this->usuarioprofissional_model->get_where(array('profissional_id' => $id));
        if (!empty($usuario_profissional)) {
            $this->data['profissional']->usuario = $usuario_profissional[0];
            if (sizeof($usuario_profissional) > 1) {
                // O profissional está vinculado a mais de um usuário
                $this->session->set_flashdata('message', 'O profissional está vinculado a mais de um usuário. Isso pode causar erros. Favor solicitar a correção ao SAC.');
            }
        } else {
            // Não há vínculo entre profissional e usuário
            $this->session->set_flashdata('message', 'O profissional não está vinculado a nenhum usuário. Favor corrigir, caso seja necessário.');
        }
        $this->data['profissional']->setores = $this->setor_model->get_setores_por_profissional($id);
        $this->data['profissional']->setorescoordena = $this->setor_model->get_setores_coordenados_por_profissional($id);

        $this->data['coordenador'] = new stdClass();
        $this->data['coordenador']->setorescoordena = $this->setor_model->get_setores_coordenados_por_usuario($this->ion_auth->user()->row()->id);

        //var_dump($this->data['coordenador']->setorescoordena);exit;

        /* Load Template */
        $this->template->admin_render('admin/profissionais/view', $this->data);
    }

    public function profissionaisporunidade($unidadehospitalar_id)
    {
        $unidadehospitalar_id = (int) $unidadehospitalar_id;
        
        if ($unidadehospitalar_id and $unidadehospitalar_id != 0) {
            $profissionais = $this->profissional_model->get_profissionais_por_unidade_hospitalar($unidadehospitalar_id);
        }
        array_unshift($profissionais, ['id' => '', 'nome' => 'Todos os Profissionais']);

        echo json_encode($profissionais);
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
