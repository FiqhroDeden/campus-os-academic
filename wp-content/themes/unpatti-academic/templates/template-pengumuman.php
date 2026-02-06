<?php
/* Template Name: Pengumuman */
get_header();

$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
$query = new WP_Query( array(
    'post_type'      => 'pengumuman',
    'posts_per_page' => 10,
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
            <div class="pengumuman-list">
                <?php while ( $query->have_posts() ) : $query->the_post();
                    $tanggal_berlaku = get_post_meta( get_the_ID(), 'pengumuman_tanggal_berlaku', true );
                    $file_lampiran   = get_post_meta( get_the_ID(), 'pengumuman_file_lampiran', true );
                ?>
                <article class="pengumuman-item">
                    <div class="pengumuman-date">
                        <span class="date-day"><?php echo get_the_date( 'j' ); ?></span>
                        <span class="date-month"><?php echo get_the_date( 'M Y' ); ?></span>
                    </div>
                    <div class="pengumuman-content">
                        <h3 class="pengumuman-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                        <?php if ( $tanggal_berlaku ) : ?>
                            <p class="pengumuman-meta">
                                <span class="dashicons dashicons-clock"></span>
                                <?php esc_html_e( 'Berlaku sampai:', 'unpatti-academic' ); ?> <?php echo esc_html( date_i18n( 'j F Y', strtotime( $tanggal_berlaku ) ) ); ?>
                            </p>
                        <?php endif; ?>
                        <?php if ( get_the_excerpt() ) : ?>
                            <p class="pengumuman-excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 30 ) ); ?></p>
                        <?php endif; ?>
                        <div class="pengumuman-actions">
                            <a href="<?php the_permalink(); ?>" class="btn btn-outline btn-sm"><?php esc_html_e( 'Baca Selengkapnya', 'unpatti-academic' ); ?></a>
                            <?php if ( $file_lampiran ) : ?>
                                <a href="<?php echo esc_url( wp_get_attachment_url( $file_lampiran ) ); ?>" class="btn btn-primary btn-sm" target="_blank">
                                    <span class="dashicons dashicons-download"></span> <?php esc_html_e( 'Download', 'unpatti-academic' ); ?>
                                </a>
                            <?php endif; ?>
                        </div>
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
            <p class="no-content"><?php esc_html_e( 'Belum ada pengumuman.', 'unpatti-academic' ); ?></p>
        <?php endif; ?>
        <?php endif; ?>
    </div>
</main>
<?php get_footer(); ?>
