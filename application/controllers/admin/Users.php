<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends Admin_Controller
{
    private $_permitted_groups = array('admin', 'profissionais', 'coordenadorplantao', 'sac', 'sad');
    private $_admin_groups = array('admin', 'sac', 'sad');

    public function __construct()
    {
        parent::__construct($this->_permitted_groups);

        /* Load :: Common */
        $this->lang->load('admin/users');

        /* Title Page :: Common */
        $this->page_title->push(lang('menu_users'));
        $this->data['pagetitle'] = $this->page_title->show();

        /* Breadcrumbs :: Common */
        $this->breadcrumbs->unshift(1, lang('menu_users'), 'admin/users');
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
        $first_name = trim($this->input->post('first_name'));
        $email = trim($this->input->post('email'));

        if ($first_name or $email) {
            $where = array();
            if ($first_name) {
                $where['first_name'] = $first_name;
            }
            if ($email) {
                $where['email'] = $email;
            }
            $this->data['users'] = $this->ion_auth->get_by_attribute($where);
            foreach ($this->data['users'] as $k => $user) {
                $this->data['users'][$k]->groups = $this->ion_auth->get_users_groups($user->id)->result();
            }
        } else {
            $this->data['users'] = array();
        }

        $this->data['first_name'] = array(
            'name'  => 'first_name',
            'id'    => 'first_name',
            'type'  => 'text',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('first_name'),
        );
        $this->data['email'] = array(
            'name'  => 'email',
            'id'    => 'email',
            'type'  => 'text',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('email'),
        );

        /* Load Template */
        $this->template->admin_render('admin/users/index', $this->data);
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
        $this->breadcrumbs->unshift(2, lang('menu_users_create'), 'admin/users/create');
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        /* Variables */
        $tables = $this->config->item('tables', 'ion_auth');

        /* Validate form input */
        $this->form_validation->set_rules('first_name', 'lang:users_firstname', 'required');
        $this->form_validation->set_rules('last_name', 'lang:users_lastname', 'required');
        $this->form_validation->set_rules('email', 'lang:users_email', 'required|valid_email|is_unique['.$tables['users'].'.email]');
        $this->form_validation->set_rules('phone', 'lang:users_phone', 'required');
        $this->form_validation->set_rules('company', 'lang:users_company', 'required');
        $this->form_validation->set_rules('password', 'lang:users_password', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
        $this->form_validation->set_rules('password_confirm', 'lang:users_password_confirm', 'required');

        if ($this->form_validation->run() == true) {
            $username = strtolower($this->input->post('first_name')) . ' ' . strtolower($this->input->post('last_name'));
            $email    = strtolower($this->input->post('email'));
            $password = $this->input->post('password');

            $additional_data = array(
                'first_name' => $this->input->post('first_name'),
                'last_name'  => $this->input->post('last_name'),
                'company'    => $this->input->post('company'),
                'phone'      => $this->input->post('phone'),
            );
        }

        if ($this->form_validation->run() == true && $this->ion_auth->register($username, $password, $email, $additional_data)) {
            $this->session->set_flashdata('message', $this->ion_auth->messages());
            redirect('admin/users', 'refresh');
        } else {
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

            $this->data['first_name'] = array(
                'name'  => 'first_name',
                'id'    => 'first_name',
                'type'  => 'text',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('first_name'),
            );
            $this->data['last_name'] = array(
                'name'  => 'last_name',
                'id'    => 'last_name',
                'type'  => 'text',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('last_name'),
            );
            $this->data['email'] = array(
                'name'  => 'email',
                'id'    => 'email',
                'type'  => 'email',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('email'),
            );
            $this->data['company'] = array(
                'name'  => 'company',
                'id'    => 'company',
                'type'  => 'text',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('company'),
            );
            $this->data['phone'] = array(
                'name'  => 'phone',
                'id'    => 'phone',
                'type'  => 'tel',
                'pattern' => '^((\+\d{1,3}(-| )?\(?\d\)?(-| )?\d{1,5})|(\(?\d{2,6}\)?))(-| )?(\d{3,4})(-| )?(\d{4})(( x| ext)\d{1,5}){0,1}$',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('phone'),
            );
            $this->data['password'] = array(
                'name'  => 'password',
                'id'    => 'password',
                'type'  => 'password',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('password'),
            );
            $this->data['password_confirm'] = array(
                'name'  => 'password_confirm',
                'id'    => 'password_confirm',
                'type'  => 'password',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('password_confirm'),
            );

            /* Load Template */
            $this->template->admin_render('admin/users/create', $this->data);
        }
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
        $this->breadcrumbs->unshift(2, lang('menu_users_edit'), 'admin/users/edit');
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        /* Load Data */
        $user = $this->ion_auth->user($id)->row();
        $this->load->model('cemerge/unidadehospitalar_model');
        $unidadeshospitalares = $this->unidadehospitalar_model->get_unidadeshospitalares();
        $setores = array();

        /* Validate form input */
        $this->form_validation->set_rules('unidadehospitalar_id', 'lang:users_unidadehospitalar', 'required');
        $this->form_validation->set_rules('setor_id', 'lang:users_setor', 'required');

        if (isset($_POST) && !empty($_POST)) {
            if ($this->form_validation->run() == true) {
                $setor_id = $this->input->post('setor_id');
                $coordenador = $this->input->post('coordenador');
                $data = array(
                    'setor_id' => $setor_id,
                    'user_id' => $id,
                    'coordenador' => $coordenador,
                );

                $this->load->model('cemerge/usuariosetor_model');

                if (empty($this->usuariosetor_model->get_where(array('setor_id' => $setor_id, 'user_id' =>$id)))) {
                    if ($this->usuariosetor_model->insert($data)) {
                        $this->session->set_flashdata('message', 'Usuário vinculado ao setor com sucesso.');

                        if ($this->ion_auth->is_admin()) {
                            redirect('admin/users/linktosector/' . $id, 'refresh');
                        } else {
                            redirect('admin', 'refresh');
                        }
                    } else {
                        $this->session->set_flashdata('message',  'Houve um erro ao vincular o usuário ao setor.');

                        if ($this->ion_auth->is_admin()) {
                            redirect('admin/users/linktosector/' . $id, 'refresh');
                        } else {
                            redirect('admin', 'refresh');
                        }
                    }
                } else {
                    $this->session->set_flashdata('message', 'O usuário já é vinculado a este setor.');

                    redirect('admin/users/linktosector/' . $id, 'refresh');
                }
            }
        }

        // display the edit user form
        $this->data['csrf'] = $this->_get_csrf_nonce();

        // set the flash data error message if there is one
        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

        // pass the user to the view
        $this->data['user'] = $user;

        // Obter
        $data['coordenador'] = 1;

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
        $this->data['coordenador'] = array(
            'name'  => 'coordenador',
            'id'    => 'coordenador',
            'type'  => 'checkbox',
            'class' => 'form-control',
            'value' => $data['coordenador']
        );

        /* Load Template */
        $this->template->admin_render('admin/users/linktosector', $this->data);
    }

    public function unlinkfromsector($user_id, $setor_id)
    {
        if (!$this->ion_auth->logged_in()) {
            $this->session->set_flashdata('message', 'Você deve estar autenticado para listar os setores.');
            redirect('auth/login', 'refresh');
        }
        if (!$this->ion_auth->in_group($this->_admin_groups)) {
            $this->session->set_flashdata('message', 'O acesso &agrave; este recurso não é permitido ao seu perfil de usuário.');
            redirect('admin/dashboard', 'refresh');
        }

        $user_id = (int) $user_id;
        $setor_id = (int) $setor_id;

        $this->load->model('cemerge/usuariosetor_model');
        $success = $this->usuariosetor_model->delete(array('user_id' => $user_id, 'setor_id' => $setor_id));

        if ($success != false) {
            $this->session->set_flashdata('message', 'Usuário desvinculado do setor com sucesso.');
        } else {
            $this->session->set_flashdata('message', 'Houve um problema ao desvincular o usuário do setor.');
        }

        /* Redirect */
        redirect('admin/setores/view/'.$setor_id, 'refresh');
    }

    public function sendmail($user_id)
    {
        $user_id = (int) $user_id;

        /* Initialize email */
        $ci_mail_config = $this->config->item('mail');
        $this->email->initialize($ci_mail_config);

        /* Load data */
        $user = $this->ion_auth->user($user_id)->row();

        $subject = 'CEMERGE - Notificação';

        $message = 'Teste de envio de e-mail.';

        $this->email->clear();
        $this->email->from(
            $this->config->item('admin_email', 'ion_auth'),
            $this->config->item('site_title', 'ion_auth')
        );
        $this->email->to($user->email);
        $this->email->subject($subject);
        $this->email->message($message);

        $email_enviado = $this->email->send();

        if ($email_enviado) {
            $this->session->set_flashdata('message', 'E-mail enviado com sucesso.');
        } else {
            $this->session->set_flashdata('message', 'Ocorreu um erro ao enviar o e-mail.');
        }

        /* Redirect */
        redirect('admin/users/edit/'.$user_id, 'refresh');
    }

    public function delete()
    {
        if (!$this->ion_auth->logged_in()) {
            $this->session->set_flashdata('message', 'Você deve estar autenticado para listar os setores.');
            redirect('auth/login', 'refresh');
        }
        if (!$this->ion_auth->is_admin()) {
            $this->session->set_flashdata('message', 'O acesso &agrave; este recurso não é permitido ao seu perfil de usuário.');
            redirect('admin/dashboard', 'refresh');
        }

        /* Load Template */
        $this->template->admin_render('admin/users/delete', $this->data);
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

        /*
        if (!$this->ion_auth->logged_in() or ( ! $this->ion_auth->is_admin() && ! ($this->ion_auth->user()->row()->id == $id))) {
            $this->session->set_flashdata('message', 'O acesso &agrave; este recurso não é permitido ao seu perfil de usuário.');
            redirect('admin/dashboard', 'refresh');
        }
        */

        /* Breadcrumbs */
        $this->breadcrumbs->unshift(2, lang('menu_users_edit'), 'admin/users/edit');
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        /* Data */
        $user = $this->ion_auth->user($id)->row();
        $groups = $this->ion_auth->groups()->result_array();
        $currentGroups = $this->ion_auth->get_users_groups($id)->result();

        /* Validate form input */
        $this->form_validation->set_rules('first_name', 'lang:users_firstname', 'required');
        $this->form_validation->set_rules('last_name', 'lang:users_lastname', 'required');
        $this->form_validation->set_rules('phone', 'lang:users_phone', 'required');
        $this->form_validation->set_rules('email', 'lang:users_email', 'required|valid_email');

        if (isset($_POST) && ! empty($_POST)) {
            if ($this->_valid_csrf_nonce() === false OR $id != $this->input->post('id')) {
                show_error($this->lang->line('error_csrf'));
            }

            if ($this->input->post('password')) {
                $this->form_validation->set_rules('password', $this->lang->line('users_password'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
                $this->form_validation->set_rules('password_confirm', $this->lang->line('users_password_confirm'), 'required');
            }

            if ($this->form_validation->run() == true) {
                $data = array(
                    'first_name' => $this->input->post('first_name'),
                    'last_name'  => $this->input->post('last_name'),
                    'email'    => $this->input->post('email'),
                    'phone'      => $this->input->post('phone')
                );

                if ($this->input->post('password')) {
                    $data['password'] = $this->input->post('password');
                }

                if ($this->ion_auth->is_admin()) {
                    $groupData = $this->input->post('groups');

                    if (isset($groupData) && !empty($groupData)) {
                        $this->ion_auth->remove_from_group('', $id);

                        foreach ($groupData as $grp) {
                            $this->ion_auth->add_to_group($grp, $id);
                        }
                    }
                }

                if ($this->ion_auth->update($user->id, $data)) {
                    $this->session->set_flashdata('message', $this->ion_auth->messages());

                    if ($this->ion_auth->is_admin()) {
                        redirect('admin/users', 'refresh');
                    } else {
                        redirect('admin', 'refresh');
                    }
                } else {
                    $this->session->set_flashdata('message', $this->ion_auth->errors());

                    if ($this->ion_auth->is_admin()) {
                        redirect('auth', 'refresh');
                    } else {
                        redirect('/', 'refresh');
                    }
                }
            }
        }

        // display the edit user form
        $this->data['csrf'] = $this->_get_csrf_nonce();

        // set the flash data error message if there is one
        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

        // pass the user to the view
        $this->data['user']          = $user;
        $this->data['groups']        = $groups;
        $this->data['currentGroups'] = $currentGroups;

        $this->data['first_name'] = array(
            'name'  => 'first_name',
            'id'    => 'first_name',
            'type'  => 'text',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('first_name', $user->first_name)
        );
        $this->data['last_name'] = array(
            'name'  => 'last_name',
            'id'    => 'last_name',
            'type'  => 'text',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('last_name', $user->last_name)
        );
        $this->data['email'] = array(
            'name'  => 'email',
            'id'    => 'email',
            'type'  => 'text',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('email', $user->email)
        );
        $this->data['phone'] = array(
            'name'  => 'phone',
            'id'    => 'phone',
            'type'  => 'tel',
            'pattern' => '^((\+\d{1,3}(-| )?\(?\d\)?(-| )?\d{1,5})|(\(?\d{2,6}\)?))(-| )?(\d{3,4})(-| )?(\d{4})(( x| ext)\d{1,5}){0,1}$',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('phone', $user->phone)
        );
        $this->data['password'] = array(
            'name' => 'password',
            'id'   => 'password',
            'class' => 'form-control',
            'type' => 'password'
        );
        $this->data['password_confirm'] = array(
            'name' => 'password_confirm',
            'id'   => 'password_confirm',
            'class' => 'form-control',
            'type' => 'password'
        );

        /* Load Template */
        $this->template->admin_render('admin/users/edit', $this->data);
    }

    function activate($id, $code = FALSE)
    {
        $id = (int) $id;

        if ($code !== false) {
            $activation = $this->ion_auth->activate($id, $code);
        } else if ($this->ion_auth->is_admin()) {
            $activation = $this->ion_auth->activate($id);
        }

        if ($activation) {
            $this->session->set_flashdata('message', $this->ion_auth->messages());
            redirect('admin/users', 'refresh');
        } else {
            $this->session->set_flashdata('message', $this->ion_auth->errors());
            redirect('auth/forgot_password', 'refresh');
        }
    }

    public function deactivate($id = null)
    {
        if (!$this->ion_auth->logged_in() OR ! $this->ion_auth->is_admin()) {
            return show_error('You must be an administrator to view this page.');
        }

        /* Breadcrumbs */
        $this->breadcrumbs->unshift(2, lang('menu_users_deactivate'), 'admin/users/deactivate');
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        /* Validate form input */
        $this->form_validation->set_rules('confirm', 'lang:deactivate_validation_confirm_label', 'required');
        $this->form_validation->set_rules('id', 'lang:deactivate_validation_user_id_label', 'required|alpha_numeric');

        $id = (int) $id;

        if ($this->form_validation->run() === false) {
            $user = $this->ion_auth->user($id)->row();

            $this->data['csrf']       = $this->_get_csrf_nonce();
            $this->data['id']         = (int) $user->id;
            $this->data['firstname']  = ! empty($user->first_name) ? htmlspecialchars($user->first_name, ENT_QUOTES, 'UTF-8') : NULL;
            $this->data['lastname']   = ! empty($user->last_name) ? ' '.htmlspecialchars($user->last_name, ENT_QUOTES, 'UTF-8') : NULL;

            /* Load Template */
            $this->template->admin_render('admin/users/deactivate', $this->data);
        } else {
            if ($this->input->post('confirm') == 'yes') {
                if ($this->_valid_csrf_nonce() === false OR $id != $this->input->post('id')) {
                    show_error($this->lang->line('error_csrf'));
                }

                if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
                    $this->ion_auth->deactivate($id);
                }
            }

            redirect('admin/users', 'refresh');
        }
    }

    public function profile($id)
    {
        if (!$this->ion_auth->logged_in()) {
            $this->session->set_flashdata('message', 'Você deve estar autenticado para acessar esta área.');
            redirect('auth/login', 'refresh');
        }

        /* Breadcrumbs */
        $this->breadcrumbs->unshift(2, lang('menu_users_profile'), 'admin/groups/profile');
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        /* Data */
        $id = (int) $id;

        if ($this->ion_auth->user()->row()->id != $id && !$this->ion_auth->in_group($this->_admin_groups)) {
            $this->session->set_flashdata('message', 'Somente administradores podem acessar perfis de outros usuários.');
            redirect('admin/dashboard', 'refresh');
        }

        $this->data['user_info'] = $this->ion_auth->user($id)->result();
        foreach ($this->data['user_info'] as $k => $user) {
            $this->data['user_info'][$k]->groups = $this->ion_auth->get_users_groups($user->id)->result();
        }

        /* Load Template */
        $this->template->admin_render('admin/users/profile', $this->data);
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
