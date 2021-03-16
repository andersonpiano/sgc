<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('show_message')) {
    function show_message()
    {
        $CI =& get_instance();

        if ($CI->load->is_loaded('session')) {
            if ($CI->session->flashdata('message')) {
                $CI->load->view('admin/_templates/message');
            }
        }
    }
}