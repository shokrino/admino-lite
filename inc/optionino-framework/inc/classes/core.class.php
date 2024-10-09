<?php defined( 'ABSPATH' ) || exit;
/**
 * Core Class
 *
 * @version 1.0.0
 * @since 1.0.0
 *
 */
if (!class_exists('OPTNNO')) {
    class OPTNNO {
        public static $settings = array();
        public static $tabs = array();
        public static $fields = array();
        public function __construct() {
            add_action('init', [$this,'setup']);
            add_action('after_setup_theme', [$this,'setup']);
            add_action('switch_theme', [$this,'setup']);
            add_action('wp_enqueue_scripts', [$this,'wp_scripts']);
            add_action('wp_head', [$this,'wp_head']);
            add_action('admin_menu', [$this,'create_menu']);
            add_action( 'init', [$this,'optionino_load_textdomain'] );
        }

        public static function setup() {

        }
        public static function set_config($dev_name, $settings) {
            foreach (self::$settings as $existing_config) {
                if ($existing_config['dev_name'] === $dev_name) {
                    add_action('admin_notices', function() use ($dev_name) {
                        // translators: %s is the ID of the configuration that caused the error.
                        echo '<div class="error"><p>' . sprintf(esc_html__('Configuration with ID "%s" is already in use. Please use a unique ID.','admino-lite'), esc_html($dev_name)) . '</p></div>';
                    });
                    return;
                }
            }
            $settings['dev_name'] = $dev_name;
            self::$settings[$dev_name] = $settings;
        }
        public static function set_tab($dev_name, $tab_settings) {
            if (!isset(self::$tabs[$dev_name])) {
                self::$tabs[$dev_name] = array();
            }
            foreach (self::$tabs[$dev_name] as $existing_tab) {
                if ($existing_tab['id'] === $tab_settings['id']) {
                    add_action('admin_notices', function() use ($tab_settings) {
                        // translators: %s is the ID of the tab that caused the error.
                        echo '<div class="error"><p>' . sprintf(esc_html__('Tab ID "%s" is already in use. Please use a unique ID.','admino-lite'), esc_html($tab_settings['id'])) . '</p></div>';
                    });
                    return;
                }
            }
            self::$tabs[$dev_name][$tab_settings['id']] = $tab_settings;
            $fields[$tab_settings['id']] = $tab_settings['fields'];
            self::set_fields($dev_name, $fields);
        }
        public static function set_fields($dev_name, $tabfields) {
            if (!isset(self::$fields[$dev_name])) {
                self::$fields[$dev_name] = array();
            }

            $existingFieldIds = [];
            foreach (self::$fields[$dev_name] as $existingTabFields) {
                foreach ($existingTabFields as $existingField) {
                    $existingFieldIds[] = $existingField['id'];
                }
            }

            foreach ($tabfields as $tab => $fields) {
                $tabFieldIds = array_column($fields, 'id');
                foreach ($tabFieldIds as $fieldId) {
                    if (in_array($fieldId, $existingFieldIds) || is_id_duplicate_optionino($tabFieldIds, $fieldId)) {
                        add_action('admin_notices', function() use ($fieldId, $tab) {
                            // translators: %s is the ID of the field that caused the error.
                            echo '<div class="error"><p>' . sprintf(esc_html__('Field ID "%s" is already in use. Please use a unique ID.','admino-lite'), esc_html($fieldId)) . '</p></div>';
                        });
                        return;
                    }
                }
            }

            foreach ($tabfields as $tab => $fields) {
                self::$fields[$dev_name][$tab] = $fields;
            }
            $get_fields = self::$fields;
            $fields_defaults = array();
            foreach ($get_fields as $dev_name => $tabs) {
                foreach ($tabs as $tab_name => $arrays) {
                    foreach ($arrays as $names => $field) {
                        foreach ($fields as $field) {
                            if (!empty($field['default'])) {
                                $fields_defaults[$field['id']] = $field['default'];
                            }
                        }
                    }
                }
                $is_set_dev = get_option($dev_name, "");
                if (empty($is_set_dev)) {
                    OPTNNO_Ajax_Handler::defaults($dev_name, $fields_defaults);
                }
            }
        }
        public function create_menu() {
            $settings = self::$settings;
            $tabs = self::$tabs;
            if (is_array($settings)) {
                foreach ($settings as $dev_name => $settings) {
                    $tab_settings = isset($tabs[$dev_name]) ? $tabs[$dev_name] : array();
                    if ($settings['menu_type'] == 'menu') {
                        add_menu_page(
                            $settings['dev_title'],
                            $settings['menu_title'],
                            $settings['page_capability'],
                            $settings['page_slug'],
                            function () use ($settings) {
                                $this->page_content($settings);
                            },
                            $settings['icon_url'],
                            $settings['menu_priority']
                        );
                    } elseif ($settings['menu_type'] == 'submenu') {
                        add_submenu_page(
                            $settings['page_parent'],
                            $settings['dev_title'],
                            $settings['menu_title'],
                            $settings['page_capability'],
                            $settings['page_slug'],
                            function () use ($settings) {
                                $this->page_content($settings);
                            },
                            $settings['menu_priority']
                        );
                    }
                }
            }
        }
        public function page_content($settings) {
            require_once OPTNNO_TMPL.'setting-page.php';
            add_filter( 'admin_footer_text', [$this,'admin_footer_text'] );
        }
        public function admin_footer_text() {
            esc_html_e('Powered by <a href="http://shokrino.com/" target="_blank">ShokrinoDevOptions Framework</a>', 'admino-lite');
        }
        public function optionino_load_textdomain() {
            load_plugin_textdomain( OPTNNO_TEXTDOMAIN, false,basename( OPTNNO_PATH ) . '/languages/' );
        }
        public function wp_scripts() {
            wp_enqueue_script('jquery');
        }
        public function admin_scripts() {
            if (!did_action('wp_enqueue_media')) {
                wp_enqueue_media();
            }
            wp_enqueue_style('wp-color-picker');
            wp_enqueue_script('wp-color-picker');
            wp_enqueue_style( 'optionino-settings-page', OPTNNO_ASSETS.'css/setting.css', array() , OPTNNO_VERSION , 'all');
            wp_enqueue_script( 'optionino-settings-page', OPTNNO_ASSETS.'js/setting.js' , array() , OPTNNO_VERSION  , true);
            wp_localize_script( 'optionino-settings-page', 'data_optionino', array(
                'ajax_url' => admin_url( 'admin-ajax.php' ),
                'nonce' => wp_create_nonce('optionino_nonce')
            ));
        }
        public function wp_head() {

        }
    }
    new OPTNNO;
}