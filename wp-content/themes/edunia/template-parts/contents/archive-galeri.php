<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>
<div class="post">
<div class="KBmodal" data-content-url="<?php echo get_the_post_thumbnail_url();?>" data-content-type="gallery" data-content-gallery="gal_1">
	<?php
	if ( has_post_thumbnail() ) {
		the_post_thumbnail(
			'news-thumb',
			array(
				'alt' => the_title_attribute(
					array(
						'echo' => false,
					)
				),
			)
		);
	}else{
		echo '<img src="'.get_template_directory_uri().'/post-thumbnail.jpg" alt="'.get_the_title().'"/>';
	}
	?>
</div>
<div class="entry-header">
<?php
the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' );
?>
</div>
</div>