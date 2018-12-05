<?php
/**
 * The template for displaying the footer
 *
 * @package WordPress
 * @subpackage Complex_theme
 * @since Complex Themes 1.0.1
 */
?>
<?php $complex_opt = get_option( 'complex_opt' ); ?>
		
		</div><!--.site-content-->
		<footer id="site-footer">
			<?php
			$complex_footer = (empty($complex_opt['footer_layout']) || $complex_opt['footer_layout'] == 'default') ? 'first': $complex_opt['footer_layout'];
			if(get_post_meta( get_the_ID(), 'complex_footer_page', true )){
				$complex_footer = get_post_meta( get_the_ID(), 'complex_footer_page', true );
			}
			get_footer($complex_footer);
			?>
		</footer>
		<?php if ( isset($complex_opt['back_to_top']) && $complex_opt['back_to_top'] ) { ?>
		<div id="back-top" class="hidden-xs"><i class="fa fa-angle-up"></i></div>
		<?php } ?>
	</div><!--.main wrapper-->
	<?php wp_footer(); ?>
</body>
</html>