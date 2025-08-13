<?php
if (!defined('ABSPATH')) exit;

function admino_array_get($arr, $key, $default = null) {
    return isset($arr[$key]) ? $arr[$key] : $default;
}

function admino_get_option($option_name, $default = []) {
    $val = get_option($option_name, null);
    if ($val === null || $val === '') return $default;
    if (is_array($val)) return $val;
    $decoded = json_decode($val, true);
    return is_array($decoded) ? $decoded : $default;
}

function admino_update_json_option($option_name, $data) {
    if (!is_array($data)) return;
    update_option($option_name, wp_json_encode($data));
}

function admino_get_ip() {
    $keys = ['HTTP_CF_CONNECTING_IP','HTTP_X_FORWARDED_FOR','HTTP_CLIENT_IP','REMOTE_ADDR'];
    foreach ($keys as $k) {
        if (!empty($_SERVER[$k])) {
            $ip = explode(',', $_SERVER[$k])[0];
            return trim($ip);
        }
    }
    return '0.0.0.0';
}
