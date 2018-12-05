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

<div class="header-container layout2">
	<?php if(!empty($complex_opt['enable_topbar'])){ ?>
	<div class="top-bar">
		<div class="container">
			<?php if (is_active_sidebar('top_header')) { ?> 
				<div class="widgets-top pull-left">
				<?php dynamic_sidebar('top_header'); ?> 
				</div>
			<?php } ?>
			
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
				<span class="lock-icon"><i class="ion-locked"></i><?php echo esc_html__('Il mio account', 'complex'); ?><i class="fa fa-angle-down"></i></span>
				<div class="acc-form">
					<div class="acc-form-inner">
						<div class="acc-form-padding">
						<?php if ( is_user_logged_in() ) {
							$current_user = wp_get_current_user();
							esc_html_e('Benvenuto ', 'complex');
							echo esc_html($current_user->user_nicename);
							?>
							<p class="acc-buttons">
								<a class="acc-btn logout-link" href="<?php echo wp_logout_url(home_url('/')); ?>" title="<?php echo esc_html_e('Logout', 'complex');?>"><?php echo esc_html_e('Logout', 'complex');?></a>
								<a class="acc-btn" href="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>" title="<?php esc_html_e('Il mio account','complex'); ?>"><?php esc_html_e('Il mio account','complex'); ?></a>
							</p>
						<?php } else { ?>
						<?php 
							wp_login_form( array('form_id' => 'top-loginform') ); ?>
							<div class="acc-link">
								<a class="lost-pwlink" href="<?php echo wp_lostpassword_url( get_permalink() ); ?>" title="<?php esc_html_e('Password dimenticata','complex'); ?>"><?php esc_html_e('Password dimenticata','complex'); ?></a>
								<?php wp_register('', ''); ?>
							</div><?php
						} ?>
						</div>
					</div>
				</div>
			</div>
		
			
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
			<div class="row">
				<?php $main_nav_col = 12; if ( has_nav_menu( 'categories' ) ){ $main_nav_col = 9; ?>
				<div class="col-md-3 categories-nav">
					<div class=" categories-menu showmore-menu">
						<div class="catmenu-opener">
							<i class="ion-navicon"></i>
							<span><?php echo esc_html__('Tutte le categorie', 'complex'); ?></span>
							<i class="ion-ios-arrow-down"></i>
							
						</div>
						<div class="menu-container">
							<div class="menu-list-wrapper">
								<?php 
									ob_start();
									wp_nav_menu( array( 'theme_location' => 'categories', 'container_class' => 'categories-menu-container', 'menu_class' => 'nav-menu' ) ); 
									$menu_html = ob_get_contents();
									ob_end_clean();
									$menu_html = preg_replace('/id="mega_main_menu"/', 'id="mega_main_menu_first"', $menu_html);
									$menu_html = preg_replace('/id="mega_main_menu_ul"/', 'id="mega_main_menu_ul_first"', $menu_html);
									echo $menu_html;
								?>
							</div>
							<?php if((!empty($complex_opt['categories_menu_items']))){ ?>
							<div data-items="<?php echo intval($complex_opt['categories_menu_items']); ?>" class="showmore-cats hide"><i class="fa fa-plus"></i><span><?php echo esc_html__('Altre categorie', 'complex') ?></span></div>
							<?php } ?>
						</div>
					</div>
				</div>
				<?php } ?>
				<div class="col-md-<?php echo $main_nav_col; ?> main-nav">
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
						<div class="toggle-menu"><i class="fa fa-bars"></i>  MENU</div>
						<div class="mobile-navigation">
							<?php wp_nav_menu( array( 'theme_location' => 'mobilemenu', 'container_class' => 'mobile-menu-container', 'menu_class' => 'nav-menu mobile-menu' ) ); ?>
						</div>
					</div>	
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
</div>