<?php
/**
 * The template for displaying posts in the Gallery post format
 *
 * @package WordPress
 * @subpackage Complex_Themes
 * @since Complex Themes 1.0.1
 */

$complex_opt = get_option( 'complex_opt' );
$blogcolumn = (isset($complex_opt['blogcolumn'])) ? $complex_opt['blogcolumn'] : '';
if(is_single()) $blogcolumn = '';
?>
<article id="post-<?php the_ID(); ?>" <?php post_class($blogcolumn); ?>>
	<div class="post-wrapper">
	<?php if ( ! post_password_required() && ! is_attachment() ) : ?>
	<?php 
		if ( is_single() ) { ?>
			<div class="post-thumbnail"><?php echo do_shortcode(get_post_meta( get_the_ID(), 'complex_featured_post_value', true )); ?>
				<span class="post-date">
					<span class="day"><?php echo get_the_date( esc_html__('d', 'complex'), get_the_ID() ); ?></span>
					<span class="month"><?php echo get_the_date( esc_html__('M', 'complex'), get_the_ID() ); ?></span>
				</span>
			</div>
		<?php }
	?>
	<?php if ( !is_single() ) { ?>
		<div class="post-thumbnail">
			<?php echo do_shortcode(get_post_meta( get_the_ID(), 'complex_featured_post_value', true )); ?>
			<span class="post-date">
				<span class="day"><?php echo get_the_date( esc_html__('d', 'complex'), get_the_ID() ); ?></span>
				<span class="month"><?php echo get_the_date( esc_html__('M', 'complex'), get_the_ID() ); ?></span>
			</span>
		</div>
	<?php } ?>
	<?php endif; ?>
	
		<div class="post-info<?php if ( !has_post_thumbnail() ) { echo ' no-thumbnail';} ?>">
			<header class="entry-header">
				<?php if ( is_single() ) : ?>
					<h1 class="entry-title"><?php the_title(); ?></h1>
				<?php else : ?>
					<h3 class="entry-title">
						<a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
					</h3>
				<?php endif; ?>
				<ul class="post-entry-data">
					<li class="post-by"><?php echo esc_html__('Posted by', 'complex'); ?> <?php the_author_posts_link(); ?></li>
					<li class="post-comments"><a href="<?php comments_link(); ?>"><?php echo sprintf(esc_html__('%d Comment(s)', 'complex'), get_comments_number( get_the_ID() )) ?></a></li>
				</ul>
			</header>
			
			<?php if (is_search()) { // Only display Excerpts for Search ?> 
			<div class="entry-summary">
				<?php the_excerpt(); ?> 
				<div class="clearfix"></div>
			</div><!-- .entry-summary -->
			<?php } else { ?> 
				<?php if ( is_single() ) : ?>
					<div class="entry-content">
						<?php the_content( esc_html__( 'Continue reading <span class="meta-nav">&rarr;</span>', 'complex' ) ); ?>
						<?php wp_link_pages(array(
							'before' => '<div class="page-links"><span>' . esc_html__('Pages:', 'complex') . '</span><ul class="pagination">',
							'after'  => '</ul></div>',
							'separator' => ''
						)); ?>
					</div>
				<?php else : ?>
					<div class="entry-summary">
						<?php the_excerpt(); ?>
					</div>
					<a class="readmore-link" href="<?php the_permalink(); ?>"><?php echo (!empty($complex_opt['readmore_text'])) ? esc_html($complex_opt['readmore_text']): esc_html__('Read more', 'complex'); ?></a>
				<?php endif; ?>
			<?php } //endif; ?> 
			<?php if ( is_single() ){ ?>
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
				</div>
				<?php } // End if 'post' == get_post_type() ?> 

				<div class="entry-counter">
					<div class="post-comments" title="<?php echo esc_html__('Total Comments', 'complex') ?>" data-toggle="tooltip"><i class="fa fa-comments"></i><?php echo get_comments_number( get_the_ID() ) ?></div>
					<div class="post-views" title="<?php echo esc_html__('Total Views', 'complex') ?>" data-toggle="tooltip">
						<i class="fa fa-eye"></i><?php echo complex_get_post_viewed(get_the_ID()); ?>
					</div>
					<?php do_action( 'lionthemes_like_button' , get_the_ID()); ?>
				</div>
				<?php if( function_exists('complex_blog_sharing') && is_single()) { ?>
					<div class="social-sharing"><?php complex_blog_sharing(); ?></div>
				<?php } ?>
			</footer>
			<?php } ?>
		</div>
	</div>
</article>