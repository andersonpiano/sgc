<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Categoria_model extends MY_Model
{
    protected $table = 'categoria_especializacao';

    public function __construct()
    {
        parent::__construct($this->table);
    }
}