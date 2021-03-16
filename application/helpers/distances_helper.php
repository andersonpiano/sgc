<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('distance')) {
    function distance($lat1, $lon1, $lat2, $lon2)
    {
        $lat1 = deg2rad($lat1);
        $lat2 = deg2rad($lat2);
        $lon1 = deg2rad($lon1);
        $lon2 = deg2rad($lon2);

        $dist = (6371 * acos(cos($lat1) * cos($lat2) * cos($lon2 - $lon1) + sin($lat1) * sin($lat2)));
        $dist = number_format($dist, 2, '.', '');
        return $dist;
    }
}

if (!function_exists('distanceDelta')) {
    function distanceDelta($lat1, $lon1, $lat2, $lon2)
    {
        $lat1 = deg2rad($lat1);
        $lat2 = deg2rad($lat2);
        $lon1 = deg2rad($lon1);
        $lon2 = deg2rad($lon2);

        $latD = $lat2 - $lat1;
        $lonD = $lon2 - $lon1;
        
        $dist = 2 * asin(sqrt(pow(sin($latD / 2), 2) + cos($lat1) * cos($lat2) * pow(sin($lonD / 2), 2)));
        $dist = $dist * 6371;
        return number_format($dist, 2, '.', '');
    }
}