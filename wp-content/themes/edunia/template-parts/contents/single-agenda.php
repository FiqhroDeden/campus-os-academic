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
		?>
	</header><!-- .entry-header -->

	<?php edunia_post_thumbnail(); ?>

	<ul class="meta-agenda">
	
	<?php
	$agenda_date = date_create(get_post_meta(get_the_ID(), "agenda-date", true));
	echo '<li><span class="icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M17 3h4a1 1 0 0 1 1 1v16a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h4V1h2v2h6V1h2v2zm3 6V5h-3v2h-2V5H9v2H7V5H4v4h16zm0 2H4v8h16v-8zM6 13h5v4H6v-4z"/></svg></span><span class="label">'.__('Date').':</span> '.__(date_format($agenda_date, 'l')).', '.__(date_format($agenda_date, 'd')).' '.__(date_format($agenda_date, 'F')).' '.__(date_format($agenda_date, 'Y')).'</li>';

	echo '<li><span class="icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10zm0-2a8 8 0 1 0 0-16 8 8 0 0 0 0 16zm1-8h4v2h-6V7h2v5z"/></svg></span><span class="label">'.__('Time').':</span> '.get_post_meta(get_the_ID(), "agenda-starttime", true) . ' - ';
	if(get_post_meta(get_the_ID(), "agenda-endtime", true) == '00:00'){
		echo __('Finished', 'edunia');
	}else{
		echo get_post_meta(get_the_ID(), "agenda-endtime", true);
	}
	echo '</li>';
	echo '<li><span class="icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M12 20.9l4.95-4.95a7 7 0 1 0-9.9 0L12 20.9zm0 2.828l-6.364-6.364a9 9 0 1 1 12.728 0L12 23.728zM12 13a2 2 0 1 0 0-4 2 2 0 0 0 0 4zm0 2a4 4 0 1 1 0-8 4 4 0 0 1 0 8z"/></svg></span><span class="label">'.__('Location', 'edunia').':</span> '.get_post_meta(get_the_ID(), "agenda-location", true).'</li>';
	?>
	</ul>

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