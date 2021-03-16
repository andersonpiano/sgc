<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class FrequenciaResidente_model extends MY_Model
{
    protected $table = 'frequenciaresidentes';

    public function __construct()
    {
        parent::__construct($this->table);
    }
}
