<?php
/**
 * Archive Template: Mitra Industri
 */
get_header();
?>
<div class="page-hero">
    <div class="container">
        <h1><?php post_type_archive_title(); ?></h1>
    </div>
</div>
<main id="primary" class="site-main">
    <div class="container">
        <?php if ( have_posts() ) : ?>
            <div class="mitra-grid">
                <?php while ( have_posts() ) : the_post();
                    $logo  = get_post_meta( get_the_ID(), 'mitra_industri_logo_mitra_di', true );
                    $jenis = get_post_meta( get_the_ID(), 'mitra_industri_jenis_kerjasama_di', true );
                ?>
                <article class="mitra-card card">
                    <div class="mitra-logo">
                        <?php if ( $logo ) : ?>
                            <img src="<?php echo esc_url( wp_get_attachment_image_url( $logo, 'medium' ) ); ?>" alt="<?php the_title_attribute(); ?>">
                        <?php elseif ( has_post_thumbnail() ) : ?>
                            <?php the_post_thumbnail( 'medium' ); ?>
                        <?php else : ?>
                            <div class="mitra-placeholder">
                                <span class="dashicons dashicons-store"></span>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="card-body">
                        <h3 class="card-title"><?php the_title(); ?></h3>
                        <?php if ( $jenis ) : ?>
                            <p class="mitra-jenis"><?php echo esc_html( $jenis ); ?></p>
                        <?php endif; ?>
                        <?php if ( get_the_excerpt() ) : ?>
                            <p class="card-text"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 15 ) ); ?></p>
                        <?php endif; ?>
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
            <p class="no-content"><?php esc_html_e( 'Belum ada data mitra industri.', 'campusos-academic' ); ?></p>
        <?php endif; ?>
    </div>
</main>
<?php get_footer(); ?>
