<?php
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<section class="homepage-section homepage-news">
    <div class="container">
        <h2><?php esc_html_e( 'Berita Terbaru', 'unpatti-academic' ); ?></h2>
        <div class="posts-grid">
            <?php
            $news = new WP_Query( [
                'post_type'      => 'post',
                'posts_per_page' => 6,
                'post_status'    => 'publish',
            ] );
            while ( $news->have_posts() ) : $news->the_post();
            ?>
            <div class="card">
                <?php if ( has_post_thumbnail() ) : ?>
                    <img class="card-img" src="<?php echo esc_url( get_the_post_thumbnail_url( null, 'unpatti-card' ) ); ?>" alt="<?php the_title_attribute(); ?>" />
                <?php endif; ?>
                <div class="card-body">
                    <span class="card-date"><?php echo get_the_date(); ?></span>
                    <h3 class="card-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                    <p class="card-text"><?php echo wp_trim_words( get_the_excerpt(), 15 ); ?></p>
                </div>
            </div>
            <?php endwhile; wp_reset_postdata(); ?>
        </div>
    </div>
</section>
