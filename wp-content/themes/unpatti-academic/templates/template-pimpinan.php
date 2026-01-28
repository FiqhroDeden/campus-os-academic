<?php
/* Template Name: Pimpinan */
get_header();
$query = new WP_Query( array(
    'post_type'      => 'pimpinan',
    'posts_per_page' => -1,
    'meta_key'       => '_pimpinan_urutan',
    'orderby'        => 'meta_value_num',
    'order'          => 'ASC',
) );
?>
<div class="page-hero"><div class="container"><h1><?php the_title(); ?></h1></div></div>
<main id="primary" class="site-main">
    <div class="container">
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
                                $jabatan = get_post_meta( get_the_ID(), '_pimpinan_jabatan', true );
                                $nip     = get_post_meta( get_the_ID(), '_pimpinan_nip', true );
                                $email   = get_post_meta( get_the_ID(), '_pimpinan_email', true );
                                ?>
                                <?php if ( $jabatan ) : ?><p class="meta-jabatan"><?php echo esc_html( $jabatan ); ?></p><?php endif; ?>
                                <?php if ( $nip ) : ?><p class="meta-nip">NIP: <?php echo esc_html( $nip ); ?></p><?php endif; ?>
                                <?php if ( $email ) : ?><p class="meta-email"><a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a></p><?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endwhile; wp_reset_postdata(); ?>
            </div>
        <?php else : ?>
            <p>Belum ada data pimpinan.</p>
        <?php endif; ?>
    </div>
</main>
<?php get_footer(); ?>
