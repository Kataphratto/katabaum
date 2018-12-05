<?php
function complex_brands_shortcode( $atts ) {
	global $complex_opt;
	$brand_index = 0;
	if(!isset($complex_opt['brand_logos'])) return;
	$brandfound = count($complex_opt['brand_logos']);
	
	$atts = shortcode_atts( array(
							'title' => '',
							'rows' => '1',
							'colsnumber' => '6',
							'showon_effect' => 'non-effect',
							'el_class' => '',
							'style'=>'grid',
							'desksmall' => '4',
							'tablet_count' => '3',
							'tabletsmall' => '3',
							'mobile_count' => '2',
							'margin' => '30',
							'autoplay' => 'true',
							'playtimeout' => '5000',
							'speed' => '250',
							), $atts, 'ourbrands' );
	extract($atts);
	switch ($colsnumber) {
		case '6':
			$class_column='col-sm-2 col-xs-6';
			break;
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
	if($brandfound <= 0) return;
	
	$owl_data = '';
	if($style == 'carousel'){
		$owl_data .= 'data-dots="false" data-nav="true" data-owl="slide" data-ow-rtl="false" ';
		$owl_data .= 'data-data-desksmall="'. esc_attr($desksmall) .'" ';
		$owl_data .= 'data-tabletsmall="'. esc_attr($tabletsmall) .'" ';
		$owl_data .= 'data-mobile="'. esc_attr($mobile_count) .'" ';
		$owl_data .= 'data-tablet="'. esc_attr($tablet_count) .'" ';
		$owl_data .= 'data-margin="'. esc_attr($margin) .'" ';
		$owl_data .= 'data-item-slide="'. esc_attr($colsnumber) .'" ';
		$owl_data .= 'data-autoplay="'. esc_attr($autoplay) .'" ';
		$owl_data .= 'data-playtimeout="'. esc_attr($playtimeout) .'" ';
		$owl_data .= 'data-speed="'. esc_attr($speed) .'" ';
	}
	
	ob_start();
	echo '<div class="brand_widget '. esc_attr($el_class) .'">';
	echo ($title) ? '<h3 class="widget-title vc-brands-title"><span>'. esc_html($title) .'</span></h3>' : '';
	if($style == 'grid'){
		$wrapdiv = '';
	}else{
		$class_column = '';
		$wrapdiv = '<div '. $owl_data .' class="owl-carousel owl-theme brands-slide">';
	}
	if($complex_opt['brand_logos']) { ?>
			<?php 
				echo $wrapdiv; 
				$duration = 100;
			?>
			<?php foreach($complex_opt['brand_logos'] as $brand) {
				if(is_ssl()){
					$brand['image'] = str_replace('http:', 'https:', $brand['image']);
				}
				$brand_index ++;
				?>
				<?php if($style == 'carousel' && $rows > 1){ ?>
					<?php if ( (0 == ( $brand_index - 1 ) % $rows ) || $brand_index == 1) { ?>
						<div class="group">
					<?php } ?>
				<?php } ?>
				<div class="brand_item wow <?php echo $showon_effect; ?> <?php echo $class_column; ?>" data-wow-delay="<?php echo $duration ?>ms" data-wow-duration="0.5s">
					<a href="<?php echo $brand['url']; ?>" title="<?php echo $brand['title']; ?>">
						<img src="<?php echo $brand['image'] ?>" alt="<?php echo $brand['title']; ?>" />
					</a>
				</div>
				<?php if($style == 'carousel' && $rows > 1){ ?>
					<?php if ( ( ( 0 == $brand_index % $rows || $brandfound == $brand_index ))  ) { ?>
						</div>
					<?php } ?>
				<?php } ?>
			<?php $duration = $duration + 100; } ?>
		</div>
	<?php }
	echo '</div>';
	$content = ob_get_contents();
	ob_end_clean();
	return $content;
}
add_shortcode( 'ourbrands', 'complex_brands_shortcode' );
?>