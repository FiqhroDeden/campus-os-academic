<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>
<article id="post-<?php the_ID(); ?>" <?php post_class('testimonial'); ?>>
	<div class="testimonial-profile">
	<div class="profile-image"><?php the_post_thumbnail();?></div>
	<?php the_title( '<h2 class="entry-title">', '</h2>' );?>
	<div class="position"><?php echo get_post_meta(get_the_ID(), "testimonial-position", true);?></div>
	</div>

	<div class="testimonial-message"><?php echo get_post_meta(get_the_ID(), "testimonial-message", true);?></div>

	<div class="entry-content">
		<?php
		the_content(
			sprintf(
				wp_kses(
					/* translators: %s: Name of current post. Only visible to screen readers */
					__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'edunia' ),
					array(
						'span' => array(
							'class' => array(),
						),
					)
				),
				wp_kses_post( get_the_title() )
			)
		);

		wp_link_pages(
			array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'edunia' ),
				'after'  => '</div>',
			)
		);
		?>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<?php edunia_entry_footer(); ?>
	</footer><!-- .entry-footer -->

	<?php edunia_shareposts();?>
	
</article><!-- #post-<?php the_ID(); ?> -->