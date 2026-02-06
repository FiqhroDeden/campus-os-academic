<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
?>
<div class="gtk-wrapper section-wrapper" style="<?php get_section_background(get_theme_mod('gtk_background', '#ffffff'), get_theme_mod('gtk_background_style', 'solid'), get_theme_mod('gtk_background_image'), get_theme_mod('gtk_background_fixed', 1));?>">
    <div class="container">
        <?php
        if ( ! empty( get_theme_mod( 'gtk_title' ) ) ) {
        ?>
        <div class="section-heading">
        <h2 class="title"><?php echo get_theme_mod('gtk_title');?></h2>
        <?php echo ((!empty(get_theme_mod('gtk_description')))?'<div class="description">'.get_theme_mod('gtk_description').'</div>':'');?>
        </div>
        <?php
        }
        ?>

        <div class="gtk-slider">
        <?php
        $gtk_limit = get_theme_mod( 'gtk_limit_home', '12' );
        if ( $gtk_limit == 'nolimit' ) {
            $gtk_limit = '-1';
        }

        $args = array(
            'posts_per_page' => $gtk_limit,
            'orderby' => 'date',
            'order' => 'DESC',
            'post_type' => 'gtk',
            'post_status' => 'publish'
        );
        $the_query = new WP_Query( $args ); 
        if ( $the_query->have_posts() ) : 
            while ( $the_query->have_posts() ) : $the_query->the_post();
            get_template_part( 'template-parts/contents/archive', 'gtk' );
            endwhile;
        else:
            echo '<div class="na-text">'.__('Not Added', 'edunia').'</div>';
        endif;
        wp_reset_postdata();
        ?>
        </div>
    </div>
</div><!-- .gtk-wrapper -->