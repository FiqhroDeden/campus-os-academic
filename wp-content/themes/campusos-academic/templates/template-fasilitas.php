<?php
/* Template Name: Fasilitas */
get_header();
$query = new WP_Query( array(
    'post_type'      => 'fasilitas',
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
                    <div class="card">
                        <?php if ( has_post_thumbnail() ) : ?>
                            <?php the_post_thumbnail( 'medium_large', array( 'class' => 'card-img' ) ); ?>
                        <?php endif; ?>
                        <div class="card-body">
                            <h3 class="card-title"><?php the_title(); ?></h3>
                            <p class="card-text"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 20 ) ); ?></p>
                            <?php
                            $kapasitas = get_post_meta( get_the_ID(), '_kapasitas', true );
                            $lokasi    = get_post_meta( get_the_ID(), '_lokasi', true );
                            ?>
                            <?php if ( $kapasitas ) : ?><p class="card-text"><strong>Kapasitas:</strong> <?php echo esc_html( $kapasitas ); ?></p><?php endif; ?>
                            <?php if ( $lokasi ) : ?><p class="card-text"><strong>Lokasi:</strong> <?php echo esc_html( $lokasi ); ?></p><?php endif; ?>
                        </div>
                    </div>
                <?php endwhile; wp_reset_postdata(); ?>
            </div>
        <?php else : ?>
            <p>Belum ada data fasilitas.</p>
        <?php endif; ?>

        <?php endif; ?>
        </div>
</main>
<?php get_footer(); ?>
