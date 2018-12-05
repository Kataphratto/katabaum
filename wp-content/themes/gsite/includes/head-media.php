<?php
/**
* Theme stylesheet & javascript registration
*
* @package WordPress
* @subpackage Complex_theme
* @since Complex Themes 1.0.1
*/
//Complex theme style and script 
function complex_register_script()
{
	global $complex_opt, $woocommerce;
	$default_font = "'Arial', Helvetica, sans-serif";
	$demos = array(
			1 => array(
				'menu_color' => '#555',
				'menu_bg_color' => '#FFF'
			),
			2 => array(
				'menu_color' => '#FFF',
				'menu_bg_color' => '#1b252f'
			)
		);


	if(isset($_GET['demo']) && !empty($demos[intval($_GET['demo'])])){
		$complex_opt['menufont']['color'] = $demos[intval($_GET['demo'])]['menu_color'];
		$complex_opt['menu_background']['background-color'] = $demos[intval($_GET['demo'])]['menu_bg_color'];
		$complex_opt['sticky_bg'] = $demos[intval($_GET['demo'])]['menu_bg_color'];
	}
	
	$params = array(
		'heading_font'=> ((!empty($complex_opt['headingfont']['font-family'])) ? $complex_opt['headingfont']['font-family'] : $default_font),
		'heading_color'=> ((!empty($complex_opt['headingfont']['color'])) ? $complex_opt['headingfont']['color'] : '#181818'),
		'heading_font_weight'=> ((!empty($complex_opt['headingfont']['font-weight'])) ? $complex_opt['headingfont']['font-weight'] : '700'),
		'menu_font'=> ((!empty($complex_opt['menufont']['font-family'])) ? $complex_opt['menufont']['font-family'] : $default_font),
		'menu_color'=> ((!empty($complex_opt['menufont']['color'])) ? $complex_opt['menufont']['color'] : '#555'),
		'menubg_color'=> ((!empty($complex_opt['menu_background']['background-color'])) ? $complex_opt['menu_background']['background-color'] : '#FFF'),
		'menu_font_size'=> ((!empty($complex_opt['menufont']['font-size'])) ? $complex_opt['menufont']['font-size'] : '14px'),
		'menu_font_weight'=> ((!empty($complex_opt['menufont']['font-weight'])) ? $complex_opt['menufont']['font-weight'] : '400'),
		'sub_menu_bg'=> ((!empty($complex_opt['sub_menu_bg'])) ? $complex_opt['sub_menu_bg'] : '#2c2c2c'),
		'sub_menu_color'=> ((!empty($complex_opt['sub_menu_color'])) ? $complex_opt['sub_menu_color'] : '#cfcfcf'),
		'body_font'=> ((!empty($complex_opt['bodyfont']['font-family'])) ? $complex_opt['bodyfont']['font-family'] : $default_font),
		'text_color'=> ((!empty($complex_opt['bodyfont']['color'])) ? $complex_opt['bodyfont']['color'] : '#6e6e6e'),
		'primary_color' => (!empty($complex_opt['primary_color'])) ? $complex_opt['primary_color'] : '#1CBAC8',
		'sale_color' => ((!empty($complex_opt['sale_color'])) ? $complex_opt['sale_color'] : '#f49835'),
		'saletext_color' => ((!empty($complex_opt['saletext_color'])) ? $complex_opt['saletext_color'] : '#f49835'),
		'rate_color' => ((!empty($complex_opt['rate_color'])) ? $complex_opt['rate_color'] : '#f49835'),
		'page_width' => (!empty($complex_opt['box_layout_width'])) ? $complex_opt['box_layout_width'] . 'px' : '1200px',
		'body_bg_color' => ((!empty($complex_opt['background_opt']['background-color'])) ? $complex_opt['background_opt']['background-color'] : '#fff'),
		'stickbg' => (!empty($complex_opt['sticky_bg'])) ? $complex_opt['sticky_bg'] : '#FFFFFF',
		'stickopacity' => (!empty($complex_opt['sticky_bg_opacity'])) ? (int)$complex_opt['sticky_bg_opacity'] . '%' : '80%',
		'textcolorfooter' => (!empty($complex_opt['footer_text_color'])) ? $complex_opt['footer_text_color'] : '#ababab',
		'headingcolorfooter' => (!empty($complex_opt['footer_heading_color'])) ? $complex_opt['footer_heading_color'] : '#fff',
		'bordercolorfooter' => (!empty($complex_opt['footer_border_color'])) ? $complex_opt['footer_border_color'] : '#5c5c5c',
		'topbarcolor' => (!empty($complex_opt['topbar_color'])) ? $complex_opt['topbar_color'] : '#555555',
		'headericonscolor' => (!empty($complex_opt['header_icons_color'])) ? $complex_opt['header_icons_color'] : '#0291d6',
	);
	
	
	if( function_exists('compileLess') ){
		if(isset($_GET['demo']) && !empty($demos[intval($_GET['demo'])])){
			compileLess('theme.less', 'theme_demo_' . intval($_GET['demo']) . '.css', $params);
		}else{
			compileLess('theme.less', 'theme.css', $params);
		}
	}
	
	wp_enqueue_style( 'complex-style', get_template_directory_uri() . '/style.css' );
	wp_enqueue_style( 'bootstrap', get_template_directory_uri() . '/css/bootstrap.min.css'  );
	wp_enqueue_style( 'bootstrap-theme', get_template_directory_uri() . '/css/bootstrap-theme.min.css'  );
	wp_enqueue_style( 'awesome', get_template_directory_uri() . '/css/font-awesome.min.css'  );
	wp_enqueue_style( 'ionicons-min', get_template_directory_uri() . '/css/ionicons.min.css'  );
	wp_enqueue_style( 'owl-style', get_template_directory_uri() . '/owl-carousel/owl.carousel.css'  );
	wp_enqueue_style( 'owl-transitions', get_template_directory_uri() . '/owl-carousel/owl.transitions.css'  );
	wp_enqueue_style( 'animate', get_template_directory_uri() . '/css/animate.min.css' );
	wp_enqueue_style( 'fancybox', get_template_directory_uri() . '/fancybox/jquery.fancybox.css' );
	if ( is_singular() ) wp_enqueue_script( 'comment-reply' );
	
	if(isset($_GET['demo']) && !empty($demos[$_GET['demo']])){
		if(file_exists( get_template_directory() . '/css/theme_demo_' . intval($_GET['demo']) . '.css' )){
			wp_enqueue_style( 'complex-theme-options', get_template_directory_uri() . '/css/theme_demo_' . intval($_GET['demo']) . '.css' );
		}
	}else{
		if(file_exists( get_template_directory() . '/css/theme.css' )){
			wp_enqueue_style( 'complex-theme-options', get_template_directory_uri() . '/css/theme.css'  );
		}
	}
	
	// add custom style sheet
	if ( isset($complex_opt['custom_css']) && $complex_opt['custom_css']!='') {
		wp_add_inline_style( 'complex-theme-options', $complex_opt['custom_css'] );
	}
	
	
	// add add-to-cart-variation js to all other pages without detail. it help quickview work with variable products
	if( class_exists('WooCommerce') && !is_product() ) {
		wp_enqueue_script( 'wc-add-to-cart-variation', $woocommerce->plugin_url() . '/assets/js/frontend/add-to-cart-variation.js', array('jquery'), '', true );
    }
		
    wp_enqueue_script( 'bootstrap', get_template_directory_uri() . '/js/bootstrap.min.js', array('jquery'), '', true );
    wp_enqueue_script( 'wow', get_template_directory_uri() . '/js/jquery.wow.min.js', array('jquery'), '', true );
    wp_enqueue_script( 'modernizr', get_template_directory_uri() . '/js/modernizr.custom.js', array('jquery'), '', true );
    wp_enqueue_script( 'owl-carousel', get_template_directory_uri() . '/owl-carousel/owl.carousel.js', array('jquery'), '', true );
    wp_enqueue_script( 'auto-grid', get_template_directory_uri() . '/js/autoGrid.min.js', array('jquery'), '', true );
    wp_enqueue_script( 'fancybox', get_template_directory_uri() . '/fancybox/jquery.fancybox.pack.js', array('jquery'), '', true );
    wp_enqueue_script( 'complex-js', get_template_directory_uri() . '/js/custom.js', array('jquery'), '', true );
	// add ajaxurl
	$ajaxurl = 'var ajaxurl = "'. esc_js(admin_url('admin-ajax.php')) .'";';
	wp_add_inline_script( 'complex-js', $ajaxurl, 'before' );
	
	
	// add newletter popup js
	if(isset($complex_opt['enable_popup']) && $complex_opt['enable_popup']){
		if (is_front_page() && (!empty($complex_opt['popup_onload_form']) || !empty($complex_opt['popup_onload_content']))) {
			$newletter_js = 'jQuery(document).ready(function($){
								if($(\'#popup_onload\').length){
									$(\'#popup_onload\').fadeIn(400);
								}
								$(\'#popup_onload .close-popup, #popup_onload .overlay-bg-popup\').click(function(){
									var not_again = $(this).closest(\'#popup_onload\').find(\'.not-again input[type="checkbox"]\').prop(\'checked\');
									if(not_again){
										var datetime = new Date();
										var exdays = '. ((!empty($complex_opt['popup_onload_expires'])) ? intval($complex_opt['popup_onload_expires']) : 7) . ';
										datetime.setTime(datetime.getTime() + (exdays*24*60*60*1000));
										document.cookie = \'no_again=1; expires=\' + datetime.toUTCString();
									}
									$(this).closest(\'#popup_onload\').fadeOut(400);
								});
							});';
			wp_add_inline_script( 'complex-js', $newletter_js );
		}
	}
	
	
	// add remove top cart item
	$remove_cartitem_js = 'jQuery(document).on(\'click\', \'.mini_cart_item .remove\', function(e){
							var product_id = jQuery(this).data("product_id");
							var item_li = jQuery(this).closest(\'li\');
							var a_href = jQuery(this).attr(\'href\');
							jQuery.ajax({
								type: \'POST\',
								dataType: \'json\',
								url: ajaxurl,
								data: \'action=complex_product_remove&\' + (a_href.split(\'?\')[1] || \'\'), 
								success: function(data){
									if(typeof(data) != \'object\'){
										alert(\'' . esc_html__('Could not remove cart item.', 'complex') . '\');
										return;
									}
									jQuery(\'.topcart .cart-toggler .qty\').html(data.qty);
									jQuery(\'.topcart .cart-toggler .subtotal\').html(data.subtotal);
									jQuery(\'.topcart_content\').css(\'height\', \'auto\');
									if(data.qtycount > 0){
										jQuery(\'.topcart_content .total .amount\').html(data.subtotal);
									}else{
										jQuery(\'.topcart_content .cart_list\').html(\'<li class="empty">' .  esc_html__('No products in the cart.', 'complex') .'</li>\');
										jQuery(\'.topcart_content .total\').remove();
										jQuery(\'.topcart_content .buttons\').remove();
									}
									item_li.remove();
								}
							});
							e.preventDefault();
							return false;
						});';
	wp_add_inline_script( 'complex-js', $remove_cartitem_js );
	
	//sticky header
	if(isset($complex_opt['sticky_header']) && $complex_opt['sticky_header']){ 
		$sticky_header_js = 'jQuery(document).ready(function($){
			$(window).scroll(function() {
				var start = $(".main-wrapper > header").outerHeight();
				' . ((is_admin_bar_showing()) ? '$("header .header-container").addClass("has_admin");' : '') . '
				if ($(this).scrollTop() > start){  
					$("header .header-container").addClass("sticky");
				}
				else{
					$("header .header-container").removeClass("sticky");
				}
			});
		});';
		wp_add_inline_script( 'complex-js', $sticky_header_js );
	}
}
add_action( 'wp_enqueue_scripts', 'complex_register_script' );

// bootstrap for back-end page
add_action( 'admin_enqueue_scripts', 'complex_admin_custom' );
function complex_admin_custom() {
	wp_enqueue_style( 'complex-admin-custom', get_template_directory_uri() . '/css/admin.css', array(), '1.0.0');
}
//Complex theme gennerate title
add_filter( 'wp_title', 'complex_wp_title', 10, 2 );
function complex_wp_title( $title, $sep ) {
	global $paged, $page;
	if ( is_feed() ) return $title;
	
	$title .= get_bloginfo( 'name', 'display' );
	
	// Add the site description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		$title = "$title $sep $site_description";
	
	if ( $paged >= 2 || $page >= 2 )
		$title = "$title $sep " . sprintf( esc_html__( 'Page %s', 'complex' ), max( $paged, $page ) );
	
	return $title;
}

// add favicon to header
add_action( 'wp_head', 'complex_wp_custom_head', 100);
function complex_wp_custom_head(){
	global $complex_opt;
	if ( ! function_exists( 'has_site_icon' ) || ! has_site_icon() ) {
		if(isset($complex_opt['opt-favicon']) && $complex_opt['opt-favicon']!="") { 
			if(is_ssl()){
				$complex_opt['opt-favicon'] = str_replace('http:', 'https:', $complex_opt['opt-favicon']);
			}
		?>
			<link rel="icon" type="image/png" href="<?php echo esc_url($complex_opt['opt-favicon']['url']);?>">
		<?php }
	}
}
// body class for wow scroll script
add_filter('body_class', 'complex_effect_scroll');
function complex_effect_scroll($classes){
	$classes[] = 'complex-animate-scroll';
	return $classes;
}
?>