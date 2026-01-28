<?php
/* Template Name: Sambutan Pimpinan */
get_header();
$foto_id  = get_post_meta( get_the_ID(), '_sambutan_foto', true );
$nama     = get_post_meta( get_the_ID(), '_sambutan_nama', true );
$jabatan  = get_post_meta( get_the_ID(), '_sambutan_jabatan', true );
$isi      = get_post_meta( get_the_ID(), '_sambutan_isi', true );
?>
<div class="page-hero"><div class="container"><h1><?php the_title(); ?></h1></div></div>
<main id="primary" class="site-main">
    <div class="container">
        <div class="sambutan-grid">
            <div class="sambutan-foto">
                <?php if ( $foto_id ) : ?>
                    <?php echo wp_get_attachment_image( $foto_id, 'medium_large' ); ?>
                <?php endif; ?>
                <?php if ( $nama ) : ?>
                    <h3><?php echo esc_html( $nama ); ?></h3>
                <?php endif; ?>
                <?php if ( $jabatan ) : ?>
                    <p class="sambutan-jabatan"><?php echo esc_html( $jabatan ); ?></p>
                <?php endif; ?>
            </div>
            <div class="sambutan-text">
                <?php if ( $isi ) : ?>
                    <div class="entry-content"><?php echo wp_kses_post( $isi ); ?></div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>
<?php get_footer(); ?>
