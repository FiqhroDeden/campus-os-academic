<?php
/**
 * Single Template: Pengumuman
 */
get_header();

while ( have_posts() ) : the_post();
    $tanggal_berlaku = get_post_meta( get_the_ID(), 'pengumuman_tanggal_berlaku', true );
    $file_lampiran   = get_post_meta( get_the_ID(), 'pengumuman_file_lampiran', true );
?>
<div class="page-hero">
    <div class="container">
        <h1><?php the_title(); ?></h1>
        <div class="breadcrumb">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Beranda', 'unpatti-academic' ); ?></a> &raquo;
            <a href="<?php echo esc_url( get_post_type_archive_link( 'pengumuman' ) ); ?>"><?php esc_html_e( 'Pengumuman', 'unpatti-academic' ); ?></a> &raquo;
            <?php the_title(); ?>
        </div>
    </div>
</div>
<main id="primary" class="site-main">
    <div class="container">
        <div class="content-sidebar-wrap">
            <article class="single-pengumuman">
                <?php if ( has_post_thumbnail() ) : ?>
                    <div class="entry-thumbnail">
                        <?php the_post_thumbnail( 'large' ); ?>
                    </div>
                <?php endif; ?>

                <div class="entry-meta">
                    <span class="posted-on">
                        <span class="dashicons dashicons-calendar-alt"></span> <?php echo get_the_date(); ?>
                    </span>
                    <?php if ( $tanggal_berlaku ) : ?>
                        <span class="valid-until">
                            <span class="dashicons dashicons-clock"></span>
                            <?php esc_html_e( 'Berlaku sampai:', 'unpatti-academic' ); ?> <?php echo esc_html( date_i18n( 'j F Y', strtotime( $tanggal_berlaku ) ) ); ?>
                        </span>
                    <?php endif; ?>
                </div>

                <div class="entry-content">
                    <?php the_content(); ?>
                </div>

                <?php if ( $file_lampiran ) :
                    $file_url  = wp_get_attachment_url( $file_lampiran );
                    $file_name = basename( get_attached_file( $file_lampiran ) );
                ?>
                    <div class="pengumuman-attachment">
                        <h3><?php esc_html_e( 'Lampiran', 'unpatti-academic' ); ?></h3>
                        <div class="attachment-item">
                            <span class="dashicons dashicons-media-document"></span>
                            <span class="attachment-name"><?php echo esc_html( $file_name ); ?></span>
                            <a href="<?php echo esc_url( $file_url ); ?>" class="btn btn-primary btn-sm" target="_blank" download>
                                <span class="dashicons dashicons-download"></span> <?php esc_html_e( 'Download', 'unpatti-academic' ); ?>
                            </a>
                        </div>
                    </div>
                <?php endif; ?>

                <?php unpatti_social_share(); ?>
            </article>

            <aside class="widget-area">
                <?php
                // Related Pengumuman
                $related = new WP_Query( array(
                    'post_type'      => 'pengumuman',
                    'posts_per_page' => 5,
                    'post__not_in'   => array( get_the_ID() ),
                    'orderby'        => 'date',
                    'order'          => 'DESC',
                ) );
                if ( $related->have_posts() ) :
                ?>
                    <div class="widget">
                        <h3 class="widget-title"><?php esc_html_e( 'Pengumuman Lainnya', 'unpatti-academic' ); ?></h3>
                        <ul class="related-list">
                            <?php while ( $related->have_posts() ) : $related->the_post(); ?>
                                <li>
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                    <span class="related-date"><?php echo get_the_date( 'j M Y' ); ?></span>
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
