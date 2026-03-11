<?php
/**
 * Single Template: Organisasi Mahasiswa
 */
get_header();

while ( have_posts() ) : the_post();
    $logo         = get_post_meta( get_the_ID(), 'organisasi_mhs_logo_org', true );
    $struktur     = get_post_meta( get_the_ID(), 'organisasi_mhs_struktur_org', true );
    $tupoksi      = get_post_meta( get_the_ID(), 'organisasi_mhs_tupoksi', true );
    $program_kerja = get_post_meta( get_the_ID(), 'organisasi_mhs_program_kerja', true );
?>
<div class="page-hero">
    <div class="container">
        <h1><?php the_title(); ?></h1>
    </div>
</div>
<main id="primary" class="site-main">
    <div class="container">
        <article class="single-organisasi">
            <div class="organisasi-header">
                <?php if ( $logo ) : ?>
                    <div class="organisasi-logo-large">
                        <img src="<?php echo esc_url( wp_get_attachment_image_url( $logo, 'medium' ) ); ?>" alt="<?php the_title_attribute(); ?>">
                    </div>
                <?php endif; ?>
                <div class="organisasi-intro">
                    <h2><?php the_title(); ?></h2>
                    <?php if ( get_the_excerpt() ) : ?>
                        <p class="organisasi-excerpt"><?php echo esc_html( get_the_excerpt() ); ?></p>
                    <?php endif; ?>
                </div>
            </div>

            <?php if ( get_the_content() ) : ?>
                <div class="organisasi-section">
                    <h3><?php esc_html_e( 'Tentang Organisasi', 'campusos-academic' ); ?></h3>
                    <div class="entry-content">
                        <?php the_content(); ?>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ( $tupoksi ) : ?>
                <div class="organisasi-section">
                    <h3><?php esc_html_e( 'Tugas Pokok dan Fungsi', 'campusos-academic' ); ?></h3>
                    <div class="organisasi-content">
                        <?php echo wp_kses_post( nl2br( $tupoksi ) ); ?>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ( $program_kerja ) : ?>
                <div class="organisasi-section">
                    <h3><?php esc_html_e( 'Program Kerja', 'campusos-academic' ); ?></h3>
                    <div class="organisasi-content">
                        <?php echo wp_kses_post( nl2br( $program_kerja ) ); ?>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ( $struktur ) : ?>
                <div class="organisasi-section">
                    <h3><?php esc_html_e( 'Struktur Organisasi', 'campusos-academic' ); ?></h3>
                    <div class="organisasi-struktur">
                        <img src="<?php echo esc_url( wp_get_attachment_image_url( $struktur, 'full' ) ); ?>" alt="<?php esc_attr_e( 'Struktur Organisasi', 'campusos-academic' ); ?>">
                    </div>
                </div>
            <?php endif; ?>

            <?php campusos_social_share(); ?>

            <?php
            // Related Organisasi
            $related = new WP_Query( array(
                'post_type'      => 'organisasi_mhs',
                'posts_per_page' => 4,
                'post__not_in'   => array( get_the_ID() ),
                'orderby'        => 'title',
                'order'          => 'ASC',
            ) );
            if ( $related->have_posts() ) :
            ?>
                <div class="related-organisasi">
                    <h3><?php esc_html_e( 'Organisasi Lainnya', 'campusos-academic' ); ?></h3>
                    <div class="organisasi-grid organisasi-grid-small">
                        <?php while ( $related->have_posts() ) : $related->the_post();
                            $rel_logo = get_post_meta( get_the_ID(), 'organisasi_mhs_logo_org', true );
                        ?>
                            <article class="organisasi-card card">
                                <div class="organisasi-logo">
                                    <?php if ( $rel_logo ) : ?>
                                        <img src="<?php echo esc_url( wp_get_attachment_image_url( $rel_logo, 'thumbnail' ) ); ?>" alt="<?php the_title_attribute(); ?>">
                                    <?php else : ?>
                                        <div class="organisasi-placeholder">
                                            <span class="dashicons dashicons-groups"></span>
                                        </div>
                                    <?php endif; ?>
                                </div>
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
