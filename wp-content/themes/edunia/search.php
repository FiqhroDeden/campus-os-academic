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

$post_object = get_queried_object();
?>
<header class="page-header">
<h2 class="page-title">
<?php
/* translators: %s: search query. */
printf( esc_html__( 'Search Results for: %s', 'edunia' ), '<span>' . get_search_query() . '</span>' );
?>
</h2>
</header><!-- .page-header -->
<div class="gridpost-wrapper <?php echo ( ( isset( $post_object ) && !empty( $post_object->name ) ) ? $post_object->name : 'post' );?>">
<?php
while ( have_posts() ) :
	the_post();
	if(isset($_GET['post_type']) && !empty($_GET['post_type'])){
		$post_type = $_GET['post_type'];
	}else{
		$post_type = '';
	}
	get_template_part( 'template-parts/contents/archive', $post_type );
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
get_template_part( 'template-parts/content', 'none' );
endif;
?>
</div>
</div>
<?php
get_footer();