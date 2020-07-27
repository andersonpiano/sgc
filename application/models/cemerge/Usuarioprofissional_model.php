<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Usuarioprofissional_model extends MY_Model {
    protected $table = 'usuariosprofissionais';

    public function __construct()
    {
        parent::__construct($this->table);
    }
}
