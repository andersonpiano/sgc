<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Passagemtroca_model extends MY_Model {
    protected $table = 'passagenstrocas';

    public function __construct()
    {
        parent::__construct($this->table);
    }
}
