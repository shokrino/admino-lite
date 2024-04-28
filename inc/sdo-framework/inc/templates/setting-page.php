<?php defined( 'SHKOFPATH' ) || exit;
/**
 * Builder Class
 *
 * @version 1.0.0
 * @since 1.0.0
 *
 */
$shkof_instance = new SHKOF();
$shkof_instance->admin_scripts();
SHKOF_Builder::container_start();
SHKOF_Builder::title($settings['dev_title']);
SHKOF_Builder::section_start($settings['dev_name']);
SHKOF_Builder::tab_start($settings['dev_name'],$settings['dev_version']);
SHKOF_Builder::tab_buttons($settings['dev_name']);
SHKOF_Builder::tab_end();
SHKOF_Builder::form_start();
SHKOF_Builder::form_fields($settings['dev_name']);
SHKOF_Builder::form_end($settings['dev_name']);
SHKOF_Builder::section_end();
SHKOF_Builder::loading();
SHKOF_Builder::container_end();