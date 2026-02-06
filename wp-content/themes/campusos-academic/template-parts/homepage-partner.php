<?php
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<section class="homepage-section homepage-partner" style="padding:3rem 0;">
    <div class="container">
        <h2 style="text-align:center;margin-bottom:1.5rem;"><?php esc_html_e( 'Mitra & Kerjasama', 'campusos-academic' ); ?></h2>
        <div class="posts-grid">
            <?php
            $items = new WP_Query( [
                'post_type'      => 'mitra_industri',
                'posts_per_page' => 12,
                'post_status'    => 'publish',
            ] );
            while ( $items->have_posts() ) : $items->the_post();
            ?>
            <div class="card" style="text-align:center;">
                <?php if ( has_post_thumbnail() ) : ?>
                    <img class="card-img" style="object-fit:contain;padding:1rem;aspect-ratio:auto;" src="<?php echo esc_url( get_the_post_thumbnail_url( null, 'campusos-card' ) ); ?>" alt="<?php the_title_attribute(); ?>" />
                <?php endif; ?>
                <div class="card-body">
                    <h3 class="card-title"><?php the_title(); ?></h3>
                </div>
            </div>
            <?php endwhile; wp_reset_postdata(); ?>
        </div>
    </div>
</section>
