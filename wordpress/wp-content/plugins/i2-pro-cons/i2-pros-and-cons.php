<?php

/**
 * Plugin Name: i2 Pros & Cons
 * Plugin URI: https://themesfirst.com/
 * Description: The plugin will allow you to show pro & cons in beautiful style.
 * Author: Ibrar Hussain
 * Author URI: https://github.com/imibrar
 * Text Domain: i2-pros-and-cons
 * Version: 1.3.1
 * License: GPLv3
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

define('I2PC_MORE_THEMES_PLUGINS_URL', 'https://www.themesfirst.com');
define('I2PC_VER', '1.3.1');
define('I2PC_CSS_VER', '1.3.1');

//include_once('include/ibr-test-block.php');
include_once('include/shortcodes.php');
include_once('include/setting.php');
include_once('include/custom-style.php');



include_once('include/ce-popup.php');


if (!function_exists('i2_pros_cons_block_assets')) {
    function i2_pros_cons_block_assets()
    { // phpcs:ignore
        // Styles.
        wp_enqueue_style(
            'i2-pros-cons-block-style-css', // Handle.
            plugins_url('dist/blocks.editor.build.css',  __FILE__),
            array('wp-editor'),
            I2PC_VER
        );
        wp_enqueue_style('i2-pros-and-cons-custom-fonts-icons-style', plugins_url('/dist/fonts/styles.css', __FILE__), null, I2PC_CSS_VER);
    }
}

// Hook: Frontend assets.
add_action('enqueue_block_assets', 'i2_pros_cons_block_assets');


if (!function_exists('i2_pros_cons_setup')) {
    function i2_pros_cons_setup()
    {

        wp_enqueue_script(
            'i2_pro_cons_script', // Handle.
            plugins_url('/dist/blocks.build.js',  __FILE__),
            array('wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor'), // Dependencies, defined above.
            I2PC_VER,
            true // Enqueue the script in the footer.
        );
        $options = get_option('i2_pros_and_cons', i2_pros_and_cons_options_default());
        $i2_pros_cons_icons = array(
            'useIcon' => $options['use_icons'],
            'pros' => $options['pros_icon'],
            'cons' => $options['cons_icon'],
            'useIconInHeader' => $options['use_heading_icon'],
            'prosHeaderIcon' => $options['heading_pros_icon'],
            'consHeaderIcon' => $options['heading_cons_icon']
        );
        wp_localize_script('i2_pro_cons_script', 'i2_pro_cons_icons', $i2_pros_cons_icons);


        wp_enqueue_style('i2_pro_cons_editor_style', plugins_url('/dist/blocks.editor.build.css', __FILE__), null, I2PC_CSS_VER);
    }
}

add_action('enqueue_block_editor_assets', 'i2_pros_cons_setup');
add_action('init', function () {
    // Skip block registration if Gutenberg is not enabled/merged.
    if (!function_exists('register_block_type')) {
        return;
    }
    register_block_type('i2-pros-and-cons/basic', array(
        'editor_script' => 'i2_pro_cons_script',
        'render_callback' => 'i2_pros_and_cons',
        'attributes' => [
            'pros' => [
                'type' => 'string',
                'default' => ''
            ],
            'cons' => [
                'type' => 'string',
                'default' => ''
            ],
            'pros_title' => [
                'type' => 'string',
                'default' => __('Pros', 'i2-pros-and-cons')
            ],
            'cons_title' => [
                'type' => 'string',
                'default' => __('Cons', 'i2-pros-and-cons')
            ],
            'show_button' => [
                'type' => 'boolean',
                'default' => false
            ],
            'link_text' => [
                'type' => 'string',
                'default' => __('Buy on Amazon', 'i2-pros-and-cons')
            ],
            'link' => [
                'type' => 'string',
                'default' => ''
            ],
            'show_title' => [
                'type' => 'boolean',
                'default' => false
            ],
            'title' => [
                'type' => 'string',
                'default' => __('Pros & Cons', 'i2-pros-and-cons')
            ],
            'pros_icon' => [
                'type' => 'string',
                'default' => ''
            ],
            'cons_icon' => [
                'type' => 'string',
                'default' => ''
            ],
            'button_icon' => [
                'type' => 'string',
                'default' => ''
            ],
            'heading_pros_icon' => [
                'type' => 'string',
                'default' => ''
            ],
            'heading_cons_icon' => [
                'type' => 'string',
                'default' => ''
            ],
            'use_heading_icon' => [
                'type' => 'string',
                'default' => ''
            ],
            'button_display_block' => [
                'type' => 'boolean',
                'default' => false
            ],
            'className' => [
                'type' => 'string',
                'default' => ''
            ]

        ]
    ));
});

add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'i2_pros_and_cons_page_settings_link');
function i2_pros_and_cons_page_settings_link($links)
{
    $links[] = '<a href="' .
        admin_url('admin.php?page=i2_pros_and_cons') .
        '">' . __('Settings', 'i2-pros-and-cons') . '</a>';
    return $links;
}

// add_action('rest_api_init', function () {
// 	global $wp_version;
//    if (version_compare($wp_version, '5.5.1', '<')) {
// 		register_rest_route( 'wp/v2/block-renderer', '/i2-pros-and-cons/basic',array(
// 			'methods'  => 'POST',
// 			'callback' => 'i2_pros_and_cons'
// 		));
//  	}
// });