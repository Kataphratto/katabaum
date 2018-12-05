<?php
	$_delay = 100;
	global $item_layout;
	$item_layout = $itemlayout;
	$owl_data = '';
	$owl_data .= 'data-dots="false" data-nav="true" data-owl="slide" data-ow-rtl="false" ';
	$owl_data .= 'data-data-desksmall="'. esc_attr($desksmall) .'" ';
	$owl_data .= 'data-tabletsmall="'. esc_attr($tabletsmall) .'" ';
	$owl_data .= 'data-mobile="'. esc_attr($mobile_count) .'" ';
	$owl_data .= 'data-tablet="'. esc_attr($tablet_count) .'" ';
	$owl_data .= 'data-margin="'. esc_attr($margin) .'" ';
	$owl_data .= 'data-item-slide="'. esc_attr($columns_count) .'" ';
	$owl_data .= 'data-autoplay="'. esc_attr($autoplay) .'" ';
	$owl_data .= 'data-playtimeout="'. esc_attr($playtimeout) .'" ';
	$owl_data .= 'data-speed="'. esc_attr($speed) .'" ';
?>
<div class="<?php echo ($item_layout == 'simple') ? 'product_list_widget': 'products-block shop-products products grid-view'; ?>">
	<div <?php echo ''.$owl_data; ?> class="owl-carousel owl-theme products-slide">
		<?php $index = 0; while ( $loop->have_posts() ) : $loop->the_post(); global $product; ?>
			<?php if($rows > 1){ ?>
				<?php if ( $index % $rows == 0 ) { ?>
					<div class="group">
				<?php } ?>
			<?php } ?>
			
				<?php 
					if($item_layout == 'simple'){
						wc_get_template( 'content-widget-product.php', array( 
							'show_rating' => $show_rating , 
							'class_column' => 'col-sm-12 col-xs-6',
							'showon_effect'=> $showon_effect,
							'hide_addcart'=> $hide_addcart,
							'delay' => $_delay ) );
					}else{
						?>
						<div class="product wow <?php echo esc_attr($showon_effect); ?>" data-wow-duration="0.5s" data-wow-delay="<?php echo esc_attr($_delay); ?>ms">
						<?php
						if(isset($is_deals) && $is_deals){
							wc_get_template_part( 'content', 'product-deals' );
						}else{
							wc_get_template_part( 'content', 'product-inner' );
						}
						?>
						</div>
						<?php
					}
				?>
			<?php $index ++; ?>
			<?php if($rows > 1){ ?>
				<?php if ( $index % $rows == 0 ||  $index == $_total) { ?>
					</div>
				<?php } ?>
			<?php } ?>
			<?php $_delay+=100; ?>
		<?php endwhile; ?>
	</div>
</div>
