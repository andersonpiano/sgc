<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Especializacao extends MY_model
{

    public function index()
    {
    $this->template->admin_render('admin/especializacao/index', $this->data);
    }
}