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
 * Plugin class. This class should be used to load and
 * validate options page settings
 *
 */
 
class Woo_Product_Carousel_Zoom_Settings {

	/**
	 * Unique identifier for the plugin.
	 *
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
	 * Custom tabs to admin
	 *
	 * @since    1.0.0
	 *
	 * @var      Array
	 */
	public $register_custom_tabs;

	
	/**
	 * Options page values
	 *
	 * @since    1.0.0
	 *
	 * @var      Array
	 */
    protected $options_carousel;
    protected $options_zoom;
    
	/**
	 * Register options page settings and admin side tabs
	 *
	 * @since     1.0.0
	 */
	private function __construct() {
		
		// Call $plugin_slug from public plugin class.
		$plugin = Woo_Product_Carousel_Zoom::get_instance();
		$this->plugin_slug = $plugin->get_plugin_slug();

		// Register page options
		add_action( 'admin_init', array( &$this, 'register_page_options') );
		
		// Get default plugin values
		$this->init();
		
		// Register admin side tabs
		$this->register_custom_tabs = array( 
			'woocz-tab-1' => __( 'Carousel Options', 'woo-product-carousel-and-zoom' ), 
			'woocz-tab-2' => __( 'Zoom Options', 'woo-product-carousel-and-zoom' ), 
		);
	}
	
	private function init() {
		
		// Set default values in Array
		$defaults = $this->get_defaults();
		
		// Check if exist `woocz_carousel_options` and set defaut values
		if ( FALSE === get_option( 'woocz_carousel_options' ) ){
			$this->options_carousel = wp_parse_args(get_option('woocz_carousel_options'), $defaults);        
		}else{
			$this->options_carousel = get_option( 'woocz_carousel_options' );
		}

		$this->options_zoom = get_option( 'woocz_zoom_options' );
	}
	
	/**
	 * Return default values
	 *
	 * @since     1.0.0
	 *
	 * @return    Array with default values
	 */
	private function get_defaults() {
		
		$defaults = array (
			'activate' => 'on',
			'navigation' => 'on',
            'dots' => 'on',
            'loop' => '',
			'opacity' => 'on',
			'margin' => '5',
			'number' => '3',
			'primary_color' => '#222222',
			'arrows_color' => '#ffffff'
        );
        
        return $defaults;
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
	 * Registering the Sections, Fields, and Settings.
	 *
	 * This function is registered with the 'admin_init' hook.
	 */
	public function register_page_options() {
		
		// Register unique id for tabs
		$tab_1 = $this->plugin_slug . '_woocz-tab-1';
		$tab_2 = $this->plugin_slug . '_woocz-tab-2';
		
		
		// Carousel Tab
		
	    add_settings_section( 'woocz_section', 'Carousel', array( $this, 'display_section' ), $tab_1 ); // id, title, display cb, page
	    
	    add_settings_field( 'woocz_navigation_field', __( 'Show Navigation', 'woo-product-carousel-and-zoom' ), array( $this, 'navigation_carousel_field' ), $tab_1, 'woocz_section' ); // id, title, display cb, page, section
	    
	    add_settings_field( 'woocz_dots_field', __( 'Show Pagination', 'woo-product-carousel-and-zoom' ), array( $this, 'dots_carousel_field' ), $tab_1, 'woocz_section' ); // id, title, display cb, page, section
	    
	    add_settings_field( 'woocz_loop_field', __( 'Infinite Loop', 'woo-product-carousel-and-zoom' ), array( $this, 'loop_carousel_field' ), $tab_1, 'woocz_section' ); // id, title, display cb, page, section
	    
	    add_settings_field( 'woocz_opacity_field', __( 'Opacity Effect', 'woo-product-carousel-and-zoom' ), array( $this, 'opacity_carousel_field' ), $tab_1, 'woocz_section' ); // id, title, display cb, page, section

	    add_settings_field( 'woocz_margins_field', __( 'Thumbnails Margins', 'woo-product-carousel-and-zoom' ), array( $this, 'margin_carousel_field' ), $tab_1, 'woocz_section' ); // id, title, display cb, page, section
	    
	    add_settings_field( 'woocz_range_field', __( 'Number of Thumbnails', 'woo-product-carousel-and-zoom' ), array( $this, 'number_carousel_field' ), $tab_1, 'woocz_section' ); // id, title, display cb, page, section
		
	    add_settings_field( 'woocz_colors_h2_field', '', array( $this, 'colors_h2_field' ), $tab_1, 'woocz_section' ); // id, title, display cb, page, section

	    add_settings_field( 'woocz_primary_color_field', __( 'Primary Color', 'woo-product-carousel-and-zoom' ), array( $this, 'primary_color_field' ), $tab_1, 'woocz_section' ); // id, title, display cb, page, section
	    
	    add_settings_field( 'woocz_bg_field', __( 'Navigation Arrows Color', 'woo-product-carousel-and-zoom' ), array( $this, 'arrows_color_field' ), $tab_1, 'woocz_section' ); // id, title, display cb, page, section
	     
	    // Register Settings
	    register_setting( $tab_1, 'woocz_carousel_options', array( $this, 'validate_options' ) ); // option group, option name, sanitize cb 
	    

	    // Zoom Tab
		
	    add_settings_section( 'woocz_section_zoom', __( 'Zoom', 'woo-product-carousel-and-zoom' ), array( $this, 'display_section' ), $tab_2 ); // id, title, display cb, page

	    add_settings_field( 'woocz_position_field', __( 'Zoom Position', 'woo-product-carousel-and-zoom' ), array( $this, 'position_zoom_field' ), $tab_2, 'woocz_section_zoom' ); // id, title, display cb, page, section
	  	     
	    // Register Settings
	    register_setting( $tab_2, 'woocz_zoom_options', array( $this, 'validate_options' ) ); // option group, option name, sanitize cb 
	    
	}


	/**
	 * Functions that display the fields.
	 */ 
	
	public function navigation_carousel_field() { 
	     
	    $val = ( $this->options_carousel['navigation'] ) ? $checked = ' checked="checked" ' : '';

	    $html = '<label>';
	    $html .= '<input type="checkbox" name="woocz_carousel_options[navigation]" ' . $checked . ' >'. __( 'Show Navigation arrows', 'woo-product-carousel-and-zoom' );
		$html .= '<p><span class="description">' . __( 'Check this option to show navigation arrows', 'woo-product-carousel-and-zoom' ) . '</span></p>';
		$html .= '</label>';
		echo $html;
		
	}  
	
	public function dots_carousel_field() { 
	     
	    $val = ( $this->options_carousel['dots'] ) ? $checked = ' checked="checked" ' : '';

	    $html = '<label>';
	    $html .= '<input type="checkbox" name="woocz_carousel_options[dots]" ' . $checked . ' >'. __( 'Show Thumbnails Pagination under the Carousel', 'woo-product-carousel-and-zoom' );
		$html .= '<p><span class="description">' . __( 'Check this option to display pagination dots below the carousel', 'woo-product-carousel-and-zoom' ) . '</span></p>';
		$html .= '</label>';
		echo $html;
		
	}   
	
	public function loop_carousel_field() { 
	     
	    $val = ( $this->options_carousel['loop'] ) ? $checked = ' checked="checked" ' : '';

	    $html = '<label>';
	    $html .= '<input type="checkbox" name="woocz_carousel_options[loop]" ' . $checked . ' >'. __( 'Infinite Loop', 'woo-product-carousel-and-zoom' );
		$html .= '<p><span class="description">' . __( 'Check this option to infinite loop', 'woo-product-carousel-and-zoom' ) . '</span></p>';
		$html .= '</label>';
		echo $html;
		
	}   
	
	public function opacity_carousel_field() { 
	     
	    $val = ( $this->options_carousel['opacity'] ) ? $checked = ' checked="checked" ' : '';

	    $html = '<label>';
	    $html .= '<input type="checkbox" name="woocz_carousel_options[opacity]" ' . $checked . ' >'. __( 'Thumbnails Opacity', 'woo-product-carousel-and-zoom' );
		$html .= '<p><span class="description">' . __( 'Opacity effect hovering the mouse behind thumbnails', 'woo-product-carousel-and-zoom' ) . '</span></p>';
		$html .= '</label>';
		echo $html;
		
	} 
	
	public function margin_carousel_field() { 
	     
    	$options = $this->options_carousel['margin'];
		$items = array(
			array( 'no-margin', __( 'No Margins', 'woo-product-carousel-and-zoom' ) ), 
			array( 'regular-margin', __( 'Regular Margin', 'woo-product-carousel-and-zoom' ) ), 
			array( 'big-margin', __( 'Big Margin', 'woo-product-carousel-and-zoom' ) )
		);
		
		$html = "<select id='drop_down1' name='woocz_carousel_options[margin]' style='width: 15em;'>";
		foreach($items as $item) {
			
			if( $item[0] == 'no-margin') $item_value = 0;
			if( $item[0] == 'regular-margin') $item_value = 8;
			if( $item[0] == 'big-margin') $item_value = 16;
			
			$selected = ($options == $item_value) ? 'selected="selected"' : '';
			$html .= "<option value='$item_value' $selected>$item[1]</option>";
		}
		$html .= "</select>";
		$html .= '<p><span class="description">' . __( 'Choose the margin between thumbnails', 'woo-product-carousel-and-zoom' ) . '</span></p>';
		echo $html;
		
	} 
	
	public function number_carousel_field() { 

	    $val = ( $this->options_carousel['number'] ) ? $this->options_carousel['number'] : '';
	    $html = '<input type="text" name="woocz_carousel_options[number]" value="' . $val . '" class="range-slider">';
	    $html .= '<p><span class="description">' . __( 'Choose the number of thumbnails to show in the carousel', 'woo-product-carousel-and-zoom' ) . '</span></p>';
		echo $html;
	}
		
	function  colors_h2_field() {
		echo '<h3>' . __( 'Colors', 'woo-product-carousel-and-zoom' ) . '</h3>';
	}

	public function primary_color_field() { 
	     
	    $val = ( isset( $this->options_carousel['primary_color'] ) ) ? $this->options_carousel['primary_color'] : '';
	    $html = '<input type="text" name="woocz_carousel_options[primary_color]" value="' . $val . '" class="color-picker" >';
	    $html .= '<p><span class="description">' . __( 'Set the primary color of buttons: <code>Navigation, Pagination...</code>', 'woo-product-carousel-and-zoom' ) . '</span></p>';
		echo $html;
	     
	}

	public function arrows_color_field() { 
	     
	    $val = ( isset( $this->options_carousel['arrows_color'] ) ) ? $this->options_carousel['arrows_color'] : '';
	    $html = '<input type="text" name="woocz_carousel_options[arrows_color]" value="' . $val . '" class="color-picker" >';
	    $html .= '<p><span class="description">' . __( 'Set the color for the carousel arrows', 'woo-product-carousel-and-zoom' ) . '</span></p><br>';
	    
	    $val = ( $this->options_carousel['transparent_background'] ) ? $checked = ' checked="checked" ' : '';
	    $html .= '<label>';
	    $html .= '<input type="checkbox" name="woocz_carousel_options[transparent_background]" ' . $checked . ' >'. __( 'Transparent Background', 'woo-product-carousel-and-zoom' );
		$html .= '<p><span class="description">' . __( 'Transparent background for navigation arrows', 'woo-product-carousel-and-zoom' ) . '</span></p>';
		$html .= '</label>';

		echo $html;
	     
	}
	
	public function position_zoom_field() { 
	     
    	$options = $this->options_zoom['position'];
		
		$items = array(
		array( 'above', __( 'Above Image', 'woo-product-carousel-and-zoom' ) ), 
		array( 'right', __( 'Right', 'woo-product-carousel-and-zoom' ) )
		);
	
		$html = "<select id='drop_down1' name='woocz_zoom_options[position]' style='width: 15em;'>";
		foreach($items as $item) {
			$selected = ($options == $item[0]) ? 'selected="selected"' : '';
			$html .= "<option value='$item[0]' $selected>$item[1]</option>";
		}
		$html .= "</select>";
		$html .= '<p><span class="description">' . __( 'Choose the zoom effect position', 'woo-product-carousel-and-zoom' ) . '</span></p>';
		echo $html;
		
	} 


	/**
     * Callback function for settings section
     */
    public function display_section() { /* Leave blank */ } 
	 
	 
	/**
	 * Function that will validate all fields.
	 */
	public function validate_options( $fields ) { 
	    
	    // If reset to default values
	    if (isset($_POST['reset'])) {

		    $valid_fields = $this->get_defaults();

		     foreach( $valid_fields as $key => $value ) {

		    	$valid_fields[$key] = $value;
		
			}

	    }
	    
	    // Validate all fields
	    else{
	    	
	    	$valid_fields = array();
	
		    foreach( $fields as $key => $value ) {
			    
			    // Check if is a valid hex color
			    if( $key === 'primary_color' || $key === 'arrows_color'):
				
					if( FALSE === $this->check_color( $value ) ) {
			     
			        	// Set the error message
						add_settings_error( 'woocz_carousel_options', 'woocz_bg_error',  __('Insert a valid color please', 'woo-product-carousel-and-zoom'), 'error' ); // $setting, $code, $message, $type
						// Get the previous valid value
						$valid_fields[$key] = $this->options_carousel[$key];
			     
			    	} else {
			       		 $valid_fields[$key] = $value;  
			    	}
		    	
		    	else:
		    		$valid_fields[$key] = sanitize_text_field( $value );
		    	endif;
		    
			}
		}

	    return apply_filters( 'validate_options', $valid_fields, $fields);
	    
	}
	 
	/**
	 * Function that will check if value is a valid HEX color.
	 */
	public function check_color( $value ) { 
	     
	    if ( preg_match( '/^#[a-f0-9]{6}$/i', $value ) ) { // if user insert a HEX color with #     
	        return true;
	    }
	     
	    return false;
	}
}

Woo_Product_Carousel_Zoom_Settings::get_instance();