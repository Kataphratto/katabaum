<?php
/**
 * The template for displaying post detail
 *
 * @package WordPress
 * @subpackage Complex_theme
 * @since Complex Themes 1.0.1
 */
?>
<?php
$complex_opt = get_option( 'complex_opt' );
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<h1 class="entry-title"><?php the_title(); ?></h1>
		<div class="entry-meta">
			<?php complex_bootstrap_post_on(); ?> 
		</div><!-- .entry-meta -->
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php the_content(); ?> 
		<div class="clearfix"></div>
		<?php wp_link_pages(array(
			'before' => '<div class="page-links"><span>' . esc_html__('Pages:', 'complex') . '</span><ul class="pagination">',
			'after'  => '</ul></div>',
			'separator' => ''
		)); ?>
	</div><!-- .entry-content -->

	<footer class="entry-meta">
		<?php
			/* translators: used between list items, there is a space after the comma */
			$category_list = get_the_category_list(esc_html__(', ', 'complex'));

			/* translators: used between list items, there is a space after the comma */
			$tag_list = get_the_tag_list('', esc_html__(', ', 'complex'));
			
			echo complex_bootstrap_categories_list($category_list);
			if ($tag_list) {
				echo ' ';
				echo complex_bootstrap_tags_list($tag_list);
			}
			echo ' ';
			printf(wp_kses(__('<span class="glyphicon glyphicon-link"></span> <a href="%1$s" title="Permalink to %2$s" rel="bookmark">permalink</a>.', 'complex'), array('span', 'a')), get_permalink(), the_title_attribute('echo=0'));
		?> 

		<?php complex_bootstrap_edit_post_link(); ?> 
	</footer><!-- .entry-meta -->
</article><!-- #post -->