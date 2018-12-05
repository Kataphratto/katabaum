<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive.
 *
 * Override this template by copying it to yourtheme/woocommerce/archive-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.0.6
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

get_header( 'shop' ); ?>
<?php
global $complex_opt, $complex_shopclass, $wp_query, $woocommerce_loop, $complex_viewmode;

$shoplayout = 'sidebar';
if(empty($complex_opt['sidebarshop_pos']) || !is_active_sidebar('shop')) $shoplayout = 'fullwidth';

if(isset($_GET['layout']) && $_GET['layout']!='') $shoplayout = $_GET['layout'];

$shopsidebar = 'left';
if(!empty($complex_opt['sidebarshop_pos'])) $shopsidebar = $complex_opt['sidebarshop_pos'];
	
if(isset($_GET['side']) && $_GET['side']!='') $shopsidebar = $_GET['side'];

switch($shoplayout) {
	case 'fullwidth':
		$complex_shopclass = 'shop-fullwidth';
		$shopcolclass = 12;
		$shopsidebar = 'none';
		$productcols = 4;
		break;
	default:
		$complex_shopclass = 'shop-sidebar';
		$shopcolclass = 9;
		$productcols = 3;
}

$complex_viewmode = 'grid-view';
if(isset($complex_opt['default_view'])) {
	if($complex_opt['default_view']=='list-view'){
		$complex_viewmode = 'list-view';
	}
}
if(isset($_GET['view']) && $_GET['view']=='list-view'){
	$complex_viewmode = $_GET['view'];
}

$shop_view = get_option('woocommerce_shop_page_display');
$cat_view = get_option('woocommerce_category_archive_display');
$detect_pro_view = true;
if (is_product_category()) {
	$detect_pro_view = ($cat_view != 'subcategories');
	$cate = get_queried_object();
	$cateID = $cate->term_id;
	$display_type = get_term_meta($cateID, 'display_type'); 
	if(!empty($display_type[0]) && ($display_type[0] == 'products' || $display_type[0] == 'both')) $detect_pro_view = true;
	if(!empty($display_type[0]) && $display_type[0] == 'subcategories') $detect_pro_view = false;
}
if(is_shop() && $shop_view == 'subcategories'){
	$detect_pro_view = false;
}
if(is_search()) $detect_pro_view = true;
?>
<div class="main-container">
	<div class="page-content">
		
		<div class="before-archive-content">
			<div class="container">
				<?php
					do_action( 'woocommerce_before_main_content' );
				?>
			</div>
		</div>
		<div class="shop_content">
				<div class="container">
					<div class="row">
						<?php if( $shopsidebar == 'left' ) :?>
							<?php get_sidebar('shop'); ?>
						<?php endif; ?>
						<div id="archive-product" class="col-xs-12 <?php echo 'col-md-'.$shopcolclass; ?>">
							<h1><?php single_term_title(); ?> </h1>
							<div class="category-desc <?php echo esc_attr($shoplayout);?>">
								<?php do_action( 'woocommerce_archive_description' ); ?>
							</div>
							<div class="archive-border">
								<?php if ( have_posts() ) : ?>
									
									<?php
										/**
										* remove message from 'woocommerce_before_shop_loop' and show here
										*/
										do_action( 'woocommerce_show_message' );
									?>
									<div class="shop-categories categories row">
										<?php woocommerce_product_subcategories();
										//reset loop
										$woocommerce_loop['loop'] = 0; ?>
									</div>
									<?php if($detect_pro_view){ ?>
									<div class="toolbar">
										<div class="view-mode">
											<label><?php esc_html_e('View on', 'complex');?></label>
											<a href="javascript:void(0)" class="grid <?php if($complex_viewmode=='grid-view'){ echo ' active';} ?>" title="<?php echo esc_attr__( 'Grid', 'complex' ); ?>"><i class="fa fa-th-large"></i></a>
											<a href="javascript:void(0)" class="list <?php if($complex_viewmode=='list-view'){ echo ' active';} ?>" title="<?php echo esc_attr__( 'List', 'complex' ); ?>"><i class="fa fa-list-ol"></i></a>
										</div>
										<?php
											/**
											 * woocommerce_before_shop_loop hook
											 *
											 * @hooked woocommerce_result_count - 20
											 * @hooked woocommerce_catalog_ordering - 30
											 */
											do_action( 'woocommerce_before_shop_loop' );
										?>
										<div class="clearfix"></div>
									</div>
								
									<?php //woocommerce_product_loop_start(); ?>
									<div class="shop-products products row <?php echo esc_attr($complex_viewmode);?> <?php echo esc_attr($shoplayout);?>">
										
										<?php $woocommerce_loop['columns'] = $productcols; ?>
										
										<?php while ( have_posts() ) : the_post(); ?>

											<?php wc_get_template_part( 'content', 'product-archive' ); ?>

										<?php endwhile; // end of the loop. ?>
									</div>
									<?php //woocommerce_product_loop_end(); ?>
									
									<div class="toolbar tb-bottom<?php echo (!empty($complex_opt['enable_loadmore'])) ? ' hide':''; ?>">
										<?php
											/**
											 * woocommerce_before_shop_loop hook
											 *
											 * @hooked woocommerce_result_count - 20
											 * @hooked woocommerce_catalog_ordering - 30
											 */
											do_action( 'woocommerce_after_shop_loop' );
											//do_action( 'woocommerce_before_shop_loop' );
										?>
										<div class="clearfix"></div>
									</div>
									<?php if(!empty($complex_opt['enable_loadmore'])){ ?>
										<div class="load-more-product text-center <?php echo esc_attr($complex_opt['enable_loadmore']); ?>">
											<?php if($complex_opt['enable_loadmore'] == 'button-more'){ ?>
												<img class="hide" src="<?php echo get_template_directory_uri() ?>/images/small-loading.gif" alt="" />
												<a class="button" href="javascript:loadmoreProducts()"><?php echo esc_html__('Load more', 'complex'); ?></a>
											<?php }else{ ?>
												<img width="100" class="hide" src="<?php echo get_template_directory_uri() ?>/images/big-loading.gif" alt="" />
											<?php } ?>
										</div>
									<?php } ?>
									<?php } ?>
								<?php elseif ( ! woocommerce_product_subcategories( array( 'before' => woocommerce_product_loop_start( false ), 'after' => woocommerce_product_loop_end( false ) ) ) ) : ?>

									<?php wc_get_template( 'loop/no-products-found.php' ); ?>

								<?php endif; ?>
							</div>
						</div>
						<?php if($shopsidebar == 'right') :?>
							<?php get_sidebar('shop'); ?>
						<?php endif; ?>
					</div>
					<?php 
						//echo do_shortcode( '[ourbrands rows="1" colsnumber="6" style="carousel"]' );
					?>
				</div>
		</div>
	</div>
</div>
<?php get_footer( 'shop' ); ?>