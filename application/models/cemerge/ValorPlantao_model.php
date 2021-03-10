<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ValorPlantao_model extends MY_Model
{
    protected $table = 'valoresplantoes';

    public function __construct()
    {
        parent::__construct($this->table);
    }
}
