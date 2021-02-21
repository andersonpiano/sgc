<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Estoque_model extends MY_Model
{
    protected $table = 'estoque';

    public function __construct()
    {
        parent::__construct($this->table);
    }
}