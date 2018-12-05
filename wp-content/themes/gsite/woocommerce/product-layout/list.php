<div class="product_list_widget">
	<div class="row">
	<?php $_delay = 100; ?>
	<?php while ( $loop->have_posts() ) : $loop->the_post(); global $product; ?>
		<?php wc_get_template( 'content-widget-product.php', array( 'show_rating' => $show_rating , 'class_column' => 'col-sm-12 col-xs-6', 'showon_effect'=> $showon_effect , 'hide_addcart'=> $hide_addcart , 'delay' => $_delay ) ); ?>
		<?php $_delay+=100; ?>
	<?php endwhile; ?>
	</div>
</div>