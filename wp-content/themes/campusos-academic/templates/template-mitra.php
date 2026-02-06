<?php
/* Template Name: Mitra Industri */
get_header();

$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
$query = new WP_Query( array(
    'post_type'      => 'mitra_industri',
    'posts_per_page' => 12,
    'paged'          => $paged,
    'orderby'        => 'title',
    'order'          => 'ASC',
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
            <div class="mitra-grid">
                <?php while ( $query->have_posts() ) : $query->the_post();
                    $logo  = get_post_meta( get_the_ID(), 'mitra_industri_logo_mitra_di', true );
                    $jenis = get_post_meta( get_the_ID(), 'mitra_industri_jenis_kerjasama_di', true );
                ?>
                <article class="mitra-card card">
                    <div class="mitra-logo">
                        <?php if ( $logo ) : ?>
                            <img src="<?php echo esc_url( wp_get_attachment_image_url( $logo, 'medium' ) ); ?>" alt="<?php the_title_attribute(); ?>">
                        <?php elseif ( has_post_thumbnail() ) : ?>
                            <?php the_post_thumbnail( 'medium' ); ?>
                        <?php else : ?>
                            <div class="mitra-placeholder">
                                <span class="dashicons dashicons-store"></span>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="card-body">
                        <h3 class="card-title"><?php the_title(); ?></h3>
                        <?php if ( $jenis ) : ?>
                            <p class="mitra-jenis"><?php echo esc_html( $jenis ); ?></p>
                        <?php endif; ?>
                    </div>
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
            <p class="no-content"><?php esc_html_e( 'Belum ada data mitra industri.', 'unpatti-academic' ); ?></p>
        <?php endif; ?>
        <?php endif; ?>
    </div>
</main>
<?php get_footer(); ?>
