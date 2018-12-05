<?php
/**
 * The template for displaying product category thumbnails within loops.
 *
 * Override this template by copying it to yourtheme/woocommerce/content-product_cat.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.0.6
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $product, $woocommerce_loop, $complex_opt, $complex_shopclass, $complex_viewmode;

// Store loop count we're currently on
if ( empty( $woocommerce_loop['loop'] ) )
	$woocommerce_loop['loop'] = 0;

// Store column count for displaying the grid
if ( empty( $woocommerce_loop['columns'] ) )
	$woocommerce_loop['columns'] = apply_filters( 'loop_shop_columns', 4 );

// Extra post classes
$classe = '';
if ( 0 == $woocommerce_loop['loop'] % $woocommerce_loop['columns'] || 1 == $woocommerce_loop['columns'] ) {
	$classe .= ' first';
}
if ( 0 == ( $woocommerce_loop['loop'] + 1 ) % $woocommerce_loop['columns'] ) {
	$classe .= ' last';
}
if($complex_shopclass=='shop-fullwidth') {
	if(isset($complex_opt['product_per_row_fw'])){
		$woocommerce_loop['columns'] = $complex_opt['product_per_row_fw'];
		$colwidth = round(12/$woocommerce_loop['columns']);
	}
	$classe .= ' item-col col-xs-12 col-sm-4 col-md-'.$colwidth ;
} else {
	if(isset($complex_opt['product_per_row'])){
		$woocommerce_loop['columns'] = $complex_opt['product_per_row'];
		$colwidth = round(12/$woocommerce_loop['columns']);
	}
	$classe .= ' item-col col-xs-12 col-sm-'.$colwidth ;
}
// Increase loop count
$woocommerce_loop['loop'] ++;
?>
<div class="product-category category<?php echo esc_attr($classe); ?>">

	<?php do_action( 'woocommerce_before_subcategory', $category ); ?>
	
	<?php do_action( 'woocommerce_before_subcategory_title', $category ); ?>
	
	<?php do_action( 'woocommerce_shop_loop_subcategory_title', $category ); ?>

	
	<?php
		/**
		 * woocommerce_after_subcategory_title hook
		 */
		do_action( 'woocommerce_after_subcategory_title', $category );
	?>
	<?php do_action( 'woocommerce_after_subcategory', $category ); ?>

</div>
