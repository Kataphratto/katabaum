<?php
/**
 * The Header template for our theme
 *
 * @package WordPress
 * @subpackage Complex_theme
 * @since Complex Themes 1.0.1
 */
?>
<?php 

$complex_opt = get_option( 'complex_opt' );

?>


<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11" />
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
	<?php wp_head(); ?>
	
	<script src="https://www.google.com/recaptcha/api.js"></script>
	
</head>
<?php
	$complex_page_layout = (isset($complex_opt['page_layout']) && $complex_opt['page_layout'] == 'box') ? 'box-layout':'';
	$complex_header = (empty($complex_opt['header_layout']) || $complex_opt['header_layout'] == 'default') ? 'first': $complex_opt['header_layout'];
	if(get_post_meta( get_the_ID(), 'complex_header_page', true )){
		$complex_header = get_post_meta( get_the_ID(), 'complex_header_page', true );
	}
	if(get_post_meta( get_the_ID(), 'complex_layout_page', true )){
		$complex_page_layout = (get_post_meta( get_the_ID(), 'complex_layout_page', true ) == 'box') ? 'box-layout' : '';
	}
?>
<!--[if lte IE 9]>
<div class="avvisoIE" style = "width:100%; padding:20px; background:#000; color:#fff; position:fixed; top:0px; left: 0px; text-align:center; z-index:99999999999999999999">Scusa, stai utilizzando un browser non aggiornato. <a href="https://browsehappy.com/" style = "text-decoration:underline; color: #fff!important;">Aggiorna il tuo browser</a> per una migliore esperienza.</div>
<![endif]-->

<body <?php body_class(); ?>>
<div class="main-wrapper <?php echo esc_attr($complex_page_layout); ?>">
<?php do_action('before'); ?> 
	<header>
	<?php
		get_header($complex_header);
	?>
	</header>
	<div id="content" class="site-content">