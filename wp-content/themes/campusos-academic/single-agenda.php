<?php
/**
 * Single Template: Agenda
 */
get_header();

while ( have_posts() ) : the_post();
    $tanggal_mulai = get_post_meta( get_the_ID(), '_agenda_tanggal_mulai_agenda', true );
    $tanggal_akhir = get_post_meta( get_the_ID(), '_agenda_tanggal_akhir_agenda', true );
    $lokasi        = get_post_meta( get_the_ID(), '_agenda_lokasi_agenda', true );
    $poster        = get_post_meta( get_the_ID(), '_agenda_poster_agenda', true );
    $deskripsi     = get_post_meta( get_the_ID(), '_agenda_deskripsi_agenda', true );

    // Calculate event status
    $status_label = '';
    $status_class = '';
    $countdown    = '';
    $today        = current_time( 'Y-m-d' );

    if ( $tanggal_mulai ) {
        $start = $tanggal_mulai;
        $end   = $tanggal_akhir ? $tanggal_akhir : $tanggal_mulai;

        if ( $today < $start ) {
            $status_label = 'Akan Datang';
            $status_class = 'upcoming';
            $diff = ( strtotime( $start ) - strtotime( $today ) ) / DAY_IN_SECONDS;
            $days = (int) ceil( $diff );
            $countdown = $days . ' hari lagi';
        } elseif ( $today >= $start && $today <= $end ) {
            $status_label = 'Berlangsung';
            $status_class = 'ongoing';
            $countdown = 'Sedang berlangsung';
        } else {
            $status_label = 'Selesai';
            $status_class = 'past';
        }
    }

    // Poster image URL
    $poster_url = '';
    if ( $poster ) {
        $poster_url = wp_get_attachment_image_url( $poster, 'large' );
    }
    if ( ! $poster_url && has_post_thumbnail() ) {
        $poster_url = get_the_post_thumbnail_url( get_the_ID(), 'large' );
    }
    if ( ! $poster_url ) {
        $poster_url = 'https://placehold.co/800x400/003d82/ffffff?text=Agenda';
    }
?>
<div class="page-hero">
    <div class="container">
        <h1><?php the_title(); ?></h1>
        <div class="breadcrumb">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Beranda', 'campusos-academic' ); ?></a> &raquo;
            <a href="<?php echo esc_url( get_post_type_archive_link( 'agenda' ) ); ?>"><?php esc_html_e( 'Agenda', 'campusos-academic' ); ?></a> &raquo;
            <?php the_title(); ?>
        </div>
    </div>
</div>
<main id="primary" class="site-main">
    <div class="container">
        <article class="single-agenda">
            <div class="agenda-detail-grid">
                <div class="agenda-main">
                    <div class="agenda-poster">
                        <img src="<?php echo esc_url( $poster_url ); ?>" alt="<?php the_title_attribute(); ?>">
                        <?php if ( $status_label ) : ?>
                            <span class="agenda-status-badge <?php echo esc_attr( $status_class ); ?>"><?php echo esc_html( $status_label ); ?></span>
                        <?php endif; ?>
                    </div>

                    <?php if ( $countdown ) : ?>
                        <div class="agenda-countdown <?php echo esc_attr( $status_class ); ?>">
                            <span class="dashicons dashicons-clock"></span>
                            <?php echo esc_html( $countdown ); ?>
                        </div>
                    <?php endif; ?>

                    <div class="agenda-content entry-content">
                        <?php if ( $deskripsi ) : ?>
                            <?php echo nl2br( esc_html( $deskripsi ) ); ?>
                        <?php else : ?>
                            <?php the_content(); ?>
                        <?php endif; ?>
                    </div>

                    <?php campusos_social_share(); ?>
                </div>

                <aside class="agenda-sidebar">
                    <div class="agenda-info-box">
                        <h3><?php esc_html_e( 'Informasi Kegiatan', 'campusos-academic' ); ?></h3>
                        <ul class="agenda-info-list">
                            <?php if ( $tanggal_mulai ) : ?>
                                <li>
                                    <span class="info-label">
                                        <span class="info-icon"><span class="dashicons dashicons-calendar-alt"></span></span>
                                        <?php esc_html_e( 'Tanggal', 'campusos-academic' ); ?>
                                    </span>
                                    <span class="info-value">
                                        <?php
                                        echo esc_html( date_i18n( 'l, j F Y', strtotime( $tanggal_mulai ) ) );
                                        if ( $tanggal_akhir && $tanggal_akhir !== $tanggal_mulai ) {
                                            echo '<br>s/d ' . esc_html( date_i18n( 'l, j F Y', strtotime( $tanggal_akhir ) ) );
                                        }
                                        ?>
                                    </span>
                                </li>
                            <?php endif; ?>
                            <?php if ( $lokasi ) : ?>
                                <li>
                                    <span class="info-label">
                                        <span class="info-icon"><span class="dashicons dashicons-location"></span></span>
                                        <?php esc_html_e( 'Lokasi', 'campusos-academic' ); ?>
                                    </span>
                                    <span class="info-value"><?php echo esc_html( $lokasi ); ?></span>
                                </li>
                            <?php endif; ?>
                            <?php if ( $status_label ) : ?>
                                <li>
                                    <span class="info-label">
                                        <span class="info-icon"><span class="dashicons dashicons-flag"></span></span>
                                        <?php esc_html_e( 'Status', 'campusos-academic' ); ?>
                                    </span>
                                    <span class="info-value">
                                        <span class="agenda-status-badge sidebar-badge <?php echo esc_attr( $status_class ); ?>"><?php echo esc_html( $status_label ); ?></span>
                                    </span>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>

                    <?php
                    // Related Agenda
                    $related = new WP_Query( array(
                        'post_type'      => 'agenda',
                        'posts_per_page' => 3,
                        'post__not_in'   => array( get_the_ID() ),
                        'orderby'        => 'meta_value',
                        'meta_key'       => '_agenda_tanggal_mulai_agenda',
                        'order'          => 'DESC',
                    ) );
                    if ( $related->have_posts() ) :
                    ?>
                        <div class="related-agenda">
                            <h3><?php esc_html_e( 'Agenda Lainnya', 'campusos-academic' ); ?></h3>
                            <ul class="related-list">
                                <?php while ( $related->have_posts() ) : $related->the_post(); ?>
                                    <li>
                                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                        <?php
                                        $rel_date = get_post_meta( get_the_ID(), '_agenda_tanggal_mulai_agenda', true );
                                        if ( $rel_date ) :
                                        ?>
                                            <span class="related-date"><?php echo esc_html( date_i18n( 'j M Y', strtotime( $rel_date ) ) ); ?></span>
                                        <?php endif; ?>
                                    </li>
                                <?php endwhile; wp_reset_postdata(); ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                </aside>
            </div>
        </article>
    </div>
</main>
<?php
endwhile;
get_footer();
?>
