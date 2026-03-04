<?php
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<section class="homepage-section homepage-hero">
    <?php
    $hero_page = get_page_by_path( 'homepage-hero' );
    if ( $hero_page && class_exists( '\Elementor\Plugin' ) && \Elementor\Plugin::$instance->documents->get( $hero_page->ID )->is_built_with_elementor() ) {
        echo \Elementor\Plugin::$instance->frontend->get_builder_content_for_display( $hero_page->ID );
    } else {
    ?>
    <div class="hero-fallback">
        <div class="container">
            <p class="hero-subtitle"><?php echo esc_html( campusos_get_institution_name() ); ?></p>
            <h1 class="hero-title"><?php echo esc_html( get_bloginfo( 'description' ) ); ?></h1>
            <p class="hero-desc"><?php esc_html_e( 'Membangun generasi unggul, kompeten, dan berakhlak mulia untuk kemajuan bangsa.', 'campusos-academic' ); ?></p>
            <div class="hero-actions">
                <a href="<?php echo esc_url( home_url( '/penerimaan/' ) ); ?>" class="btn btn-primary btn-lg"><?php esc_html_e( 'Daftar Sekarang', 'campusos-academic' ); ?></a>
                <a href="<?php echo esc_url( home_url( '/profil/' ) ); ?>" class="btn btn-outline-white btn-lg"><?php esc_html_e( 'Profil', 'campusos-academic' ); ?></a>
            </div>
        </div>
    </div>
    <?php } ?>
</section>
