<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>
<div class="post">
	<p class="message">
		<?php echo get_post_meta(get_the_ID(), "testimonial-message", true);?>
	</p>
	<div class="profile">
	<?php
	the_post_thumbnail(
		'testimonial-thumb',
		array(
			'alt' => the_title_attribute(
				array(
					'echo' => false,
				)
			),
		)
	);

	the_title( '<h2 class="name">', '</h2>' );
	if(!empty(get_post_meta(get_the_ID(), "testimonial-position", true))){
		echo '<div class="position">'.get_post_meta(get_the_ID(), "testimonial-position", true).'</div>';
	}
	?>
	</div>
</div>