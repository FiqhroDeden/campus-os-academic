<?php
/* Template Name: Struktur Organisasi */
get_header();
$bagan_id  = get_post_meta( get_the_ID(), '_bagan_organisasi', true );
$deskripsi = get_post_meta( get_the_ID(), '_deskripsi_struktur', true );
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
        <?php if ( $bagan_id ) : ?>
            <div class="struktur-bagan" style="text-align:center;margin-bottom:2rem;">
                <?php echo wp_get_attachment_image( $bagan_id, 'full' ); ?>
            </div>
        <?php endif; ?>

        <?php if ( $deskripsi ) : ?>
            <div class="entry-content">
                <p><?php echo esc_html( $deskripsi ); ?></p>
            </div>
        <?php endif; ?>

        <?php endif; ?>
        </div>
</main>
<?php get_footer(); ?>
