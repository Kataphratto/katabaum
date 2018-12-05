<?php

global $product, $complex_opt, $item_layout;
?>
	<div class="product-wrapper<?php echo (isset($item_layout) && $item_layout == 'list') ? ' item-list-layout':' item-box-layout'; ?>">
		
		<?php if ( $product->is_on_sale()  && $item_layout != 'list') : ?>
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
						<?php if(!empty($item_layout) && $item_layout != 'list'){ ?>
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
				<?php if(!empty($complex_opt['enable_addcart']) && empty($hide_addcart)){ ?>
					<?php complex_ajax_add_to_cart_button(); ?>
				<?php } ?>
			</div>
		</div>
		<div class="clearfix"></div>
		<?php do_action( 'woocommerce_after_shop_loop_item' ); ?>
	</div>