<?php
if (!defined('ABSPATH')) exit;

add_action('admin_menu', function() {
    add_submenu_page(
        'options-general.php',
        __('Admino Security Hub', 'admino'),
        __('Admino Security', 'admino'),
        'manage_options',
        'admino-security-hub',
        'admino_render_security_hub_tools'
    );
}, 50);

function admino_render_security_hub_tools() {
    if (isset($_POST['admino_flush_rewrite']) && check_admin_referer('admino_tools')) {
        flush_rewrite_rules();
        echo '<div class="updated"><p>Rewrite rules flushed.</p></div>';
    }

    if (isset($_POST['admino_test_http']) && check_admin_referer('admino_tools')) {
        $resp = wp_remote_get(home_url('/'), ['timeout' => 5]);
        $ok = !is_wp_error($resp) && wp_remote_retrieve_response_code($resp) >= 200;
        echo '<div class="'.($ok?'updated':'error').'"><p>HTTP Test: '.($ok?'OK':'Failed').'</p></div>';
    }

    ?>
    <div class="wrap">
        <h1>Admino – Security Hub (Tools)</h1>
        <form method="post">
            <?php wp_nonce_field('admino_tools'); ?>
            <p><button class="button button-primary" name="admino_flush_rewrite" value="1">Flush Rewrite Rules</button></p>
            <p><button class="button" name="admino_test_http" value="1">Test HTTP Request</button></p>
            <p>برای تنظیمات: به منوی Optionino → "Admino Security Hub" مراجعه کنید.</p>
        </form>
    </div>
    <?php
}
