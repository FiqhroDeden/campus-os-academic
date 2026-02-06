<?php
/**
 * Archive Template: Kerjasama
 */
get_header();
?>
<div class="page-hero">
    <div class="container">
        <h1><?php post_type_archive_title(); ?></h1>
        <div class="breadcrumb">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Beranda', 'campusos-academic' ); ?></a> &raquo;
            <?php post_type_archive_title(); ?>
        </div>
    </div>
</div>
<main id="primary" class="site-main">
    <div class="container">
        <?php if ( have_posts() ) : ?>
            <div class="kerjasama-grid">
                <?php while ( have_posts() ) : the_post();
                    $logo        = get_post_meta( get_the_ID(), '_kerjasama_logo', true );
                    $jenis       = get_post_meta( get_the_ID(), '_kerjasama_jenis', true );
                    $periode     = get_post_meta( get_the_ID(), '_kerjasama_periode', true );
                    $lingkup     = get_post_meta( get_the_ID(), '_kerjasama_lingkup', true );
                ?>
                <article class="kerjasama-card card">
                    <div class="kerjasama-logo">
                        <?php if ( $logo ) : ?>
                            <img src="<?php echo esc_url( wp_get_attachment_image_url( $logo, 'medium' ) ); ?>" alt="<?php the_title_attribute(); ?>">
                        <?php elseif ( has_post_thumbnail() ) : ?>
                            <?php the_post_thumbnail( 'medium' ); ?>
                        <?php else : ?>
                            <div class="kerjasama-placeholder"><span class="dashicons dashicons-networking"></span></div>
                        <?php endif; ?>
                    </div>
                    <div class="card-body">
                        <h3 class="card-title"><?php the_title(); ?></h3>
                        <?php if ( $jenis ) : ?>
                            <p class="kerjasama-jenis"><?php echo esc_html( $jenis ); ?></p>
                        <?php endif; ?>
                        <?php if ( $lingkup ) : ?>
                            <span class="badge badge-lingkup"><?php echo esc_html( ucfirst( $lingkup ) ); ?></span>
                        <?php endif; ?>
                        <?php if ( $periode ) : ?>
                            <p class="kerjasama-periode">Periode: <?php echo esc_html( $periode ); ?></p>
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
            <p class="no-content"><?php esc_html_e( 'Belum ada data kerjasama.', 'campusos-academic' ); ?></p>
        <?php endif; ?>
    </div>
</main>
<?php get_footer(); ?>
