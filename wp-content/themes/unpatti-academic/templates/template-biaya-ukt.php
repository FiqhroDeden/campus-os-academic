<?php
/* Template Name: Biaya Pendidikan */
get_header();
$deskripsi = get_post_meta( get_the_ID(), '_deskripsi_ukt', true );
$tabel     = get_post_meta( get_the_ID(), '_tabel_ukt', true );
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
        <?php if ( $deskripsi ) : ?>
            <div class="entry-content" style="margin-bottom:2rem;">
                <p><?php echo esc_html( $deskripsi ); ?></p>
            </div>
        <?php endif; ?>

        <?php if ( ! empty( $tabel ) && is_array( $tabel ) ) : ?>
            <table class="ukt-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kategori</th>
                        <th>Nominal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ( $tabel as $i => $row ) : ?>
                        <tr>
                            <td><?php echo esc_html( $i + 1 ); ?></td>
                            <td><?php echo esc_html( $row['kategori'] ?? '' ); ?></td>
                            <td><?php echo esc_html( $row['nominal'] ?? '' ); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <?php endif; ?>
        </div>
</main>
<?php get_footer(); ?>
