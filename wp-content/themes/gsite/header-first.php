<?php
/**
 * The Header template for our theme
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package WordPress
 * @subpackage Complex_Themes
 * @since Complex Themes 1.0.1
 */
 
$complex_opt = get_option( 'complex_opt' );
$logo = ( !empty($complex_opt['logo_main']['url']) ) ? $complex_opt['logo_main']['url'] : '';
if(get_post_meta( get_the_ID(), 'complex_logo_page', true )){
	$logo = get_post_meta( get_the_ID(), 'complex_logo_page', true );
}
?>

<!--[if lte IE 9]>
<div class="avvisoIE" style = "width:100%; padding:20px; background:#000; color:#fff; position:fixed; top:0px; left: 0px; text-align:center; z-index:99999999999999999999">Scusa, stai utilizzando un browser non aggiornato. <a href="https://browsehappy.com/" style = "text-decoration:underline; color: #fff!important;">Aggiorna il tuo browser</a> per una migliore esperienza.</div>
<![endif]-->

<div class="header-container layout1">
	<?php if(!empty($complex_opt['enable_topbar'])){ ?>
	<div class="top-bar">
		<div class="container">

			<?php if(isset($complex_opt['welcome_message']) && $complex_opt['welcome_message']!=''){ ?>
				<div class="welcome-message pull-left">
					<?php echo wp_kses($complex_opt['welcome_message'], array(
						'a' => array(
							'href' => array(),
							'title' => array()
						),
						'img' => array(
							'src' => array(),
							'alt' => array()
						),
						'ul' => array(),
						'li' => array(),
						'i' => array(
							'class' => array()
						),
						'br' => array(),
						'em' => array(),
						'strong' => array(),
						'p' => array(),
					)); ?>
				</div>
			<?php } ?>

			<div class="header-login-form">
				<span class="lock-icon"><i class="ion-locked"></i><?php echo esc_html__('My Account', 'complex'); ?></span>
				<div class="acc-form">
					<div class="acc-form-inner">
						<div class="acc-form-padding">
						<?php if ( is_user_logged_in() ) {
							$current_user = wp_get_current_user();
							esc_html_e('Welcome ', 'complex');
							echo esc_html($current_user->user_nicename);
							?>
							<p class="acc-buttons">
								<a class="acc-btn logout-link" href="<?php echo wp_logout_url(home_url('/')); ?>" title="<?php echo esc_html_e('Logout', 'complex');?>"><?php echo esc_html_e('Logout', 'complex');?></a>
								<a class="acc-btn" href="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>" title="<?php esc_html_e('My Account','complex'); ?>"><?php esc_html_e('My Account','complex'); ?></a>
							</p>
						<?php } else { ?>
						<?php 
							wp_login_form( array('form_id' => 'top-loginform') ); ?>
							<div class="acc-link">
								<a class="lost-pwlink" href="<?php echo wp_lostpassword_url( get_permalink() ); ?>" title="<?php esc_html_e('Lost Password','complex'); ?>"><?php esc_html_e('Lost Password','complex'); ?></a>
								<?php wp_register('', ''); ?>
							</div><?php
						} ?>
						</div>
					</div>
				</div>
			</div>
		
			<?php if (is_active_sidebar('top_header')) { ?> 
				<div class="widgets-top pull-right">
				<?php dynamic_sidebar('top_header'); ?> 
				</div>
			<?php } ?>

		</div>
	</div>	
	<?php } ?>
	
	<div class="header">
		<div class="container">
			<div class="row">
				<div class="col-sm-3">
					<?php if( $logo ){ ?>
					<div class="logo"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><img src="<?php echo esc_url($logo); ?>" alt="" /></a></div>
					<?php
					} else { ?>
						<h1 class="logo"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
						<?php
					} ?>
				</div>
				<div class="col-sm-9">
					<?php if(class_exists('WC_Widget_Cart') && !empty($complex_opt['show_minicart'])) { ?>
						<?php the_widget('WC_Widget_Cart'); ?>
					<?php } ?>
					
					<?php if(class_exists('WC_Widget_Product_Search')) { ?>	
					<?php the_widget('WC_Widget_Product_Search', array('title' => '')); ?>
					<?php } ?>
				</div>
			</div>
		
		</div>
	</div>
	
	<div class="nav-menus">
		<div class="container">
			<?php if ( has_nav_menu( 'primary' ) ) { ?>
			<div class="visible-lg visible-md">
				<div class="nav-desktop">
					<?php wp_nav_menu( array( 'theme_location' => 'primary', 'container_class' => 'primary-menu-container', 'menu_class' => 'nav-menu' ) ); ?>
				</div>
				
			</div>
			<?php } ?>
			
			<?php if ( has_nav_menu( 'mobilemenu' ) ) { ?>
			<div class="nav-mobile visible-xs visible-sm">
				<div class="mobile-menu-overlay"></div>
				<div class="toggle-menu"><i class="fa fa-bars"></i></div>
				<div class="mobile-navigation">
					<?php wp_nav_menu( array( 'theme_location' => 'mobilemenu', 'container_class' => 'mobile-menu-container', 'menu_class' => 'nav-menu mobile-menu' ) ); ?>
				</div>
			</div>	
			<?php } ?>
	
		</div>
	</div>

</div>