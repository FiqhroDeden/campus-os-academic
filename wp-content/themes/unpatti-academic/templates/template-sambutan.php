<?php
/* Template Name: Sambutan Pimpinan */
get_header();

// Get pimpinan data from settings
$pimpinan = get_option( 'unpatti_pimpinan_settings', [] );

$foto_id  = ! empty( $pimpinan['foto_id'] ) ? $pimpinan['foto_id'] : '';
$nama     = '';
$jabatan  = ! empty( $pimpinan['jabatan'] ) ? $pimpinan['jabatan'] : '';
$periode  = ! empty( $pimpinan['periode'] ) ? $pimpinan['periode'] : '';
$isi      = ! empty( $pimpinan['sambutan'] ) ? $pimpinan['sambutan'] : '';
$bio      = ! empty( $pimpinan['bio'] ) ? $pimpinan['bio'] : '';

// Build full name with titles
if ( ! empty( $pimpinan['nama'] ) ) {
    $nama_parts = [];
    if ( ! empty( $pimpinan['gelar_depan'] ) ) {
        $nama_parts[] = $pimpinan['gelar_depan'];
    }
    $nama_parts[] = $pimpinan['nama'];
    if ( ! empty( $pimpinan['gelar_belakang'] ) ) {
        $nama_parts[] = $pimpinan['gelar_belakang'];
    }
    $nama = implode( ' ', $nama_parts );
}

$use_pimpinan_data = ! empty( $nama );

// Fall back to page meta fields if no pimpinan settings
if ( ! $use_pimpinan_data ) {
    $page_id  = get_the_ID();
    $foto_id  = get_post_meta( $page_id, '_sambutan_foto', true );
    $nama     = get_post_meta( $page_id, '_sambutan_nama', true );
    $jabatan  = get_post_meta( $page_id, '_sambutan_jabatan', true );
    $isi      = get_post_meta( $page_id, '_sambutan_isi', true );
}

// Use page content if no isi from settings
if ( empty( $isi ) ) {
    while ( have_posts() ) : the_post();
        $isi = get_the_content();
    endwhile;
    rewind_posts();
}
?>
<div class="page-hero">
    <div class="container">
        <h1><?php the_title(); ?></h1>
        <p class="page-hero-subtitle"><?php esc_html_e( 'Selamat datang di Program Studi Ilmu Komputer UNPATTI', 'unpatti-academic' ); ?></p>
    </div>
</div>
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
        <div class="sambutan-grid">
            <div class="sambutan-foto">
                <?php if ( $foto_id ) : ?>
                    <?php echo wp_get_attachment_image( $foto_id, 'medium_large' ); ?>
                <?php else : ?>
                    <div class="sambutan-foto-placeholder"><span class="dashicons dashicons-admin-users"></span></div>
                <?php endif; ?>
                <?php if ( $nama ) : ?>
                    <h3><?php echo esc_html( $nama ); ?></h3>
                <?php endif; ?>
                <?php if ( $jabatan ) : ?>
                    <p class="sambutan-jabatan"><?php echo esc_html( $jabatan ); ?></p>
                <?php endif; ?>
                <?php if ( $periode ) : ?>
                    <p class="sambutan-periode">Periode <?php echo esc_html( $periode ); ?></p>
                <?php endif; ?>
            </div>
            <div class="sambutan-text">
                <?php if ( $isi ) : ?>
                    <div class="entry-content"><?php echo wp_kses_post( wpautop( $isi ) ); ?></div>
                <?php endif; ?>
            </div>
        </div>


        <?php endif; ?>
    </div>
</main>
<?php get_footer(); ?>
