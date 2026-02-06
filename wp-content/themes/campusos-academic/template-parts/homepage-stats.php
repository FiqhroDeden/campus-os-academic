<?php
if ( ! defined( 'ABSPATH' ) ) exit;

$stats_page = get_page_by_path( 'homepage-stats' );
if ( $stats_page && class_exists( '\Elementor\Plugin' ) && \Elementor\Plugin::$instance->documents->get( $stats_page->ID )->is_built_with_elementor() ) {
    echo '<section class="homepage-section homepage-stats">';
    echo \Elementor\Plugin::$instance->frontend->get_builder_content_for_display( $stats_page->ID );
    echo '</section>';
} else {
?>
<section class="homepage-section homepage-stats" style="background:var(--campusos-bg-alt, #f9fafb);padding:3rem 0;">
    <div class="container">
        <h2 style="text-align:center;margin-bottom:1.5rem;"><?php esc_html_e( 'Statistik', 'campusos-academic' ); ?></h2>
        <div class="stat-grid">
            <?php
            $stat_items = [
                [ 'count' => wp_count_posts( 'tenaga_pendidik' )->publish ?? 0, 'label' => __( 'Tenaga Pendidik', 'campusos-academic' ) ],
                [ 'count' => wp_count_posts( 'prestasi' )->publish ?? 0, 'label' => __( 'Prestasi', 'campusos-academic' ) ],
                [ 'count' => wp_count_posts( 'kerjasama' )->publish ?? 0, 'label' => __( 'Kerjasama', 'campusos-academic' ) ],
                [ 'count' => wp_count_posts( 'publikasi' )->publish ?? 0, 'label' => __( 'Publikasi', 'campusos-academic' ) ],
            ];
            foreach ( $stat_items as $item ) :
            ?>
            <div class="stat-item">
                <div class="stat-number"><?php echo (int) $item['count']; ?></div>
                <div class="stat-label"><?php echo esc_html( $item['label'] ); ?></div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php } ?>
