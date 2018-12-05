<?php
function lionthemes_testimonials_shortcode( $atts ) {
	extract( shortcode_atts( array(
		'title'=>'',
		'short_desc'=>'',
		'el_class' => '',
		'number' => 10,
		'style'=>'carousel',
		'columns' => 1,
		'showon_effect'=> 'non-effect',
		'item_style' => '',
		'desksmall' => '1',
		'tablet_count' => '1',
		'tabletsmall' => '1',
		'mobile_count' => '1',
		'margin' => '0',
		'autoplay' => 'true',
		'pagination' => 'true',
		'control_nav' => 'false',
		'playtimeout' => '5000',
		'speed' => '250',
	), $atts, 'testimonials' ) );

	$_id = complex_make_id();
	$args = array(
		'post_type' => 'testimonial',
		'posts_per_page' => $number,
		'post_status' => 'publish'
	);
	
	
	$owl_data = '';
	if($style == 'carousel'){
		$owl_data .= 'data-owl="slide" data-ow-rtl="false" ';
		$owl_data .= 'data-data-desksmall="'. esc_attr($desksmall) .'" ';
		$owl_data .= 'data-tabletsmall="'. esc_attr($tabletsmall) .'" ';
		$owl_data .= 'data-mobile="'. esc_attr($mobile_count) .'" ';
		$owl_data .= 'data-tablet="'. esc_attr($tablet_count) .'" ';
		$owl_data .= 'data-margin="'. esc_attr($margin) .'" ';
		$owl_data .= 'data-item-slide="'. esc_attr($columns) .'" ';
		$owl_data .= 'data-autoplay="'. esc_attr($autoplay) .'" ';
		$owl_data .= 'data-nav="'. esc_attr($control_nav) .'" ';
		$owl_data .= 'data-dots="'. esc_attr($pagination) .'" ';
		$owl_data .= 'data-playtimeout="'. esc_attr($playtimeout) .'" ';
		$owl_data .= 'data-speed="'. esc_attr($speed) .'" ';
	}
	
$query = new WP_Query($args);
?>
<?php if($query->have_posts()){ ob_start(); ?>
	<div class="testimonials <?php echo esc_attr($el_class); ?>">
		<?php if($title){ ?>
			<h3 class="vc_widget_title vc_testimonial_title">
				<span><?php echo esc_html($title); ?></span>
			</h3>
		<?php } ?>
		<?php if($short_desc){ ?>
			<div class="widget-sub-title">
				<?php echo nl2br(esc_html($short_desc)); ?>
			</div>
		<?php } ?>
		<div <?php echo ($style == 'carousel') ? $owl_data :'' ?> id="testimonial-<?php echo esc_attr($_id); ?>" class="testimonials-list<?php echo ($style == 'carousel') ? ' owl-carousel owl-theme':'' ?>">
			<?php $i=0; $duration = 100; while($query->have_posts()): $query->the_post(); $i++; ?>
				<!-- Wrapper for slides -->
				<div class="quote wow <?php echo $showon_effect; ?> <?php echo ($item_style) ? $item_style : ''; ?>" data-wow-delay="<?php echo $duration; ?>ms" data-wow-duration="0.5s">
					
					<div class="testitop">
						<blockquote class="testimonials-text">
								<?php the_content(); ?>
						</blockquote>
						
						<div class="author">
							<div class="by-author">
								<span class="author-name"><?php the_title(); ?></span>
								<?php if(get_post_meta(get_the_ID(), '_byline', true)){ ?>
								<span class="author-byline"><?php echo get_post_meta(get_the_ID(), '_byline', true); ?></span>
								<?php } ?>
							</div>
							<div class="image">
								<?php the_post_thumbnail( 'full' ); ?>
							</div>
						</div>
					</div>
					
				</div>
				<?php $duration = $duration + 100; ?>
			<?php endwhile; ?>
		</div>
	</div>
<?php 
	$content = ob_get_contents();
	ob_end_clean();
	wp_reset_postdata();
	return $content;
	}
}
add_shortcode( 'testimonials', 'lionthemes_testimonials_shortcode' );
?>