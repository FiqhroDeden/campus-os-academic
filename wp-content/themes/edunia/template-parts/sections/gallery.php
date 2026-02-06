<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
?>
<div class="gallery-wrapper section-wrapper" style="<?php get_section_background(get_theme_mod('gallery_background', '#d4ebf1'), get_theme_mod('gallery_background_style', 'gradient_two'), get_theme_mod('gallery_background_image'), get_theme_mod('gallery_background_fixed', 0));?>">
    <div class="container">
        <?php
        if ( ! empty( get_theme_mod( 'gallery_maintitle' ) ) || ! empty( get_theme_mod( 'gallery_secondtitle' ) ) ) {
        ?>
        <div class="section-heading">
            <h2 class="title"><?php echo get_theme_mod('gallery_maintitle') . ((!empty(get_theme_mod('gallery_secondtitle')))?' <span>'.get_theme_mod('gallery_secondtitle').'</span>':'');?></h2>
            <?php echo ((!empty(get_theme_mod('gallery_description')))?'<div class="description">'.get_theme_mod('gallery_description').'</div>':'');?>
            <a class="more-link" href="<?php echo get_post_type_archive_link('galeri');?>"><span class="text"><?php echo __('View All', 'edunia');?></span><span class="icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M13.172 12l-4.95-4.95 1.414-1.414L16 12l-6.364 6.364-1.414-1.414z"/></svg></span></a>
        </div>
        <?php
        }
        ?>

        <div class="gallery-content">
            <?php
            $args = array(
                'posts_per_page' => 8,
                'orderby' => 'date',
                'order' => 'DESC',
                'post_type' => 'galeri',
                'post_status' => 'publish'
            );
            $the_query = new WP_Query( $args ); 
            if ( $the_query->have_posts() ) : 
                while ( $the_query->have_posts() ) : $the_query->the_post();
                get_template_part( 'template-parts/contents/archive', 'galeri' );
                endwhile;
            else:
                echo '<div class="na-text">'.__('Not Added', 'edunia').'</div>';
            endif;
            wp_reset_postdata();
            ?>
        </div>
    </div>
</div><!-- .gallery-wrapper -->