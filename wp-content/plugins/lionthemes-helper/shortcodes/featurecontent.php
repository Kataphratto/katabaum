<?php
function complex_feature_content_shortcode( $atts ) {
	
	$atts = shortcode_atts( array(
							'icon'=>'',
							'feature_text'=>'',
							'short_desc'=>'',
							'showon_effect' => 'non-effect',
							'style'=>'style_1',
							'el_class' => '',
							), $atts, 'featuredcontent' );
	extract($atts);
	
	if(!$feature_text) return;
	
	$classes = esc_attr($showon_effect . ' ' . $style . ' ' . $el_class);
	ob_start();
	echo '<div class="feature_text_widget wow '. $classes .'" data-wow-delay="100ms" data-wow-duration="0.5s">';
		echo '<div class="feature_icon">';
			echo ($icon) ? '<span class="'. esc_attr($icon) .'"></span>':'';
		echo '</div>';
		echo '<div class="feature_content">';
			echo '<div class="feature_text">' . urldecode(base64_decode($feature_text)) . '</div>';
			echo ($short_desc) ? '<div class="short_desc">' . urldecode(base64_decode($short_desc)) . '</div>':'';
		echo '</div>';
	echo '</div>';
	$content = ob_get_contents();
	ob_end_clean();
	return $content;
}
add_shortcode( 'featuredcontent', 'complex_feature_content_shortcode' );
?>