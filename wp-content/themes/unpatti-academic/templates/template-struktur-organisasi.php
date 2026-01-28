<?php
/* Template Name: Struktur Organisasi */
get_header();
$bagan_id  = get_post_meta( get_the_ID(), '_bagan_organisasi', true );
$deskripsi = get_post_meta( get_the_ID(), '_deskripsi_struktur', true );
?>
<div class="page-hero"><div class="container"><h1><?php the_title(); ?></h1></div></div>
<main id="primary" class="site-main">
    <div class="container">
        <?php if ( $bagan_id ) : ?>
            <div class="struktur-bagan" style="text-align:center;margin-bottom:2rem;">
                <?php echo wp_get_attachment_image( $bagan_id, 'full' ); ?>
            </div>
        <?php endif; ?>

        <?php if ( $deskripsi ) : ?>
            <div class="entry-content">
                <p><?php echo esc_html( $deskripsi ); ?></p>
            </div>
        <?php endif; ?>
    </div>
</main>
<?php get_footer(); ?>
