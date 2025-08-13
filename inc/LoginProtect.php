<?php
if (!defined('ABSPATH')) exit;

add_action('wp_login_failed', function($username) {
    $opt = admino_get_option('admino_login_protect', []);
    if (!intval(admino_array_get($opt, 'enabled', 1))) return;

    $ip = admino_get_ip();
    $max = max(1, intval(admino_array_get($opt, 'max_attempts', 5)));
    $window = max(1, intval(admino_array_get($opt, 'window_minutes', 15)));
    $lock = max(1, intval(admino_array_get($opt, 'lock_minutes', 30)));

    $ip_key = 'ao_lp_ip_' . md5($ip);
    $u_key  = 'ao_lp_user_' . md5(strtolower($username));
    $now = time();

    foreach ([$ip_key, $u_key] as $k) {
        $data = get_transient($k) ?: ['count' => 0, 'first_at' => $now];
        if (($now - intval($data['first_at'])) > ($window * 60)) {
            $data = ['count' => 0, 'first_at' => $now];
        }
        $data['count']++;
        set_transient($k, $data, $window * 60);
        if ($data['count'] >= $max) {
            set_transient('ao_lp_lock_' . md5($ip), 1, $lock * 60);
        }
    }
});

add_filter('authenticate', function($user, $username) {
    $opt = admino_get_option('admino_login_protect', []);
    if (!intval(admino_array_get($opt, 'enabled', 1))) return $user;

    $ip = admino_get_ip();
    if (get_transient('ao_lp_lock_' . md5($ip))) {
        return new WP_Error('locked', __('Too many login attempts. Please try again later.', 'admino'));
    }
    return $user;
}, 99, 2);
