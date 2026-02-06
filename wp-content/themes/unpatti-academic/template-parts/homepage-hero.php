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
    <div class="page-hero">
        <div class="container">
            <h1><?php echo esc_html( get_bloginfo( 'name' ) ); ?></h1>
            <p><?php echo esc_html( get_bloginfo( 'description' ) ); ?></p>
        </div>
    </div>
    <?php } ?>
</section>
