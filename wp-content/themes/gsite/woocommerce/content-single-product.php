<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * Override this template by copying it to yourtheme/woocommerce/content-single-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.0.6
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
<?php global $complex_opt; ?>
<?php 
	$maincol = 12;
	$prosidebar = '';
	if(!empty($complex_opt['sidebarpro_pos']) && is_active_sidebar('product')){
		$prosidebar = $complex_opt['sidebarpro_pos'];
		$maincol = 9;
	}
?>
<?php
	/**
	 * woocommerce_before_single_product hook
	 *
	 * @hooked wc_print_notices - 10
	 */
	 do_action( 'woocommerce_before_single_product' );

	 if ( post_password_required() ) {
	 	echo get_the_password_form();
	 	return;
	 }
?>

<div itemscope itemtype="<?php echo complex_get_product_schema(); ?>" id="product-<?php the_ID(); ?>" <?php post_class(); ?>>

		<div class="row product-view">
			<?php if ($prosidebar == 'left') { ?> 
				<?php get_sidebar('product'); ?>
			<?php } ?>
			<div class="col-xs-12 col-md-<?php echo esc_attr($maincol); ?>">
				<div class="row">
					<div class="col-xs-12 col-md-5 f-image">
						<div class="single-product-image ">
							<?php
								/**
								 * woocommerce_before_single_product_summary hook
								 *
								 * @hooked woocommerce_show_product_sale_flash - 10
								 * @hooked woocommerce_show_product_images - 20
								 */
								do_action( 'woocommerce_before_single_product_summary' );
							?>
						</div>
					</div>
					<div class="col-xs-12 col-md-7 r-product-info">
						<div class="summary entry-summary single-product-info">				
							<?php
								/**
								 * woocommerce_single_product_summary hook
								 *
								 * @hooked woocommerce_template_single_title - 5
								 * @hooked woocommerce_template_single_rating - 10
								 * @hooked woocommerce_template_single_price - 10
								 * @hooked woocommerce_template_single_excerpt - 20
								 * @hooked woocommerce_template_single_add_to_cart - 30
								 * @hooked woocommerce_template_single_meta - 40
								 * @hooked woocommerce_template_single_sharing - 50
								 */
								do_action( 'woocommerce_single_product_summary' );
							?>
						</div>
					</div>	

				<?php
					/**
					 * woocommerce_after_single_product_summary hook
					 *
					 * @hooked woocommerce_output_product_data_tabs - 10
					 * @hooked woocommerce_output_related_products - 20
					 */
					do_action( 'woocommerce_after_single_product_summary' );
				?>
				
				<meta itemprop="url" content="<?php the_permalink(); ?>" />	
				
				<?php do_action('woocommerce_show_related_products'); ?>

				<?php do_action( 'woocommerce_after_single_product' ); ?>

			</div>
		</div>
		<?php if ($prosidebar == 'right') { ?> 
			<?php get_sidebar('product'); ?>
		<?php } ?> 
	</div>
		
</div><!-- #product-<?php the_ID(); ?> -->

