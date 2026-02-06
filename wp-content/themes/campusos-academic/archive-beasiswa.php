<?php
/**
 * Archive Template: Beasiswa
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
            <div class="beasiswa-grid">
                <?php while ( have_posts() ) : the_post();
                    $persyaratan = get_post_meta( get_the_ID(), 'beasiswa_persyaratan_beasiswa', true );
                    $deadline    = get_post_meta( get_the_ID(), 'beasiswa_deadline_beasiswa', true );
                    $link        = get_post_meta( get_the_ID(), 'beasiswa_link_pendaftaran', true );

                    // Check if deadline passed
                    $is_expired = $deadline && strtotime( $deadline ) < time();
                ?>
                <article class="beasiswa-card card <?php echo $is_expired ? 'beasiswa-expired' : ''; ?>">
                    <div class="card-body">
                        <div class="beasiswa-header">
                            <h3 class="card-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                            <?php if ( $is_expired ) : ?>
                                <span class="beasiswa-status status-expired"><?php esc_html_e( 'Ditutup', 'unpatti-academic' ); ?></span>
                            <?php elseif ( $deadline ) : ?>
                                <span class="beasiswa-status status-open"><?php esc_html_e( 'Dibuka', 'unpatti-academic' ); ?></span>
                            <?php endif; ?>
                        </div>

                        <?php if ( get_the_excerpt() ) : ?>
                            <p class="card-text"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 20 ) ); ?></p>
                        <?php endif; ?>

                        <?php if ( $deadline ) : ?>
                            <p class="beasiswa-deadline">
                                <span class="dashicons dashicons-calendar-alt"></span>
                                <strong><?php esc_html_e( 'Deadline:', 'unpatti-academic' ); ?></strong>
                                <?php echo esc_html( date_i18n( 'j F Y', strtotime( $deadline ) ) ); ?>
                            </p>
                        <?php endif; ?>

                        <div class="beasiswa-actions">
                            <a href="<?php the_permalink(); ?>" class="btn btn-outline btn-sm"><?php esc_html_e( 'Lihat Detail', 'unpatti-academic' ); ?></a>
                            <?php if ( $link && ! $is_expired ) : ?>
                                <a href="<?php echo esc_url( $link ); ?>" class="btn btn-primary btn-sm" target="_blank"><?php esc_html_e( 'Daftar', 'unpatti-academic' ); ?></a>
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
            <p class="no-content"><?php esc_html_e( 'Belum ada data beasiswa.', 'unpatti-academic' ); ?></p>
        <?php endif; ?>
    </div>
</main>
<?php get_footer(); ?>
