<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

$featbox_isactive = get_theme_mod( 'featbox_isactive', 1 );
?>
<div class="featured-wrapper">
<div class="featured-slider<?php echo ($featbox_isactive !== 1)?' featbox-disabled':'';?>">
    <?php
    for ($x = 1; $x <= 3; $x++){
        $slider_video = get_theme_mod( 'slider' . $x . '_video' );
        if ( !empty( get_theme_mod( 'slider'.$x.'_image' ) ) || !empty( $slider_video )){
            echo '<div>';
            if ( !empty( $slider_video ) ) {
                echo '<video class="feathome-video" autoplay muted playsinline loop="" src="' . wp_get_attachment_url( $slider_video ) . '"></video>';
            } else {
                echo '<img src="'.get_theme_mod('slider'.$x.'_image').'"/>';
            }
            if(!empty(get_theme_mod('slider'.$x.'_subtitle')) || !empty(get_theme_mod('slider'.$x.'_title')) || !empty(get_theme_mod('slider'.$x.'_btnname'))){
                echo '<div class="fsc-wrapper"><div class="fsc-container"><div class="fsc-content">';
                echo ((!empty(get_theme_mod('slider'.$x.'_subtitle')))?'<h3>'.get_theme_mod('slider'.$x.'_subtitle').'</h3>':'');
                if(!empty(get_theme_mod('slider'.$x.'_title'))){
                    echo '<h2><a href="'.((!empty(get_theme_mod('slider'.$x.'_link')))?get_theme_mod('slider'.$x.'_link'):'#').'">'.get_theme_mod('slider'.$x.'_title').'</a></h2>';
                }
                if(!empty(get_theme_mod('slider'.$x.'_btnname'))){
                    echo '<a class="btn" href="'.((!empty(get_theme_mod('slider'.$x.'_link')))?get_theme_mod('slider'.$x.'_link'):'#').'">'.get_theme_mod('slider'.$x.'_btnname').'</a>';
                }
                echo '</div></div></div>';
            }
            echo '</div>';
        }
    }
    ?>

</div>
<?php
if ( $featbox_isactive == 1 ) {
?>
<div class="featured-box">
    <?php
    for ($x = 1; $x <= 3; $x++){
        if(!empty(get_theme_mod('fbox'.$x.'_image')) || !empty(get_theme_mod('fbox'.$x.'_title')) || !empty(get_theme_mod('fbox'.$x.'_btnname'))){
            echo '<div class="box box-'.$x.'">';
            echo ((!empty(get_theme_mod('fbox'.$x.'_image')))?'<img class="icon" src="'.get_theme_mod('fbox'.$x.'_image').'"/>':'');
            if(!empty(get_theme_mod('fbox'.$x.'_title'))){
                echo '<h2 class="title"><a href="'.((!empty(get_theme_mod('fbox'.$x.'_link')))?get_theme_mod('fbox'.$x.'_link'):'#').'">'.get_theme_mod('fbox'.$x.'_title').'</a></h2>';
            }
            if(!empty(get_theme_mod('fbox'.$x.'_btnname'))){
                echo '<a class="button" href="'.((!empty(get_theme_mod('fbox'.$x.'_link')))?get_theme_mod('fbox'.$x.'_link'):'#').'">'.get_theme_mod('fbox'.$x.'_btnname').'</a>';
            }
            echo '</div>';
        }
    }
    ?>
</div><!-- .featured-box -->
<?php
}
?>
</div><!-- .featured-wrapper -->