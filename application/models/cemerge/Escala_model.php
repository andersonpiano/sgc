<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Escala_model extends MY_Model {
    protected $table = 'escalas';

    public function __construct()
    {
        parent::__construct($this->table);
    }
}
