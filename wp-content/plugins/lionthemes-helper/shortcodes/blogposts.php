<?php
function lionthemes_blogposts_shortcode( $atts ) {
	global $complex_opt;
	$post_index = 0;
	$atts = shortcode_atts( array(
		'title' => '',
		'short_desc' => '',
		'number' => 5,
		'order' => 'DESC',
		'orderby' => 'post_date',
		'image' => 'wide',
		'length' => 20,
		'readmore_text' => 'Read more',
		'style' => 'carousel',
		'columns' => '1',
		'showon_effect' => 'non-effect',
		'rows' => '1',
		'desksmall' => '4',
		'tablet_count' => '3',
		'tabletsmall' => '2',
		'mobile_count' => '1',
		'margin' => '0',
		'autoplay' => 'true',
		'playtimeout' => '5000',
		'speed' => '250',
	), $atts, 'blogposts' );
	extract($atts);
	if($image == 'wide'){
		$imagesize = 'complex-post-thumbwide';
	} else {
		$imagesize = 'complex-post-thumb';
	}
	$html = '';

	$postargs = array(
		'posts_per_page'   => $number,
		'offset'           => 0,
		'category'         => '',
		'category_name'    => '',
		'orderby'          => $orderby,
		'order'            => $order,
		'include'          => '',
		'exclude'          => '',
		'meta_key'         => '',
		'meta_value'       => '',
		'post_type'        => 'post',
		'post_mime_type'   => '',
		'post_parent'      => '',
		'post_status'      => 'publish',
		'suppress_filters' => true );
	$postslist = get_posts( $postargs );
	$total = count($postslist);
	if($total == 0) return;
	switch ($columns) {
		case '5':
			$class_column='col-sm-20 col-xs-6';
			break;
		case '4':
			$class_column='col-sm-3 col-xs-6';
			break;
		case '3':
			$class_column='col-lg-4 col-md-4 col-sm-4 col-xs-6';
			break;
		case '2':
			$class_column='col-lg-6 col-md-6 col-sm-6 col-xs-6';
			break;
		default:
			$class_column='col-lg-12 col-md-12 col-sm-12 col-xs-6';
			break;
	}
	$row_cl = ' row';
	if($style != 'grid'){
		$row_cl = $class_column = '';
	}
	$owl_data = '';
	if($style == 'carousel'){
		$owl_data .= 'data-dots="false" data-nav="true" data-owl="slide" data-ow-rtl="false" ';
		$owl_data .= 'data-data-desksmall="'. esc_attr($desksmall) .'" ';
		$owl_data .= 'data-tabletsmall="'. esc_attr($tabletsmall) .'" ';
		$owl_data .= 'data-mobile="'. esc_attr($mobile_count) .'" ';
		$owl_data .= 'data-tablet="'. esc_attr($tablet_count) .'" ';
		$owl_data .= 'data-margin="'. esc_attr($margin) .'" ';
		$owl_data .= 'data-item-slide="'. esc_attr($columns) .'" ';
		$owl_data .= 'data-autoplay="'. esc_attr($autoplay) .'" ';
		$owl_data .= 'data-playtimeout="'. esc_attr($playtimeout) .'" ';
		$owl_data .= 'data-speed="'. esc_attr($speed) .'" ';
	}

	$html.='<div class="blog-posts'. esc_attr($row_cl) .'">';
		$html .= ($title) ? '<h3 class="vc_widget_title vc_blog_title"><span>'. esc_html($title) .'</span></h3>' : '';
		$html .= ($short_desc) ? '<div class="widget-sub-title">'. esc_html($short_desc) .'</div>' : '';
			$html .= ($style == 'carousel') ? '<div class="owl-carousel owl-theme" '.$owl_data.'>':'';
			$duration = 100;
			foreach ( $postslist as $post ) {
				if($rows > 1 && $style == 'carousel'){
					
					if ($post_index % $rows == 0 ){
						$html .= '<div class="group">';
					}
				}
				$class_nothumb = '';
				if(!get_the_post_thumbnail( $post->ID, $imagesize ) && !get_post_meta( $post->ID, 'complex_featured_post_value', true )) $class_nothumb = ' no-thumb';
				$html.='<div class="item-post post-'. $post->ID .' ' . $class_column . $class_nothumb . ' wow '. $showon_effect .'" data-wow-delay="'. $duration .'ms" data-wow-duration="0.5s">';
					$html.='<div class="post-wrapper">';
						
						$html.='<div class="post-thumb">';
							if(get_post_format( $post->ID ) == 'audio' && get_the_post_thumbnail( $post->ID, $imagesize )){
								$html.='<a href="'.get_the_permalink($post->ID).'">'.get_the_post_thumbnail($post->ID, $imagesize).'</a>';
							}
							if(get_post_meta( $post->ID, 'complex_featured_post_value', true ) && get_post_format( $post->ID ) != ''){
								$html.= str_replace('"title="', '" title="', do_shortcode(get_post_meta( $post->ID, 'complex_featured_post_value', true )));
							}elseif(get_the_post_thumbnail( $post->ID, $imagesize )){
								$html.='<a href="'.get_the_permalink($post->ID).'">'.get_the_post_thumbnail($post->ID, $imagesize).'</a>';
							}
							$html.='<div class="post-date">
								<span class="day">'. get_the_date('d', $post->ID) . '</span>
								<span class="month">'. get_the_date('M', $post->ID). '</span>
							</div>';
						$html.='</div>';
						
						$html.='<div class="post-info">';
							$html.='<h3 class="post-title"><a href="'.get_the_permalink($post->ID).'">'.get_the_title($post->ID).'</a></h3>';	
		
							$html.='<div class="post-excerpt">';
								$html.= lionthemes_get_excerpt($post->ID, $length);
							$html.='</div>';
							if($readmore_text){
								$html.= '<a href="'. get_the_permalink($post->ID) .'"><span class="readmore-text">'. esc_html($readmore_text) .'</span><span class="fa fa-angle-right"></span> </a>';
							}
						$html.='</div>';

					$html.='</div>';
				$html.='</div>';
				$post_index ++;
				if($rows > 1 && $style == 'carousel'){
					if (($post_index % $rows == 0) || $post_index == $total ) {
						$html .= '</div>';
					}
				}
				$duration = $duration + 100;
			}
		$html .= ($style == 'carousel') ? '</div>':'';
	$html.='</div>';

	wp_reset_postdata();
	
	return $html;
}
add_shortcode( 'blogposts', 'lionthemes_blogposts_shortcode' );
?>