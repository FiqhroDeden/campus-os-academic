<?php get_header(); ?>
<div class="page-hero">
    <div class="container">
        <h1><?php esc_html_e( '404 - Halaman Tidak Ditemukan', 'unpatti-academic' ); ?></h1>
    </div>
</div>
<main id="primary" class="site-main">
    <div class="container" style="text-align:center;padding:3rem 0;">
        <p><?php esc_html_e( 'Halaman yang Anda cari tidak ditemukan.', 'unpatti-academic' ); ?></p>
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn-primary"><?php esc_html_e( 'Kembali ke Beranda', 'unpatti-academic' ); ?></a>
    </div>
</main>
<?php get_footer(); ?>
