<?php
defined('BASEPATH') OR exit('No direct script access allowed');

date_default_timezone_set('America/Fortaleza');

class MY_Controller extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        /* COMMON :: ADMIN & PUBLIC */
        /* Load */
        $this->load->database();

        /* Data */
        $this->data['lang'] = element($this->config->item('language'), $this->config->item('language_abbr'));
        $this->data['charset'] = $this->config->item('charset');
        $this->data['frameworks_dir'] = $this->config->item('frameworks_dir');
        $this->data['plugins_dir'] = $this->config->item('plugins_dir');
        $this->data['avatar_dir'] = $this->config->item('avatar_dir');

        /* Any mobile device (phones or tablets) */
        if ($this->mobile_detect->isMobile()) {
            $this->data['mobile'] = true;

            if ($this->mobile_detect->isiOS()) {
                $this->data['ios'] = true;
                $this->data['android'] = false;
            } elseif ($this->mobile_detect->isAndroidOS()) {
                $this->data['ios'] = false;
                $this->data['android'] = true;
            } else {
                $this->data['ios'] = false;
                $this->data['android'] = false;
            }

            if ($this->mobile_detect->getBrowsers('IE')) {
                $this->data['mobile_ie'] = true;
            } else {
                $this->data['mobile_ie'] = false;
            }
        } else {
            $this->data['mobile'] = false;
            $this->data['ios'] = false;
            $this->data['android'] = false;
            $this->data['mobile_ie'] = false;
        }
    }
}


class Admin_Controller extends MY_Controller
{
    public function __construct($permitted_groups = null)
    {
        parent::__construct();

        if (!$permitted_groups) {
            $permitted_groups = array('admin');
        }

        if (!$this->ion_auth->logged_in() or !$this->ion_auth->in_group($permitted_groups)) {
            redirect('auth/login', 'refresh');
        } else {
            /* Load */
            $this->load->config('admin/dp_config');
            $this->load->library(['admin/breadcrumbs', 'admin/page_title']);
            $this->load->model('admin/core_model');
            $this->load->helper('menu');
            $this->lang->load(['admin/main_header', 'admin/main_sidebar', 'admin/footer', 'admin/actions']);

            /* Load library function  */
            $this->breadcrumbs->unshift(0, $this->lang->line('menu_dashboard'), 'admin/dashboard');

            /* Data */
            $this->data['title'] = $this->config->item('title');
            $this->data['title_lg'] = $this->config->item('title_lg');
            $this->data['title_mini'] = $this->config->item('title_mini');
            $this->data['admin_prefs'] = $this->prefs_model->admin_prefs();
            $this->data['user_login'] = $this->prefs_model->user_info_login($this->ion_auth->user()->row()->id);

            if ($this->router->fetch_class() == 'dashboard') {
                $this->data['dashboard_alert_file_install'] = $this->core_model->get_file_install();
                $this->data['header_alert_file_install'] = null;
            } else {
                $this->data['dashboard_alert_file_install'] = null;
                $this->data['header_alert_file_install'] = null; /* << A MODIFIER !!! */
            }
        }
    }
}


class Public_Controller extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
            $this->data['admin_link'] = true;
        } else {
            $this->data['admin_link'] = false;
        }

        if ($this->ion_auth->logged_in()) {
            $this->data['logout_link'] = true;
        } else {
            $this->data['logout_link'] = false;
        }
    }
}
