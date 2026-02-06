<?php
/* Template Name: Tracer Study */
get_header();
$links    = get_post_meta( get_the_ID(), '_link_survey', true );
$dokumen  = get_post_meta( get_the_ID(), '_dokumen_tracer', true );
$stats    = get_post_meta( get_the_ID(), '_statistik_alumni', true );
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
        <?php if ( ! empty( $links ) && is_array( $links ) ) : ?>
            <section class="vmts-section">
                <h2>Link Survey</h2>
                <ul class="document-list">
                    <?php foreach ( $links as $link ) : ?>
                        <div class="doc-item">
                            <span class="doc-title"><?php echo esc_html( $link['nama'] ?? '' ); ?></span>
                            <?php if ( ! empty( $link['url'] ) ) : ?>
                                <a href="<?php echo esc_url( $link['url'] ); ?>" class="doc-download btn btn-primary" target="_blank" rel="noopener">Buka Survey</a>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </ul>
            </section>
        <?php endif; ?>

        <?php if ( $dokumen ) : ?>
            <section class="vmts-section">
                <h2>Dokumen Tracer Study</h2>
                <p><a href="<?php echo esc_url( wp_get_attachment_url( $dokumen ) ); ?>" class="btn btn-primary" target="_blank" rel="noopener">Download Dokumen</a></p>
            </section>
        <?php endif; ?>

        <?php if ( $stats ) : ?>
            <section class="vmts-section">
                <h2>Statistik Alumni</h2>
                <p><?php echo esc_html( $stats ); ?></p>
            </section>
        <?php endif; ?>

        <?php endif; ?>
        </div>
</main>
<?php get_footer(); ?>
