<?php
/**
 * The template for displaying the footer
 *
 * Contains footer content and the closing of the #main and #page div elements.
 *
 * @package WordPress
 * @subpackage Complex_Themes
 * @since Complex Themes 1.0.1
 */
?>
<?php 
$complex_opt = get_option( 'complex_opt' );
$ft_col_class = '';
?>

	<div class="footer layout1">
		<?php if (is_active_sidebar('footer_widget_top')) { ?> 
		<div class="footer-widget-top">
			<div class="container">
				<div class="container-inner">
					<?php dynamic_sidebar('footer_widget_top'); ?>
				</div>
			</div>
		</div>
		<?php } ?>
		<?php if(isset($complex_opt)) { ?>
		<div class="footer-top">
			<div class="container">
				<div class="row">

					<?php if (is_active_sidebar('footer_widget_1')) { ?> 
					<div class="col-sm-3 col-md-4 col-xs-12 widget-footer widget-footer-1">
						<?php dynamic_sidebar('footer_widget_1'); ?>
					</div>
					<?php } ?>
					
					<?php if (is_active_sidebar('footer_widget_2')) { ?> 
					<div class="col-sm-3 col-md-2 col-xs-12 widget-footer widget-footer-2">
						<?php dynamic_sidebar('footer_widget_2'); ?> 
					</div>
					<?php } ?>
					
					<?php if (is_active_sidebar('footer_widget_3')) { ?> 
					<div class="col-sm-3 col-md-2 col-xs-12 widget-footer widget-footer-3">
						<?php dynamic_sidebar('footer_widget_3'); ?> 
					</div>
					<?php } ?>
					
					<?php if (is_active_sidebar('footer_widget_4')) { ?> 
					<div class="col-sm-3 col-md-4 col-xs-12 widget-footer widget-footer-4">
						<?php dynamic_sidebar('footer_widget_4'); ?> 
					</div>
					<?php } ?>

				</div>
			</div>
		</div>
		<?php } ?>
		
		<?php if(isset($complex_opt)) { ?>
		<div class="footer-bottom">
			<div class="container">
				<div class="container-inner">
					<div class="row">				
						<div class="col-sm-4">
							<div class="widget-copyright">
								<?php 
								if( isset($complex_opt['copyright']) && $complex_opt['copyright']!='' ) {
									echo wp_kses($complex_opt['copyright'], array(
										'a' => array(
											'href' => array(),
											'title' => array()
										),
										'br' => array(),
										'em' => array(),
										'strong' => array(),
									));
								} else {
									echo 'Copyright <a href="'.esc_url( home_url( '/' ) ).'">'.get_bloginfo('name').'</a> '.date('Y').'. All Rights Reserved';
								}
								?>
							</div>		
						</div>
						<div class="col-sm-8">
							<?php if(isset($complex_opt['social_icons']) && $complex_opt['social_icons']!=''){ ?>
							<div class="widget widget-social">
								<?php
								if(isset($complex_opt['social_icons'])) {
									echo '<ul class="social-icons">';
									foreach($complex_opt['social_icons'] as $key=>$value ) {
										if($value!=''){
											if($key=='vimeo'){
												echo '<li><a class="'.esc_attr($key).' social-icon" href="'.esc_url($value).'" title="'.ucwords(esc_attr($key)).'" target="_blank"><i class="fa fa-vimeo-square"></i></a></li>';
											} else {
												echo '<li><a class="'.esc_attr($key).' social-icon" href="'.esc_url($value).'" title="'.ucwords(esc_attr($key)).'" target="_blank"><i class="fa fa-'.esc_attr($key).'"></i></a></li>';
											}
										}
									}
									echo '</ul>';
								}
								?>
							</div>
							<?php } ?>

							<div class="widget-payment">
								<?php if(isset($complex_opt['payment_icons']) && $complex_opt['payment_icons']!='' ) {
									echo wp_kses($complex_opt['payment_icons'], array(
										'a' => array(
											'href' => array(),
											'title' => array()
										),
										'img' => array(
											'src' => array(),
											'alt' => array()
										),
									)); 
								} ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php } ?>
	</div>