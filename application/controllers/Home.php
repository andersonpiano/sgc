<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends Public_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->load->view('public/home', $this->data);
    }

    public function help()
    {
        $this->load->view('public/help', $this->data);
    }

    public function downloads()
    {
        $this->load->helper('download');
        $file_name = $this->uri->segment(3);
        if ($file_name) {
            $data = @file_get_contents('assets/downloads/' . $file_name);
        }
        if ($data) {
            force_download($name, $data);
        } else {
            $this->session->set_flashdata('message', 'O arquivo solicitado não existe.');
            redirect('home/help', 'refresh');
        }
    }
}
