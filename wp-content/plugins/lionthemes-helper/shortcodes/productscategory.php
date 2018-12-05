<?php
function lionthemes_productscategory_shortcode( $atts ) {
	global $complex_opt;
	
	$atts = shortcode_atts( array(
							'title' => '',
							'short_desc' => '',
							'category' => '',
							'item_layout'=>'box',
							'number' => 10,
							'hide_addcart' => '',
							'showon_effect'=> 'non-effect',
							'columns'=> '4',
							'rows'=> '1',
							'el_class' => '',
							'style'=>'grid',
							'desksmall' => '4',
							'tablet_count' => '3',
							'tabletsmall' => '2',
							'mobile_count' => '1',
							'margin' => '0',
							'autoplay' => 'true',
							'playtimeout' => '5000',
							'speed' => '250',
							), $atts, 'productscategory' ); 
	extract($atts);
	switch ($columns) {
		case '5':
			$class_column='col-md-20 col-sm-4 col-xs-6';
			break;
		case '4':
			$class_column='col-sm-3 col-xs-6';
			break;
		case '3':
			$class_column='col-sm-4 col-xs-6';
			break;
		case '2':
			$class_column='col-sm-6 col-xs-6';
			break;
		default:
			$class_column='col-sm-12 col-xs-6';
			break;
	}
	
	if($category=='') return;
	$_id = complex_make_id();
	$loop = complex_woocommerce_query('',$number, $category);
	if ( $loop->have_posts() ){ 

		ob_start();
	?>
		<?php $_total = $loop->found_posts; ?>
		<div class="woocommerce<?php echo esc_attr($el_class); ?>">
			<?php if($title){ ?>
				<h3 class="vc_widget_title vc_products_title">
					<span><?php echo esc_html($title); ?></span>
				</h3>
			<?php } ?>
			<?php if($short_desc){ ?>
				<div class="widget-sub-title">
					<?php echo nl2br(esc_html($short_desc)); ?>
				</div>
			<?php } ?>
			<div class="inner-content">
				
				<?php wc_get_template( 'product-layout/'.$style.'.php', array( 
						'show_rating' => true,
						'_id'=>$_id,
						'loop'=>$loop,
						'columns_count'=>$columns,
						'class_column' => $class_column,
						'_total'=>$_total,
						'number'=>$number,
						'rows'=>$rows,
						'margin'=>$margin,
						'desksmall'=>$desksmall,
						'tabletsmall'=>$tabletsmall,
						'tablet_count'=>$tablet_count,
						'mobile_count'=>$mobile_count,
						'itemlayout'=> $item_layout,
						'autoplay' => $autoplay,
						'playtimeout' => $playtimeout,
						'speed' => $speed,
						'showon_effect' => $showon_effect,
						'hide_addcart' => $hide_addcart,
						 ) ); ?>
				
			</div>
		</div>
	<?php 
		$content = ob_get_contents();
		ob_end_clean();
		wp_reset_postdata();
		return $content;
	} 
} 
add_shortcode( 'productscategory', 'lionthemes_productscategory_shortcode' );
?>