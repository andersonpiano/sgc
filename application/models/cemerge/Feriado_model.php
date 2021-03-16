<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Feriado_model extends MY_Model
{
    protected $table = 'feriados';

    public function __construct()
    {
        parent::__construct($this->table);
    }
}
