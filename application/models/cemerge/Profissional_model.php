<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profissional_model extends MY_Model {
    protected $table = 'profissionais';

    public function __construct()
    {
        parent::__construct($this->table);
    }
}
