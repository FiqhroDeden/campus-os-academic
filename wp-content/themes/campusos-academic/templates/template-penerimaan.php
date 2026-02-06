<?php
/* Template Name: Penerimaan Mahasiswa */
get_header();
$jalur = get_post_meta( get_the_ID(), '_jalur_penerimaan', true );
$biaya = get_post_meta( get_the_ID(), '_biaya_pendaftaran', true );
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
        <?php if ( ! empty( $jalur ) && is_array( $jalur ) ) : ?>
            <div class="profile-grid">
                <?php foreach ( $jalur as $j ) : ?>
                    <div class="card">
                        <div class="card-body">
                            <h3 class="card-title"><?php echo esc_html( $j['nama'] ?? '' ); ?></h3>
                            <?php if ( ! empty( $j['persyaratan'] ) ) : ?>
                                <p class="card-text"><?php echo esc_html( $j['persyaratan'] ); ?></p>
                            <?php endif; ?>
                            <?php if ( ! empty( $j['link'] ) ) : ?>
                                <a href="<?php echo esc_url( $j['link'] ); ?>" class="btn btn-primary" target="_blank" rel="noopener">Daftar</a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if ( $biaya ) : ?>
            <section class="vmts-section" style="margin-top:2rem;">
                <h2>Biaya Pendaftaran</h2>
                <p><?php echo esc_html( $biaya ); ?></p>
            </section>
        <?php endif; ?>

        <?php endif; ?>
        </div>
</main>
<?php get_footer(); ?>
