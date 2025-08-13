<?php
if (!defined('ABSPATH')) exit;

add_action('admin_bar_menu', function($wp_admin_bar) {
    $opt = admino_get_option('admino_whitelabel', []);
    if (intval(admino_array_get($opt, 'hide_wp_branding', 1))) {
        $wp_admin_bar->remove_node('wp-logo');
    }
    $logo = esc_url(admino_array_get($opt, 'brand_logo_small', ''));
    if ($logo) {
        $wp_admin_bar->add_node([
            'id'    => 'admino-brand',
            'title' => '<img src="'.$logo.'" style="height:16px;width:16px;vertical-align:middle;" alt="brand"/>',
            'href'  => admin_url(),
            'meta'  => ['title' => 'Admino']
        ]);
    }
}, 100);

add_filter('admin_footer_text', function($text) {
    $opt = admino_get_option('admino_whitelabel', []);
    $custom = admino_array_get($opt, 'footer_text', '');
    return $custom !== '' ? wp_kses_post($custom) : $text;
}, 999);
add_filter('update_footer', '__return_empty_string', 999);

add_action('admin_head', function() {
    $opt = admino_get_option('admino_whitelabel', []);
    $css = '';
    if (intval(admino_array_get($opt, 'hide_help', 1))) {
        $css .= '#contextual-help-link-wrap{display:none !important;}';
    }
    if (intval(admino_array_get($opt, 'hide_screen_options', 1))) {
        $css .= '#screen-options-link-wrap{display:none !important;}';
    }
    if ($css) echo '<style>'.$css.'</style>';
});

add_action('wp_dashboard_setup', function() {
    $opt = admino_get_option('admino_whitelabel', []);
    if (!intval(admino_array_get($opt, 'clean_dashboard', 1))) return;
    remove_meta_box('dashboard_quick_press', 'dashboard', 'side');
    remove_meta_box('dashboard_primary', 'dashboard', 'side');
    remove_meta_box('dashboard_secondary', 'dashboard', 'side');
    remove_meta_box('dashboard_activity', 'dashboard', 'normal');
    remove_meta_box('dashboard_site_health', 'dashboard', 'normal');
}, 100);

add_action('login_enqueue_scripts', function() {
    $opt = admino_get_option('admino_whitelabel', []);
    $logo = esc_url(admino_array_get($opt, 'login_logo', ''));
    if ($logo) {
        echo '<style>.login h1 a{background-image:url("'.$logo.'") !important;background-size:contain !important;width:100% !important;}</style>';
    }
});
add_filter('login_headerurl', function() { return home_url('/'); });
add_filter('login_headertext', function() { return get_bloginfo('name'); });
