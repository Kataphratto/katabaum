<?php
function lionthemes_menu_location( $atts ) {
	global $complex_opt;
	$atts = shortcode_atts( array(
							'title'=>'',
							'location'=>'',
							'limit_items'=>'0',
							'el_class' => ''
							), $atts, 'menu_location' );
	extract($atts);
	
	if($location=='') return;

	
	ob_start();
	wp_nav_menu( array( 'theme_location' => $location, 'container_class' => 'widget-menu-container', 'menu_class' => 'nav-menu' ) );
	$content = ob_get_contents();
	ob_end_clean();
	if(function_exists('complex_make_id')){
		$get_id = complex_make_id();
	}else{
		$get_id = substr(str_shuffle(md5(time())),0, 6);
	}
	
	$new_menu_id = 'id="mega_menu_widget_'. $get_id .'"';
	$new_menu_ul_id = 'id="mega_menu_ul_widget_'. $get_id .'"';
	$content = preg_replace('/id="mega_main_menu"/', $new_menu_id, $content, 1);
	$content = preg_replace('/id="mega_main_menu_ul"/', $new_menu_ul_id, $content, 1);
	
	
	$html = '<div id="vc-menu-'.$get_id.'" class="menu-widget-container vc-menu-widget ' . (($limit_items != '0') ? 'showmore-menu ':'') . esc_attr($el_class) .'">';
	$html .= ($title) ? '<h3 class="vc_widget_title vc_menu_title"><span>'. esc_html($title) .'</span></h3>' : '';
	$html .= '<div class="categories-menu">';
	$html .= $content;
	if($limit_items != '0'){
		$html .= '<div data-items="'. intval($limit_items) .'" class="showmore-cats hide"><i class="fa fa-plus"></i><span>'. esc_html__('More Categories', 'complex') .'</span></div>';
	}
	$html .= '</div>';
	$html .= '</div>';
	
	return $html;
}
add_shortcode( 'menu_location', 'lionthemes_menu_location' );
?>