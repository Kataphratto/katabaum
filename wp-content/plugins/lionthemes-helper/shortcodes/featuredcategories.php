<?php
function lionthemes_featuredcategories_shortcode( $atts ) {
	global $orienko_opt;
	
	$atts = shortcode_atts( array(
							'title' => '',
							'number' => 10,
							'columns'=> '6',
							'showon_effect'=> 'non-effect',
							'rows'=> '1',
							'el_class' => '',
							'style'=>'carousel',
							'desksmall' => '5',
							'tablet_count' => '4',
							'tabletsmall' => '3',
							'mobile_count' => '2',
							'margin' => '0',
							'sub_categories' => '5',
							), $atts, 'featuredcategories' ); 
	extract($atts);

	$terms = get_terms( array(
		'taxonomy' => 'product_cat',
		'hide_empty' => false,
		'meta_query'=> array(
			array(
			 'key' => '_featured',
			 'value' => '1',
			 'compare' => '='
			 )
		)
	) );
	$owl_data = 'data-owl="slide" data-desksmall="' . esc_attr($desksmall) . '" data-tabletsmall="'. esc_attr($tabletsmall) .'" data-mobile="'. esc_attr($mobile_count) .'" data-tablet="'. esc_attr($tablet_count) . '" data-margin="'. esc_attr($margin) .'" data-item-slide="'. esc_attr($columns) . '"';
	if ( !empty($terms) ){ 

		ob_start();
	?>
		<div class="vc-categories <?php echo esc_attr($el_class); ?>">
			<?php if($title){ ?>
				<h3 class="vc_widget_title vc_categories_title">
					<span><?php echo esc_html($title); ?></span>
				</h3>
			<?php } ?>
			<div class="inner-content">
				<div <?php echo $owl_data; ?> class="owl-carousel owl-theme categories-slide">
				<?php 
				$duration = 100;
				foreach($terms as $cat){
				$image = get_term_meta($cat->term_id, '_square_image');
				
				?>
					<div class="cat-item wow <?php echo $showon_effect; ?>" data-wow-delay="<?php echo $duration; ?>ms" data-wow-duration="0.5s">
						<?php if ( !empty($image[0]) ) { ?>
						<div class="cat-image">
							<a href="<?php echo get_term_link($cat->term_id); ?>">
								<img src="<?php echo esc_url($image[0]); ?>" alt="" />
							</a>
						</div>
						<?php } ?>
						<h3 class="cat-title text-center"><a href="<?php echo get_term_link($cat->term_id); ?>"><?php echo $cat->name; ?></a></h3>
						<?php if($sub_categories){ 
							$term_children = get_term_children( $cat->term_id, 'product_cat' );
							if(!empty($term_children)){
						?>
							<ul class="sub-featured-cats">
								<?php foreach($term_children as $child){ $term = get_term_by( 'id', $child, 'product_cat' ); ?>
								<li><a href="<?php echo get_term_link( $child, 'product_cat' ); ?>"><?php echo $term->name; ?></a></li>
								<?php } ?>
							</ul>
							<?php } ?>
						<?php } ?>
					</div>
					<?php $duration = $duration + 100; ?>
				<?php } ?>
				</div>
			</div>
		</div>
	<?php 
		$content = ob_get_contents();
		ob_end_clean();
		wp_reset_postdata();
		return $content;
	} 
} 
add_shortcode( 'featuredcategories', 'lionthemes_featuredcategories_shortcode' );
?>