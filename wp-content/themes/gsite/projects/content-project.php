<?php
/**
 * The template for displaying project content within loops.
 *
 * Override this template by copying it to yourtheme/projects/content-project.php
 *
 * @author 		WooThemes
 * @package 	Projects/Templates
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $post, $projects_loop, $complex_opt, $complex_projectrows, $complex_projectsfound;

// Store loop count we're currently on
if ( empty( $projects_loop['loop'] ) )
	$projects_loop['loop'] = 0;
// Store column count for displaying the grid
if ( empty( $projects_loop['columns'] ) ) {
	$projects_loop['columns'] = apply_filters( 'projects_loop_columns', 3 );
}

$projects_loop['columns'] = $complex_opt['portfolio_columns'];

if (isset($_GET['col'])) {
	$projects_loop['columns'] = (int)$_GET['col'];
}

// Increase loop count
$projects_loop['loop']++;

// Extra post classes
$classes = array();
if ( 0 == ( $projects_loop['loop'] - 1 ) % $projects_loop['columns'] && $projects_loop['loop'] > 1 )
	$classes[] = 'first';
if ( 0 == $projects_loop['loop'] % $projects_loop['columns'] )
	$classes[] = 'last';

$colwidth = 12/$projects_loop['columns'];
$classes[] = 'item-col col-xs-12 col-sm-'.$colwidth;

$prcates = get_the_terms($post->ID, 'project-category' );
$datagroup = array();
if($prcates){
	foreach ($prcates as $category ) {
		$datagroup[] = $category->slug;
	}
}
$datagroup = implode(", ", $datagroup);
?>
<?php
if ( ( 0 == ( $projects_loop['loop'] - 1 ) % 2 ) && ( $projects_loop['columns'] == 2 ) ) {
	if($complex_projectrows!=1) {
		echo '<div class="group">';
	}
}
?>
<div <?php post_class( $classes ); ?> data-groups="<?php echo esc_attr($datagroup); ?>">

	<?php do_action( 'projects_before_loop_item' ); ?>
	
	<div class="project-thumbnail">
		<?php echo projects_get_project_thumbnail() ?>
		<div class="icon-group">
			<div class="project-link"><a data-toggle="tooltip" title="<?php echo esc_html__('View more', 'complex') ?>" href="<?php the_permalink(); ?>" class="project-permalink"><i class="fa fa-link"></i></a></div>
			<?php do_action( 'lionthemes_like_button' , get_the_ID()); ?>
		</div>
	</div>
	<div class="project-info">
		<h3 class="project-title"><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h3>
		<div class="project-date"><?php echo get_the_date( get_option( 'date_format' ), get_the_ID() ); ?></div>
	</div>
</div>
<?php if ( ( 0 == $projects_loop['loop'] % 2 || $complex_projectsfound == $projects_loop['loop'] ) && ( $projects_loop['columns'] == 2 ) ) { /* for odd case: $complex_projectsfound == $projects_loop['loop'] */
	if($complex_projectrows!=1) {
		echo '</div>';
	}
} ?>