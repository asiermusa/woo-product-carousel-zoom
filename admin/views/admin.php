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
 * Represents the view for the administration dashboard.
 *
 * This includes the header, options, and other information that should provide
 * The User Interface to the end user.
 *
 */
?>

<!-- Create a header in the default WordPress 'wrap' container -->
<div class="wrap woocz-admin-view">
	
	<?php 
	// Return an instances to use
	$plugin = Woo_Product_Carousel_Zoom::get_instance();
	$settings = Woo_Product_Carousel_Zoom_Settings::get_instance();
	?>
	
	<div id="icon-themes" class="icon32"></div>
	
	<h2><?php echo sprintf( __( '%s Settings', 'woo-product-carousel-and-zoom' ), get_admin_page_title() ); ?></h2>
	
	<?php settings_errors(); ?>

	<?php
	// Load active tab
	if ( isset( $_GET['tab'] ) ) {
		$active_tab = $_GET['tab'];
	} else {
		$active_tab = 'woocz-tab-1';
	}
	?>

	<h2 class="nav-tab-wrapper">
		
		<?php 
		// Load allregistered tabs `$settings->register_custom_tabs`
		foreach ( $settings->register_custom_tabs as $key => $tab_title ) { ?>
		
			<a href="?page=<?php echo $plugin->get_plugin_slug(); ?>&tab=<?php echo esc_attr( $key ); ?>" class="nav-tab <?php echo ( $key == $active_tab ) ? 'nav-tab-active' : ''; ?>">
				<?php echo esc_html( $tab_title ); ?>
			</a>

		<?php } ?>
		
	</h2>
	
	<form method="post" action="options.php">
		<?php
			
			$section = $plugin->get_plugin_slug() . '_' . $active_tab;

			settings_fields($section);      
            do_settings_sections($section);
            submit_button();
            submit_button( __('Default Settings', 'woo-product-carousel-and-zoom' ), 'secondary', 'reset', false);
		?>
	</form>
	
	<p style="margin: 0;"><?php echo __('Would you like to support the advancement of this plugin?', 'woo-product-carousel-and-zoom' ); ?></p>
	
	<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=7KXBDSF8ASB4Q" target="_blank" style="font-weight: bold;"><?php echo __('Donate to this plugin', 'woo-product-carousel-and-zoom' ); ?></a>
	
</div><!-- /.wrap -->
