<?php

global $product, $complex_opt, $item_layout;
?>
	<div class="product-wrapper products-countdown<?php echo (isset($item_layout) && $item_layout == 'list') ? ' item-list-layout':' item-box-layout'; ?>">
		<?php if ( $product->is_on_sale() ) : ?>
			<?php echo apply_filters( 'woocommerce_sale_flash', '<span class="on-sale"><span class="sale-text">' . esc_html__( 'Sale', 'complex' ) . '</span></span>', $post, $product ); ?>
		<?php endif; ?>
		<div class="list-col4 countdown">
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
				<?php if((isset($item_layout) && $item_layout == 'box') || (!isset($item_layout))){ ?>
			</div>
		</div>
		<div class="list-col8">
			<div class="gridview">
				<?php do_action( 'complex_before_title_loop_product', get_the_ID() ); ?>
				<span class="product-name">
					<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
				</span>
				<div class="price"><?php echo ''.$product->get_price_html(); ?></div>

				<div class="product-desc"><?php the_excerpt(); ?></div>
				<?php
					$current_date = current_time( 'timestamp' );
					$sale_end = get_post_meta( get_the_ID(), '_sale_price_dates_to', true );
					$timestemp_left = $sale_end + 24*60*60 - 1 - $current_date;
					if($timestemp_left > 0){
						$day_left = floor($timestemp_left / (24 * 60 * 60));
						$hours_left = floor(($timestemp_left - ($day_left * 60 * 60 * 24)) / (60 * 60));
						$mins_left = floor(($timestemp_left - ($day_left * 60 * 60 * 24) - ($hours_left * 60 * 60)) / 60);
						$secs_left = floor($timestemp_left - ($day_left * 60 * 60 * 24) - ($hours_left * 60 * 60) - ($mins_left * 60));
						?>
						<div class="deals-countdown">
							<span class="des-countdown"><?php echo esc_html__('Hurry up! Special offer:', 'complex'); ?></span>
							<span class="countdown-row">	
								<span class="countdown-section">
									<span class="countdown-val days_left"><?php echo ''.$day_left; ?></span>
									<span class="countdown-label"><?php echo esc_html__('Days', 'complex'); ?></span>
								</span>
								<span class="countdown-section">
									<span class="countdown-val hours_left"><?php echo ''.$hours_left; ?></span>
									<span class="countdown-label"><?php echo esc_html__('Hrs', 'complex'); ?></span>
								</span>
								<span class="countdown-section">
									<span class="countdown-val mins_left"><?php echo ''.$mins_left; ?></span>
									<span class="countdown-label"><?php echo esc_html__('Mins', 'complex'); ?></span>
								</span>
								<span class="countdown-section">
									<span class="countdown-val secs_left"><?php echo ''.$secs_left; ?></span>
									<span class="countdown-label"><?php echo esc_html__('Secs', 'complex'); ?></span>
								</span>
							</span>
						</div>
					<?php } ?>
				<?php } ?>
			</div>
		</div>
		<div class="clearfix"></div>
	</div>
