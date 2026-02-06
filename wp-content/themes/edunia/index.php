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
			echo '<h1 class="page-title">'.__('Archives:', 'edunia').' <span>'.__('News', 'edunia').'</span></h1>';
			// the_archive_description( '<div class="archive-description">', '</div>' );
			?>
			</header><!-- .page-header -->
			<div class="gridpost-wrapper">
			<?php
			/* Start the Loop */
			while ( have_posts() ) :
				the_post();

				/*
				 * Include the Post-Type-specific template for the content.
				 * If you want to override this in a child theme, then include a file
				 * called content-___.php (where ___ is the Post Type name) and that will be used instead.
				 */
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

			get_template_part( 'template-parts/content', 'none' );

		endif;
		?>
</div>
</div>
<?php
get_footer();
