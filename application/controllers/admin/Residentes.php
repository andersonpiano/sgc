<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Residentes extends Admin_Controller
{
    private $_permitted_groups = array('admin', 'coordenadorplantao', 'sac', 'sad', 'residentes');
    private $_admin_groups = array('admin', 'coordenadorplantao', 'sac', 'sad');

    public function __construct()
    {
        parent::__construct($this->_permitted_groups);

        /* Load :: Common */
        $this->load->model('cemerge/residente_model');
        $this->lang->load('admin/residentes');

        /* Title Page */
        $this->page_title->push(lang('menu_residentes'));
        $this->data['pagetitle'] = $this->page_title->show();

        /* Breadcrumbs :: Common */
        $this->breadcrumbs->unshift(1, lang('menu_residentes'), 'admin/residentes');
    }

    public function index()
    {
        if (!$this->ion_auth->logged_in() or !$this->ion_auth->in_group($this->_permitted_groups)) {
            redirect('auth/login', 'refresh');
        } else {
            /* Breadcrumbs */
            $this->data['breadcrumb'] = $this->breadcrumbs->show();

            /* Validate form input */
            $this->form_validation->set_rules('nome', 'lang:residentes_nome', 'required');

            if ($this->form_validation->run() == true) {
                $nome = $this->input->post('nome');
                
                /* Residente */
                $this->data['residentes'] = $this->residente_model->get_like('nome', $nome, 'nome');
            } else {
                $this->data['residentes'] = array();
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
            $this->template->admin_render('admin/residentes/index', $this->data);
        }
    }

    public function create()
    {
        if (!$this->ion_auth->logged_in() or !$this->ion_auth->is_admin()) {
            $this->session->set_flashdata('message', 'Apenas administradores podem cadastrar residentes.');
            redirect('admin/', 'refresh');
        } else {
            /* Breadcrumbs */
            $this->breadcrumbs->unshift(2, lang('menu_residentes_create'), 'admin/residentes/create');
            $this->data['breadcrumb'] = $this->breadcrumbs->show();

            /* Variables */
            $tables = $this->config->item('tables', 'ion_auth');

            /* Validate form input */
            $this->form_validation->set_rules('registro', 'lang:residentes_registro', 'required|is_unique[residentes.registro]');
            $this->form_validation->set_rules('nome', 'lang:residentes_nome', 'required');
            $this->form_validation->set_rules('nomecurto', 'lang:residentes_nomecurto', 'required');
            $this->form_validation->set_rules('email', 'lang:residentes_email', 'required|valid_email|is_unique[residentes.email]');
            $this->form_validation->set_rules('cpf', 'lang:residentes_cpf', 'required|is_unique[residentes.cpf]');
            $this->form_validation->set_rules('rg', 'lang:residentes_rg', 'required');
            $this->form_validation->set_rules('orgao_expeditor_rg', 'lang:residentes_orgao_expeditor_rg', 'required');

            if ($this->form_validation->run() == true) {
                $registro = $this->input->post('registro');
                $nome = $this->input->post('nome');
                $nomecurto = $this->input->post('nomecurto');
                $email = $this->input->post('email');
                $cpf = $this->input->post('cpf');
                $rg = $this->input->post('rg');
                $orgao_expeditor_rg = $this->input->post('orgao_expeditor_rg');
                $active = $this->input->post('active');

                $additional_data = array(
                    'registro' => $this->input->post('registro'),
                    'nome' => $this->input->post('nome'),
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
                && $this->residente_model->insert($additional_data)
            ) {
                $this->session->set_flashdata('message', $this->ion_auth->messages());
                redirect('admin/residentes', 'refresh');
            } else {
                $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

                $this->data['registro'] = array(
                    'name'  => 'registro',
                    'id'    => 'registro',
                    'type'  => 'text',
                    'class' => 'form-control',
                    'value' => $this->form_validation->set_value('registro'),
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
                $this->template->admin_render('admin/residentes/create', $this->data);
            }
        }
    }

    public function createuser($id)
    {
        if (!$this->ion_auth->logged_in() or !$this->ion_auth->is_admin()) {
            $this->session->set_flashdata('message', 'Apenas administradores podem criar usuários a partir de um residente.');
        } else {
            $id = (int) $id;

            /* Load Data */
            $residente = $this->residente_model->get_by_id($id);

            if ($residente and trim($residente->cpf) != '0' and trim($residente->cpf) != '') {
                $username = strtolower($residente->nomecurto);
                $password = $residente->cpf;
                $email = $residente->email;
                $additional_data = array(
                            'first_name' => $residente->nome,
                            );
                $group = array('3'); // Sets user to residente group

                // Validate status
                if ($residente->active == 0) {
                    $this->session->set_flashdata('message', 'O residente está inativo. Ative-o primeiro e depois crie seu usuário.');
                    redirect('admin/residentes/edit/' . $id, 'refresh');
                }

                // Validate email
                if (trim($residente->email) == "@") {
                    $this->session->set_flashdata('message', 'O residente não possui um e-mail válido cadastrado.');
                    redirect('admin/residentes/edit/' . $id, 'refresh');
                }

                // User exists?
                if ($this->ion_auth->email_check($email)) {
                    $this->session->set_flashdata('message', 'Já existe um usuário criado para este residente. Favor editá-lo.');
                    redirect('admin/residentes/edit/' . $id, 'refresh');
                }

                $userCreated = $this->ion_auth->register($username, $password, $email, $additional_data, $group);

                if ($userCreated) {
                    // Vincular usuário ao residente
                    $this->load->model('cemerge/usuarioresidente_model');
                    $usuarioresidente = $this->usuarioresidente_model->insert(array('residente_id' => $id, 'user_id' => $userCreated));
                    if ($usuarioresidente) {
                        $this->session->set_flashdata('message', 'Usuário criado e vinculado ao residente com sucesso.');
                    } else {
                        $this->session->set_flashdata('message', 'Houve um erro ao vincular o usuário ao residente. Tente novamente.');
                    }
                } else {
                    $this->session->set_flashdata('message', 'Houve um erro ao criar o usuário. Tente novamente.');
                }
            } else {
                $this->session->set_flashdata('message', 'Não foi encontrado residente com o código informado ou o residente não possui CPF cadastrado.');
            }
        }

        /* Redirect to edit page */
        redirect('admin/residentes/edit/' . $id, 'refresh');
    }

    public function register()
    {
        if (!$this->ion_auth->logged_in() or !$this->ion_auth->in_group(array('residentes', 'admin'))) {
            $this->session->set_flashdata('message', 'Somente residentes podem registrar suas frequências.');
            redirect('auth', 'refresh');
        }

        /* Breadcrumbs */
        $this->breadcrumbs->unshift(2, lang('menu_residentes_register'), 'admin/residentes/register');
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        /* Load Data */
        if (!isset($_SESSION['residente_id'])) {
            $this->session->set_flashdata('message', 'Não há residente vinculado ao usuário logado. Favor fazer logon no sistema com um usuário do grupo de residentes.');
            redirect('admin/dashboard', 'refresh');
        }
        $residente_id = $_SESSION['residente_id'];
        $residente = $this->residente_model->get_by_id($residente_id);
        $unidadeshospitalares = $this->_get_unidadeshospitalares();

        /* Validate form input */
        $this->form_validation->set_rules('unidadehospitalar_id', 'lang:residentes_unidadehospitalar', 'required');
        //$this->form_validation->set_rules('latitude', 'lang:residentes_latitude', 'required');
        //$this->form_validation->set_rules('longitude', 'lang:residentes_longitude', 'required');

        if (isset($_POST) && !empty($_POST)) {
            if ($this->form_validation->run() == true) {
                $unidadehospitalar_id = $this->input->post('unidadehospitalar_id');
                $unidadehospitalar = $this->unidadehospitalar_model->get_by_id($unidadehospitalar_id);
                $latitude = $this->input->post('latitude');
                $longitude = $this->input->post('longitude');
                
                $this->load->helper('distances');
                $distancia = distance($latitude, $longitude, $unidadehospitalar->latitude, $unidadehospitalar->longitude);
                $valido = false;
                if ($distancia <= 0.3) {
                    $valido = true;
                }

                $data = array(
                    'unidadehospitalar_id' => $unidadehospitalar_id,
                    'datahora' => date('Y-m-d H:i:s'),
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                    'residente_id' => $residente_id,
                    'valido' => $valido,
                );

                $this->load->model('cemerge/frequenciaresidente_model');

                if ($this->frequenciaresidente_model->insert($data)) {
                    $this->session->set_flashdata('message', 'Frequência registrada com sucesso.');
                } else {
                    $this->session->set_flashdata('message', 'Houve um erro ao registrar a frequência.');
                }
                redirect('admin/residentes/register', 'refresh');
            }
        }

        // display the edit user form
        $this->data['csrf'] = $this->_get_csrf_nonce();

        // set the flash data error message if there is one
        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

        // pass the residente to the view
        $this->data['residente'] = $residente;

        $this->data['unidadehospitalar_id'] = array(
            'name'  => 'unidadehospitalar_id',
            'id'    => 'unidadehospitalar_id',
            'type'  => 'select',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('unidadehospitalar_id'),
            'options' => $unidadeshospitalares,
        );

        /* Load Template */
        $this->template->admin_render('admin/residentes/register', $this->data);
    }

    public function linktounit($id)
    {
        $id = (int) $id;

        if (!$this->ion_auth->logged_in() or !$this->ion_auth->in_group($this->_permitted_groups)) {
            $this->session->set_flashdata('message', 'Somente administradores podem vincular residentes a unidades hospitalares.');
            redirect('auth', 'refresh');
        }

        /* Breadcrumbs */
        $this->breadcrumbs->unshift(2, lang('menu_residentes_edit'), 'admin/residentes/edit');
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        /* Load Data */
        $residente = $this->residente_model->get_by_id($id);
        $unidadeshospitalares = $this->_get_unidadeshospitalares();
        //$setores = array();

        /* Validate form input */
        $this->form_validation->set_rules('unidadehospitalar_id', 'lang:residentes_unidadehospitalar', 'required');

        if (isset($_POST) && !empty($_POST)) {
            if ($this->form_validation->run() == true) {
                $unidadehospitalar_id = $this->input->post('unidadehospitalar_id');
                $data = array(
                    'unidadehospitalar_id' => $unidadehospitalar_id,
                    'residente_id' => $id,
                );

                $this->load->model('cemerge/residenteunidadehospitalar_model');

                if (empty($this->residenteunidadehospitalar_model->get_where(array('setor_id' => $setor_id, 'residente_id' =>$id)))) {
                    if ($this->residenteunidadehospitalar_model->insert($data)) {
                        $this->session->set_flashdata('message', 'Residente vinculado à unidade hospitalar com sucesso.');

                        if ($this->ion_auth->is_admin()) {
                            redirect('admin/residentes', 'refresh');
                        } else {
                            redirect('admin', 'refresh');
                        }
                    } else {
                        $this->session->set_flashdata('message', 'Houve um erro ao vincular o residente ao setor.');

                        if ($this->ion_auth->is_admin()) {
                            redirect('admin/residentes', 'refresh');
                        } else {
                            redirect('admin', 'refresh');
                        }
                    }
                } else {
                    $this->session->set_flashdata('message', 'O residente já é vinculado a este setor.');

                    redirect('admin/residentes', 'refresh');
                }
            }
        }

        // display the edit user form
        $this->data['csrf'] = $this->_get_csrf_nonce();

        // set the flash data error message if there is one
        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

        // pass the residente to the view
        $this->data['residente'] = $residente;

        $this->data['unidadehospitalar_id'] = array(
            'name'  => 'unidadehospitalar_id',
            'id'    => 'unidadehospitalar_id',
            'type'  => 'select',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('unidadehospitalar_id'),
            'options' => $unidadeshospitalares,
        );

        /* Load Template */
        $this->template->admin_render('admin/residentes/linktounit', $this->data);
    }

    public function unlinkfromunit($residente_id, $unidadehospitalar_id)
    {
        if (!$this->ion_auth->logged_in() or !$this->ion_auth->in_group($this->_permitted_groups)) {
            $this->session->set_flashdata('message', 'Somente administradores podem desvincular residente de unidades hospitalares.');
            redirect('auth', 'refresh');
        }

        $residente_id = (int) $residente_id;
        $unidadehospitalar_id = (int) $unidadehospitalar_id;

        $this->load->model('cemerge/residenteunidadehospitalar_model');
        $success = $this->residenteunidadehospitalar_model->delete(array('residente_id' => $residente_id, 'unidadehospitalar_id' => $unidadehospitalar_id));

        if ($success != false) {
            $this->session->set_flashdata('message', 'Residente desvinculado do setor com sucesso.');
        } else {
            $this->session->set_flashdata('message', 'Houve um problema ao desvincular o residente do setor.');
        }

        /* Redirect */
        redirect('admin/residentes/edit/'.$residente_id, 'refresh');
    }

    public function edit($id)
    {
        $id = (int) $id;

        if (!$this->ion_auth->logged_in() or !$this->ion_auth->in_group($this->_permitted_groups)) {
            $this->session->set_flashdata('message', 'Seu perfil não permite a edição de residentes.');
            redirect('/admin', 'refresh');
        }

        /* Breadcrumbs */
        $this->breadcrumbs->unshift(2, lang('menu_residentes_edit'), 'admin/residentes/edit');
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        /* Load Data */
        $residente = $this->residente_model->get_by_id($id);
        //$groups        = $this->ion_auth->groups()->result_array();
        //$currentGroups = $this->ion_auth->get_users_groups($id)->result();

        /* Validate form input */
        $this->form_validation->set_rules('registro', 'lang:residentes_registro', 'required');
        $this->form_validation->set_rules('nome', 'lang:residentes_nome', 'required');
        $this->form_validation->set_rules('nomecurto', 'lang:residentes_nomecurto', 'required');
        $this->form_validation->set_rules('email', 'lang:residentes_email', 'required|valid_email');
        $this->form_validation->set_rules('cpf', 'lang:residentes_cpf', 'required');
        $this->form_validation->set_rules('rg', 'lang:residentes_rg', 'required');
        $this->form_validation->set_rules('orgao_expeditor_rg', 'lang:orgao_expeditor_rg', 'required');
        //$this->form_validation->set_rules('active', 'lang:edit_user_validation_company_label', 'required');

        if (isset($_POST) and !empty($_POST)) {
            if ($this->ion_auth->is_admin()) {
                if ($this->_valid_csrf_nonce() === false or $id != $this->input->post('id')) {
                    show_error($this->lang->line('error_csrf'));
                }

                if ($this->form_validation->run() == true) {
                    $data = array(
                        'registro' => $this->input->post('registro'),
                        'nome' => $this->input->post('nome'),
                        'nomecurto' => $this->input->post('nomecurto'),
                        'email' => $this->input->post('email'),
                        'cpf' => $this->input->post('cpf'),
                        'rg' => $this->input->post('rg'),
                        'orgao_expeditor_rg' => $this->input->post('orgao_expeditor_rg'),
                        'active' => $this->input->post('active')
                    );

                    if ($this->residente_model->update($residente->id, $data)) {
                        $this->session->set_flashdata('message', 'Residente atualizado com sucesso.');

                        if ($this->ion_auth->is_admin()) {
                            redirect('admin/residentes', 'refresh');
                        } else {
                            redirect('admin', 'refresh');
                        }
                    } else {
                        $this->session->set_flashdata('message', $this->ion_auth->errors());

                        if ($this->ion_auth->is_admin()) {
                            redirect('admin/residentes', 'refresh');
                        } else {
                            redirect('admin', 'refresh');
                        }
                    }
                }
            } else {
                $this->session->set_flashdata('message', 'Somente administradores podem alterar dados de residentes.');
            }
        }

        // display the edit user form
        $this->data['csrf'] = $this->_get_csrf_nonce();

        // set the flash data error message if there is one
        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

        // pass the residente to the view
        $this->data['residente'] = $residente;

        $this->data['registro'] = array(
            'name'  => 'registro',
            'id'    => 'registro',
            'type'  => 'text',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('registro', $residente->registro)
        );
        $this->data['nome'] = array(
            'name'  => 'nome',
            'id'    => 'nome',
            'type'  => 'text',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('nome', $residente->nome)
        );
        $this->data['nomecurto'] = array(
            'name'  => 'nomecurto',
            'id'    => 'nomecurto',
            'type'  => 'text',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('nomecurto', $residente->nomecurto)
        );
        $this->data['email'] = array(
            'name'  => 'email',
            'id'    => 'email',
            'type'  => 'text',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('email', $residente->email)
        );
        $this->data['cpf'] = array(
            'name'  => 'cpf',
            'id'    => 'cpf',
            'type'  => 'text',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('cpf', $residente->cpf)
        );
        $this->data['rg'] = array(
            'name'  => 'rg',
            'id'    => 'rg',
            'type'  => 'text',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('rg', $residente->rg)
        );
        $this->data['orgao_expeditor_rg'] = array(
            'name'  => 'orgao_expeditor_rg',
            'id'    => 'orgao_expeditor_rg',
            'type'  => 'text',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('orgao_expeditor_rg', $residente->orgao_expeditor_rg)
        );
        $this->data['active'] = array(
            'name'  => 'active',
            'id'    => 'active',
            'type'  => 'checkbox',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('active', $residente->active)
        );

        /* Load Template */
        $this->template->admin_render('admin/residentes/edit', $this->data);
    }

    public function view($id)
    {
        /* Load aditional models */
        $this->load->model('cemerge/unidadehospitalar_model');
        $this->load->model('cemerge/usuarioresidente_model');

        /* Breadcrumbs */
        $this->breadcrumbs->unshift(2, lang('menu_residentes_profile'), 'admin/residentes/view');
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        /* Data */
        $id = (int) $id;

        $this->data['residente'] = $this->residente_model->get_by_id($id);
        $this->data['residente']->usuario = array();
        $usuario_residente = $this->usuarioresidente_model->get_where(array('residente_id' => $id));
        if (!empty($usuario_residente)) {
            $this->data['residente']->usuario = $usuario_residente[0];
            if (sizeof($usuario_residente) > 1) {
                // O residente está vinculado a mais de um usuário
                $this->session->set_flashdata('message', 'O residente está vinculado a mais de um usuário. Isso pode causar erros. Favor solicitar a correção ao SAC.');
            }
        } else {
            // Não há vínculo entre residente e usuário
            $this->session->set_flashdata('message', 'O residente não está vinculado a nenhum usuário. Favor corrigir, caso seja necessário.');
        }
        $this->data['residente']->unidadeshospitalares = $this->unidadeshospitalares_model->get_unidades_por_residente($id);

        /* Load Template */
        $this->template->admin_render('admin/residentes/view', $this->data);
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
