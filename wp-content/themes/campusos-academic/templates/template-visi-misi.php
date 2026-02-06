<?php
/* Template Name: Visi Misi Tujuan Sasaran */
get_header();
$visi    = get_post_meta( get_the_ID(), '_visi', true );
$misi    = get_post_meta( get_the_ID(), '_misi', true );
$tujuan  = get_post_meta( get_the_ID(), '_tujuan', true );
$sasaran = get_post_meta( get_the_ID(), '_sasaran', true );
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
        <?php if ( $visi ) : ?>
            <section class="vmts-section">
                <h2>Visi</h2>
                <p><?php echo esc_html( $visi ); ?></p>
            </section>
        <?php endif; ?>

        <?php if ( ! empty( $misi ) && is_array( $misi ) ) : ?>
            <section class="vmts-section">
                <h2>Misi</h2>
                <ol>
                    <?php foreach ( $misi as $item ) : ?>
                        <li><?php echo esc_html( $item['item'] ?? '' ); ?></li>
                    <?php endforeach; ?>
                </ol>
            </section>
        <?php endif; ?>

        <?php if ( ! empty( $tujuan ) && is_array( $tujuan ) ) : ?>
            <section class="vmts-section">
                <h2>Tujuan</h2>
                <ol>
                    <?php foreach ( $tujuan as $item ) : ?>
                        <li><?php echo esc_html( $item['item'] ?? '' ); ?></li>
                    <?php endforeach; ?>
                </ol>
            </section>
        <?php endif; ?>

        <?php if ( ! empty( $sasaran ) && is_array( $sasaran ) ) : ?>
            <section class="vmts-section">
                <h2>Sasaran</h2>
                <ol>
                    <?php foreach ( $sasaran as $item ) : ?>
                        <li><?php echo esc_html( $item['item'] ?? '' ); ?></li>
                    <?php endforeach; ?>
                </ol>
            </section>
        <?php endif; ?>

        <?php endif; ?>
        </div>
</main>
<?php get_footer(); ?>
