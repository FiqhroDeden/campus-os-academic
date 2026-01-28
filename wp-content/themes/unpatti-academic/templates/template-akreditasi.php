<?php
/* Template Name: Akreditasi */
get_header();
$status     = get_post_meta( get_the_ID(), '_akreditasi_status', true );
$no_sk      = get_post_meta( get_the_ID(), '_akreditasi_no_sk', true );
$tanggal    = get_post_meta( get_the_ID(), '_akreditasi_tanggal', true );
$sertifikat = get_post_meta( get_the_ID(), '_akreditasi_sertifikat', true );
$deskripsi  = get_post_meta( get_the_ID(), '_akreditasi_deskripsi', true );
?>
<div class="page-hero"><div class="container"><h1><?php the_title(); ?></h1></div></div>
<main id="primary" class="site-main">
    <div class="container">
        <?php if ( $status ) : ?>
            <div class="akreditasi-badge"><?php echo esc_html( $status ); ?></div>
        <?php endif; ?>

        <table class="ukt-table" style="margin-bottom:2rem;">
            <tbody>
                <?php if ( $no_sk ) : ?>
                    <tr><th>No. SK</th><td><?php echo esc_html( $no_sk ); ?></td></tr>
                <?php endif; ?>
                <?php if ( $tanggal ) : ?>
                    <tr><th>Tanggal</th><td><?php echo esc_html( $tanggal ); ?></td></tr>
                <?php endif; ?>
                <?php if ( $status ) : ?>
                    <tr><th>Status</th><td><?php echo esc_html( $status ); ?></td></tr>
                <?php endif; ?>
            </tbody>
        </table>

        <?php if ( $sertifikat ) : ?>
            <p><a href="<?php echo esc_url( wp_get_attachment_url( $sertifikat ) ); ?>" class="btn btn-primary" target="_blank" rel="noopener">Download Sertifikat</a></p>
        <?php endif; ?>

        <?php if ( $deskripsi ) : ?>
            <div class="entry-content"><?php echo wp_kses_post( $deskripsi ); ?></div>
        <?php endif; ?>
    </div>
</main>
<?php get_footer(); ?>
