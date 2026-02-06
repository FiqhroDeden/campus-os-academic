<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php
		if ( is_singular() ) :
			the_title( '<h1 class="entry-title">', '</h1>' );
		else :
			the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
		endif;

		if ( 'download' === get_post_type() ) :
			?>
			<div class="entry-meta">
				<?php
				edunia_posted_by();
				edunia_posted_on();
				?>
			</div><!-- .entry-meta -->
		<?php endif; ?>
	</header><!-- .entry-header -->

	<?php edunia_post_thumbnail(); ?>

	<div class="entry-content">
		<?php
		the_excerpt();

		$download_fileurl = get_post_meta(get_the_ID(), "download-fileurl", true);
		$download_filesize = get_post_meta(get_the_ID(), "download-filesize", true);
		$download_filename = get_post_meta(get_the_ID(), "download-filename", true);
		if(!empty($download_fileurl) && !empty($download_filesize) && !empty($download_filename)){
			echo '<ul class="download-details"><li class="file-name"><span class="label">'.__('File Name','edunia').':</span>'.$download_filename.'</li><li class="file-size"><span class="label">'.__('Size','edunia').':</span>'.$download_filesize.'</li><li class="file-download"><a class="download-link" href="'.$download_fileurl.'">Download</a></li></ul>';
		}

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