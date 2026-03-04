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

$bg_image = get_theme_mod( 'campusos_staff_bg_image', '' );
$bg_style = $bg_image ? 'background-image:url(' . esc_url( $bg_image ) . ');background-size:cover;background-position:center;' : 'background:#001432;';
$archive_url = get_post_type_archive_link( 'tenaga_pendidik' );
?>
<section class="homepage-section homepage-staff" style="<?php echo esc_attr( $bg_style . 'padding:0;position:relative;' ); ?>">
    <div class="homepage-staff-overlay">
        <div class="container">
            <div class="section-header-centered">
                <div class="section-decorator"></div>
                <h2><?php esc_html_e( 'TENAGA PENDIDIK', 'campusos-academic' ); ?></h2>
            </div>
            <div class="staff-carousel-wrapper">
                <div class="staff-carousel-track">
                    <?php while ( $staff_query->have_posts() ) : $staff_query->the_post();
                        $phone = get_post_meta( get_the_ID(), 'tenaga_pendidik_phone', true );
                        if ( ! $phone ) $phone = get_post_meta( get_the_ID(), '_phone', true );
                        $email_addr = get_post_meta( get_the_ID(), 'tenaga_pendidik_email', true );
                        if ( ! $email_addr ) $email_addr = get_post_meta( get_the_ID(), '_email', true );
                        $whatsapp = get_post_meta( get_the_ID(), 'tenaga_pendidik_whatsapp', true );
                        if ( ! $whatsapp ) $whatsapp = get_post_meta( get_the_ID(), '_whatsapp', true );
                    ?>
                    <div class="staff-card">
                        <div class="staff-card-photo">
                            <?php if ( has_post_thumbnail() ) : ?>
                                <?php the_post_thumbnail( 'medium', [ 'loading' => 'lazy' ] ); ?>
                            <?php else : ?>
                                <div class="staff-card-placeholder">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="staff-card-body">
                            <div class="staff-card-name" title="<?php echo esc_attr( get_the_title() ); ?>"><?php the_title(); ?></div>
                            <div class="staff-card-role"><?php esc_html_e( 'Dosen', 'campusos-academic' ); ?></div>
                            <div class="staff-card-contacts">
                                <?php if ( $phone ) : ?>
                                <a href="tel:<?php echo esc_attr( $phone ); ?>" aria-label="<?php esc_attr_e( 'Telepon', 'campusos-academic' ); ?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                                </a>
                                <?php endif; ?>
                                <?php if ( $email_addr ) : ?>
                                <a href="mailto:<?php echo esc_attr( $email_addr ); ?>" aria-label="<?php esc_attr_e( 'Email', 'campusos-academic' ); ?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                                </a>
                                <?php endif; ?>
                                <?php if ( $whatsapp ) : ?>
                                <a href="https://wa.me/<?php echo esc_attr( preg_replace( '/[^0-9]/', '', $whatsapp ) ); ?>" target="_blank" rel="noopener noreferrer" aria-label="WhatsApp">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                </a>
                                <?php endif; ?>
                            </div>
                        </div>
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
    </div>
</section>
