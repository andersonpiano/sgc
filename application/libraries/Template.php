<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Template
{
    protected $CI;

    public function __construct()
    {
        $this->CI =& get_instance();
    }

    public function admin_render($content, $data = null)
    {
        if (!$content) {
            return null;
        } else {
            $this->template['header']          = $this->CI->load->view('admin/_templates/header', $data, true);
            $this->template['main_header']     = $this->CI->load->view('admin/_templates/main_header', $data, true);
            $this->template['main_sidebar']    = $this->CI->load->view('admin/_templates/main_sidebar', $data, true);
            $this->template['content']         = $this->CI->load->view($content, $data, true);
            $this->template['control_sidebar'] = $this->CI->load->view('admin/_templates/control_sidebar', $data, true);
            $this->template['footer']          = $this->CI->load->view('admin/_templates/footer', $data, true);

            return $this->CI->load->view('admin/_templates/template', $this->template);
        }
    }

    public function auth_render($content, $data = null)
    {
        if (!$content) {
            return null;
        } else {
            $this->template['header']  = $this->CI->load->view('auth/_templates/header', $data, true);
            $this->template['content'] = $this->CI->load->view($content, $data, true);
            $this->template['footer']  = $this->CI->load->view('auth/_templates/footer', $data, true);

            return $this->CI->load->view('auth/_templates/template', $this->template);
        }
    }
}