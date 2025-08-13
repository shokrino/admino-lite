<?php
if (!defined('ABSPATH')) exit;

add_action('init', function() {
    $opt = admino_get_option('admino_hardening', []);
    if (intval(admino_array_get($opt, 'disable_file_edit', 1))) {
        if (!defined('DISALLOW_FILE_EDIT')) define('DISALLOW_FILE_EDIT', true);
    }
    if (intval(admino_array_get($opt, 'disallow_plugin_theme_install', 0))) {
        if (!defined('DISALLOW_FILE_MODS')) define('DISALLOW_FILE_MODS', true);
    }
}, 0);

add_filter('xmlrpc_enabled', function($enabled) {
    $opt = admino_get_option('admino_hardening', []);
    return intval(admino_array_get($opt, 'disable_xmlrpc', 1)) ? false : $enabled;
});

add_filter('rest_endpoints', function($endpoints) {
    $opt = admino_get_option('admino_hardening', []);
    if (intval(admino_array_get($opt, 'rest_users_block_anon', 1)) && !is_user_logged_in()) {
        foreach ($endpoints as $route => $defs) {
            if (strpos($route, '/wp/v2/users') !== false) unset($endpoints[$route]);
        }
    }
    return $endpoints;
});

add_filter('the_generator', function($gen) {
    $opt = admino_get_option('admino_hardening', []);
    return intval(admino_array_get($opt, 'remove_wp_version', 1)) ? '' : $gen;
});