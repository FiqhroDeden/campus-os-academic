<?php
/**
 * Archive Template: Pengumuman
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
            <div class="pengumuman-list">
                <?php while ( have_posts() ) : the_post();
                    $tanggal_berlaku = get_post_meta( get_the_ID(), 'pengumuman_tanggal_berlaku', true );
                    $file_lampiran   = get_post_meta( get_the_ID(), 'pengumuman_file_lampiran', true );
                ?>
                <article class="pengumuman-item">
                    <div class="pengumuman-date">
                        <span class="date-day"><?php echo get_the_date( 'j' ); ?></span>
                        <span class="date-month"><?php echo get_the_date( 'M Y' ); ?></span>
                    </div>
                    <div class="pengumuman-content">
                        <h3 class="pengumuman-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                        <?php if ( $tanggal_berlaku ) : ?>
                            <p class="pengumuman-meta">
                                <span class="dashicons dashicons-clock"></span>
                                <?php esc_html_e( 'Berlaku sampai:', 'unpatti-academic' ); ?> <?php echo esc_html( date_i18n( 'j F Y', strtotime( $tanggal_berlaku ) ) ); ?>
                            </p>
                        <?php endif; ?>
                        <?php if ( get_the_excerpt() ) : ?>
                            <p class="pengumuman-excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 30 ) ); ?></p>
                        <?php endif; ?>
                        <div class="pengumuman-actions">
                            <a href="<?php the_permalink(); ?>" class="btn btn-outline btn-sm"><?php esc_html_e( 'Baca Selengkapnya', 'unpatti-academic' ); ?></a>
                            <?php if ( $file_lampiran ) : ?>
                                <a href="<?php echo esc_url( wp_get_attachment_url( $file_lampiran ) ); ?>" class="btn btn-primary btn-sm" target="_blank">
                                    <span class="dashicons dashicons-download"></span> <?php esc_html_e( 'Download', 'unpatti-academic' ); ?>
                                </a>
                            <?php endif; ?>
                        </div>
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
            <p class="no-content"><?php esc_html_e( 'Belum ada pengumuman.', 'unpatti-academic' ); ?></p>
        <?php endif; ?>
    </div>
</main>
<?php get_footer(); ?>
