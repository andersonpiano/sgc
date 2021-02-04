<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Usuarioresidente_model extends MY_Model {
    protected $table = 'usuariosresidentes';

    public function __construct()
    {
        parent::__construct($this->table);
    }
}
