<?php
/**
 * Template for quote post format
 *
 * @package WordPress
 * @subpackage Complex_theme
 * @since Complex Themes 1.0.1
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="entry-content">
		<?php the_content(complex_bootstrap_more_link_text()); ?> 
		<div class="clearfix"></div>
		<?php wp_link_pages(array(
			'before' => '<div class="page-links"><span>' . esc_html__('Pages:', 'complex') . '</span><ul class="pagination">',
			'after'  => '</ul></div>',
			'separator' => ''
		)); ?>
	</div><!-- .entry-content -->

	<footer class="entry-meta">
		<?php if ('post' == get_post_type()) { // Hide category and tag text for pages on Search ?> 
		<div class="entry-meta-category-tag">
			<?php
				/* translators: used between list items, there is a space after the comma */
				$categories_list = get_the_category_list(esc_html__(', ', 'complex'));
				if (!empty($categories_list)) {
			?> 
			<span class="cat-links">
				<?php echo complex_bootstrap_categories_list($categories_list); ?> 
			</span>
			<?php } // End if categories ?> 

			<?php
				/* translators: used between list items, there is a space after the comma */
				$tags_list = get_the_tag_list('', esc_html__(', ', 'complex'));
				if ($tags_list) {
			?> 
			<span class="tags-links">
				<?php echo complex_bootstrap_tags_list($tags_list); ?> 
			</span>
			<?php } // End if $tags_list ?> 
		</div><!--.entry-meta-category-tag-->
		<?php } // End if 'post' == get_post_type() ?> 

		<div class="entry-meta-comment-tools">
			<?php if (! post_password_required() && (comments_open() || '0' != get_comments_number())) { ?> 
			<span class="comments-link"><?php complex_bootstrap_comments_popup_link(); ?></span>
			<?php } //endif; ?> 

			<?php complex_bootstrap_edit_post_link(); ?> 
		</div><!--.entry-meta-comment-tools-->
	</footer><!-- .entry-meta -->
</article><!-- #post -->