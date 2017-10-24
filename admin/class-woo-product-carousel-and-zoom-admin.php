<?php
/*
 * WooCommerce Product Carousel and Zoom
 *
 * @package   Woo_Product_Carousel_Zoom
 * @author    Asier Musatadi <info@biklik.net>
 * @license   GPL-2.0+
 * @link      http://www.biklik.net
 * @copyright 2017 http://www.biklik.net
 *
 * Plugin class. This class should ideally be used to work with the
 * admin side of the WordPress site.
 *
 * To use frontend side functionality, then refer to `class-woocommerce-product-carousel-and-zoom.php`
 *
 */
 
class Woo_Product_Carousel_Zoom_Admin {

	/**
	 * Unique identifier for the plugin.
	 *
	 * Call $plugin_slug from public plugin class later.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_slug = null;

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Slug of the plugin screen.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_screen_hook_suffix = null;

	/**
	 * Initialize the plugin by loading admin scripts & styles,
	 * adding a settings page and menu.
	 *
	 * @since     1.0.0
	 */
	private function __construct() {

		// Call $plugin_slug from public plugin class.
		$plugin = Woo_Product_Carousel_Zoom::get_instance();
		$this->plugin_slug = $plugin->get_plugin_slug();
		
		// Add admin side script & styles
		add_action( 'admin_enqueue_scripts', array( $this, 'woocz_scripts_styles' ) );
		
		// Add the options page and menu item.
		add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu' ) );
		
		// Add the options page and menu item.
		require_once( plugin_dir_path( __FILE__ ) . 'includes/settings.php' );
		
		// Add an action link pointing to the options page.
		$plugin_basename = plugin_basename( plugin_dir_path( realpath( dirname( __FILE__ ) ) ) . 'woo-product-carousel-and-zoom.php' );
		add_filter( 'plugin_action_links_' . $plugin_basename, array( $this, 'add_action_links' ) );
	}
	
	
	function woocz_scripts_styles( $hook ) {

		// Add Range slider JS
        wp_enqueue_script( 'woocz-admin-scripts',
        	WOOCZ_PLUGIN_URL . 'assets/admin/js/admin.libs.min.js',
			array( 'jquery' ),
			WOOCZ_VERSION,
			false 
		);
		
		// Add Range slider CSS
        wp_enqueue_style( 'woocz-admin-styles',
        	WOOCZ_PLUGIN_URL . 'assets/admin/css/admin.styles.css',
        	false, 
			WOOCZ_VERSION
		);
		
		// Add the color picker css file       
        wp_enqueue_style( 'wp-color-picker' ); 
         
        // Include our custom jQuery file with WordPress Color Picker dependency
        wp_enqueue_script( 'woocz-admin-script-custom',
        	WOOCZ_PLUGIN_URL . 'assets/admin/js/admin.custom.js',
			array( 'jquery', 'wp-color-picker' ),
			WOOCZ_VERSION,
			true 
		);    
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @since    1.0.1
	 */
	public function add_plugin_admin_menu() {

		/*
		 * Add a settings page for this plugin to the Settings menu.
		 *
		 * NOTE:  Alternative menu locations are available via WordPress administration menu functions.
		 * Administration Menus: http://codex.wordpress.org/Administration_Menus
		 *
		 */
		 $this->plugin_screen_hook_suffix = add_submenu_page(
			'woocommerce', 
			__( 'Woo Product Carousel and Zoom', 'woo-product-carousel-and-zoom' ),
			__( 'Woo Product Carousel and Zoom', 'woo-product-carousel-and-zoom' ),
			'manage_options', 
			$this->plugin_slug,
			array( $this, 'display_plugin_admin_page' )
		 );
	}

	/**
	 * Render the settings page for this plugin.
	 *
	 * @since    1.0.0
	 */
	public function display_plugin_admin_page() {
		include_once( 'views/admin.php' );
	}
	
	/**
	 * Add settings action link to the plugins page.
	 *
	 * @since    1.0.1
	 */
	public function add_action_links( $links ) {

		return array_merge(
			array(
				'settings' => '<a href="' . admin_url( 'admin.php?page=' . $this->plugin_slug ) . '">' . __( 'Settings', 'woo-product-carousel-and-zoom' ) . '</a>'
			),
			$links
		);
	}
}