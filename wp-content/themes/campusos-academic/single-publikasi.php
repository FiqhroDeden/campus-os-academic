<?php
/**
 * Single Template: Publikasi
 */
get_header();

while ( have_posts() ) : the_post();
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
<div class="page-hero">
    <div class="container">
        <h1><?php the_title(); ?></h1>
        <div class="breadcrumb">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Beranda', 'campusos-academic' ); ?></a> &raquo;
            <a href="<?php echo esc_url( get_post_type_archive_link( 'publikasi' ) ); ?>"><?php esc_html_e( 'Publikasi', 'campusos-academic' ); ?></a> &raquo;
            <?php the_title(); ?>
        </div>
    </div>
</div>
<main id="primary" class="site-main">
    <div class="container">
        <div class="content-sidebar-wrap">
            <article class="single-publikasi">
                <div class="publikasi-header">
                    <?php if ( $jenis && isset( $jenis_labels[ $jenis ] ) ) : ?>
                        <span class="badge badge-<?php echo esc_attr( $jenis ); ?>"><?php echo esc_html( $jenis_labels[ $jenis ] ); ?></span>
                    <?php endif; ?>
                    <?php if ( $tahun ) : ?>
                        <span class="publikasi-year"><?php echo esc_html( $tahun ); ?></span>
                    <?php endif; ?>
                </div>

                <?php if ( $penulis ) : ?>
                    <div class="publikasi-authors">
                        <h3><?php esc_html_e( 'Penulis', 'campusos-academic' ); ?></h3>
                        <p><?php echo esc_html( $penulis ); ?></p>
                    </div>
                <?php endif; ?>

                <div class="publikasi-details">
                    <h3><?php esc_html_e( 'Detail Publikasi', 'campusos-academic' ); ?></h3>
                    <table class="publikasi-table">
                        <?php if ( $jenis && isset( $jenis_labels[ $jenis ] ) ) : ?>
                            <tr>
                                <th><?php esc_html_e( 'Jenis', 'campusos-academic' ); ?></th>
                                <td><?php echo esc_html( $jenis_labels[ $jenis ] ); ?></td>
                            </tr>
                        <?php endif; ?>
                        <?php if ( $tahun ) : ?>
                            <tr>
                                <th><?php esc_html_e( 'Tahun', 'campusos-academic' ); ?></th>
                                <td><?php echo esc_html( $tahun ); ?></td>
                            </tr>
                        <?php endif; ?>
                        <?php if ( $kategori ) : ?>
                            <tr>
                                <th><?php esc_html_e( 'Kategori', 'campusos-academic' ); ?></th>
                                <td><?php echo esc_html( ucfirst( $kategori ) ); ?></td>
                            </tr>
                        <?php endif; ?>
                        <?php if ( $doi ) : ?>
                            <tr>
                                <th><?php esc_html_e( 'DOI', 'campusos-academic' ); ?></th>
                                <td><a href="https://doi.org/<?php echo esc_attr( $doi ); ?>" target="_blank"><?php echo esc_html( $doi ); ?></a></td>
                            </tr>
                        <?php endif; ?>
                        <?php if ( $link ) : ?>
                            <tr>
                                <th><?php esc_html_e( 'Link', 'campusos-academic' ); ?></th>
                                <td><a href="<?php echo esc_url( $link ); ?>" target="_blank"><?php echo esc_html( $link ); ?></a></td>
                            </tr>
                        <?php endif; ?>
                    </table>
                </div>

                <?php if ( get_the_content() ) : ?>
                    <div class="publikasi-abstract">
                        <h3><?php esc_html_e( 'Abstrak', 'campusos-academic' ); ?></h3>
                        <div class="entry-content">
                            <?php the_content(); ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ( $link ) : ?>
                    <div class="publikasi-actions">
                        <a href="<?php echo esc_url( $link ); ?>" class="btn btn-primary" target="_blank">
                            <span class="dashicons dashicons-external"></span> <?php esc_html_e( 'Lihat Publikasi', 'campusos-academic' ); ?>
                        </a>
                    </div>
                <?php endif; ?>

                <?php campusos_social_share(); ?>
            </article>

            <aside class="widget-area">
                <?php
                // Related Publikasi
                $related_args = array(
                    'post_type'      => 'publikasi',
                    'posts_per_page' => 5,
                    'post__not_in'   => array( get_the_ID() ),
                    'orderby'        => 'date',
                    'order'          => 'DESC',
                );
                if ( $jenis ) {
                    $related_args['meta_query'] = array(
                        array(
                            'key'   => 'publikasi_jenis_pub',
                            'value' => $jenis,
                        ),
                    );
                }
                $related = new WP_Query( $related_args );
                if ( $related->have_posts() ) :
                ?>
                    <div class="widget">
                        <h3 class="widget-title"><?php esc_html_e( 'Publikasi Terkait', 'campusos-academic' ); ?></h3>
                        <ul class="related-list">
                            <?php while ( $related->have_posts() ) : $related->the_post();
                                $rel_tahun = get_post_meta( get_the_ID(), 'publikasi_tahun_pub', true );
                            ?>
                                <li>
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                    <?php if ( $rel_tahun ) : ?>
                                        <span class="related-date"><?php echo esc_html( $rel_tahun ); ?></span>
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
