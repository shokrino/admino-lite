<?php
if (!defined('ABSPATH')) exit;

function admino_captcha_should_render($page) {
    $opt = admino_get_option('admino_captcha', []);
    $on  = admino_array_get($opt, 'on', []);
    return in_array($page, (array)$on, true) && admino_array_get($opt, 'provider', 'none') !== 'none';
}

add_action('login_form', function() {
    $opt = admino_get_option('admino_captcha', []);
    $provider = admino_array_get($opt, 'provider', 'none');
    $site_key = esc_attr(admino_array_get($opt, 'site_key', ''));

    if (!admino_captcha_should_render('login') && !admino_captcha_should_render('lostpassword') && !admino_captcha_should_render('register')) {
        return;
    }

    if ($provider === 'turnstile') {
        echo '<div class="cf-turnstile" data-sitekey="' . $site_key . '"></div>';
        wp_enqueue_script('turnstile', 'https://challenges.cloudflare.com/turnstile/v0/api.js', [], null, true);
    } elseif ($provider === 'recaptcha') {
        echo '<div class="g-recaptcha" data-sitekey="' . $site_key . '"></div>';
        wp_enqueue_script('recaptcha', 'https://www.google.com/recaptcha/api.js', [], null, true);
    }
});

add_filter('authenticate', function($user, $username, $password) {
    $opt = admino_get_option('admino_captcha', []);
    $provider = admino_array_get($opt, 'provider', 'none');
    if ($provider === 'none') return $user;

    $page = 'login';
    $action = isset($_REQUEST['action']) ? sanitize_text_field($_REQUEST['action']) : '';
    if ($action === 'lostpassword') $page = 'lostpassword';
    if ($action === 'register')     $page = 'register';

    if (!admino_captcha_should_render($page)) return $user;

    $secret = admino_array_get($opt, 'secret_key', '');
    if ($provider === 'turnstile') {
        $token = isset($_POST['cf-turnstile-response']) ? sanitize_text_field($_POST['cf-turnstile-response']) : '';
        if (!$token) return new WP_Error('captcha', __('Please verify the CAPTCHA.', 'admino'));
        $resp = wp_remote_post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
            'body' => ['secret' => $secret, 'response' => $token, 'remoteip' => admino_get_ip()],
            'timeout' => 8
        ]);
        if (is_wp_error($resp)) return new WP_Error('captcha', __('CAPTCHA verification failed (network).', 'admino'));
        $data = json_decode(wp_remote_retrieve_body($resp), true);
        if (empty($data['success'])) return new WP_Error('captcha', __('CAPTCHA verification failed.', 'admino'));
    } elseif ($provider === 'recaptcha') {
        $token = isset($_POST['g-recaptcha-response']) ? sanitize_text_field($_POST['g-recaptcha-response']) : '';
        if (!$token) return new WP_Error('captcha', __('Please verify the CAPTCHA.', 'admino'));
        $resp = wp_remote_post('https://www.google.com/recaptcha/api/siteverify', [
            'body' => ['secret' => $secret, 'response' => $token, 'remoteip' => admino_get_ip()],
            'timeout' => 8
        ]);
        if (is_wp_error($resp)) return new WP_Error('captcha', __('CAPTCHA verification failed (network).', 'admino'));
        $data = json_decode(wp_remote_retrieve_body($resp), true);
        if (empty($data['success'])) return new WP_Error('captcha', __('CAPTCHA verification failed.', 'admino'));
    }

    return $user;
}, 20, 3);
