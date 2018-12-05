<?php
function lionthemes_brands_shortcode( $atts ) {
	
	$atts = shortcode_atts( array(
							'title' => '',
							'short_desc' => '',
							'id'=>'',
							'style'=>'',
							'el_class' => ''
							), $atts, 'mailchimp_form' );
	extract($atts);
	
	if(!$id) return;
	$el_class .= $style;
	ob_start();
	echo '<div class="mailchimp_form_widget '. esc_attr($el_class) .'">';
	echo ($title) ? '<h3 class="vc_widget_title vc_mailchimp_title"><span>'. esc_html($title) .'</span></h3>' : '';
	echo ($short_desc) ? '<div class="widget-sub-title">'. nl2br(esc_html($short_desc)) .'</div>' : '';
	echo do_shortcode( '[mc4wp_form id="'. intval($id) .'"]' );
	echo '</div>';
	$content = ob_get_contents();
	ob_end_clean();
	return $content;
}
add_shortcode( 'mailchimp_form', 'lionthemes_brands_shortcode' );
?>