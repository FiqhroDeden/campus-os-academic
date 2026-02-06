<?php
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<section class="homepage-section homepage-announcement">
    <div class="container">
        <h2><?php esc_html_e( 'Pengumuman', 'unpatti-academic' ); ?></h2>
        <div class="posts-grid">
            <?php
            $items = new WP_Query( [
                'post_type'      => 'pengumuman',
                'posts_per_page' => 6,
                'post_status'    => 'publish',
            ] );
            while ( $items->have_posts() ) : $items->the_post();
            ?>
            <div class="card">
                <?php if ( has_post_thumbnail() ) : ?>
                    <img class="card-img" src="<?php echo esc_url( get_the_post_thumbnail_url( null, 'unpatti-card' ) ); ?>" alt="<?php the_title_attribute(); ?>" />
                <?php endif; ?>
                <div class="card-body">
                    <span class="card-date"><?php echo get_the_date(); ?></span>
                    <h3 class="card-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                </div>
            </div>
            <?php endwhile; wp_reset_postdata(); ?>
        </div>
    </div>
</section>
