<?php
/**
 * Archive Template: Organisasi Mahasiswa
 */
get_header();
?>
<div class="page-hero">
    <div class="container">
        <h1><?php post_type_archive_title(); ?></h1>
        <div class="breadcrumb">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Beranda', 'unpatti-academic' ); ?></a> &raquo;
            <?php post_type_archive_title(); ?>
        </div>
    </div>
</div>
<main id="primary" class="site-main">
    <div class="container">
        <?php if ( have_posts() ) : ?>
            <div class="organisasi-grid">
                <?php while ( have_posts() ) : the_post();
                    $logo    = get_post_meta( get_the_ID(), 'organisasi_mhs_logo_org', true );
                    $tupoksi = get_post_meta( get_the_ID(), 'organisasi_mhs_tupoksi', true );
                ?>
                <article class="organisasi-card card">
                    <div class="organisasi-logo">
                        <?php if ( $logo ) : ?>
                            <img src="<?php echo esc_url( wp_get_attachment_image_url( $logo, 'medium' ) ); ?>" alt="<?php the_title_attribute(); ?>">
                        <?php elseif ( has_post_thumbnail() ) : ?>
                            <?php the_post_thumbnail( 'medium' ); ?>
                        <?php else : ?>
                            <div class="organisasi-placeholder">
                                <span class="dashicons dashicons-groups"></span>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="card-body">
                        <h3 class="card-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                        <?php if ( $tupoksi ) : ?>
                            <p class="card-text"><?php echo esc_html( wp_trim_words( $tupoksi, 20 ) ); ?></p>
                        <?php elseif ( get_the_excerpt() ) : ?>
                            <p class="card-text"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 20 ) ); ?></p>
                        <?php endif; ?>
                        <a href="<?php the_permalink(); ?>" class="btn btn-outline btn-sm"><?php esc_html_e( 'Lihat Detail', 'unpatti-academic' ); ?></a>
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
            <p class="no-content"><?php esc_html_e( 'Belum ada data organisasi mahasiswa.', 'unpatti-academic' ); ?></p>
        <?php endif; ?>
    </div>
</main>
<?php get_footer(); ?>
