<?php
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<section class="homepage-section homepage-faq">
    <div class="container">
        <h2><?php esc_html_e( 'Pertanyaan Umum', 'campusos-academic' ); ?></h2>
        <?php
        $items = new WP_Query( [
            'post_type'      => 'faq',
            'posts_per_page' => 10,
            'post_status'    => 'publish',
        ] );
        if ( $items->have_posts() ) :
        ?>
        <div class="faq-list" style="margin-top:1rem;">
            <?php while ( $items->have_posts() ) : $items->the_post(); ?>
            <details style="border:1px solid var(--campusos-border, #e5e7eb);border-radius:4px;margin-bottom:0.5rem;padding:1rem;">
                <summary style="font-weight:600;cursor:pointer;"><?php the_title(); ?></summary>
                <div style="margin-top:0.75rem;line-height:1.7;"><?php the_content(); ?></div>
            </details>
            <?php endwhile; ?>
        </div>
        <?php endif; wp_reset_postdata(); ?>
    </div>
</section>
