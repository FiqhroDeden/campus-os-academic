<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

get_header();
?>
<div class="main-wrapper">
<div class="container">
	<main id="primary" class="site-main">
		<?php
		edunia_breadcrumbs();

		while ( have_posts() ) :
			the_post();

			get_template_part( 'template-parts/contents/single', get_post_type() );

			the_post_navigation(
				array(
					'prev_text' => '<span class="nav-subtitle">' . esc_html__( 'Previous', 'edunia' ) . '</span> <span class="nav-title">%title</span>',
					'next_text' => '<span class="nav-subtitle">' . esc_html__( 'Next', 'edunia' ) . '</span> <span class="nav-title">%title</span>',
				)
			);

			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;

		endwhile; // End of the loop.
		?>

	</main><!-- #main -->

<?php
get_sidebar();
?>
</div><!-- .container -->
</div><!-- .main-wrapper -->
<?php
get_footer();
