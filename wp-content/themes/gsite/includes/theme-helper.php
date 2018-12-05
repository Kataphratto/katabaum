<?php
// All Complex theme helper functions in here
function complex_woocommerce_query($type,$post_per_page=-1,$cat=''){
	$args = complex_woocommerce_query_args($type,$post_per_page,$cat);
	return new WP_Query($args);
}
function complex_vc_custom_css_class( $param_value, $prefix = '' ) {
	$css_class = preg_match( '/\s*\.([^\{]+)\s*\{\s*([^\}]+)\s*\}\s*/', $param_value ) ? $prefix . preg_replace( '/\s*\.([^\{]+)\s*\{\s*([^\}]+)\s*\}\s*/', '$1', $param_value ) : '';
	return $css_class;
}


// All Complex theme helper functions in here
function complex_get_effect_list(){
	return array(
		esc_html__( 'None', 'complex' ) 	=> 'non-effect',
		esc_html__( 'Bounce In', 'complex' ) 	=> 'bounceIn',
		esc_html__( 'Bounce In Down', 'complex' ) 	=> 'bounceInDown',
		esc_html__( 'Bounce In Left', 'complex' ) 	=> 'bounceInLeft',
		esc_html__( 'Bounce In Right', 'complex' ) 	=> 'bounceInRight',
		esc_html__( 'Bounce In Up', 'complex' ) 	=> 'bounceInUp',
		esc_html__( 'Fade In', 'complex' ) 	=> 'fadeIn',
		esc_html__( 'Fade In Down', 'complex' ) 	=> 'fadeInDown',
		esc_html__( 'Fade In Left', 'complex' ) 	=> 'fadeInLeft',
		esc_html__( 'Fade In Right', 'complex' ) 	=> 'fadeInRight',
		esc_html__( 'Fade In Up', 'complex' ) 	=> 'fadeInUp',
		esc_html__( 'Flip In X', 'complex' ) 	=> 'flipInX',
		esc_html__( 'Flip In Y', 'complex' ) 	=> 'flipInY',
		esc_html__( 'Light Speed In', 'complex' ) 	=> 'lightSpeedIn',
		esc_html__( 'Rotate In', 'complex' ) 	=> 'rotateIn',
		esc_html__( 'Rotate In Down Left', 'complex' ) 	=> 'rotateInDownLeft',
		esc_html__( 'Rotate In Down Right', 'complex' ) 	=> 'rotateInDownRight',
		esc_html__( 'Rotate In Up Left', 'complex' ) 	=> 'rotateInUpLeft',
		esc_html__( 'Rotate In Up Right', 'complex' ) 	=> 'rotateInUpRight',
		esc_html__( 'Slide In Down', 'complex' ) 	=> 'slideInDown',
		esc_html__( 'Slide In Left', 'complex' ) 	=> 'slideInLeft',
		esc_html__( 'Slide In Right', 'complex' ) 	=> 'slideInRight',
		esc_html__( 'Roll In', 'complex' ) 	=> 'rollIn',
	);
}


function complex_woocommerce_query_args($type,$post_per_page=-1,$cat=''){
	global $woocommerce;
    remove_filter( 'posts_clauses', array( $woocommerce->query, 'order_by_popularity_post_clauses' ) );
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => $post_per_page,
        'post_status' => 'publish',
		'date_query' => array(
			array(
			   'before' => date('Y-m-d H:i:s', current_time( 'timestamp' ))
			)
		 )
    );
    switch ($type) {
        case 'best_selling':
            $args['meta_key']='total_sales';
            $args['orderby']='meta_value_num';
            $args['ignore_sticky_posts']   = 1;
            $args['meta_query'] = array();
            break;
        case 'featured_product':
            $args['ignore_sticky_posts']=1;
            $args['meta_query'] = array();
            $args['meta_query'][] = $woocommerce->query->stock_status_meta_query();
            $args['meta_query'][] = array(
                         'key' => '_featured',
                         'value' => 'yes'
                     );
            $query_args['meta_query'][] = $woocommerce->query->visibility_meta_query();
            break;
        case 'top_rate':
            $args['meta_key']='_wc_average_rating';
            $args['orderby']='meta_value_num';
            $args['order']='DESC';
            $args['meta_query'] = array();
            $args['meta_query'][] = $woocommerce->query->stock_status_meta_query();
            $args['meta_query'][] = $woocommerce->query->visibility_meta_query();
            break;
        case 'recent_product':
            $args['meta_query'] = array();
            $args['meta_query'][] = $woocommerce->query->stock_status_meta_query();
            break;
        case 'on_sale':
            $args['meta_query'] = array();
            $args['meta_query'][] = $woocommerce->query->stock_status_meta_query();
            $args['meta_query'][] = $woocommerce->query->visibility_meta_query();
            $args['post__in'] = wc_get_product_ids_on_sale();
            break;
        case 'recent_review':
            if($post_per_page == -1) $_limit = 4;
            else $_limit = $post_per_page;
            global $wpdb;
            $query = "SELECT c.comment_post_ID FROM {$wpdb->posts} p, {$wpdb->comments} c WHERE p.ID = c.comment_post_ID AND c.comment_approved > 0 AND p.post_type = 'product' AND p.post_status = 'publish' AND p.comment_count > 0 ORDER BY c.comment_date ASC LIMIT 0, %d";
            $safe_sql = $wpdb->prepare( $query, $_limit );
			$results = $wpdb->get_results($safe_sql, OBJECT);
            $_pids = array();
            foreach ($results as $re) {
                $_pids[] = $re->comment_post_ID;
            }

            $args['meta_query'] = array();
            $args['meta_query'][] = $woocommerce->query->stock_status_meta_query();
            $args['meta_query'][] = $woocommerce->query->visibility_meta_query();
            $args['post__in'] = $_pids;
            break;
        case 'deals':
            $args['meta_query'] = array();
            $args['meta_query'][] = $woocommerce->query->stock_status_meta_query();
            $args['meta_query'][] = $woocommerce->query->visibility_meta_query();
            $args['meta_query'][] = array(
                                 'key' => '_sale_price_dates_to',
                                 'value' => '0',
                                 'compare' => '>');
            $args['post__in'] = wc_get_product_ids_on_sale();
            break;
    }

    if($cat!=''){
        $args['tax_query'] = array(
				array(
					'taxonomy' => 'product_cat',
					'field' => 'slug',
					'terms' => $cat
				)
			);
    }
    return $args;
}
function complex_make_id($length = 5){
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}
//Change excerpt length
add_filter( 'excerpt_length', 'complex_excerpt_length', 999 );
function complex_excerpt_length( $length ) {
	global $complex_opt;
	if(isset($complex_opt['excerpt_length'])){
		return $complex_opt['excerpt_length'];
	}
	return 22;
}
function complex_get_the_excerpt($post_id) {
	global $post;
	$temp = $post;
    $post = get_post( $post_id );
    setup_postdata( $post );
    $excerpt = get_the_excerpt();
    wp_reset_postdata();
    $post = $temp;
    return $excerpt;
}

//Add breadcrumbs
function complex_breadcrumb() {
	global $post, $complex_opt;
	
	$brseparator = '<span class="separator">/</span>';
	if (!is_home()) {
		echo '<div class="breadcrumbs">';
		
		echo '<a href="';
		echo esc_url( home_url( '/' ) );
		echo '">';
		echo esc_html__('Home', 'complex');
		echo '</a>'.$brseparator;
		if (is_category() || is_single()) {
			$cats = get_the_category($post->ID);
			if(!empty($cats[0]->name)){
				echo '<a href="'. get_category_link($cats[0]->cat_ID) .'">' . $cats[0]->name . '</a>';
			}
			if (is_single()) {
				echo ''.$brseparator;
				the_title();
			}
		} elseif (is_page()) {
			if($post->post_parent){
				$anc = get_post_ancestors( $post->ID );
				$title = get_the_title();
				foreach ( $anc as $ancestor ) {
					$output = '<a href="'. esc_url(get_permalink($ancestor)).'" title="'.esc_attr(get_the_title($ancestor)).'">'. esc_html(get_the_title($ancestor)) .'</a>'.$brseparator;
				}
				echo wp_kses($output, array(
						'a'=>array(
							'href' => array(),
							'title' => array()
						),
						'span'=>array(
							'class'=>array()
						)
					)
				);
				echo '<span title="'.esc_attr($title).'"> '.esc_html($title).'</span>';
			} else {
				echo '<span> '. esc_html(get_the_title()).'</span>';
			}
		}
		elseif (is_tag()) {single_tag_title();}
		elseif (is_day()) {echo "<span>" . sprintf(esc_html__('Archive for %s', 'complex'), get_the_time('F jS, Y')); echo '</span>';}
		elseif (is_month()) {echo "<span>" . sprintf(esc_html__('Archive for %s', 'complex'), get_the_time('F, Y')); echo '</span>';}
		elseif (is_year()) {echo "<span>" . sprintf(esc_html__('Archive for %s', 'complex'), get_the_time('Y')); echo '</span>';}
		elseif (is_author()) {echo "<span>" . esc_html__('Author Archive', 'complex'); echo '</span>';}
		elseif (isset($_GET['paged']) && !empty($_GET['paged'])) {echo "<span>" . esc_html__('Blog Archives', 'complex'); echo '</span>';}
		elseif (is_search()) {echo "<span>" . esc_html__('Search Results', 'complex'); echo '</span>';}
		
		echo '</div>';
	} else {
		echo '<div class="breadcrumbs">';
		
		echo '<a href="';
		echo esc_url( home_url( '/' ) );
		echo '">';
		echo esc_html__('Home', 'complex');
		echo '</a>'.$brseparator;
		
		if(isset($complex_opt['blog_header_text']) && $complex_opt['blog_header_text']!=""){
			echo esc_html($complex_opt['blog_header_text']);
		} else {
			echo esc_html__('Blog', 'complex');
		}
		
		echo '</div>';
	}
}
//social share products
function complex_product_sharing() {
	global $complex_opt;
	
	if(isset($_POST['data'])) { // for the quickview
		$postid = intval( $_POST['data'] );
	} else {
		$postid = get_the_ID();
	}
	
	$share_url = get_permalink( $postid );

	$large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id( $postid ), 'large' );
	$postimg = $large_image_url[0];
	$posttitle = get_the_title( $postid );
	?>
	<div class="widget widget_socialsharing_widget">
		<h3 class="widget-title"><?php if(isset($complex_opt['product_share_title'])) { echo esc_html($complex_opt['product_share_title']); } else { esc_html_e('Share this product', 'complex'); } ?></h3>
		<ul class="social-icons">
			<li><a class="facebook social-icon" href="javascript:void(0)" onclick="javascript:window.open('<?php echo 'https://www.facebook.com/sharer/sharer.php?u='.$share_url; ?>', '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600'); return false;" title="<?php echo esc_attr__('Facebook', 'complex'); ?>"><i class="fa fa-facebook"></i></a></li>
			<li><a class="twitter social-icon" href="javascript:void(0)" onclick="javascript:window.open('<?php echo 'https://twitter.com/home?status='.$posttitle.'&nbsp;'.$share_url; ?>', '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600'); return false;" title="<?php echo esc_attr__('Twitter', 'complex'); ?>" ><i class="fa fa-twitter"></i></a></li>
			<li><a class="pinterest social-icon" href="javascript:void(0)" onclick="javascript:window.open('<?php echo 'https://pinterest.com/pin/create/button/?url='.$share_url.'&amp;media='.$postimg.'&amp;description='.$posttitle; ?>', '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600'); return false;" title="<?php echo esc_attr__('Pinterest', 'complex'); ?>"><i class="fa fa-pinterest"></i></a></li>
			<li><a class="gplus social-icon" href="javascript:void(0)" onclick="javascript:window.open('<?php echo 'https://plus.google.com/share?url='.$share_url; ?>', '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600'); return false;" title="<?php echo esc_attr__('Google +', 'complex'); ?>"><i class="fa fa-google-plus"></i></a></li>
			<li><a class="linkedin social-icon" href="javascript:void(0)" onclick="javascript:window.open('<?php echo 'https://www.linkedin.com/shareArticle?mini=true&amp;url='.$share_url.'&amp;title='.$posttitle; ?>', '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600'); return false;" title="<?php echo esc_attr__('LinkedIn', 'complex'); ?>"><i class="fa fa-linkedin"></i></a></li>
		</ul>
	</div>
	<?php
}
//social share blog
function complex_blog_sharing() {
	global $complex_opt;
	
	if(isset($_POST['data'])) { // for the quickview
		$postid = intval( $_POST['data'] );
	} else {
		$postid = get_the_ID();
	}
	
	$share_url = get_permalink( $postid );

	$large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id( $postid ), 'large' );
	$postimg = $large_image_url[0];
	$posttitle = get_the_title( $postid );
	?>
	<div class="widget widget_socialsharing_widget">
		<ul class="social-icons">
			<li><a class="facebook social-icon" href="javascript:void(0)" onclick="javascript:window.open('<?php echo 'https://www.facebook.com/sharer/sharer.php?u='.$share_url; ?>', '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600'); return false;" title="<?php echo esc_attr__('Facebook', 'complex'); ?>"><i class="fa fa-facebook"></i></a></li>
			<li><a class="twitter social-icon" href="javascript:void(0)" onclick="javascript:window.open('<?php echo 'https://twitter.com/home?status='.$posttitle.'&nbsp;'.$share_url; ?>', '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600'); return false;" title="<?php echo esc_attr__('Twitter', 'complex'); ?>"><i class="fa fa-twitter"></i></a></li>
			<li><a class="pinterest social-icon" href="javascript:void(0)" onclick="javascript:window.open('<?php echo 'https://pinterest.com/pin/create/button/?url='.$share_url.'&amp;media='.$postimg.'&amp;description='.$posttitle; ?>', '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600'); return false;" title="<?php echo esc_attr__('Pinterest', 'complex'); ?>"><i class="fa fa-pinterest"></i></a></li>
			<li><a class="gplus social-icon" href="javascript:void(0)" onclick="javascript:window.open('<?php echo 'https://plus.google.com/share?url='.$share_url; ?>', '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600'); return false;" title="<?php echo esc_attr__('Google +', 'complex'); ?>"><i class="fa fa-google-plus"></i></a></li>
			<li><a class="linkedin social-icon" href="javascript:void(0)" onclick="javascript:window.open('<?php echo 'https://www.linkedin.com/shareArticle?mini=true&amp;url='.$share_url.'&amp;title='.$posttitle; ?>', '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600'); return false;" title="<?php echo esc_attr__('LinkedIn', 'complex'); ?>"><i class="fa fa-linkedin"></i></a></li>
		</ul>
	</div>
	<?php
}
// display number view of posts.
function complex_get_post_viewed($postID){
    $count_key = 'post_views_count';
	delete_post_meta($postID, 'post_like_count');
    $count = get_post_meta($postID, $count_key, true);
    if($count==''){
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
        return 0;
    }
    return $count;
}
// count views.
function complex_set_post_view($postID){
    $count_key = 'post_views_count';
    $count = (int)get_post_meta($postID, $count_key, true);
    if(!$count){
        $count = 1;
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, $count);
    }else{
        $count++;
        update_post_meta($postID, $count_key, $count);
    }
}

//get taxonomy list by parent children
function complex_get_all_taxonomy_terms($taxonomy = 'product_cat', $all = false){
	
	global $wpdb;
	
	$arr = array(
		'orderby' => 'name',
		'hide_empty' => 0
	);
	$categories = $wpdb->get_results($wpdb->prepare("SELECT t.name,t.slug,t.term_group,x.term_taxonomy_id,x.term_id,x.taxonomy,x.description,x.parent,x.count FROM {$wpdb->prefix}term_taxonomy x LEFT JOIN {$wpdb->prefix}terms t ON (t.term_id = x.term_id) WHERE x.taxonomy=%s;", $taxonomy));
	$output = array();
	if($all) $output = array( array('label' => esc_html__('All categories', 'complex'), 'value' => '') );
	if(!is_array($categories)) return $output;
	complex_get_repare_terms_children( 0, 0, $categories, 0, $output );
	
	return $output;
}

function complex_get_repare_terms_children( $parent_id, $pos, $categories, $level, &$output ) {
	for ( $i = $pos; $i < count( $categories ); $i ++ ) {
		if ( isset($categories[ $i ]->parent) && $categories[ $i ]->parent == $parent_id ) {
			$name = str_repeat( " - ", $level ) . ucfirst($categories[ $i ]->name);
			$value = $categories[ $i ]->slug;
			$output[] = array( 'label' => $name, 'value' => $value );
			complex_get_repare_terms_children( (int)$categories[ $i ]->term_id, $i, $categories, $level + 1, $output );
		}
	}
}

//register new menu location
function complex_register_menu(){
	register_nav_menu( 'primary', esc_html__( 'Primary Menu', 'complex' ) );
	register_nav_menu( 'categories', esc_html__( 'Categories Menu', 'complex' ) );
	register_nav_menu( 'mobilemenu', esc_html__( 'Mobile Menu', 'complex' ) );
}
add_action( 'init', 'complex_register_menu' );

//Footer html addition
add_action( 'wp_footer', 'complex_popup_onload');
function complex_popup_onload(){
	
	global $complex_opt;
	
	//quickview wrapper
	?>
	<div class="quickview-wrapper">
		<div class="overlay-bg" onclick="hideQuickView()"></div>
		<div class="quick-modal">
			<span class="qvloading"></span>
			<span class="closeqv"><i class="fa fa-times"></i></span>
			<div id="quickview-content"></div><div class="clearfix"></div>
		</div>
	</div>
	
	<?php
	//side social icons display
	if(!empty($complex_opt['sticky_icons']) && !empty($complex_opt['social_icons'])){ ?>
		<div class="block-social <?php echo esc_attr($complex_opt['sticky_icons']) ?>">
			<ul>
				<?php foreach($complex_opt['sticky_social_icons'] as $key=>$value ) { 
					$s_title = ucwords(esc_attr($key));
					if(!empty($complex_opt['sticky_social_titles'][$key])) $s_title = $complex_opt['sticky_social_titles'][$key];
					?>
					<?php if($value!=''){ ?>
						<?php if($key=='vimeo'){ ?>
							<li><a class="<?php echo esc_attr($key) ?>" target="_blank" href="<?php echo esc_url($value) ?>"><span><i class="fa fa-vimeo-square"></i><span class="social-text"><?php echo esc_html($s_title); ?></span></span></a></li>
						<?php }elseif($key=='mail-to'){ ?>
							<li><a class="<?php echo esc_attr($key) ?>" target="_blank" href="<?php echo esc_url($value) ?>"><span><i class="fa fa-envelope-o"></i><span class="social-text"><?php echo esc_html($s_title); ?></span></span></a></li>
						<?php }else{ ?>
							<li><a class="<?php echo esc_attr($key) ?>" target="_blank" href="<?php echo esc_url($value) ?>"><span><i class="fa fa-<?php echo esc_attr($key) ?>"></i><span class="social-text"><?php echo esc_html($s_title); ?></span></span></a></li>
						<?php } ?>
					<?php } ?>
				<?php } ?>
			</ul>
		</div>
	<?php } 
	
	// newletter-form popup
	if(isset($complex_opt['enable_popup']) && $complex_opt['enable_popup']){
		if (is_front_page() && !empty($complex_opt['popup_onload_form']) && !empty($complex_opt['popup_onload_form'])) {
			$no_again = 0; 
			if(isset($_COOKIE['no_again'])) $no_again = $_COOKIE['no_again'];
			if(!$no_again){
		?>
			<div class="popup-content" id="popup_onload">
				<div class="overlay-bg-popup"></div>
				<div class="popup-content-wrapper" id="popup-style-apply">
					<div class="col-popup">
					<a class="close-popup" href="javascript:void(0)"><i class="fa fa-times"></i></a>
					<?php if(!empty($complex_opt['popup_onload_content'])){ ?>
					<div class="popup-content-text">
						<?php echo wp_kses($complex_opt['popup_onload_content'], array(
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
						)); ?>
					</div>
					<?php } ?>
					<?php if(!empty($complex_opt['popup_onload_form'])){ ?>
					<div class="newletter-form">
						<?php echo do_shortcode( '[mc4wp_form id="'. intval($complex_opt['popup_onload_form']) .'"]' ); ?>
					</div>
					<?php } ?>
					<label class="not-again"><input type="checkbox" value="1" name="not-again" /><span><?php echo esc_html__('Do not shop this popup again', 'complex'); ?></span></label>
					</div>
				</div>
			</div>
		<?php } 
		}
	}
}

?>