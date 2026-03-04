<?php get_header(); ?>
<div class="page-hero">
    <div class="container">
        <h1><?php esc_html_e( '404 - Halaman Tidak Ditemukan', 'campusos-academic' ); ?></h1>
    </div>
</div>
<main id="primary" class="site-main">
    <div class="container">
        <div class="error-404-content">
            <div class="error-404-number">404</div>
            <h2><?php esc_html_e( 'Halaman yang Anda cari tidak ditemukan', 'campusos-academic' ); ?></h2>
            <p><?php esc_html_e( 'Mungkin halaman telah dipindahkan atau alamat yang Anda masukkan salah. Silakan coba pencarian atau kunjungi halaman lain.', 'campusos-academic' ); ?></p>
            <form role="search" method="get" class="error-404-search" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                <input type="search" name="s" placeholder="<?php esc_attr_e( 'Cari di situs ini...', 'campusos-academic' ); ?>" />
                <button type="submit" class="btn btn-primary"><?php esc_html_e( 'Cari', 'campusos-academic' ); ?></button>
            </form>
            <div class="error-404-links">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn-outline"><?php esc_html_e( 'Beranda', 'campusos-academic' ); ?></a>
                <a href="<?php echo esc_url( home_url( '/pengumuman/' ) ); ?>" class="btn btn-outline"><?php esc_html_e( 'Pengumuman', 'campusos-academic' ); ?></a>
            </div>
        </div>
    </div>
</main>
<?php get_footer(); ?>
