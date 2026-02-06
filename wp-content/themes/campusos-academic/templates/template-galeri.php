<?php
/* Template Name: Galeri */
get_header();

$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
$query = new WP_Query( array(
    'post_type'      => 'galeri',
    'posts_per_page' => 12,
    'paged'          => $paged,
    'orderby'        => 'date',
    'order'          => 'DESC',
) );
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
            <div class="galeri-grid">
                <?php while ( $query->have_posts() ) : $query->the_post();
                    $kategori = get_post_meta( get_the_ID(), 'galeri_kategori_galeri', true );
                    $tanggal  = get_post_meta( get_the_ID(), 'galeri_tanggal_galeri', true );
                ?>
                <article class="galeri-item">
                    <a href="<?php the_permalink(); ?>" class="galeri-link">
                        <?php if ( has_post_thumbnail() ) : ?>
                            <?php the_post_thumbnail( 'medium_large', array( 'class' => 'galeri-img' ) ); ?>
                        <?php else : ?>
                            <div class="galeri-placeholder">
                                <span class="dashicons dashicons-format-gallery"></span>
                            </div>
                        <?php endif; ?>
                        <div class="galeri-overlay">
                            <h3 class="galeri-title"><?php the_title(); ?></h3>
                            <?php if ( $kategori ) : ?>
                                <span class="galeri-category"><?php echo esc_html( $kategori ); ?></span>
                            <?php endif; ?>
                        </div>
                    </a>
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
            <p class="no-content"><?php esc_html_e( 'Belum ada galeri foto.', 'campusos-academic' ); ?></p>
        <?php endif; ?>
        <?php endif; ?>
    </div>
</main>
<?php get_footer(); ?>
