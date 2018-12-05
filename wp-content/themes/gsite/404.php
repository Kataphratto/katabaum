<?php get_header(); ?> 
<?php $complex_opt = get_option( 'complex_opt' ); ?>
	<div class="page-404">
		<div class="container text-center">
			<article>
				<div class="page-content">
					<?php if(!empty($complex_opt['404_content'])){
						echo wp_kses($complex_opt['404_content'], array(
								'a' => array(
								'href' => array(),
								'title' => array()
								),
								'div' => array(
									'class' => array(),
								),
								'img' => array(
									'src' => array(),
									'alt' => array()
								),
								'h1' => array(
									'class' => array(),
								),
								'h2' => array(
									'class' => array(),
								),
								'h3' => array(
									'class' => array(),
								),
								'h4' => array(
									'class' => array(),
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
						));
					} ?>
					
					
					<?php get_search_form(); ?>

				</div>
			</article>
		</div>

	</div>
	

<?php get_footer(); ?> 