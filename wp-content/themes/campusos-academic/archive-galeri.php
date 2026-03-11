<?php
/**
 * Archive Template: Galeri
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
            <div class="galeri-grid">
                <?php while ( have_posts() ) : the_post();
                    $kategori = get_post_meta( get_the_ID(), 'galeri_kategori_galeri', true );
                    $tanggal  = get_post_meta( get_the_ID(), 'galeri_tanggal_galeri', true );
                ?>
                <article class="galeri-item">
                    <a href="<?php the_permalink(); ?>" class="galeri-link">
                        <?php if ( has_post_thumbnail() ) : ?>
                            <?php the_post_thumbnail( 'medium_large', array( 'class' => 'galeri-img' ) ); ?>
                        <?php else : ?>
                            <div class="galeri-placeholder">
                                <span class="dashicons dashicons-format-gallery"></span>
                            </div>
                        <?php endif; ?>
                        <div class="galeri-overlay">
                            <h3 class="galeri-title"><?php the_title(); ?></h3>
                            <?php if ( $kategori ) : ?>
                                <span class="galeri-category"><?php echo esc_html( $kategori ); ?></span>
                            <?php endif; ?>
                            <?php if ( $tanggal ) : ?>
                                <span class="galeri-date"><?php echo esc_html( date_i18n( 'j F Y', strtotime( $tanggal ) ) ); ?></span>
                            <?php endif; ?>
                        </div>
                    </a>
                </article>
                <?php endwhile; ?>
            </div>
            <?php the_posts_pagination( array(
                'mid_size'  => 2,
                'prev_text' => '&laquo;',
                'next_text' => '&raquo;',
            ) ); ?>
        <?php else : ?>
            <p class="no-content"><?php esc_html_e( 'Belum ada galeri foto.', 'campusos-academic' ); ?></p>
        <?php endif; ?>
    </div>
</main>
<?php get_footer(); ?>
