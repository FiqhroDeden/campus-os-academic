<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$archive_title = array(
	'announcement' => get_theme_mod( 'announcement_archive_title' ),
	'agenda' => get_theme_mod( 'agenda_archive_title' ),
	'achievement' => get_theme_mod( 'achievement_archive_title' ),
	'gallery' => get_theme_mod( 'gallery_archive_title' ),
	'video' => get_theme_mod( 'video_archive_title' ),
	'facility' => get_theme_mod( 'facility_archive_title' ),
	'gtk' => get_theme_mod( 'gtk_archive_title' ),
	'download' => get_theme_mod( 'download_archive_title' ),
);

$announcement_title = ( ( ! empty( $archive_title['announcement'] ) ) ? $archive_title['announcement'] : __( 'Announcement', 'edunia') );
$agenda_title = ( ( ! empty( $archive_title['agenda'] ) ) ? $archive_title['agenda'] : __( 'Agenda', 'edunia') );
$achievement_title = ( ( ! empty( $archive_title['achievement'] ) ) ? $archive_title['achievement'] : __( 'Achievement', 'edunia') );
$gallery_title = ( ( ! empty( $archive_title['gallery'] ) ) ? $archive_title['gallery'] : __( 'Gallery', 'edunia') );
$video_title = ( ( ! empty( $archive_title['video'] ) ) ? $archive_title['video'] : __( 'Video', 'edunia') );
$facility_title = ( ( ! empty( $archive_title['facility'] ) ) ? $archive_title['facility'] : __( 'Facility', 'edunia') );
$gtk_title = ( ( ! empty( $archive_title['gtk'] ) ) ? $archive_title['gtk'] : __( 'GTK', 'edunia') );
$download_title = ( ( ! empty( $archive_title['download'] ) ) ? $archive_title['download'] : __( 'Download', 'edunia') );
?>

	<div class="search-wrapper">
	<div class="search-content">
	<form action="<?php echo get_home_url();?>" method="GET">
	<label class="search-icon" for="search-input"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"></path><path d="M18.031 16.617l4.283 4.282-1.415 1.415-4.282-4.283A8.96 8.96 0 0 1 11 20c-4.968 0-9-4.032-9-9s4.032-9 9-9 9 4.032 9 9a8.96 8.96 0 0 1-1.969 5.617zm-2.006-.742A6.977 6.977 0 0 0 18 11c0-3.868-3.133-7-7-7-3.868 0-7 3.132-7 7 0 3.867 3.132 7 7 7a6.977 6.977 0 0 0 4.875-1.975l.15-.15z"></path></svg></label>
	<input type="search" name="s" class="search-input" id="search-input" placeholder="<?php echo __('Search');?>.."<?php echo ((!empty($_GET['s']))?' value="'.$_GET['s'].'"':'');?> required>
	<select name="post_type">
	<option value="post"><?php echo __('News', 'edunia');?></option>
	<option value="agenda"><?php echo $agenda_title;?></option>
	<option value="download"><?php echo $download_title;?></option>
	<option value="galeri"><?php echo $gallery_title;?></option>
	<option value="gtk"><?php echo $gtk_title;?></option>
	<option value="pengumuman"><?php echo $announcement_title;?></option>
	<option value="prestasi"><?php echo $achievement_title;?></option>
	<option value="video"><?php echo $video_title;?></option>
	</select>
	</form>
	</div>
	</div>

	<footer id="colophon" class="footer-wrapper">
		<div class="footer-column">
		<div class="container">
		<div class="footer-rows">
			<div class="footer-col footer-1">
			<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('footer-1') ) : endif; ?>
			</div>
			<div class="footer-col footer-2">
			<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('footer-2') ) : endif; ?>
			</div>
			<div class="footer-col footer-3">
			<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('footer-3') ) : endif; ?>
			</div>
			<div class="footer-col footer-4">
			<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('footer-4') ) : endif; ?>
			</div>
		</div>
		</div>
		</div>

		<div class="site-info">
		<div class="container">
		<?php
		$footer_credit = get_theme_mod( 'footer_credit' );
        if ( $footer_credit ) {
        	echo $footer_credit;
        } else {
        	echo 'Copyright &#169; ' . date( 'Y' ) . ' <a href="' . home_url( '/' ) . '">' . get_bloginfo( 'name' ) . '</a>. ' . __( 'Theme by', 'edunia' ) . ' <a href="https://garudatheme.com">Garudatheme</a>.';
        }
		?>
		</div>
		</div>
	</footer>
</div>

<a class="scroll-to-top" href="#"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"></path><path d="M12 10.828l-4.95 4.95-1.414-1.414L12 8l6.364 6.364-1.414 1.414z"></path></svg></a>

<script type="text/javascript">
document.getElementsByTagName("BODY")[0].classList.add('overflow-hidden');
window.onload = function () {
	setTimeout(function(){
	document.querySelector('.preloader-wrapper').classList.add('hide');
	document.getElementsByTagName("BODY")[0].classList.remove('overflow-hidden');
	}, 500);
}
</script>

<?php wp_footer(); ?>

</body>
</html>