<?php
if ( ! defined( 'ABSPATH' ) ) exit;

$stats_page = get_page_by_path( 'homepage-stats' );
$stats_elementor_doc = $stats_page && class_exists( '\Elementor\Plugin' ) ? \Elementor\Plugin::$instance->documents->get( $stats_page->ID ) : null;
if ( $stats_elementor_doc && $stats_elementor_doc->is_built_with_elementor() ) {
    echo '<section class="homepage-section homepage-stats">';
    echo \Elementor\Plugin::$instance->frontend->get_builder_content_for_display( $stats_page->ID );
    echo '</section>';
} else {

$bg_image        = get_theme_mod( 'campusos_stats_bg_image', '' );
$institution     = campusos_get_institution_name();
$mhs_baru        = (int) get_theme_mod( 'campusos_stat_mhs_baru', 0 );
$mhs_terdaftar   = (int) get_theme_mod( 'campusos_stat_mhs_terdaftar', 0 );
$beasiswa        = (int) get_theme_mod( 'campusos_stat_beasiswa', 0 );

// Query jabatan fungsional counts from tenaga_pendidik CPT
$jabatan_keys = [
    'Guru Besar'     => 0,
    'Lektor Kepala'  => 0,
    'Lektor'         => 0,
    'Asisten Ahli'   => 0,
];

$all_dosen = get_posts( [
    'post_type'      => 'tenaga_pendidik',
    'posts_per_page' => -1,
    'post_status'    => 'publish',
    'fields'         => 'ids',
] );
$total_dosen = count( $all_dosen );

foreach ( $all_dosen as $dosen_id ) {
    $jabatan = get_post_meta( $dosen_id, 'tenaga_pendidik_jabatan_fungsional', true );
    if ( ! $jabatan ) {
        $jabatan = get_post_meta( $dosen_id, '_jabatan_fungsional', true );
    }
    if ( $jabatan && isset( $jabatan_keys[ $jabatan ] ) ) {
        $jabatan_keys[ $jabatan ]++;
    }
}

$sum_jabatan   = array_sum( $jabatan_keys );
$dosen_non_pns = max( 0, $total_dosen - $sum_jabatan );

$stat_items = [
    [
        'count' => $mhs_baru,
        'label' => __( 'Mahasiswa Baru', 'campusos-academic' ),
        'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c0 1.1 2.7 3 6 3s6-1.9 6-3v-5"/></svg>',
    ],
    [
        'count' => $mhs_terdaftar,
        'label' => __( 'Mahasiswa Terdaftar', 'campusos-academic' ),
        'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>',
    ],
    [
        'count' => $beasiswa,
        'label' => __( 'Beasiswa', 'campusos-academic' ),
        'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>',
    ],
    [
        'count' => $jabatan_keys['Guru Besar'],
        'label' => __( 'Guru Besar', 'campusos-academic' ),
        'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 15l-3-3h6l-3 3z"/><circle cx="12" cy="8" r="3"/><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/></svg>',
    ],
    [
        'count' => $jabatan_keys['Lektor Kepala'],
        'label' => __( 'Lektor Kepala', 'campusos-academic' ),
        'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>',
    ],
    [
        'count' => $jabatan_keys['Lektor'],
        'label' => __( 'Lektor', 'campusos-academic' ),
        'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>',
    ],
    [
        'count' => $jabatan_keys['Asisten Ahli'],
        'label' => __( 'Asisten Ahli', 'campusos-academic' ),
        'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/></svg>',
    ],
    [
        'count' => $dosen_non_pns,
        'label' => __( 'Dosen Non PNS', 'campusos-academic' ),
        'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>',
    ],
];

$bg_style = $bg_image ? 'background-image:url(' . esc_url( $bg_image ) . ');background-size:cover;background-position:center;' : 'background:#001432;';
?>
<section class="homepage-section homepage-stats-v2" style="<?php echo esc_attr( $bg_style . 'padding:0;position:relative;' ); ?>">
    <div class="stats-overlay">
        <div class="container">
            <div class="section-header-centered">
                <div class="section-decorator"></div>
                <h2><?php echo esc_html( $institution ); ?></h2>
                <p><?php esc_html_e( 'Dalam Angka', 'campusos-academic' ); ?></p>
            </div>
            <div class="stats-grid-v2">
                <?php foreach ( $stat_items as $item ) : ?>
                <div class="stats-item-v2">
                    <div class="stats-icon-circle">
                        <?php echo $item['icon']; ?>
                    </div>
                    <div class="stats-number-v2" data-count="<?php echo (int) $item['count']; ?>">0</div>
                    <div class="stats-label-v2"><?php echo esc_html( $item['label'] ); ?></div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>
<?php } ?>
