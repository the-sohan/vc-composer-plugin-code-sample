<?php

/**
 * @package shortcodes
 * @version 1.0
 */
/*
Plugin Name: WowShop Toolkit
Description: This is not just a plugin, 
Author: Sohan Chowdhury
Version: 1.0
Author URI: http://sohan.me
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Define
define( 'sohan_wowshop_ACC_URL' , WP_PLUGIN_URL . '/' . plugin_basename( dirname( __FILE__ ) ) . '/' );
define( 'sohan_wowshop_ACC_PATH' , plugin_dir_path( __FILE__ ) );





// slide custom post
add_action( 'init', 'sohan_wowshop_toolkit_custom_post' );
function sohan_wowshop_toolkit_custom_post() {
	register_post_type( 'ws-slide' , 
		array(
			'labels' => array(
				'name' => _( 'Slides' ),
				'singular_name' => __( 'Slide' )
			),
			'supports' => array('title', 'editor', 'thumbnail', 'page-attributes'),
			'public' => false,
			'show_ui' => true,
		)
	);
}






// Print Shortcode in widgets
add_filter( 'widget_text', 'do_shortcode' );


// VC addon load
require_once( sohan_wowshop_ACC_PATH . 'vc-addons.php' );


// Theme Shortcodes
require_once( sohan_wowshop_ACC_PATH . 'theme-shortcodes.php' );


// Shortcodes depended on Visual Composer
require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
if (is_plugin_active('js_composer/js_composer.php')) {
	//require_once( sohan_wowshop_ACC_PATH . 'theme-shortcodes/staff-shortcode.php' );
}

// Registering sohan_wowshop Toolkit Files
function sohan_wowshop_toolkit_files(){

	wp_enqueue_style( 'owl-carousel', plugin_dir_url( __FILE__ ) .'assets/css/owl.carousel.css' );
	wp_enqueue_style( 'sohan_wowshop_toolkit', plugin_dir_url( __FILE__ ) .'assets/css/sohan_wowshop_toolkit.css' );

	wp_enqueue_script( 'owl-carousel', plugin_dir_url( __FILE__ ) .'assets/js/owl.carousel.min.js', array('jquery'), '1.0', true );
}
add_action('wp_enqueue_scripts', 'sohan_wowshop_toolkit_files');



add_action( 'woocommerce_after_add_to_cart_button', 'content_after_addtocart_button');
function content_after_addtocart_button() {
	echo '<i class="fa fa-shopping-cart"></i>';
}

function sv_add_text_above_wc_shop_image() {
	echo '<h4 style="text-align: center;">Some sample text</h4>';
}
add_action('woocommerce_before_shop_loop_item_title', 'sv_add_text_above_wc_shop_image', 9);




