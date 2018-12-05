<?php
/**
 * Template Name: Page Template Home 2
 *
 * @package WordPress
 * @subpackage Complex_theme
 * @since Complex Themes 1.0.1
 */
get_header(); 
?>
	<div id="main-content" class="home2-content">
		<?php while ( have_posts() ) : the_post(); ?>
			<?php 
				the_content(); 
			?>
			
		<?php endwhile; // end of the loop. ?>
	</div>
<?php
get_footer();
