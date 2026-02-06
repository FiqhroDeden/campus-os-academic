<?php
/**
 * Archive Template: Video
 */
get_header();
?>
<div class="page-hero">
    <div class="container">
        <h1><?php post_type_archive_title(); ?></h1>
        <div class="breadcrumb">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Beranda', 'unpatti-academic' ); ?></a> &raquo;
            <?php post_type_archive_title(); ?>
        </div>
    </div>
</div>
<main id="primary" class="site-main">
    <div class="container">
        <?php if ( have_posts() ) : ?>
            <div class="video-grid">
                <?php while ( have_posts() ) : the_post();
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
                <?php endwhile; ?>
            </div>
            <?php the_posts_pagination( array(
                'mid_size'  => 2,
                'prev_text' => '&laquo;',
                'next_text' => '&raquo;',
            ) ); ?>
        <?php else : ?>
            <p class="no-content"><?php esc_html_e( 'Belum ada video.', 'unpatti-academic' ); ?></p>
        <?php endif; ?>
    </div>
</main>
<?php get_footer(); ?>
