<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>
<div class="post">
<div class="post-thumbnail">
	<?php
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
	?>
</div>
</div>