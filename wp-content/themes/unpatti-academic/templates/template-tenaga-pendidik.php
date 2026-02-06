<?php
/* Template Name: Tenaga Pendidik */
get_header();
$query = new WP_Query( array(
    'post_type'      => 'tenaga_pendidik',
    'posts_per_page' => -1,
    'orderby'        => 'title',
    'order'          => 'ASC',
) );
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
        <?php if ( $query->have_posts() ) : ?>
            <div class="profile-grid">
                <?php while ( $query->have_posts() ) : $query->the_post(); ?>
                    <div class="profile-card">
                        <div class="profile-photo">
                            <?php if ( has_post_thumbnail() ) : ?>
                                <?php the_post_thumbnail( 'medium' ); ?>
                            <?php endif; ?>
                        </div>
                        <div class="profile-info">
                            <h3 class="profile-name"><?php the_title(); ?></h3>
                            <div class="profile-meta">
                                <?php
                                $jab_fungsional  = get_post_meta( get_the_ID(), '_jabatan_fungsional', true );
                                $bidang_keahlian = get_post_meta( get_the_ID(), '_bidang_keahlian', true );
                                $email           = get_post_meta( get_the_ID(), '_email', true );
                                ?>
                                <?php if ( $jab_fungsional ) : ?><p class="meta-jabatan"><?php echo esc_html( $jab_fungsional ); ?></p><?php endif; ?>
                                <?php if ( $bidang_keahlian ) : ?><p class="meta-keahlian"><?php echo esc_html( $bidang_keahlian ); ?></p><?php endif; ?>
                                <?php if ( $email ) : ?><p class="meta-email"><a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a></p><?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endwhile; wp_reset_postdata(); ?>
            </div>
        <?php else : ?>
            <p>Belum ada data tenaga pendidik.</p>
        <?php endif; ?>

        <?php endif; ?>
        </div>
</main>
<?php get_footer(); ?>
