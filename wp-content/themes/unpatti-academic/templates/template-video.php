<?php
/* Template Name: Video */
get_header();

$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
$query = new WP_Query( array(
    'post_type'      => 'video',
    'posts_per_page' => 9,
    'paged'          => $paged,
    'orderby'        => 'date',
    'order'          => 'DESC',
) );
?>
<div class="page-hero"><div class="container"><h1><?php the_title(); ?></h1></div></div>
<main id="primary" class="site-main">
    <div class="container">
        <?php
        $is_elementor = class_exists('\Elementor\Plugin') && \Elementor\Plugin::$instance->documents->get(get_the_ID()) && \Elementor\Plugin::$instance->documents->get(get_the_ID())->is_built_with_elementor();
        if ($is_elementor) :
            while (have_posts()) : the_post();
        ?>
            <div class="entry-content"><?php the_content(); ?></div>
        <?php
            endwhile;
        else :
        ?>
        <?php if ( $query->have_posts() ) : ?>
            <div class="video-grid">
                <?php while ( $query->have_posts() ) : $query->the_post();
                    $youtube_url = get_post_meta( get_the_ID(), 'video_youtube_url', true );
                    $duration    = get_post_meta( get_the_ID(), 'video_video_duration', true );
                    $video_id    = '';
                    if ( $youtube_url ) {
                        preg_match( '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $youtube_url, $matches );
                        $video_id = isset( $matches[1] ) ? $matches[1] : '';
                    }
                ?>
                <article class="video-card card">
                    <a href="<?php the_permalink(); ?>" class="video-thumbnail">
                        <?php if ( $video_id ) : ?>
                            <img src="https://img.youtube.com/vi/<?php echo esc_attr( $video_id ); ?>/mqdefault.jpg" alt="<?php the_title_attribute(); ?>" class="card-img">
                        <?php elseif ( has_post_thumbnail() ) : ?>
                            <?php the_post_thumbnail( 'medium_large', array( 'class' => 'card-img' ) ); ?>
                        <?php endif; ?>
                        <div class="video-play-icon">
                            <span class="dashicons dashicons-controls-play"></span>
                        </div>
                        <?php if ( $duration ) : ?>
                            <span class="video-duration"><?php echo esc_html( $duration ); ?></span>
                        <?php endif; ?>
                    </a>
                    <div class="card-body">
                        <h3 class="card-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                        <p class="card-date"><?php echo get_the_date(); ?></p>
                    </div>
                </article>
                <?php endwhile; wp_reset_postdata(); ?>
            </div>
            <?php
            $big = 999999999;
            echo '<div class="nav-links">';
            echo paginate_links( array(
                'base'      => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
                'format'    => '?paged=%#%',
                'current'   => max( 1, $paged ),
                'total'     => $query->max_num_pages,
                'prev_text' => '&laquo;',
                'next_text' => '&raquo;',
            ) );
            echo '</div>';
            ?>
        <?php else : ?>
            <p class="no-content"><?php esc_html_e( 'Belum ada video.', 'unpatti-academic' ); ?></p>
        <?php endif; ?>
        <?php endif; ?>
    </div>
</main>
<?php get_footer(); ?>
