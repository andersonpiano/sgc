<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Residenteunidadehospitalar_model extends MY_Model
{
    protected $table = 'residenteunidadehospitalar';

    public function __construct()
    {
        parent::__construct($this->table);
    }
}
