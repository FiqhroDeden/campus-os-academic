<?php
/* Template Name: Agenda */
get_header();

$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
$query = new WP_Query( array(
    'post_type'      => 'agenda',
    'posts_per_page' => 9,
    'paged'          => $paged,
    'meta_key'       => 'agenda_tanggal_mulai_agenda',
    'orderby'        => 'meta_value',
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
            <div class="agenda-grid">
                <?php while ( $query->have_posts() ) : $query->the_post();
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
                        <a href="<?php the_permalink(); ?>" class="btn btn-outline btn-sm"><?php esc_html_e( 'Lihat Detail', 'campusos-academic' ); ?></a>
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
            <p class="no-content"><?php esc_html_e( 'Belum ada agenda.', 'campusos-academic' ); ?></p>
        <?php endif; ?>
        <?php endif; ?>
    </div>
</main>
<?php get_footer(); ?>
