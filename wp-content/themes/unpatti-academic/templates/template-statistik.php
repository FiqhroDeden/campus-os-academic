<?php
/* Template Name: Statistik */
get_header();
$mhs_aktif = get_post_meta( get_the_ID(), '_stat_mahasiswa_aktif', true );
$lulusan   = get_post_meta( get_the_ID(), '_stat_lulusan', true );
$dosen     = get_post_meta( get_the_ID(), '_stat_dosen', true );
$beasiswa  = get_post_meta( get_the_ID(), '_stat_beasiswa', true );
$tambahan  = get_post_meta( get_the_ID(), '_stat_tambahan', true );

$stats = array();
if ( $mhs_aktif ) $stats[] = array( 'label' => 'Mahasiswa Aktif', 'value' => $mhs_aktif );
if ( $lulusan )   $stats[] = array( 'label' => 'Lulusan', 'value' => $lulusan );
if ( $dosen )     $stats[] = array( 'label' => 'Dosen', 'value' => $dosen );
if ( $beasiswa )  $stats[] = array( 'label' => 'Penerima Beasiswa', 'value' => $beasiswa );
if ( ! empty( $tambahan ) && is_array( $tambahan ) ) {
    foreach ( $tambahan as $item ) {
        $stats[] = array( 'label' => $item['label'] ?? '', 'value' => $item['value'] ?? '' );
    }
}
?>
<div class="page-hero"><div class="container"><h1><?php the_title(); ?></h1></div></div>
<main id="primary" class="site-main">
    <div class="container">
        <?php if ( ! empty( $stats ) ) : ?>
            <div class="stat-grid">
                <?php foreach ( $stats as $stat ) : ?>
                    <div class="stat-item">
                        <div class="stat-number"><?php echo esc_html( $stat['value'] ); ?></div>
                        <div class="stat-label"><?php echo esc_html( $stat['label'] ); ?></div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</main>
<?php get_footer(); ?>
