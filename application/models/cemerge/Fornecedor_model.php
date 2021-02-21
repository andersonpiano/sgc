<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Fornecedor_model extends MY_Model
{
    protected $table = 'fornecedor';

    public function __construct()
    {
        parent::__construct($this->table);
    }
}