<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('group_by')) {
    function group_by($key, $data)
    {
        $result = array();
    
        foreach ($data as $val) {
            if (isset($val->$key)) {
                $result[$val->$key][] = $val;
            } else {
                $result[""][] = $val;
            }
        }

        return $result;
    }
}