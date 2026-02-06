<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
?>
<div class="angen-wrapper section-wrapper" style="<?php get_section_background(get_theme_mod('angen_background', '#d4ebf1'), get_theme_mod('angen_background_style', 'solid'), get_theme_mod('angen_background_image'), get_theme_mod('angen_background_fixed', 0));?>">
    <div class="container">
        <div class="announcement">
        <div class="section-heading">
            <h2 class="title"><?php echo get_theme_mod('announcement_title');?></h2>
            <?php echo ((!empty(get_theme_mod('announcement_description')))?'<div class="description">'.get_theme_mod('announcement_description').'</div>':'');?>
            <a class="more-link" href="<?php echo get_post_type_archive_link('pengumuman');?>"><span class="icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M13.172 12l-4.95-4.95 1.414-1.414L16 12l-6.364 6.364-1.414-1.414z"/></svg></span></a>
        </div>
        <div class="announcement-list">
        <?php
        $args = array(
            'posts_per_page' => 6,
            'orderby' => 'date',
            'order' => 'DESC',
            'post_type' => 'pengumuman',
            'post_status' => 'publish'
        );
        $the_query = new WP_Query( $args ); 
        if ( $the_query->have_posts() ) : 
            while ( $the_query->have_posts() ) : $the_query->the_post();
            get_template_part( 'template-parts/contents/archive', 'pengumuman' );
            endwhile;
        else:
            echo '<div class="na-text">'.__('Not Added', 'edunia').'</div>';
        endif;
        wp_reset_postdata();
        ?>
        </div>
        </div>

        <div class="agenda">
        <div class="section-heading">
            <h2 class="title"><?php echo get_theme_mod('agenda_title');?></h2>
            <?php echo ((!empty(get_theme_mod('agenda_description')))?'<div class="description">'.get_theme_mod('agenda_description').'</div>':'');?>
            <a class="more-link" href="<?php echo get_post_type_archive_link('agenda');?>"><span class="icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M13.172 12l-4.95-4.95 1.414-1.414L16 12l-6.364 6.364-1.414-1.414z"/></svg></span></a>
        </div>
        <div class="agenda-list">
        <?php
        $args = array(
            'posts_per_page' => 3,
            'orderby' => 'date',
            'order' => 'DESC',
            'post_type' => 'agenda',
            'post_status' => 'publish'
        );
        $the_query = new WP_Query( $args ); 
        if ( $the_query->have_posts() ) : 
            while ( $the_query->have_posts() ) : $the_query->the_post();
            get_template_part( 'template-parts/contents/archive', 'agenda' );
            endwhile;
        else:
            echo '<div class="na-text">'.__('Not Added', 'edunia').'</div>';
        endif;
        wp_reset_postdata();
        ?>
        </div>
        </div>
    </div>
</div><!-- .announcement-agenda -->