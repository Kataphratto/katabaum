<?php
/**
 * The template for displaying product content within loops.
 *
 * Override this template by copying it to yourtheme/woocommerce/content-product.php
 *
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.0.6
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product, $woocommerce_loop, $complex_opt, $complex_productsfound;

// Store loop count we're currently on
if ( empty( $woocommerce_loop['loop'] ) ) {
	$woocommerce_loop['loop'] = 0;
}

// Store column count for displaying the grid
if ( empty( $woocommerce_loop['columns'] ) ) {
	$woocommerce_loop['columns'] = apply_filters( 'loop_shop_columns', 4 );
}

// Ensure visibility
if ( ! $product || ! $product->is_visible() ) {
	return;
}

// Increase loop count
$woocommerce_loop['loop']++;

// Extra post classes
$classes = array();
if ( 0 == $woocommerce_loop['loop'] % $woocommerce_loop['columns'] || 1 == $woocommerce_loop['columns'] ) {
	$classes[] = 'first';
}
if ( 0 == ( $woocommerce_loop['loop'] + 1 ) % $woocommerce_loop['columns'] ) {
	$classes[] = 'last';
}
if($woocommerce_loop['columns'] > 1){
	$complex_opt['product_per_row'] = $woocommerce_loop['columns'];
	$colwidth = round(12/$woocommerce_loop['columns']);
	$classes[] = ' item-col col-xs-12 col-sm-'.$colwidth;
}
if ( ( 0 == $woocommerce_loop['loop'] % $woocommerce_loop['columns'] ) && ( $woocommerce_loop['columns'] >= 2 ) ) {
	if( $complex_opt['product_per_row'] != 1 ){
		echo '<div class="group">';
	}
} ?>
<div <?php post_class( $classes ); ?>>
	<div class="product-wrapper">
		<?php do_action( 'woocommerce_before_shop_loop_item' ); ?>
		
		<?php if ( $product->is_on_sale() ) : ?>
			<?php echo apply_filters( 'woocommerce_sale_flash', '<span class="onsale"><span class="sale-text">' . esc_html__( 'Sale', 'complex' ) . '</span></span>', $post, $product ); ?>
		<?php endif; ?>	
		<div class="list-col4">
			<div class="product-image">
											
				<a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( $product->get_title() ); ?>">
					<?php 
					if(!empty($complex_opt['second_image'])){
						echo ''.$product->get_image('shop_catalog', array('class'=>'primary_image'));
						$attachment_ids = $product->get_gallery_image_ids();
						if ( $attachment_ids ) {
							echo wp_get_attachment_image( $attachment_ids[0], apply_filters( 'single_product_small_thumbnail_size', 'shop_catalog' ), false, array('class'=>'secondary_image') );
						}
					}else{
						echo ''.$product->get_image('shop_catalog', array());
					}
					?>
				</a>
				<div class="actions">
					<ul class="add-to-links clearfix">
						
						<?php if(!empty($complex_opt['enable_quickview'])){ ?>
							<li class="quickviewbtn">
								<a class="detail-link quickview" data-quick-id="<?php the_ID();?>" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php esc_html_e('Quick View', 'complex');?></a>
							</li>
						<?php } ?>
						
						<?php if(!empty($complex_opt['enable_compare'])){ ?>
						<li>	
							<?php if ( class_exists( 'YITH_WCWL' ) ) {
								echo preg_replace("/<img[^>]+\>/i", " ", do_shortcode('[yith_wcwl_add_to_wishlist]'));
							} ?>
						</li>
						<?php } ?>
						
						<?php if(!empty($complex_opt['enable_wishlist'])){ ?>
						<li>
							<?php if( class_exists( 'YITH_Woocompare' ) ) {
							echo do_shortcode('[yith_compare_button]');
							} ?>
						</li>
						<?php } ?>

					</ul>
				</div>
			</div>
		</div>
		<div class="list-col8">
			<div class="gridview">
				<?php do_action( 'complex_before_title_loop_product', get_the_ID() ); ?>
				<span class="product-name">
					<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
				</span>
				<div class="price"><?php echo ''.$product->get_price_html(); ?></div>
				<?php if(!empty($complex_opt['enable_addcart'])){ ?>
					<?php complex_ajax_add_to_cart_button(); ?>
				<?php } ?>
			</div>
		</div>
		<div class="clearfix"></div>
		<?php do_action( 'woocommerce_after_shop_loop_item' ); ?>
	</div>
</div>
<?php if ( ( ( 0 == $woocommerce_loop['loop'] % $woocommerce_loop['columns'] || $complex_productsfound == $woocommerce_loop['loop'] ) && $woocommerce_loop['columns'] >= 2 )  ) {
	if( $complex_opt['product_per_row'] != 1 ){
		echo '</div>';
	}
} ?>
