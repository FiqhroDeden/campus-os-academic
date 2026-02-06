<?php
/* Template Name: Dokumen */
get_header();
$query = new WP_Query( array(
    'post_type'      => 'dokumen',
    'posts_per_page' => -1,
    'orderby'        => 'title',
    'order'          => 'ASC',
) );

$grouped = array();
if ( $query->have_posts() ) {
    while ( $query->have_posts() ) {
        $query->the_post();
        $kategori = get_post_meta( get_the_ID(), '_kategori_dokumen', true );
        if ( ! $kategori ) $kategori = 'Lainnya';
        $grouped[ $kategori ][] = array(
            'title' => get_the_title(),
            'file'  => get_post_meta( get_the_ID(), '_file_dokumen', true ),
        );
    }
    wp_reset_postdata();
}
?>
<div class="page-hero"><div class="container"><h1><?php the_title(); ?></h1></div></div>
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
        <?php if ( ! empty( $grouped ) ) : ?>
            <div class="document-list">
                <?php foreach ( $grouped as $kategori => $docs ) : ?>
                    <h2><?php echo esc_html( $kategori ); ?></h2>
                    <?php foreach ( $docs as $doc ) : ?>
                        <div class="doc-item">
                            <span class="doc-title"><?php echo esc_html( $doc['title'] ); ?></span>
                            <?php if ( $doc['file'] ) : ?>
                                <a href="<?php echo esc_url( wp_get_attachment_url( $doc['file'] ) ); ?>" class="doc-download btn btn-outline" target="_blank" rel="noopener">Download</a>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </div>
        <?php else : ?>
            <p>Belum ada dokumen.</p>
        <?php endif; ?>

        <?php endif; ?>
        </div>
</main>
<?php get_footer(); ?>
