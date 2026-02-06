<?php
/**
 * Single Template: Agenda
 */
get_header();

while ( have_posts() ) : the_post();
    $tanggal_mulai = get_post_meta( get_the_ID(), 'agenda_tanggal_mulai_agenda', true );
    $tanggal_akhir = get_post_meta( get_the_ID(), 'agenda_tanggal_akhir_agenda', true );
    $lokasi        = get_post_meta( get_the_ID(), 'agenda_lokasi_agenda', true );
    $poster        = get_post_meta( get_the_ID(), 'agenda_poster_agenda', true );
?>
<div class="page-hero">
    <div class="container">
        <h1><?php the_title(); ?></h1>
        <div class="breadcrumb">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Beranda', 'unpatti-academic' ); ?></a> &raquo;
            <a href="<?php echo esc_url( get_post_type_archive_link( 'agenda' ) ); ?>"><?php esc_html_e( 'Agenda', 'unpatti-academic' ); ?></a> &raquo;
            <?php the_title(); ?>
        </div>
    </div>
</div>
<main id="primary" class="site-main">
    <div class="container">
        <article class="single-agenda">
            <div class="agenda-detail-grid">
                <div class="agenda-main">
                    <?php if ( $poster ) : ?>
                        <div class="agenda-poster">
                            <img src="<?php echo esc_url( wp_get_attachment_image_url( $poster, 'large' ) ); ?>" alt="<?php the_title_attribute(); ?>">
                        </div>
                    <?php elseif ( has_post_thumbnail() ) : ?>
                        <div class="agenda-poster">
                            <?php the_post_thumbnail( 'large' ); ?>
                        </div>
                    <?php endif; ?>

                    <div class="agenda-content entry-content">
                        <?php the_content(); ?>
                    </div>

                    <?php unpatti_social_share(); ?>
                </div>

                <aside class="agenda-sidebar">
                    <div class="agenda-info-box">
                        <h3><?php esc_html_e( 'Informasi Kegiatan', 'unpatti-academic' ); ?></h3>
                        <ul class="agenda-info-list">
                            <?php if ( $tanggal_mulai ) : ?>
                                <li>
                                    <span class="info-label"><span class="dashicons dashicons-calendar-alt"></span> <?php esc_html_e( 'Tanggal', 'unpatti-academic' ); ?></span>
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
                                    <span class="info-label"><span class="dashicons dashicons-location"></span> <?php esc_html_e( 'Lokasi', 'unpatti-academic' ); ?></span>
                                    <span class="info-value"><?php echo esc_html( $lokasi ); ?></span>
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
                        'meta_key'       => 'agenda_tanggal_mulai_agenda',
                        'order'          => 'DESC',
                    ) );
                    if ( $related->have_posts() ) :
                    ?>
                        <div class="related-agenda">
                            <h3><?php esc_html_e( 'Agenda Lainnya', 'unpatti-academic' ); ?></h3>
                            <ul class="related-list">
                                <?php while ( $related->have_posts() ) : $related->the_post(); ?>
                                    <li>
                                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                        <?php
                                        $rel_date = get_post_meta( get_the_ID(), 'agenda_tanggal_mulai_agenda', true );
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
