<?php
if (!defined('ABSPATH')) exit;

add_action('init', function() {
    $opt = admino_get_option('admino_login_url', []);
    $slug = sanitize_title(admino_array_get($opt, 'slug', 'my-login'));
    if ($slug) {
        add_rewrite_rule("^{$slug}/?$", 'wp-login.php', 'top');
    }
}, 1);

add_filter('login_url', function($login_url, $redirect, $force_reauth) {
    $opt = admino_get_option('admino_login_url', []);
    $slug = sanitize_title(admino_array_get($opt, 'slug', 'my-login'));
    if (!$slug) return $login_url;
    $url = home_url('/' . $slug . '/');
    if ($redirect) $url = add_query_arg('redirect_to', urlencode($redirect), $url);
    return $url;
}, 10, 3);

add_filter('site_url', function($url, $path, $scheme, $blog_id) {
    if (false !== strpos($url, 'wp-login.php')) {
        $opt  = admino_get_option('admino_login_url', []);
        $slug = sanitize_title(admino_array_get($opt, 'slug', 'my-login'));
        if ($slug) {
            $url = home_url('/' . $slug . '/');
        }
    }
    return $url;
}, 10, 4);

add_action('template_redirect', function() {
    if (!isset($_SERVER['REQUEST_URI'])) return;
    $req = $_SERVER['REQUEST_URI'];
    if (false === stripos($req, 'wp-login.php')) return;

    $opt = admino_get_option('admino_login_url', []);
    $block = intval(admino_array_get($opt, 'block_direct', 1)) === 1;
    if (!$block) return;

    $allow_lost = intval(admino_array_get($opt, 'allow_lostpassword', 1)) === 1;
    $secret = trim(admino_array_get($opt, 'secret_fallback', ''));
    $action = isset($_REQUEST['action']) ? sanitize_text_field($_REQUEST['action']) : '';
    $is_ajax = defined('DOING_AJAX') && DOING_AJAX;

    $secret_ok = ($secret && isset($_GET['secret']) && hash_equals($secret, sanitize_text_field($_GET['secret'])));

    if ($is_ajax || $action === 'logout' || ($allow_lost && $action === 'lostpassword') || $secret_ok) {
        return;
    }

    status_header(404);
    nocache_headers();
    include get_404_template();
    exit;
}, 0);

function admino_flush_rewrites_now() { flush_rewrite_rules(); }
