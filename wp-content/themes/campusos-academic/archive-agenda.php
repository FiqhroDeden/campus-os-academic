<?php
/**
 * Archive Template: Agenda
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
            <div class="agenda-grid">
                <?php while ( have_posts() ) : the_post();
                    $tanggal_mulai = get_post_meta( get_the_ID(), 'agenda_tanggal_mulai_agenda', true );
                    $tanggal_akhir = get_post_meta( get_the_ID(), 'agenda_tanggal_akhir_agenda', true );
                    $lokasi        = get_post_meta( get_the_ID(), 'agenda_lokasi_agenda', true );
                    $poster        = get_post_meta( get_the_ID(), 'agenda_poster_agenda', true );
                ?>
                <article class="agenda-card card">
                    <?php if ( $poster ) : ?>
                        <img src="<?php echo esc_url( wp_get_attachment_image_url( $poster, 'medium_large' ) ); ?>" alt="<?php the_title_attribute(); ?>" class="card-img">
                    <?php elseif ( has_post_thumbnail() ) : ?>
                        <?php the_post_thumbnail( 'medium_large', array( 'class' => 'card-img' ) ); ?>
                    <?php endif; ?>
                    <div class="card-body">
                        <h3 class="card-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                        <div class="agenda-meta">
                            <?php if ( $tanggal_mulai ) : ?>
                                <p class="meta-date">
                                    <span class="dashicons dashicons-calendar-alt"></span>
                                    <?php
                                    echo esc_html( date_i18n( 'j F Y', strtotime( $tanggal_mulai ) ) );
                                    if ( $tanggal_akhir && $tanggal_akhir !== $tanggal_mulai ) {
                                        echo ' - ' . esc_html( date_i18n( 'j F Y', strtotime( $tanggal_akhir ) ) );
                                    }
                                    ?>
                                </p>
                            <?php endif; ?>
                            <?php if ( $lokasi ) : ?>
                                <p class="meta-location">
                                    <span class="dashicons dashicons-location"></span>
                                    <?php echo esc_html( $lokasi ); ?>
                                </p>
                            <?php endif; ?>
                        </div>
                        <?php if ( get_the_excerpt() ) : ?>
                            <p class="card-text"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 20 ) ); ?></p>
                        <?php endif; ?>
                        <a href="<?php the_permalink(); ?>" class="btn btn-outline btn-sm"><?php esc_html_e( 'Lihat Detail', 'campusos-academic' ); ?></a>
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
            <p class="no-content"><?php esc_html_e( 'Belum ada agenda.', 'campusos-academic' ); ?></p>
        <?php endif; ?>
    </div>
</main>
<?php get_footer(); ?>
