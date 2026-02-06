<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
?>
<div class="testimonial-wrapper section-wrapper" style="<?php get_section_background(get_theme_mod('testimonial_background', '#ffffff'), get_theme_mod('testimonial_background_style', 'solid'), get_theme_mod('testimonial_background_image'), get_theme_mod('testimonial_background_fixed', 0));?>">
    <div class="container">
        <?php
        if ( ! empty( get_theme_mod( 'testimonial_maintitle' ) ) || ! empty( get_theme_mod( 'testimonial_secondtitle' ) ) ) {
        ?>
        <div class="section-heading">
            <h2 class="title"><?php echo get_theme_mod( 'testimonial_maintitle' ) . ( ( ! empty( get_theme_mod( 'testimonial_secondtitle' ) ) ) ? ' <span>'.get_theme_mod('testimonial_secondtitle').'</span>':'');?></h2>
            <?php echo ( ( ! empty( get_theme_mod( 'testimonial_description' ) ) ) ? '<div class="description">' . get_theme_mod( 'testimonial_description' ) . '</div>' : '' );?>
        </div>
        <?php
        }
        ?>

        <div class="testimonial-slider">
        <?php
        $args = array(
            'posts_per_page' => 6,
            'orderby' => 'date',
            'order' => 'DESC',
            'post_type' => 'testimonial',
            'post_status' => 'publish'
        );
        $the_query = new WP_Query( $args ); 
        if ( $the_query->have_posts() ) : 
            while ( $the_query->have_posts() ) : $the_query->the_post();
            get_template_part( 'template-parts/contents/archive', 'testimonial' );
            endwhile;
        else:
            echo '<div class="na-text">'.__('Not Added', 'edunia').'</div>';
        endif;
        wp_reset_postdata();
        ?>
        </div>
    </div>
</div><!-- .testimonials -->