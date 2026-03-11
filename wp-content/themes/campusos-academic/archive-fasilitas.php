<?php
/**
 * Archive Template: Fasilitas
 */
get_header();
?>
<div class="page-hero">
    <div class="container">
        <h1><?php post_type_archive_title(); ?></h1>
    </div>
</div>
<main id="primary" class="site-main">
    <div class="container">
        <?php if ( have_posts() ) : ?>
            <div class="profile-grid">
                <?php while ( have_posts() ) : the_post();
                    $kapasitas = get_post_meta( get_the_ID(), '_kapasitas', true );
                    $lokasi    = get_post_meta( get_the_ID(), '_lokasi', true );
                ?>
                <div class="card">
                    <?php if ( has_post_thumbnail() ) : ?>
                        <?php the_post_thumbnail( 'medium_large', array( 'class' => 'card-img' ) ); ?>
                    <?php else : ?>
                        <div class="card-img-placeholder"><span class="dashicons dashicons-building"></span></div>
                    <?php endif; ?>
                    <div class="card-body">
                        <h3 class="card-title"><?php the_title(); ?></h3>
                        <p class="card-text"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 20 ) ); ?></p>
                        <?php if ( $kapasitas ) : ?>
                            <p class="card-text"><strong>Kapasitas:</strong> <?php echo esc_html( $kapasitas ); ?></p>
                        <?php endif; ?>
                        <?php if ( $lokasi ) : ?>
                            <p class="card-text"><strong>Lokasi:</strong> <?php echo esc_html( $lokasi ); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
            <?php the_posts_pagination( array(
                'mid_size'  => 2,
                'prev_text' => '&laquo;',
                'next_text' => '&raquo;',
            ) ); ?>
        <?php else : ?>
            <p class="no-content"><?php esc_html_e( 'Belum ada data fasilitas.', 'campusos-academic' ); ?></p>
        <?php endif; ?>
    </div>
</main>
<?php get_footer(); ?>
