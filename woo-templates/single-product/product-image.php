<?php
/*
 * Single Product Image
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

global $post, $product;

// Get zoom options
$zoom_options = get_option( 'woocz_zoom_options' );
?>

<div class="images">
	
	<div class="image-zoom <?php if($zoom_options['position'] === 'right') echo 'image-zoom-lateral'; ?>">
			
	<?php
		if ( has_post_thumbnail() ) {
			$attachment_count = count( $product->get_gallery_attachment_ids() );
			$gallery          = $attachment_count > 0 ? '[product-gallery]' : '';
			$props            = wc_get_product_attachment_props( get_post_thumbnail_id(), $post );
			$image            = get_the_post_thumbnail( $post->ID, apply_filters( 'single_product_large_thumbnail_size', 'shop_single' ), array(
				'title'	 => $props['title'],
				'alt'    => $props['alt'],
			) );
			echo apply_filters(
				'woocommerce_single_product_image_html',
				sprintf(
					'<a href="%s" itemprop="image" class="woocommerce-main-image zooming" title="%s">%s</a>',
					esc_url( $props['url'] ),
					esc_attr( $props['caption'] ),
					$image
				),
				$post->ID
			);
		} else {
			echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<img src="%s" class="img-responsive" alt="%s" />', wc_placeholder_img_src(), __( 'Placeholder', 'woo-product-carousel-and-zoom' ) ), $post->ID );
		}?>
		
	</div>

	<?php do_action( 'woocommerce_product_thumbnails' ); ?>
	
</div>
