<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

get_header();

?>
<div class="main-wrapper">
<div class="container">
<?php
edunia_breadcrumbs();

if ( have_posts() ) :
?>
<header class="page-header">
<?php
the_archive_title( '<h1 class="page-title">', '</h1>' );
?>
</header><!-- .page-header -->
<div class="gridpost-wrapper <?php echo get_queried_object()->name;?>">
<?php
while ( have_posts() ) :
	the_post();
	get_template_part( 'template-parts/contents/archive', get_post_type() );
endwhile;
?>
</div>
<?php
echo '<div class="paginate-numbers">';
$big = 999999999;
echo paginate_links( array(
	'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
	'format' => '?paged=%#%',
	'current' => max( 1, get_query_var('paged') ),
	'total' => $wp_query->max_num_pages
) );
echo '</div>';
else :
get_template_part( 'template-parts/contents/none' );
endif;
?>
</div>
</div>
<?php
get_footer();