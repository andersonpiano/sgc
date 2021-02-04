<?php
class Frequencia extends CI_Controller
{
    public function __construct()
    {
        if (!isset($_SERVER['HTTP_HOST'])) {
            $_SERVER['HTTP_HOST'] = 'localhost';
        }
        parent::__construct();
        $CI =& get_instance();
        $CI->load->library('database');
        //$CI->load->library('input');
        //$this->load->model('expiry_model');
    }

    public function index()
    {
        /*
        if (!$CI->input->is_cli_request()) {
            echo "This script can only be accessed via the command line" . PHP_EOL;
            return;
        }
        */

        echo('CLI Test');
        var_dump($CI);
        $cemerge_assesus = $CI->load->database('cemerge_assesus', TRUE); // the TRUE paramater tells CI that you'd like to return the database object.
        $query = $cemerge_assesus->select('*')->get('dbo.tb_set');
        var_dump($query);
    }
}