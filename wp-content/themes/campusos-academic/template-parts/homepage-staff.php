<?php
if ( ! defined( 'ABSPATH' ) ) exit;

$staff_query = new WP_Query( [
    'post_type'      => 'tenaga_pendidik',
    'posts_per_page' => 20,
    'post_status'    => 'publish',
    'orderby'        => 'menu_order',
    'order'          => 'ASC',
] );

if ( ! $staff_query->have_posts() ) return;

$archive_url = get_post_type_archive_link( 'tenaga_pendidik' );
?>
<section class="homepage-section homepage-staff">
    <div class="container">
        <div class="section-header-centered">
            <div class="section-decorator"></div>
            <h2><?php esc_html_e( 'TENAGA PENDIDIK', 'campusos-academic' ); ?></h2>
        </div>
        <div class="staff-carousel-wrapper">
            <div class="staff-carousel-track">
                <?php while ( $staff_query->have_posts() ) : $staff_query->the_post(); ?>
                <div class="staff-card">
                    <div class="staff-card-photo-ring">
                        <div class="staff-card-photo">
                            <?php if ( has_post_thumbnail() ) : ?>
                                <?php the_post_thumbnail( 'medium', [ 'loading' => 'lazy' ] ); ?>
                            <?php else : ?>
                                <div class="staff-card-placeholder">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="staff-card-name" title="<?php echo esc_attr( get_the_title() ); ?>"><?php the_title(); ?></div>
                </div>
                <?php endwhile; wp_reset_postdata(); ?>
            </div>
            <button class="staff-carousel-btn staff-carousel-prev" aria-label="<?php esc_attr_e( 'Sebelumnya', 'campusos-academic' ); ?>">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
            </button>
            <button class="staff-carousel-btn staff-carousel-next" aria-label="<?php esc_attr_e( 'Selanjutnya', 'campusos-academic' ); ?>">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
            </button>
        </div>
        <?php if ( $archive_url ) : ?>
        <div class="staff-view-all">
            <a href="<?php echo esc_url( $archive_url ); ?>" class="btn"><?php esc_html_e( 'Lihat Semua', 'campusos-academic' ); ?></a>
        </div>
        <?php endif; ?>
    </div>
</section>
