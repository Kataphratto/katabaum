<?php
/**
 * Complex Themes functions and definitions
 *
 * @package WordPress
 * @subpackage Complex_theme
 * @since Complex Themes 1.0.1
 */

//Plugin-Activation
require_once( get_template_directory().'/class-tgm-plugin-activation.php' );

 //Init the Redux Framework options
if ( class_exists( 'ReduxFramework' ) && !isset( $redux_demo ) ) {
	if(file_exists( get_stylesheet_directory().'/theme-config.php')){
		require_once( get_stylesheet_directory().'/theme-config.php' );
	}else{
		require_once( get_template_directory().'/theme-config.php' );
	}
}

// require system parts
if ( file_exists( get_template_directory().'/includes/theme-helper.php' ) ) {
	require_once( get_template_directory().'/includes/theme-helper.php' );
}
if ( file_exists( get_template_directory().'/includes/custom-fields.php' ) ) {
	require_once( get_template_directory().'/includes/custom-fields.php' );
}
if ( file_exists( get_template_directory().'/includes/widgets.php' ) ) {
	require_once( get_template_directory().'/includes/widgets.php' );
}
if ( file_exists( get_template_directory().'/includes/head-media.php' ) ) {
	require_once( get_template_directory().'/includes/head-media.php' );
}
if ( file_exists( get_template_directory().'/includes/bootstrap-extras.php' ) ) {
	require_once( get_template_directory().'/includes/bootstrap-extras.php' );
}
if ( file_exists( get_template_directory().'/includes/bootstrap-tags.php' ) ) {
	require_once( get_template_directory().'/includes/bootstrap-tags.php' );
}
if ( file_exists( get_template_directory().'/includes/woo-hook.php' ) ) {
	require_once( get_template_directory().'/includes/woo-hook.php' );
}
if( class_exists('Vc_Manager') && file_exists( get_template_directory().'/includes/composer-shortcodes.php' ) ){
	require_once( get_template_directory().'/includes/composer-shortcodes.php' );
}

// theme setup
add_action( 'after_setup_theme', 'complex_setup');
function complex_setup(){
	// Load languages
	load_theme_textdomain( 'complex', get_template_directory() . '/languages' );

	// This theme supports a variety of post formats.
	add_theme_support( 'post-formats', array( 'image', 'gallery', 'video', 'audio' ) );
	
	// set default content width
	if ( ! isset( $content_width ) ) $content_width = 625;
	
	// This theme uses a custom image size for featured images, displayed on "standard" posts.
	add_theme_support( 'post-thumbnails' );
	
	// theme support
	add_theme_support( 'title-tag' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'custom-background', array() );
	add_theme_support( 'custom-header', array() );
	add_theme_support( 'woocommerce' );
	
	// thumbnails
	set_post_thumbnail_size( 1170, 9999 ); // Unlimited height, soft crop
	add_image_size( 'complex-category-thumb', 1170, 650, true ); // (cropped)
	add_image_size( 'complex-category-full', 1170, 650, true ); // (cropped)
	add_image_size( 'complex-post-thumb', 1170, 650, true ); // (cropped)
	add_image_size( 'complex-post-thumbwide', 590, 350, true ); // (cropped)
	
}
function complex_register_url($link){
    return '<a href="' . get_permalink( get_option('woocommerce_myaccount_page_id') ) . '">'. esc_html__('Register', 'complex') .'</a>';
}
add_filter('register','complex_register_url');
/**
* TGM-Plugin-Activation
*/
add_action( 'tgmpa_register', 'complex_register_required_plugins'); 
function complex_register_required_plugins(){
	$plugins = array(
				array(
					'name'               => esc_html__('LionThemes Helper', 'complex'),
					'slug'               => 'lionthemes-helper',
					'source'             => get_template_directory() . '/plugins/lionthemes-helper.zip',
					'required'           => true,
					'external_url'       => '',
					'force_activation' => false,
					'force_deactivation' => false,
				),
				array(
					'name'               => esc_html__('Mega Main Menu', 'complex'),
					'slug'               => 'mega_main_menu',
					'source'             => get_template_directory() . '/plugins/mega_main_menu.zip',
					'required'           => true,
					'external_url'       => '',
				),
				array(
					'name'               => esc_html__('Revolution Slider', 'complex'),
					'slug'               => 'revslider',
					'source'             => get_template_directory() . '/plugins/revslider.zip',
					'required'           => true,
					'external_url'       => '',
				),
				array(
					'name'               => esc_html__('Visual Composer', 'complex'),
					'slug'               => 'js_composer',
					'source'             => get_template_directory() . '/plugins/js_composer.zip',
					'required'           => true,
					'external_url'       => '',
				),
				// Plugins from the Online WordPress Plugin
				array(
					'name'               => esc_html__('Redux Framework', 'complex'),
					'slug'               => 'redux-framework',
					'required'           => true,
					'force_activation'   => false,
					'force_deactivation' => false,
				),
				array(
					'name'      => esc_html__('Contact Form 7', 'complex'),
					'slug'      => 'contact-form-7',
					'required'  => true,
				),
				array(
					'name'      => esc_html__('MailChimp for WP', 'complex'),
					'slug'      => 'mailchimp-for-wp',
					'required'  => true,
				),
				array(
					'name'      => esc_html__('Projects', 'complex'),
					'slug'      => 'projects-by-woothemes',
					'required'  => false,
				),
				array(
					'name'      => esc_html__('Shortcodes Ultimate', 'complex'),
					'slug'      => 'shortcodes-ultimate',
					'required'  => true,
				),
				array(
					'name'      => esc_html__('Testimonials', 'complex'),
					'slug'      => 'testimonials-by-woothemes',
					'required'  => false,
				),
				array(
					'name'      => esc_html__('TinyMCE Advanced', 'complex'),
					'slug'      => 'tinymce-advanced',
					'required'  => false,
				),
				array(
					'name'      => esc_html__('Black Studio TinyMCE Widget', 'complex'),
					'slug'      => 'black-studio-tinymce-widget',
					'required'  => false,
				),
				array(
					'name'      => esc_html__('Widget Importer & Exporter', 'complex'),
					'slug'      => 'widget-importer-exporter',
					'required'  => false,
				),
				array(
					'name'      => esc_html__('WooCommerce', 'complex'),
					'slug'      => 'woocommerce',
					'required'  => true,
				),
				array(
					'name'      => esc_html__('YITH WooCommerce Compare', 'complex'),
					'slug'      => 'yith-woocommerce-compare',
					'required'  => true,
				),
				array(
					'name'      => esc_html__('YITH WooCommerce Wishlist', 'complex'),
					'slug'      => 'yith-woocommerce-wishlist',
					'required'  => true,
				),
				array(
					'name'      => esc_html__('YITH WooCommerce Zoom Magnifier', 'complex'),
					'slug'      => 'yith-woocommerce-zoom-magnifier',
					'required'  => true,
				),
			);
			
	/**
	 * Array of configuration settings. Amend each line as needed.
	 * If you want the default strings to be available under your own theme domain,
	 * leave the strings uncommented.
	 * Some of the strings are added into a sprintf, so see the comments at the
	 * end of each line for what each argument will be.
	 */
	$config = array(
		'default_path' => '',                      // Default absolute path to pre-packaged plugins.
		'menu'         => 'tgmpa-install-plugins', // Menu slug.
		'has_notices'  => true,                    // Show admin notices or not.
		'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
		'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
		'is_automatic' => false,                   // Automatically activate plugins after installation or not.
		'message'      => '',                      // Message to output right before the plugins table.
		'strings'      => array(
			'page_title'                      => esc_html__( 'Install Required Plugins', 'complex' ),
			'menu_title'                      => esc_html__( 'Install Plugins', 'complex' ),
			'installing'                      => esc_html__( 'Installing Plugin: %s', 'complex' ), // %s = plugin name.
			'oops'                            => esc_html__( 'Something went wrong with the plugin API.', 'complex' ),
			'notice_can_install_required'     => _n_noop( 'This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.', 'complex' ),
			'notice_can_install_recommended'  => _n_noop( 'This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.', 'complex' ), // %1$s = plugin name(s).
			'notice_cannot_install'           => _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.', 'complex' ), // %1$s = plugin name(s).
			'notice_can_activate_required'    => _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.', 'complex' ), // %1$s = plugin name(s).
			'notice_can_activate_recommended' => _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.', 'complex' ), // %1$s = plugin name(s).
			'notice_cannot_activate'          => _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.', 'complex' ), // %1$s = plugin name(s).
			'notice_ask_to_update'            => _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.', 'complex' ), // %1$s = plugin name(s).
			'notice_cannot_update'            => _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.', 'complex' ), // %1$s = plugin name(s).
			'install_link'                    => _n_noop( 'Begin installing plugin', 'Begin installing plugins', 'complex' ),
			'activate_link'                   => _n_noop( 'Begin activating plugin', 'Begin activating plugins', 'complex' ),
			'return'                          => esc_html__( 'Return to Required Plugins Installer', 'complex' ),
			'plugin_activated'                => esc_html__( 'Plugin activated successfully.', 'complex' ),
			'complete'                        => esc_html__( 'All plugins installed and activated successfully. %s', 'complex' ), // %s = dashboard link.
			'nag_type'                        => 'updated' // Determines admin notice type - can only be 'updated', 'update-nag' or 'error'.
		)
	);
	tgmpa( $plugins, $config );
}

// Use WC 2.0 variable price format
add_filter( 'woocommerce_variable_price_html', 'bbloomer_variation_price_format_310', 10, 2 );
 
function bbloomer_variation_price_format_310( $price, $product ) {
 
// 1. Find the minimum regular and sale prices
 
$min_var_reg_price = $product->get_variation_regular_price( 'min', true );
$min_var_sale_price = $product->get_variation_sale_price( 'min', true );
 
// 2. New $price
 
if ( $min_var_sale_price ) {
$price = sprintf( __( 'Da: <del>%1$s</del><ins>%2$s</ins>', 'woocommerce' ), wc_price( $min_var_reg_price ), wc_price( $min_var_sale_price ) );
} else {
$price = sprintf( __( 'Da: %1$s', 'woocommerce' ), wc_price( $min_var_reg_price ) );
}
 
// 3. Return edited $price
 
return $price;
}
