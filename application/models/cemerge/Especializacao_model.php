<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Especializacao_model extends MY_Model
{
    protected $table = 'especializacao';

    public function __construct()
    {
        parent::__construct($this->table);
    }
}