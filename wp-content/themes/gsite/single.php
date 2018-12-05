<?php
/**
 * Template for dispalying single post (read full post page).
 * 
 * @package complex
 */

get_header();

/**
 * determine main column size from actived sidebar
 */
$complex_opt = get_option( 'complex_opt' );
?>
<div id="main-content">
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
				<?php complex_breadcrumb(); ?>
			</div>
			<?php if(isset($complex_opt['sidebarblog_pos']) && $complex_opt['sidebarblog_pos']=='left') :?>
				<?php get_sidebar('blog'); ?>
			<?php endif; ?>
				<div class="col-xs-12 <?php if ( is_active_sidebar( 'blog' ) ) : ?>col-md-9<?php endif; ?> content-area" id="main-column">
					<main id="main" class="site-main single-post-content">
						<?php 
						while (have_posts()) {
							the_post();
							complex_set_post_view(get_the_ID());
							get_template_part('content', get_post_format());

							echo "\n\n";
							
							complex_bootstrap_pagination();

							echo "\n\n";
							
							// If comments are open or we have at least one comment, load up the comment template
							if (comments_open() || '0' != get_comments_number()) {
								comments_template();
							}

							echo "\n\n";

						} //endwhile;
						?> 
					</main>
				</div>
			<?php if(isset($complex_opt['sidebarblog_pos']) && $complex_opt['sidebarblog_pos']=='right') :?>
				<?php get_sidebar('blog'); ?>
			<?php endif; ?>
		</div>
	</div>
</div>
<?php get_footer(); ?> 