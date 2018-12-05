<?php
/**
 * The template for displaying product content within loops.
 *
 * Override this template by copying it to yourtheme/woocommerce/content-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.0.6
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $product, $woocommerce_loop, $complex_opt, $complex_shopclass, $complex_viewmode;

// Store loop count we're currently on
if ( empty( $woocommerce_loop['loop'] ) )
	$woocommerce_loop['loop'] = 0;

// Store column count for displaying the grid
if ( empty( $woocommerce_loop['columns'] ) )
	$woocommerce_loop['columns'] = apply_filters( 'loop_shop_columns', 4 );

// Ensure visibility
if ( ! $product || ! $product->is_visible() )
	return;

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

$count   = $product->get_rating_count();

if($complex_shopclass=='shop-fullwidth') {
	$classes[] = 'item-col col-xs-12 col-sm-4';
	if(!empty($complex_opt['product_per_row_fw'])){
		$woocommerce_loop['columns'] = $complex_opt['product_per_row_fw'];
		$colwidth = round(12/$woocommerce_loop['columns']);
		$classes[] = 'col-md-'.$colwidth;
	}
} else {
	$classes[] = 'item-col col-xs-12';
	if(!empty($complex_opt['product_per_row'])){
		$woocommerce_loop['columns'] = $complex_opt['product_per_row'];
		$colwidth = round(12/$woocommerce_loop['columns']);
		$classes[] = 'col-sm-'.$colwidth;
	}
}
?>

<div <?php post_class( $classes ); ?>>
	<div class="product-wrapper">
		<?php do_action( 'woocommerce_before_shop_loop_item' ); ?>
		
		<?php if ( $product->is_on_sale() ) : ?>
			<?php echo apply_filters( 'woocommerce_sale_flash', '<span class="onsale"><span class="sale-text">' . esc_html__( 'Sale', 'complex' ) . '</span></span>', $post, $product ); ?>
		<?php endif; ?>
		<div class="list-col4 <?php if($complex_viewmode=='list-view'){ echo ' col-xs-12 col-sm-4';} ?>">
			<div class="product-image">
				
			
				<a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( $product->get_title() ); ?>">
					<?php 
					if(!empty($complex_opt['second_image'])){
						echo wp_kses($product->get_image('shop_catalog', array('class'=>'primary_image')), array(
							'a'=>array(
								'href'=>array(),
								'title'=>array(),
								'class'=>array(),
							),
							'img'=>array(
								'src'=>array(),
								'height'=>array(),
								'width'=>array(),
								'class'=>array(),
								'alt'=>array(),
							)
						));
						$attachment_ids = $product->get_gallery_image_ids();
						if ( $attachment_ids ) {
							echo wp_get_attachment_image( $attachment_ids[0], apply_filters( 'single_product_small_thumbnail_size', 'shop_catalog' ), false, array('class'=>'secondary_image') );
						}
					}else{
						echo wp_kses($product->get_image('shop_catalog', array()), array(
							'a'=>array(
								'href'=>array(),
								'title'=>array(),
								'class'=>array(),
							),
							'img'=>array(
								'src'=>array(),
								'height'=>array(),
								'width'=>array(),
								'class'=>array(),
								'alt'=>array(),
							)
						));
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
		<div class="list-col8 <?php if($complex_viewmode=='list-view'){ echo ' col-xs-12 col-sm-8';} ?>">
			<div class="gridview">
				<span class="product-name">
					<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
				</span>
				<div class="price"><?php echo ''.$product->get_price_html(); ?></div>
				
				<?php if(!empty($complex_opt['enable_addcart'])){ ?>
					<?php complex_ajax_add_to_cart_button(); ?>
				<?php } ?>
				
			</div>
			<div class="listview">
				<span class="product-name">
					<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
				</span>
				<div class="ratings"><?php echo wc_get_rating_html($product->get_average_rating()); ?></div>
				<div class="price"><?php echo ''.$product->get_price_html(); ?></div>
				<div class="product-desc"><?php the_excerpt(); ?></div>
				<div class="actions">
					<ul class="add-to-links clearfix">
						<?php if(!empty($complex_opt['enable_addcart'])){ ?>
						<li>
							<?php complex_ajax_add_to_cart_button(); ?>
						</li>
						<?php } ?>
						
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
		<div class="clearfix"></div>
		<?php do_action( 'woocommerce_after_shop_loop_item' ); ?>
	</div>
</div>