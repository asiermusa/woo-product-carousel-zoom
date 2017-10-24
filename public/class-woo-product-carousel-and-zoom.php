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
 * frontend side of the WordPress site.
 *
 * If you're interested in introducing admin or dashboard
 * functionality, then refer to `class-woocommerce-product-carousel-and-zoom-admin.php`
 *
 */
class Woo_Product_Carousel_Zoom {
	
	/**
	 * Unique identifier for your plugin.
	 *
	 *
	 * The variable name is used as the text domain when internationalizing strings
	 * of text. Its value should match the Text Domain file header in the main
	 * plugin file.
	 *
	 * @since    0.1.0
	 *
	 * @var      string
	 */
	protected $plugin_slug = 'woocz';

	/**
	 * Instance of this class.
	 *
	 * @since    0.1.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Initialize the plugin by setting localization and loading public scripts
	 * and styles.
	 *
	 * @since     0.1.0
	 */
	private function __construct() {

		// Load plugin text domain
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		// Display the admin notification
		add_action( 'admin_notices', array( $this, 'admin_notice_activation' ) );
		
		// Add admin scripts and styles
		add_action( 'wp_enqueue_scripts', array( $this, 'front_scripts_styles' ) );
		
		// Load Woo Templates
        //add_filter( 'woocommerce_locate_template', array( $this, 'woocommerce_locate_template' ), 10, 3 );
		add_filter( 'woocommerce_locate_template', array( $this, 'woocommerce_locate_template' ), 10, 3 );
	}
	
	
	/**
	* Make sure that WooCommerce is active
	**/
	public static function is_woocommerce() {
		return in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) );
	}

	/**
	 * Enqueue admin scripts and styles
	 *
	 * @since    0.1.0
	 */
	public function woocommerce_locate_template( $template, $template_name, $template_path ) {
	 
	  global $woocommerce;

	  $_template = $template;
	 
	  if ( ! $template_path ) $template_path = $woocommerce->template_url;
	 
	  $plugin_path  = WOOCZ_DIR . 'woo-templates/';
	 
	  // Look within passed path within the theme - this is priority
	  $template = locate_template(
	    array(
	      $template_path . $template_name,
	      $template_name
	    )
	 
	  );
	  
	  // Modification: Get the template from this plugin, if it exists
	  // if ( ! $template && file_exists( $plugin_path . $template_name ) )
	  
	  if ( file_exists( $plugin_path . $template_name ) )
	  
	    $template = $plugin_path . $template_name;

	  // Use default template
	 
	  if ( ! $template )
	 
	    $template = $_template;

	  // Return what we found
	 
	  return $template;
	}
	

	/**
	 * Enqueue admin scripts and styles
	 *
	 * @since    0.1.0
	 */
	public function front_scripts_styles() {
		
		wp_enqueue_script( 'woocz-scripts',
            WOOCZ_PLUGIN_URL . 'assets/public/js/libs.min.js',
            array ('jquery'),
            WOOCZ_VERSION,
            false 
        );
        
        wp_enqueue_script( 'woocz-script-custom',
            WOOCZ_PLUGIN_URL . 'assets/public/js/custom.js',
            array ('jquery'),
            WOOCZ_VERSION,
            false 
        );
        
        wp_enqueue_style( 'woocz-styles', 
        	WOOCZ_PLUGIN_URL . 'assets/public/css/styles.css', 
        	false, 
        	WOOCZ_VERSION
        );
        
        wp_enqueue_style( 'woocz-linear-icons', 
        	'https://cdn.linearicons.com/free/1.0.0/icon-font.min.css', 
        	false, 
        	WOOCZ_VERSION
        );
        
        // Inline css
		wp_enqueue_style( 'woocz-custom-styles', 
        	WOOCZ_PLUGIN_URL . 'assets/public/css/custom.styles.css', 
        	false, 
        	WOOCZ_VERSION
        );
        
		wp_add_inline_style('woocz-custom-styles', $this->custom_styles() );
	
	}
	
	
	/**
	 * Custom css
	 *
	 * @since    1.0.0
	 *
	 * @return   Custom css declarations.
	 */
	private function custom_styles() {
		
		$carousel = get_option( 'woocz_carousel_options' );
		
		$arrows_back = ( $carousel['transparent_background'] === 'on' ) ? 'transparent' : '';
		$opacity = ( $carousel['opacity'] === 'on' ) ? 'opacity: 0.4;' : 'opacity: 1;';
		
		if( $arrows_back == '' ){
			$custom_css = '.owl-carousel .owl-prev,.owl-carousel .owl-next {color: ' . esc_attr($carousel['arrows_color']) . ' !important; background: rgba(' . $this->hex2rgb($carousel['primary_color']) . ', 0.4) !important;} .owl-carousel .owl-prev:hover,.owl-carousel .owl-next:hover { background: ' . $carousel['primary_color'] .' !important; }';
			
		}else{
			$custom_css = '.owl-carousel .owl-prev,.owl-carousel .owl-next {color: ' . esc_attr($carousel['arrows_color']) . ' !important; background: transparent !important;} .owl-carousel .owl-prev:hover,.owl-carousel .owl-next:hover { background: transparent !important; }';
			
		}

		$custom_css .= '.owl-theme .owl-dots .owl-dot { background: rgba(' . $this->hex2rgb($carousel['primary_color']) . ', 0.4) !important; }.owl-theme .owl-dots .owl-dot.active { background: rgba(' . $this->hex2rgb($carousel['primary_color']) . ', 1) !important; }.owl-theme .owl-dots .owl-dot:hover { background: rgba(' . $this->hex2rgb($carousel['primary_color']) . ', 0.8) !important; }.owl-carousel .owl-item img { ' . esc_attr($opacity). ' }.owl-carousel { margin-top: ' . esc_attr($carousel['margin']). 'px; }';

		return $custom_css;
	}
	
	
	/**
	 * Hex values
	 *
	 * @since    1.0.0
	 *
	 * @return   rgb values.
	 */
	public function hex2rgb($hex) {
	   
	   $hex = str_replace("#", "", $hex);

	   if(strlen($hex) == 3) {
	      $r = hexdec(substr($hex,0,1).substr($hex,0,1));
	      $g = hexdec(substr($hex,1,1).substr($hex,1,1));
	      $b = hexdec(substr($hex,2,1).substr($hex,2,1));
	   } else {
	      $r = hexdec(substr($hex,0,2));
	      $g = hexdec(substr($hex,2,2));
	      $b = hexdec(substr($hex,4,2));
	   }
	   $rgb = array($r, $g, $b);
	
	   return $rgb[0].','.$rgb[1].','.$rgb[2];
	}

	/**
	 * Return the plugin slug.
	 *
	 * @since    1.0.0
	 *
	 * @return    Plugin slug variable.
	 */
	public function get_plugin_slug() {

		return $this->plugin_slug;
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
	 * Fired when the plugin is activated.
	 *
	 * @since    1.0.0
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses
	 *                                       "Network Activate" action, false if
	 *                                       WPMU is disabled or plugin is
	 *                                       activated on an individual blog.
	 */
	public static function activate( $network_wide ) {

		if ( function_exists( 'is_multisite' ) && is_multisite() ) {

			if ( $network_wide  ) {

				// Get all blog ids
				$blog_ids = self::get_blog_ids();

				foreach ( $blog_ids as $blog_id ) {

					switch_to_blog( $blog_id );
					self::single_activate();
				}

				restore_current_blog();

			} else {
				self::single_activate();
			}

		} else {
			self::single_activate();
		}

	}

	/**
	 * Fired when the plugin is deactivated.
	 *
	 * @since    1.0.0
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses
	 *                                       "Network Deactivate" action, false if
	 *                                       WPMU is disabled or plugin is
	 *                                       deactivated on an individual blog.
	 */
	public static function deactivate( $network_wide ) {

		if ( function_exists( 'is_multisite' ) && is_multisite() ) {

			if ( $network_wide ) {

				// Get all blog ids
				$blog_ids = self::get_blog_ids();

				foreach ( $blog_ids as $blog_id ) {

					switch_to_blog( $blog_id );
					self::single_deactivate();

				}

				restore_current_blog();

			} else {
				self::single_deactivate();
			}

		} else {
			self::single_deactivate();
		}

	}

	/**
	 * Get all blog ids of blogs in the current network that are:
	 * - not archived
	 * - not spam
	 * - not deleted
	 *
	 * @since    1.0.0
	 *
	 * @return   array|false    The blog ids, false if no matches.
	 */
	private static function get_blog_ids() {

		global $wpdb;

		// get an array of blog ids
		$sql = "SELECT blog_id FROM $wpdb->blogs
			WHERE archived = '0' AND spam = '0'
			AND deleted = '0'";

		return $wpdb->get_col( $sql );

	}

	/**
	 * Fired for each blog when the plugin is activated.
	 *
	 * @since    1.0.0
	 */
	private static function single_activate() {

		if ( false == get_option( 'woocz-display-activation-message' ) ) {
			add_option( 'woocz-display-activation-message', true );
		}
	}

	/**
	 * Fired for each blog when the plugin is deactivated.
	 *
	 * @since    0.4.0
	 */
	private static function single_deactivate() {

		delete_option( 'woocz-display-activation-message' );

	}

	/**
	 * Display notice message when activating the plugin.
	 *
	 * @since 1.0.0
	 */
	public function admin_notice_activation() {

		$screen = get_current_screen();

		if ( true == get_option( 'woocz-display-activation-message' ) && 'plugins' == $screen->id && Woo_Product_Carousel_Zoom::is_woocommerce() === TRUE ){
			
			$plugin = self::get_instance();

			$html  = '<div class="notice notice-success is-dismissible">';
			$html .= '<p>';
			$html .= '<h3>Woo Product Carousel and Zoom</h3>';
			$html .= sprintf( __( 'Thanks for using the <code>Woo Product Carousel and Zoom</code> plugin. Take a moment and configure the options in the <strong><a href="%s">Settings Page.</a></strong>', 'woo-product-carousel-and-zoom' ), 
			admin_url( 'admin.php?page=' . $plugin->get_plugin_slug() ) );
			$html .= '</p>';
			$html .= '</div>';

			echo $html;
			
			delete_option( 'woocz-display-activation-message' );
		}
		
		if ( Woo_Product_Carousel_Zoom::is_woocommerce() === FALSE ) {
			$plugin = self::get_instance();

			$html  = '<div class="notice notice-error is-dismissible">';
			$html .= '<p>';
			$html .= __( '<b>WooCommerce</b> is required to use <b>Woo Product Carousel and Zoom</b> plugin.', 'woo-product-carousel-and-zoom' );
			$html .= '</p>';
			$html .= '</div>';

			echo $html;
		}
	}

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {
		
		$locale = apply_filters( 'plugin_locale', get_locale(), 'woocz' );
				
		load_textdomain( 'woo-product-carousel-and-zoom', WOOCZ_DIR . '/languages/woo-product-carousel-and-zoom-' . $locale . '.mo' );
		load_plugin_textdomain( 'woo-product-carousel-and-zoom', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );
		
	}

}
