<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>
<div class="post">
<div class="entry-header">
<?php
the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' );
echo '<p class="entry-excerpt">'.get_the_excerpt().'</p>';
?>
</div>
<div class="entry-download">
<?php
$download_fileurl = get_post_meta(get_the_ID(), "download-fileurl", true);
$download_filesize = get_post_meta(get_the_ID(), "download-filesize", true);
$download_filename = get_post_meta(get_the_ID(), "download-filename", true);
if(!empty($download_fileurl) && !empty($download_filesize) && !empty($download_filename)){
?>
<a class="download-link" href="<?php echo $download_fileurl;?>"><span class="text">Download</span><span class="size"><?php echo $download_filesize;?></span></a>
<?php
}
?>
</div>
</div>