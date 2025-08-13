<?php
/*
Plugin Name: Admino Lite
Plugin URI:  https://shokrino.com/admino-plugin/
Description: The easiest , The best and fastest way to make WordPress admin panel and login page beautiful!
Author:      Shokrino
Author URI:  https://shokrino.com/admino-plugin/
Version:     1.0.1
Textdomain:  admino-l
آموزش برنامه نویسی مشابه همین پلاگین در سایت شکرینو
*/
defined( 'ABSPATH' ) || exit;

$plugin_data = get_file_data(__FILE__, array('Version' => 'Version'), false);
$plugin_data_name = get_file_data(__FILE__, array('Plugin Name' => 'Plugin Name'), false);
$plugin_version = $plugin_data['Version'];
$plugin_name = $plugin_data_name['Plugin Name'];
define('ADMNL_NAME', $plugin_name);
define('ADMNL_VERSION', $plugin_version);
define('ADMNL_PATH', plugin_dir_path(__FILE__));
define('ADMNL_URL',  plugin_dir_url(__FILE__));
define('ADMNL_INC' , ADMNL_PATH.'/inc');
define('ADMNL_LIB' , ADMNL_PATH.'/inc/lib');
define('ADMNL_TPL' , ADMNL_PATH.'/inc/templates');
define('ADMNL_ASSETS' , ADMNL_URL.'assets');
define('ADMNL_FONTS' , ADMNL_ASSETS.'/fonts');
define('ADMNL_TEXTDOMAIN' , 'admino-l');

/////////////////////////////////////////////////////////////////////////////////////////////////
if (!class_exists('OPTNNO')) {
    require_once 'inc/optionino-framework/optionino-framework.php';
}
if (class_exists('OPTNNO')) {
    // require_once ADMNL_PATH . 'inc/helpers.php';
    // require_once ADMNL_PATH . 'inc/MenuEditor.php';
    // require_once ADMNL_PATH . 'inc/Columns.php';
    // require_once ADMNL_PATH . 'inc/WhiteLabel.php';
    // require_once ADMNL_PATH . 'inc/LoginURL.php';
    // require_once ADMNL_PATH . 'inc/LoginProtect.php';
    // require_once ADMNL_PATH . 'inc/Captcha.php';
    // require_once ADMNL_PATH . 'inc/Hardening.php';
    // require_once ADMNL_PATH . 'inc/SecurityHubPage.php';

    require_once 'inc/optionino-config.php';

    // register_activation_hook(__FILE__, function () {

    //     add_option('admino_menu_profile_default', wp_json_encode([
    //         'order' => ['index.php','edit.php','upload.php'],
    //         'items' => [],
    //         'subs'  => [],
    //     ]), '', false);

    //     add_option('admino_columns', wp_json_encode([
    //         'posts' => [],
    //         'tax'   => [],
    //     ]), '', false);

    //     add_option('admino_whitelabel', wp_json_encode([
    //         'login_logo' => '',
    //         'brand_logo_small' => '',
    //         'footer_text' => '',
    //         'hide_help' => 0,
    //         'hide_screen_options' => 0,
    //         'clean_dashboard' => 0,
    //         'hide_wp_branding' => 0,
    //     ]), '', false);

    //     add_option('admino_login_url', wp_json_encode([
    //         'slug' => 'my-login',
    //         'block_direct' => 1,
    //         'allow_lostpassword' => 1,
    //         'secret_fallback' => '',
    //     ]), '', false);

    //     add_option('admino_login_protect', wp_json_encode([
    //         'enabled' => 1,
    //         'max_attempts' => 5,
    //         'window_minutes' => 15,
    //         'lock_minutes' => 30,
    //         'notify_admin' => 0,
    //     ]), '', false);

    //     add_option('admino_captcha', wp_json_encode([
    //         'provider' => 'none',
    //         'site_key' => '',
    //         'secret_key' => '',
    //         'on' => ['login'],
    //     ]), '', false);

    //     add_option('admino_hardening', wp_json_encode([
    //         'disable_file_edit' => 1,
    //         'disable_xmlrpc' => 1,
    //         'rest_users_block_anon' => 1,
    //         'remove_wp_version' => 1,
    //         'disallow_plugin_theme_install' => 0,
    //     ]), '', false);

    //     flush_rewrite_rules();
    // });

    // register_deactivation_hook(__FILE__, function () {
    //     flush_rewrite_rules();
    // });



    function adminol_load_textdomain() {
        load_plugin_textdomain( 'admino-l', false, basename( dirname( __FILE__ ) ) . '/languages/' );
    }
    add_action( 'init', 'adminol_load_textdomain' );

    function adminol_font_dashboard() {
        wp_enqueue_style('general_admin_panel_font', ADMNL_ASSETS . '/css/admin-font-general.css');
        wp_enqueue_style('custom_admin_panel_font', ADMNL_ASSETS . '/css/font-shabnam.css');
    }

    function adminol_adminbar_css() {
        if (strpos(implode(' ', get_body_class()), 'admin-bar') !== false) {
            wp_enqueue_style('custom_admin_panel_font', ADMNL_ASSETS . '/css/font-shabnam.css', array('admin-bar'));
        }
    }

    function adminol_style_dashboard() {
        if (admnl_options('admin_style_type') == "dark") {
            wp_enqueue_style('custom_admin_panel_style', ADMNL_ASSETS . '/css/style2.css');
        } else {
            wp_enqueue_style('custom_admin_panel_style', ADMNL_ASSETS . '/css/style1.css');
        }
    }

    function adminol_style_dashboard_head() {
        $admin_style1_main_color = admnl_options('admin_color');
        ?>
        <style>
            :root  {
                --var-admino-lite-color-main: <?php echo $admin_style1_main_color; ?> !important;
            }
        </style>
        <?php
    }

    function adminol_style_login() {
        if (admnl_options('login_style') == "off") { ?>
            <style>
                body.login #backtoblog {
                    display: none !important;
                }
            </style>
        <?php }
        $logo_option_admino = admnl_options('admin_logo');
        if (!empty($logo_option_admino)) { ?>
            <style>
                #login h1 a, .login h1 a {
                    background-image: url(<?php echo $logo_option_admino; ?>);
                    background-repeat: no-repeat;
                    background-size: contain;
                    width: 100%;
                    box-shadow: none !important;
                }
            </style>
        <?php } else { ?>
            <style>
                #login > h1 {
                    display: none !important;
                }
            </style>
        <?php }
        $disable_signup_lostpassword = admnl_options('disable_signup_lostpassword') !== "false" ? true : false;
        $disable_backtoblog = admnl_options('disable_backtoblog') !== "false" ? true : false;
        $background_login_picture = admnl_options('admin_bg');
        wp_enqueue_style('login-page-general-styles', ADMNL_ASSETS . '/css/login-page-general.css');
        ?>
        <style>
            #login {
                margin: auto !important;
            }
        </style>
        <?php if (!$disable_signup_lostpassword) { ?>
            <style>
                body.login #nav {
                    display: none !important;
                }
            </style>
        <?php } if (!$disable_backtoblog) { ?>
            <style>
                body.login #backtoblog {
                    display: none !important;
                }
            </style>
        <?php }
        if (!empty($login_other_color)) { ?>
            <style>
                .wp-core-ui .button-secondary .dashicons:before,
                #wp-submit:hover, .wp-core-ui .button-primary:focus, .wp-core-ui .button-primary.active,
                .wp-core-ui .button-primary.active:focus, .wp-core-ui .button-primary:active {
                    color: <?php echo $login_other_color;?> !important;
                    background: white !important;
                }
                .wp-core-ui .button-primary {
                    background: <?php echo $login_other_color;?> !important;
                }
                .login #login_error, .login .message, .login .success {
                    border-color: <?php echo $login_other_color;?> !important;
                }
                #user_login:focus, #user_email:focus, #user_pass:focus, .input:focus, .password-input:focus, .login input[type="text"]:focus, input:focus {
                    border: 2px solid <?php echo $login_other_color;?> !important;
                }
            </style>
        <?php } if (!empty($login_box_bg_color)) { ?>
            <style>
                #login, #loginform {
                    background: <?php echo $login_box_bg_color;?> !important;
                }
            </style>
        <?php } if (!empty($login_text_color)) { ?>
            <style>
                .message-wp-login-admino, .login form, .login #backtoblog, .login #nav, .login #login_error, .login .message, .login .success, #nav a, #backtoblog a {
                    color: <?php echo $login_text_color;?> !important;
                }
            </style>
        <?php }
        if (admnl_options('admin_bg_login_type') == "image") {
        if (!empty($background_login_picture)) { ?>
            <style>
                body.login {
                    background-image: url(<?php echo $background_login_picture;?>) !important;
                }
            </style>
        <?php }
        } else if (admnl_options('admin_bg_login_type') == "color") {
            $background_login_color = admnl_options('admin_bg_color');
            ?>
            <style>
                body.login {
                    background-color: <?php echo $background_login_color; ?> !important;
                }
            </style>
            <?php
        }
    }

    function admnl_login_headerurl() {
        return home_url();
    }

    function admnl_login_headertext() {
        return '';
    }

    function admnl_login_body_class($classes) {
        $classes[] = 'admino-login-page-plugin';
        return $classes;
    }

    function admnl_login_display_language_dropdown() {
        if (admnl_options('disable_wp_language_switcher') !== "false") {
            return true;
        } else {
            return false;
        }
    }

    function admnl_enable_login_autofocus() {
        return false;
    }
    
    if (admnl_options('persian_font') !== "false") {
        add_action('wp_enqueue_scripts', 'adminol_adminbar_css');
        add_action('admin_enqueue_scripts', 'adminol_font_dashboard');
        add_action('login_enqueue_scripts', 'adminol_font_dashboard');
    }

    if (admnl_options('admin_style') !== "false") {
        add_action('admin_enqueue_scripts', 'adminol_style_dashboard');
        add_action('admin_head', 'adminol_style_dashboard_head');
    }

    if (admnl_options('login_style') !== "false") {
        add_action('login_enqueue_scripts', 'adminol_style_login');
        add_action('login_head', 'adminol_style_dashboard_head');
        add_filter('login_headerurl', 'admnl_login_headerurl', 100);
        add_filter('login_headertext', 'admnl_login_headertext');
        add_filter('login_body_class', 'admnl_login_body_class');
        add_filter('login_display_language_dropdown', 'admnl_login_display_language_dropdown');
        add_filter('enable_login_autofocus', 'admnl_enable_login_autofocus');
    }

    add_filter('use_block_editor_for_post_type', '__return_false', 100);
    add_filter('use_widgets_block_editor', '__return_false');

    if (admnl_options('admin_style_type') == "dark") {
        add_action('admin_init', function() {
            add_editor_style(plugin_dir_url(__FILE__) . 'assets/css/editor-dark.css');
        });
    }
}