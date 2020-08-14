<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends MY_Controller
{
    function __construct()
    {
        parent::__construct();

        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));

        $this->lang->load('auth');
    }

    function index()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        } else {
            redirect('/admin/dashboard', 'refresh');
        }
    }

    function login()
    {
        if (!$this->ion_auth->logged_in()) {
            /* Load */
            $this->load->config('admin/dp_config');
            $this->load->config('common/dp_config');

            /* Valid form */
            $this->form_validation->set_rules('identity', 'lang:auth_email', 'required');
            $this->form_validation->set_rules('password', 'lang:auth_password', 'required');

            /* Data */
            $this->data['title']               = $this->config->item('title');
            $this->data['title_lg']            = $this->config->item('title_lg');
            $this->data['auth_social_network'] = $this->config->item('auth_social_network');
            $this->data['forgot_password']     = $this->config->item('forgot_password');
            $this->data['new_membership']      = $this->config->item('new_membership');

            $destino = $this->input->get('destino', null);

            if ($this->form_validation->run() == true) {
                $remember = (bool) $this->input->post('remember');
                $destino = $this->input->post('destino');

                if ($this->ion_auth->login($this->input->post('identity'), $this->input->post('password'), $remember)) {
                    if (!$this->ion_auth->is_admin()) {
                        $this->session->set_flashdata('message', $this->ion_auth->messages());
                        //redirect('/', 'refresh');
                        if (is_null($destino) or empty($destino)) {
                            redirect('/admin/dashboard', 'refresh');
                        } else {
                            redirect($destino, 'refresh');
                        }
                    } else {
                        /* Data */
                        $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

                        /* Load Template */
                        //$this->template->auth_render('auth/choice', $this->data);
                        //redirect('/admin', 'refresh');
                        if (is_null($destino) or empty($destino)) {
                            redirect('/admin/dashboard', 'refresh');
                        } else {
                            redirect($destino, 'refresh');
                        }
                    }
                } else {
                    $this->session->set_flashdata('message', $this->ion_auth->errors());
                    if (is_null($destino)) {
                        redirect('auth/login', 'refresh');
                    } else {
                        redirect('auth/login?' . $destino, 'refresh');
                    }
                }
            } else {
                $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

                $this->data['identity'] = array(
                    'name'        => 'identity',
                    'id'          => 'identity',
                    'type'        => 'email',
                    'value'       => $this->form_validation->set_value('identity'),
                    'class'       => 'form-control',
                    'placeholder' => lang('auth_your_email')
                );
                $this->data['password'] = array(
                    'name'        => 'password',
                    'id'          => 'password',
                    'type'        => 'password',
                    'class'       => 'form-control',
                    'placeholder' => lang('auth_your_password')
                );
                $this->data['destino'] = $destino;

                /* Load Template */
                $this->template->auth_render('auth/login', $this->data);
            }
        } else {
            redirect('/', 'refresh');
        }
    }

    function logout($src = null)
    {
        $logout = $this->ion_auth->logout();

        $this->session->set_flashdata('message', $this->ion_auth->messages());

        if ($src == 'admin') {
            redirect('auth/login', 'refresh');
        } else {
            redirect('/', 'refresh');
        }
    }

    /**
    * Change password
    */
    public function changepassword()
    {
        if ($this->ion_auth->logged_in()) {
            /* Load */
            $this->load->config('admin/dp_config');
            $this->load->config('common/dp_config');

            /* Valid form */
            $this->form_validation->set_rules('old', 'lang:auth_your_current_password', 'required');
            $this->form_validation->set_rules('new', 'lang:auth_your_new_password', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|matches[new_confirm]');
            $this->form_validation->set_rules('new_confirm', 'lang:auth_new_password_confirm', 'required');

            /* Data */
            $this->data['title'] = $this->config->item('title');
            $this->data['title_lg'] = $this->config->item('title_lg');

            if (!$this->ion_auth->logged_in()) {
                redirect('auth/login', 'refresh');
            }

            $user = $this->ion_auth->user()->row();

            if ($this->form_validation->run() === false) {
                // display the form
                // set the flash data error message if there is one
                $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

                $this->data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');
                $this->data['old_password'] = [
                    'name' => 'old',
                    'id' => 'old',
                    'type' => 'password',
                    'class'       => 'form-control',
                    'placeholder' => lang('auth_your_current_password')
                ];
                $this->data['new_password'] = [
                    'name' => 'new',
                    'id' => 'new',
                    'type' => 'password',
                    'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
                    'class'       => 'form-control',
                    'placeholder' => lang('auth_your_new_password')
                ];
                $this->data['new_password_confirm'] = [
                    'name' => 'new_confirm',
                    'id' => 'new_confirm',
                    'type' => 'password',
                    'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
                    'class'       => 'form-control',
                    'placeholder' => lang('auth_new_password_confirm')
                ];
                $this->data['user_id'] = [
                    'name' => 'user_id',
                    'id' => 'user_id',
                    'type' => 'hidden',
                    'value' => $user->id,
                ];

                // render
                $this->template->auth_render('auth/change_password', $this->data);
            } else {
                $identity = $this->session->userdata('identity');

                $change = $this->ion_auth->change_password($identity, $this->input->post('old'), $this->input->post('new'));

                if ($change) {
                    //if the password was successfully changed
                    $this->session->set_flashdata('message', $this->ion_auth->messages());
                    $this->logout();
                } else {
                    $this->session->set_flashdata('message', $this->ion_auth->errors());
                    redirect('auth/changepassword', 'refresh');
                }
            }
        } else {
            redirect('/admin', 'refresh');
        }
    }

    /**
    * Forgot password
    */
    public function forgotpassword()
    {
        /* Load */
        $this->load->config('admin/dp_config');
        $this->load->config('common/dp_config');

        /* Data */
        $this->data['title'] = $this->config->item('title');
        $this->data['title_lg'] = $this->config->item('title_lg');
        
        // Identity Type (email or username)
        $identityType = $this->config->item('identity', 'ion_auth');
        
        // setting validation rules by checking whether identity is username or email
        if ($identityType != 'email') {
            $this->form_validation->set_rules('identity', 'lang:auth_forgot_password_identity', 'required');
            $identityLabel = lang('auth_forgot_password_enter_identity');
        } else {
            $this->form_validation->set_rules('identity', 'lang:auth_forgot_password_email', 'required|valid_email');
            $identityLabel = lang('auth_forgot_password_enter_email');
        }

        if ($this->form_validation->run() === false) {
            // setup the input
            $this->data['identity'] = [
                'name' => 'identity',
                'id' => 'identity',
                'class' => 'form-control',
                'placeholder' => $identityLabel
            ];

            // set any errors and display the form
            $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
            $this->template->auth_render('auth/forgot_password', $this->data);
        } else {
            $identity = $this->ion_auth->where(
                $identityType, $this->input->post('identity')
            )->users()->row();

            if (empty($identity)) {
                if ($identityType != 'email') {
                    $this->ion_auth->set_error('auth_forgot_password_identity_not_found');
                } else {
                    $this->ion_auth->set_error('auth_forgot_password_email_not_found');
                }

                $this->session->set_flashdata('message', $this->ion_auth->errors());
                redirect("auth/forgotpassword", 'refresh');
            }

            // run the forgotten password method to email an activation code to the user
            $forgotten = $this->ion_auth->forgotten_password($identity->{ $identityType });

            if ($forgotten) {
                // if there were no errors
                $this->session->set_flashdata('message', $this->ion_auth->messages());
                redirect("auth/login", 'refresh'); //we should display a confirmation page here instead of the login page
            } else {
                $this->session->set_flashdata('message', $this->ion_auth->errors());
                redirect("auth/forgotpassword", 'refresh');
            }
        }
    }

    /**
    * Reset password - final step for forgotten password
    *
    * @param string|null $code The reset code
    */
    public function resetpassword($code = null)
    {
        if (!$code) {
            show_404();
        }

        /* Load */
        $this->load->config('admin/dp_config');
        $this->load->config('common/dp_config');

        /* Data */
        $this->data['title'] = $this->config->item('title');
        $this->data['title_lg'] = $this->config->item('title_lg');
        
        $user = $this->ion_auth->forgotten_password_check($code);

        if ($user) {
            // if the code is valid then display the password reset form
            $this->form_validation->set_rules('new', $this->lang->line('reset_password_validation_new_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|matches[new_confirm]');
            $this->form_validation->set_rules('new_confirm', $this->lang->line('reset_password_validation_new_password_confirm_label'), 'required');

            if ($this->form_validation->run() === false) {
                // display the form

                // set the flash data error message if there is one
                $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

                $this->data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');
                $this->data['new_password'] = [
                    'name' => 'new',
                    'id' => 'new',
                    'type' => 'password',
                    'class' => 'form-control',
                    'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
                ];
                $this->data['new_password_confirm'] = [
                    'name' => 'new_confirm',
                    'id' => 'new_confirm',
                    'type' => 'password',
                    'class' => 'form-control',
                    'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
                ];
                $this->data['user_id'] = [
                    'name' => 'user_id',
                    'id' => 'user_id',
                    'type' => 'hidden',
                    'value' => $user->id,
                ];
                $this->data['csrf'] = $this->_get_csrf_nonce();
                $this->data['code'] = $code;

                // render
                $this->template->auth_render('auth/reset_password', $this->data);
            } else {
                $identity = $user->{$this->config->item('identity', 'ion_auth')};

                // do we have a valid request?
                if ($this->_valid_csrf_nonce() === false || $user->id != $this->input->post('user_id')) {
                    // something fishy might be up
                    $this->ion_auth->clear_forgotten_password_code($identity);

                    show_error($this->lang->line('error_csrf'));
                } else {
                    // finally change the password
                    $change = $this->ion_auth->reset_password($identity, $this->input->post('new'));

                    if ($change) {
                        // if the password was successfully changed
                        $this->session->set_flashdata('message', $this->ion_auth->messages());
                        redirect("auth/login", 'refresh');
                    } else {
                        $this->session->set_flashdata('message', $this->ion_auth->errors());
                        redirect('auth/resetpassword/' . $code, 'refresh');
                    }
                }
            }
        } else {
            // if the code is invalid then send them back to the forgot password page
            $this->session->set_flashdata('message', $this->ion_auth->errors());
            redirect("auth/forgotpassword", 'refresh');
        }
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
            && $this->input->post($this->session->flashdata('csrfkey'))
            == $this->session->flashdata('csrfvalue')
        ) {
            return true;
        } else {
            return false;
        }
    }
}
