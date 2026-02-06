<?php
/**
 * Archive Template: Publikasi
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
            <div class="publikasi-list">
                <?php while ( have_posts() ) : the_post();
                    $penulis  = get_post_meta( get_the_ID(), 'publikasi_penulis_pub', true );
                    $jenis    = get_post_meta( get_the_ID(), 'publikasi_jenis_pub', true );
                    $tahun    = get_post_meta( get_the_ID(), 'publikasi_tahun_pub', true );
                    $link     = get_post_meta( get_the_ID(), 'publikasi_link_pub', true );
                    $doi      = get_post_meta( get_the_ID(), 'publikasi_doi_pub', true );
                    $kategori = get_post_meta( get_the_ID(), 'publikasi_kategori_pub', true );

                    $jenis_labels = array(
                        'jurnal'    => 'Jurnal',
                        'prosiding' => 'Prosiding',
                        'buku'      => 'Buku',
                        'hki'       => 'HKI',
                    );
                ?>
                <article class="publikasi-item">
                    <div class="publikasi-icon">
                        <?php
                        $icon = 'dashicons-media-text';
                        if ( $jenis === 'jurnal' ) $icon = 'dashicons-book-alt';
                        elseif ( $jenis === 'prosiding' ) $icon = 'dashicons-clipboard';
                        elseif ( $jenis === 'buku' ) $icon = 'dashicons-book';
                        elseif ( $jenis === 'hki' ) $icon = 'dashicons-awards';
                        ?>
                        <span class="dashicons <?php echo esc_attr( $icon ); ?>"></span>
                    </div>
                    <div class="publikasi-content">
                        <h3 class="publikasi-title">
                            <?php if ( $link ) : ?>
                                <a href="<?php echo esc_url( $link ); ?>" target="_blank"><?php the_title(); ?></a>
                            <?php else : ?>
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            <?php endif; ?>
                        </h3>
                        <?php if ( $penulis ) : ?>
                            <p class="publikasi-authors"><?php echo esc_html( $penulis ); ?></p>
                        <?php endif; ?>
                        <div class="publikasi-meta">
                            <?php if ( $jenis && isset( $jenis_labels[ $jenis ] ) ) : ?>
                                <span class="meta-jenis badge badge-<?php echo esc_attr( $jenis ); ?>"><?php echo esc_html( $jenis_labels[ $jenis ] ); ?></span>
                            <?php endif; ?>
                            <?php if ( $tahun ) : ?>
                                <span class="meta-tahun"><?php echo esc_html( $tahun ); ?></span>
                            <?php endif; ?>
                            <?php if ( $kategori ) : ?>
                                <span class="meta-kategori"><?php echo esc_html( ucfirst( $kategori ) ); ?></span>
                            <?php endif; ?>
                        </div>
                        <?php if ( $doi ) : ?>
                            <p class="publikasi-doi">DOI: <a href="https://doi.org/<?php echo esc_attr( $doi ); ?>" target="_blank"><?php echo esc_html( $doi ); ?></a></p>
                        <?php endif; ?>
                    </div>
                    <?php if ( $link ) : ?>
                        <div class="publikasi-action">
                            <a href="<?php echo esc_url( $link ); ?>" class="btn btn-outline btn-sm" target="_blank">
                                <span class="dashicons dashicons-external"></span> <?php esc_html_e( 'Lihat', 'campusos-academic' ); ?>
                            </a>
                        </div>
                    <?php endif; ?>
                </article>
                <?php endwhile; ?>
            </div>
            <?php the_posts_pagination( array(
                'mid_size'  => 2,
                'prev_text' => '&laquo;',
                'next_text' => '&raquo;',
            ) ); ?>
        <?php else : ?>
            <p class="no-content"><?php esc_html_e( 'Belum ada publikasi.', 'campusos-academic' ); ?></p>
        <?php endif; ?>
    </div>
</main>
<?php get_footer(); ?>
