<?php
/**
 * Single Template: Beasiswa
 */
get_header();

while ( have_posts() ) : the_post();
    $persyaratan = get_post_meta( get_the_ID(), 'beasiswa_persyaratan_beasiswa', true );
    $deadline    = get_post_meta( get_the_ID(), 'beasiswa_deadline_beasiswa', true );
    $link        = get_post_meta( get_the_ID(), 'beasiswa_link_pendaftaran', true );

    // Check if deadline passed
    $is_expired = $deadline && strtotime( $deadline ) < time();
?>
<div class="page-hero">
    <div class="container">
        <h1><?php the_title(); ?></h1>
        <div class="breadcrumb">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Beranda', 'unpatti-academic' ); ?></a> &raquo;
            <a href="<?php echo esc_url( get_post_type_archive_link( 'beasiswa' ) ); ?>"><?php esc_html_e( 'Beasiswa', 'unpatti-academic' ); ?></a> &raquo;
            <?php the_title(); ?>
        </div>
    </div>
</div>
<main id="primary" class="site-main">
    <div class="container">
        <div class="beasiswa-detail-grid">
            <article class="beasiswa-main">
                <div class="beasiswa-status-banner <?php echo $is_expired ? 'status-expired' : 'status-open'; ?>">
                    <?php if ( $is_expired ) : ?>
                        <span class="dashicons dashicons-no"></span>
                        <?php esc_html_e( 'Pendaftaran Ditutup', 'unpatti-academic' ); ?>
                    <?php else : ?>
                        <span class="dashicons dashicons-yes"></span>
                        <?php esc_html_e( 'Pendaftaran Dibuka', 'unpatti-academic' ); ?>
                    <?php endif; ?>
                </div>

                <div class="entry-content">
                    <?php the_content(); ?>
                </div>

                <?php if ( $persyaratan ) : ?>
                    <div class="beasiswa-section">
                        <h3><?php esc_html_e( 'Persyaratan', 'unpatti-academic' ); ?></h3>
                        <div class="beasiswa-persyaratan">
                            <?php echo wp_kses_post( nl2br( $persyaratan ) ); ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ( $link && ! $is_expired ) : ?>
                    <div class="beasiswa-cta">
                        <a href="<?php echo esc_url( $link ); ?>" class="btn btn-primary btn-lg" target="_blank">
                            <span class="dashicons dashicons-external"></span>
                            <?php esc_html_e( 'Daftar Sekarang', 'unpatti-academic' ); ?>
                        </a>
                    </div>
                <?php endif; ?>

                <?php unpatti_social_share(); ?>
            </article>

            <aside class="beasiswa-sidebar">
                <div class="beasiswa-info-box">
                    <h3><?php esc_html_e( 'Informasi Beasiswa', 'unpatti-academic' ); ?></h3>
                    <ul class="beasiswa-info-list">
                        <?php if ( $deadline ) : ?>
                            <li>
                                <span class="info-label"><span class="dashicons dashicons-calendar-alt"></span> <?php esc_html_e( 'Deadline', 'unpatti-academic' ); ?></span>
                                <span class="info-value <?php echo $is_expired ? 'text-expired' : ''; ?>">
                                    <?php echo esc_html( date_i18n( 'l, j F Y', strtotime( $deadline ) ) ); ?>
                                </span>
                            </li>
                        <?php endif; ?>
                        <li>
                            <span class="info-label"><span class="dashicons dashicons-tag"></span> <?php esc_html_e( 'Status', 'unpatti-academic' ); ?></span>
                            <span class="info-value">
                                <?php echo $is_expired ? esc_html__( 'Ditutup', 'unpatti-academic' ) : esc_html__( 'Dibuka', 'unpatti-academic' ); ?>
                            </span>
                        </li>
                    </ul>
                </div>

                <?php
                // Related Beasiswa
                $related = new WP_Query( array(
                    'post_type'      => 'beasiswa',
                    'posts_per_page' => 3,
                    'post__not_in'   => array( get_the_ID() ),
                    'orderby'        => 'date',
                    'order'          => 'DESC',
                ) );
                if ( $related->have_posts() ) :
                ?>
                    <div class="related-beasiswa">
                        <h3><?php esc_html_e( 'Beasiswa Lainnya', 'unpatti-academic' ); ?></h3>
                        <ul class="related-list">
                            <?php while ( $related->have_posts() ) : $related->the_post();
                                $rel_deadline = get_post_meta( get_the_ID(), 'beasiswa_deadline_beasiswa', true );
                            ?>
                                <li>
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                    <?php if ( $rel_deadline ) : ?>
                                        <span class="related-date"><?php echo esc_html( date_i18n( 'j M Y', strtotime( $rel_deadline ) ) ); ?></span>
                                    <?php endif; ?>
                                </li>
                            <?php endwhile; wp_reset_postdata(); ?>
                        </ul>
                    </div>
                <?php endif; ?>
            </aside>
        </div>
    </div>
</main>
<?php
endwhile;
get_footer();
?>
