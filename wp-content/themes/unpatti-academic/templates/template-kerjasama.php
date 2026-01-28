<?php
/* Template Name: Kerjasama */
get_header();
$query = new WP_Query( array(
    'post_type'      => 'kerjasama',
    'posts_per_page' => -1,
    'orderby'        => 'date',
    'order'          => 'DESC',
) );
?>
<div class="page-hero"><div class="container"><h1><?php the_title(); ?></h1></div></div>
<main id="primary" class="site-main">
    <div class="container">
        <?php if ( $query->have_posts() ) : ?>
            <div class="profile-grid">
                <?php while ( $query->have_posts() ) : $query->the_post(); ?>
                    <div class="card">
                        <div class="card-body">
                            <?php if ( has_post_thumbnail() ) : ?>
                                <div class="partner-logo" style="margin-bottom:1rem;"><?php the_post_thumbnail( 'medium' ); ?></div>
                            <?php endif; ?>
                            <h3 class="card-title"><?php the_title(); ?></h3>
                            <?php
                            $jenis       = get_post_meta( get_the_ID(), '_jenis', true );
                            $tanggal_mulai  = get_post_meta( get_the_ID(), '_tanggal_mulai', true );
                            $tanggal_selesai = get_post_meta( get_the_ID(), '_tanggal_selesai', true );
                            ?>
                            <?php if ( $jenis ) : ?><p class="card-text"><strong>Jenis:</strong> <?php echo esc_html( $jenis ); ?></p><?php endif; ?>
                            <?php if ( $tanggal_mulai || $tanggal_selesai ) : ?>
                                <p class="card-text"><?php echo esc_html( $tanggal_mulai ); ?> &mdash; <?php echo esc_html( $tanggal_selesai ); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endwhile; wp_reset_postdata(); ?>
            </div>
        <?php else : ?>
            <p>Belum ada data kerjasama.</p>
        <?php endif; ?>
    </div>
</main>
<?php get_footer(); ?>
