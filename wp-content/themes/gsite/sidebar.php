<?php
/**
 * The sidebar containing the main widget area
 *
 * @package WordPress
 * @subpackage Complex_theme
 * @since Complex Themes 1.0.1
 */
?>

<?php if ( is_active_sidebar( 'page' ) ) : ?>
	<div class="col-md-3" id="sidebar-page">
		<?php do_action('before_sidebar'); ?> 
		<?php dynamic_sidebar( 'page' ); ?>
	</div><!-- #sidebar -->
<?php endif; ?>