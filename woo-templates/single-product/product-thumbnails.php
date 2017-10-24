<?php
/*
 * Single Product Thumbnails
 *
 * @package   Woo_Product_Carousel_Zoom
 * @author    Asier Musatadi <info@biklik.net>
 * @license   GPL-2.0+
 * @link      http://www.biklik.net
 * @copyright 2017 http://www.biklik.net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $post, $product, $woocommerce;

$attachment_ids = $product->get_gallery_attachment_ids();

// Get carousel options
$carousel_options = get_option( 'woocz_carousel_options' );
$activate = $carousel_options['activate'];

if ( $attachment_ids ) {
	$loop 		= 0;
	$columns 	= apply_filters( 'woocommerce_product_thumbnails_columns', 3 );
	
	$count= 0;
	foreach ( $attachment_ids as $attachment_id ) {
		$count++;
	}
	
	$nav = ( $carousel_options['number'] <= $count ) ? 'true' : 'false';
	?>
	
	<div id="images-carousel" class="carousel-standard woo-carousel" 
	data-nav="<?php echo ($carousel_options['navigation'] == 'on' ) ? $nav : 'false'; ?>" 
	data-dots="<?php echo ($carousel_options['dots'] == 'on' ) ? $nav : 'false'; ?>"
	data-loop="<?php echo ($carousel_options['loop'] == 'on' ) ? 'true' : 'false'; ?>" 
	data-margin="<?php echo esc_attr($carousel_options['margin']); ?>" 
	data-items="<?php echo esc_attr($carousel_options['number']); ?>">

	<?php

		foreach ( $attachment_ids as $attachment_id ) {
			?>
			<div class="item">
			<?php
			$classes = array( 'zoom' );

			if ( $loop === 0 || $loop % $columns === 0 ) {
				$classes[] = 'first';
			}

			if ( ( $loop + 1 ) % $columns === 0 ) {
				$classes[] = 'last';
			}

			$image_class = implode( ' ', $classes );
			$props       = wc_get_product_attachment_props( $attachment_id, $post );

			if ( ! $props['url'] ) {
				continue;
			}
	
			echo apply_filters(
				'woocommerce_single_product_image_thumbnail_html',
				sprintf(
					'<a href="'.esc_url( $props['url'] ).'" class="product-carousel" title="%s" data-standard="'.esc_url( $props['url'] ).'" data-image="'.esc_url( $props['url'] ).'">%s</a>',
					esc_attr( $props['caption'] ),
					wp_get_attachment_image( $attachment_id, apply_filters( 'single_product_small_thumbnail_size', 'shop_thumbnail' ), 0, $props )
				),
				$attachment_id,
				$post->ID,
				esc_attr( $image_class )
			);
	
			$loop++;
			
			?>
			
			</div>
			
			<?php
		}
	?>
	
	</div>
	
<?php
}
