<?php
/* Template Name: Publikasi */
get_header();

$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
$query = new WP_Query( array(
    'post_type'      => 'publikasi',
    'posts_per_page' => 15,
    'paged'          => $paged,
    'orderby'        => 'date',
    'order'          => 'DESC',
) );

$jenis_labels = array(
    'jurnal'    => 'Jurnal',
    'prosiding' => 'Prosiding',
    'buku'      => 'Buku',
    'hki'       => 'HKI',
);
?>
<div class="page-hero"><div class="container"><h1><?php the_title(); ?></h1></div></div>
<main id="primary" class="site-main">
    <div class="container">
        <?php
        $is_elementor = class_exists('\Elementor\Plugin') && \Elementor\Plugin::$instance->documents->get(get_the_ID()) && \Elementor\Plugin::$instance->documents->get(get_the_ID())->is_built_with_elementor();
        if ($is_elementor) :
            while (have_posts()) : the_post();
        ?>
            <div class="entry-content"><?php the_content(); ?></div>
        <?php
            endwhile;
        else :
        ?>
        <?php if ( $query->have_posts() ) : ?>
            <div class="publikasi-list">
                <?php while ( $query->have_posts() ) : $query->the_post();
                    $penulis  = get_post_meta( get_the_ID(), 'publikasi_penulis_pub', true );
                    $jenis    = get_post_meta( get_the_ID(), 'publikasi_jenis_pub', true );
                    $tahun    = get_post_meta( get_the_ID(), 'publikasi_tahun_pub', true );
                    $link     = get_post_meta( get_the_ID(), 'publikasi_link_pub', true );
                    $doi      = get_post_meta( get_the_ID(), 'publikasi_doi_pub', true );
                    $kategori = get_post_meta( get_the_ID(), 'publikasi_kategori_pub', true );
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
                                <span class="dashicons dashicons-external"></span> <?php esc_html_e( 'Lihat', 'unpatti-academic' ); ?>
                            </a>
                        </div>
                    <?php endif; ?>
                </article>
                <?php endwhile; wp_reset_postdata(); ?>
            </div>
            <?php
            $big = 999999999;
            echo '<div class="nav-links">';
            echo paginate_links( array(
                'base'      => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
                'format'    => '?paged=%#%',
                'current'   => max( 1, $paged ),
                'total'     => $query->max_num_pages,
                'prev_text' => '&laquo;',
                'next_text' => '&raquo;',
            ) );
            echo '</div>';
            ?>
        <?php else : ?>
            <p class="no-content"><?php esc_html_e( 'Belum ada publikasi.', 'unpatti-academic' ); ?></p>
        <?php endif; ?>
        <?php endif; ?>
    </div>
</main>
<?php get_footer(); ?>
