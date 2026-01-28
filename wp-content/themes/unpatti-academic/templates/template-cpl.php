<?php
/* Template Name: Capaian Pembelajaran */
get_header();
$sikap      = get_post_meta( get_the_ID(), '_cpl_sikap', true );
$pengetahuan = get_post_meta( get_the_ID(), '_cpl_pengetahuan', true );
$kt_umum    = get_post_meta( get_the_ID(), '_cpl_keterampilan_umum', true );
$kt_khusus  = get_post_meta( get_the_ID(), '_cpl_keterampilan_khusus', true );

$sections = array(
    'Sikap'                => $sikap,
    'Pengetahuan'          => $pengetahuan,
    'Keterampilan Umum'    => $kt_umum,
    'Keterampilan Khusus'  => $kt_khusus,
);
?>
<div class="page-hero"><div class="container"><h1><?php the_title(); ?></h1></div></div>
<main id="primary" class="site-main">
    <div class="container">
        <?php foreach ( $sections as $heading => $items ) : ?>
            <?php if ( ! empty( $items ) && is_array( $items ) ) : ?>
                <section class="vmts-section">
                    <h2><?php echo esc_html( $heading ); ?></h2>
                    <ol>
                        <?php foreach ( $items as $item ) : ?>
                            <li><?php echo esc_html( $item['item'] ?? '' ); ?></li>
                        <?php endforeach; ?>
                    </ol>
                </section>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
</main>
<?php get_footer(); ?>
