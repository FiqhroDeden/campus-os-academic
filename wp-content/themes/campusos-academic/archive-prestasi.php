<?php
/**
 * Archive Template: Prestasi
 */
get_header();

$tingkat_labels = [
    'lokal'         => 'Lokal',
    'nasional'      => 'Nasional',
    'internasional' => 'Internasional',
];
$kategori_labels = [
    'mahasiswa' => 'Mahasiswa',
    'dosen'     => 'Dosen',
];
?>
<div class="page-hero">
    <div class="container">
        <h1><?php post_type_archive_title(); ?></h1>
    </div>
</div>
<main id="primary" class="site-main">
    <div class="container">
        <?php if ( have_posts() ) : ?>
            <div class="prestasi-grid">
                <?php while ( have_posts() ) : the_post();
                    $tanggal   = get_post_meta( get_the_ID(), 'prestasi_tanggal_prestasi', true );
                    $kategori  = get_post_meta( get_the_ID(), 'prestasi_kategori_prestasi', true );
                    $tingkat   = get_post_meta( get_the_ID(), 'prestasi_tingkat_prestasi', true );
                    $peraih    = get_post_meta( get_the_ID(), 'prestasi_nama_peraih', true );
                ?>
                <article class="prestasi-card card">
                    <?php if ( has_post_thumbnail() ) : ?>
                        <?php the_post_thumbnail( 'medium_large', array( 'class' => 'card-img' ) ); ?>
                    <?php endif; ?>
                    <div class="card-body">
                        <div class="prestasi-badges">
                            <?php if ( $tingkat && isset( $tingkat_labels[ $tingkat ] ) ) : ?>
                                <span class="badge badge-tingkat badge-<?php echo esc_attr( $tingkat ); ?>"><?php echo esc_html( $tingkat_labels[ $tingkat ] ); ?></span>
                            <?php endif; ?>
                            <?php if ( $kategori && isset( $kategori_labels[ $kategori ] ) ) : ?>
                                <span class="badge badge-kategori"><?php echo esc_html( $kategori_labels[ $kategori ] ); ?></span>
                            <?php endif; ?>
                        </div>
                        <h3 class="card-title"><?php the_title(); ?></h3>
                        <?php if ( $peraih ) : ?>
                            <p class="prestasi-peraih"><strong><?php echo esc_html( $peraih ); ?></strong></p>
                        <?php endif; ?>
                        <?php if ( $tanggal ) : ?>
                            <p class="prestasi-tanggal">
                                <span class="dashicons dashicons-calendar-alt"></span>
                                <?php echo esc_html( date_i18n( 'j F Y', strtotime( $tanggal ) ) ); ?>
                            </p>
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
            <p class="no-content"><?php esc_html_e( 'Belum ada data prestasi.', 'campusos-academic' ); ?></p>
        <?php endif; ?>
    </div>
</main>
<?php get_footer(); ?>
