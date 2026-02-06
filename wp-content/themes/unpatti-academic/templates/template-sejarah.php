<?php
/* Template Name: Sejarah */
get_header();
$content   = get_post_meta( get_the_ID(), '_sejarah_content', true );
$timeline  = get_post_meta( get_the_ID(), '_sejarah_timeline', true );
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
        <?php if ( $content ) : ?>
            <div class="entry-content"><?php echo wp_kses_post( $content ); ?></div>
        <?php endif; ?>

        <?php if ( ! empty( $timeline ) && is_array( $timeline ) ) : ?>
            <div class="timeline">
                <?php foreach ( $timeline as $item ) : ?>
                    <div class="timeline-item">
                        <div class="timeline-year"><?php echo esc_html( $item['tahun'] ?? '' ); ?></div>
                        <div class="timeline-content"><?php echo esc_html( $item['peristiwa'] ?? '' ); ?></div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php endif; ?>
        </div>
</main>
<?php get_footer(); ?>
