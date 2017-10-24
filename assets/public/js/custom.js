/*
 * WooCommerce Product Carousel and Zoom
 *
 * @package   Woo_Product_Carousel_Zoom
 * @author    Asier Musatadi <info@biklik.net>
 * @license   GPL-2.0+
 * @link      http://www.biklik.net
 * @copyright 2017 http://www.biklik.net
 *
 */
jQuery(document).ready(function(a){a(".carousel-standard").each(function(){var b=a(this);b.owlCarousel({dots:b.data("dots"),stagePadding:b.data("stagepadding"),items:b.data("items"),center:b.data("center"),loop:b.data("loop"),margin:b.data("margin"),nav:b.data("nav"),autoplay:b.data("autoplay"),navText:['<span class="lnr lnr-arrow-left"></span>','<span class="lnr lnr-arrow-right"></span>']})}),a(".product-carousel").click(function(){var b=a(this).attr("data-image");a("a.woocommerce-main-image.zooming > img").attr("src",b),a("a.woocommerce-main-image.zooming > img").attr("srcset",b)});var b=a(".image-zoom").easyZoom(),c=b.filter(".image-zoom").data("easyZoom");a("#images-carousel.carousel-standard").on("click","a",function(b){var d=a(this);b.preventDefault(),c.swap(d.data("standard"),d.attr("href"))}),a(".single_variation_wrap").on("show_variation",function(b,d){var e=a("a.woocommerce-main-image").attr("href");console.log(e),c.swap(e)})});