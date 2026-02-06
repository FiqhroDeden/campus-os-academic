<?php
/**
 * Single Template: Galeri
 */
get_header();

while ( have_posts() ) : the_post();
    $kategori = get_post_meta( get_the_ID(), 'galeri_kategori_galeri', true );
    $tanggal  = get_post_meta( get_the_ID(), 'galeri_tanggal_galeri', true );
?>
<div class="page-hero">
    <div class="container">
        <h1><?php the_title(); ?></h1>
        <div class="breadcrumb">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Beranda', 'campusos-academic' ); ?></a> &raquo;
            <a href="<?php echo esc_url( get_post_type_archive_link( 'galeri' ) ); ?>"><?php esc_html_e( 'Galeri', 'campusos-academic' ); ?></a> &raquo;
            <?php the_title(); ?>
        </div>
    </div>
</div>
<main id="primary" class="site-main">
    <div class="container">
        <article class="single-galeri">
            <div class="galeri-meta">
                <?php if ( $kategori ) : ?>
                    <span class="galeri-category-tag"><?php echo esc_html( $kategori ); ?></span>
                <?php endif; ?>
                <?php if ( $tanggal ) : ?>
                    <span class="galeri-date-tag">
                        <span class="dashicons dashicons-calendar-alt"></span>
                        <?php echo esc_html( date_i18n( 'j F Y', strtotime( $tanggal ) ) ); ?>
                    </span>
                <?php endif; ?>
            </div>

            <?php if ( has_post_thumbnail() ) : ?>
                <div class="galeri-featured">
                    <?php the_post_thumbnail( 'full' ); ?>
                </div>
            <?php endif; ?>

            <div class="entry-content galeri-description">
                <?php the_content(); ?>
            </div>

            <?php
            // Get gallery images from content
            $gallery = get_post_gallery( get_the_ID(), false );
            if ( $gallery && ! empty( $gallery['ids'] ) ) :
                $image_ids = explode( ',', $gallery['ids'] );
            ?>
                <div class="galeri-lightbox-grid">
                    <?php foreach ( $image_ids as $image_id ) :
                        $full_url  = wp_get_attachment_image_url( $image_id, 'full' );
                        $thumb_url = wp_get_attachment_image_url( $image_id, 'medium' );
                        $alt       = get_post_meta( $image_id, '_wp_attachment_image_alt', true );
                    ?>
                        <a href="<?php echo esc_url( $full_url ); ?>" class="galeri-lightbox-item" data-lightbox="gallery">
                            <img src="<?php echo esc_url( $thumb_url ); ?>" alt="<?php echo esc_attr( $alt ); ?>">
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php campusos_social_share(); ?>

            <?php
            // Related Galeri
            $related = new WP_Query( array(
                'post_type'      => 'galeri',
                'posts_per_page' => 4,
                'post__not_in'   => array( get_the_ID() ),
                'orderby'        => 'date',
                'order'          => 'DESC',
            ) );
            if ( $related->have_posts() ) :
            ?>
                <div class="related-galeri">
                    <h3><?php esc_html_e( 'Galeri Lainnya', 'campusos-academic' ); ?></h3>
                    <div class="galeri-grid galeri-grid-small">
                        <?php while ( $related->have_posts() ) : $related->the_post(); ?>
                            <a href="<?php the_permalink(); ?>" class="galeri-item">
                                <?php if ( has_post_thumbnail() ) : ?>
                                    <?php the_post_thumbnail( 'medium', array( 'class' => 'galeri-img' ) ); ?>
                                <?php endif; ?>
                                <div class="galeri-overlay">
                                    <span class="galeri-title"><?php the_title(); ?></span>
                                </div>
                            </a>
                        <?php endwhile; wp_reset_postdata(); ?>
                    </div>
                </div>
            <?php endif; ?>
        </article>
    </div>
</main>
<?php
endwhile;
get_footer();
?>
