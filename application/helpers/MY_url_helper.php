<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('is_url_exist')) {
    function is_url_exist($url = null)
    {
        if (!empty($url) && filter_var($url, FILTER_VALIDATE_URL)) {
            if (function_exists('curl_version')) {
                $ch = curl_init($url);

                curl_setopt($ch, CURLOPT_NOBODY, true);
                curl_exec($ch);

                $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

                if ($code == 200) {
                    $status = true;
                } else {
                    $status = false;
                }

                curl_close($ch);

                return $status;
            } else {
                $headers = @get_headers($url);

                return stripos($headers[0], '200 OK') ? true : false;
            }
        } else {
            return false;
        }
    }
}

