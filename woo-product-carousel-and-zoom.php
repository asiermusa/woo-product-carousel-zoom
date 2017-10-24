<?php	
/*
WooCommerce Product Carousel and Zoom

@package   Woo_Product_Carousel_Zoom
@author    Asier Musatadi <info@biklik.net>
@license   GPL-2.0+
@link      http://www.biklik.net
@copyright 2017 http://www.biklik.net

Plugin Name: Woo Product Carousel and Zoom
Plugin URI:  https://es.wordpress.org/plugins/woo-product-carousel-and-zoom/
Description: Add an easy product carousel and zoom to product page on WooCommerce
Version:     1.0.4
Author:      biklik
Author URI:  http://www.biklik.net
Text Domain: woo-product-carousel-and-zoom
Domain Path: /languages
License:     GPL2
 
Woo Product Carousel and Zoom is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.
 
Woo Product Carousel and Zoom is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License
along with Woo Product Carousel and Zoom. If not, see {License URI}.
*/

/* If this file is called directly, abort.*/
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/*
 * Frontend Functionality
 */

require_once( plugin_dir_path( __FILE__ ) . 'public/class-woo-product-carousel-and-zoom.php' );

/*
 * Register hooks that are fired when the plugin is activated or deactivated.
 * When the plugin is deleted, the uninstall.php file is loaded.
 */
register_activation_hook( __FILE__, array( 'Woo_Product_Carousel_Zoom', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'Woo_Product_Carousel_Zoom', 'deactivate' ) );

add_action( 'plugins_loaded', array( 'Woo_Product_Carousel_Zoom', 'get_instance' ) );

defined ( 'WOOCZ_PLUGIN_URL' ) || define ( 'WOOCZ_PLUGIN_URL', plugins_url ( '/', __FILE__ ) );
defined ( 'WOOCZ_VERSION' ) || define ( 'WOOCZ_VERSION', '1.0.0' );
defined ( 'WOOCZ_DIR' ) || define ( 'WOOCZ_DIR', plugin_dir_path ( __FILE__ ) );

/* 
 * Dashboard and Admin Functionality
 */

if ( is_admin() && Woo_Product_Carousel_Zoom::is_woocommerce() === TRUE ) {
	
	require_once( plugin_dir_path( __FILE__ ) . 'admin/class-woo-product-carousel-and-zoom-admin.php' );
	add_action( 'plugins_loaded', array( 'Woo_Product_Carousel_Zoom_Admin', 'get_instance' ) );

}
