<?php defined( 'ABSPATH' ) || exit;
/**
 * Plugin Name: ShokrinoDevOptions Framework
 * Plugin URI: https://shokrino.com/
 * Author: Shokrino Team
 * Author URI: https://shokrino.com/
 * Version: 1.0.0
 * Text Domain: shkof
 * Domain Path: /languages
 * Description: Professional Tool to develop your WordPress theme and plugin easier
 * @package   SHKOF Framework - WordPress Options
 * @link      https://shokrino.com
 * @copyright 2024 Shokrino
 */
define('SHKOFPATH' , defined( 'ABSPATH' ));
defined( 'SHKOFPATH' ) || exit;
define('SHKOF_PATH' , plugin_dir_path(__FILE__));
define('SHKOF_URL' , plugin_dir_url(__FILE__));
define('SHKOF_INC' , SHKOF_PATH.'inc/');
define('SHKOF_CLSS' , SHKOF_PATH.'inc/classes/');
define('SHKOF_TMPL' , SHKOF_PATH.'inc/templates/');
define('SHKOF_ASSETS' , SHKOF_URL.'assets/');
$plugin_data_name = get_file_data(__FILE__, array('Plugin Name' => 'Plugin Name'), false);
$plugin_data = get_file_data(__FILE__, array('Version' => 'Version'), false);
$current_theme = wp_get_theme()->get( 'Name' );
define('SHKOF_NAME', $plugin_data_name['Plugin Name']);
define('SHKOF_VERSION', $plugin_data['Version']);
define('SHKOF_CURRENT_THEME', $current_theme);
define('SHKOF_CURRENT_PHP',substr(phpversion(), 0, strpos(phpversion(), '.', strpos(phpversion(), '.') + 1)));

include_once SHKOF_INC.'functions.php';
include_once SHKOF_CLSS.'secure.class.php';
include_once SHKOF_CLSS.'builder.class.php';
include_once SHKOF_CLSS.'ajax.class.php';
include_once SHKOF_CLSS.'core.class.php';

# customize framework
include_once 'config.php';