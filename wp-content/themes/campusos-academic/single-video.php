<?php
/**
 * Single Template: Video
 */
get_header();

while ( have_posts() ) : the_post();
    $youtube_url = get_post_meta( get_the_ID(), 'video_youtube_url', true );
    $duration    = get_post_meta( get_the_ID(), 'video_video_duration', true );
    $video_id    = '';
    if ( $youtube_url ) {
        preg_match( '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $youtube_url, $matches );
        $video_id = isset( $matches[1] ) ? $matches[1] : '';
    }
?>
<div class="page-hero">
    <div class="container">
        <h1><?php the_title(); ?></h1>
    </div>
</div>
<main id="primary" class="site-main">
    <div class="container">
        <article class="single-video">
            <?php if ( $video_id ) : ?>
                <div class="video-player">
                    <div class="video-responsive">
                        <iframe src="https://www.youtube.com/embed/<?php echo esc_attr( $video_id ); ?>?rel=0" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    </div>
                </div>
            <?php endif; ?>

            <div class="video-info">
                <div class="video-meta">
                    <span class="video-date">
                        <span class="dashicons dashicons-calendar-alt"></span> <?php echo get_the_date(); ?>
                    </span>
                    <?php if ( $duration ) : ?>
                        <span class="video-duration-meta">
                            <span class="dashicons dashicons-clock"></span> <?php echo esc_html( $duration ); ?>
                        </span>
                    <?php endif; ?>
                </div>

                <div class="entry-content">
                    <?php the_content(); ?>
                </div>

                <?php campusos_social_share(); ?>
            </div>

            <?php
            // Related Videos
            $related = new WP_Query( array(
                'post_type'      => 'video',
                'posts_per_page' => 4,
                'post__not_in'   => array( get_the_ID() ),
                'orderby'        => 'date',
                'order'          => 'DESC',
            ) );
            if ( $related->have_posts() ) :
            ?>
                <div class="related-videos">
                    <h3><?php esc_html_e( 'Video Lainnya', 'campusos-academic' ); ?></h3>
                    <div class="video-grid video-grid-small">
                        <?php while ( $related->have_posts() ) : $related->the_post();
                            $rel_youtube = get_post_meta( get_the_ID(), 'video_youtube_url', true );
                            $rel_vid_id  = '';
                            if ( $rel_youtube ) {
                                preg_match( '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $rel_youtube, $rel_matches );
                                $rel_vid_id = isset( $rel_matches[1] ) ? $rel_matches[1] : '';
                            }
                        ?>
                            <article class="video-card card">
                                <a href="<?php the_permalink(); ?>" class="video-thumbnail">
                                    <?php if ( $rel_vid_id ) : ?>
                                        <img src="https://img.youtube.com/vi/<?php echo esc_attr( $rel_vid_id ); ?>/mqdefault.jpg" alt="<?php the_title_attribute(); ?>" class="card-img">
                                    <?php elseif ( has_post_thumbnail() ) : ?>
                                        <?php the_post_thumbnail( 'medium', array( 'class' => 'card-img' ) ); ?>
                                    <?php endif; ?>
                                    <div class="video-play-icon">
                                        <span class="dashicons dashicons-controls-play"></span>
                                    </div>
                                </a>
                                <div class="card-body">
                                    <h4 class="card-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                                </div>
                            </article>
                        <?php endwhile; wp_reset_postdata(); ?>
                    </div>
                </div>
            <?php endif; ?>
        </article>
    </div>
</main>
<?php
endwhile;
get_footer();
?>
