<?php get_header(); ?>
<div class="page-hero">
    <div class="container">
        <h1><?php the_archive_title(); ?></h1>
        <?php the_archive_description( '<div class="archive-description">', '</div>' ); ?>
    </div>
</div>
<main id="primary" class="site-main">
    <div class="container">
        <?php if ( have_posts() ) : ?>
            <div class="posts-grid">
                <?php while ( have_posts() ) : the_post(); ?>
                    <article <?php post_class( 'card' ); ?>>
                        <?php if ( has_post_thumbnail() ) : ?>
                            <a href="<?php the_permalink(); ?>">
                                <?php the_post_thumbnail( 'unpatti-card', [ 'class' => 'card-img' ] ); ?>
                            </a>
                        <?php endif; ?>
                        <div class="card-body">
                            <h2 class="card-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                            <p class="card-text"><?php echo wp_trim_words( get_the_excerpt(), 20 ); ?></p>
                            <span class="card-date"><?php echo get_the_date(); ?></span>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>
            <?php the_posts_pagination( [ 'mid_size' => 2 ] ); ?>
        <?php else : ?>
            <p><?php esc_html_e( 'Tidak ada konten ditemukan.', 'unpatti-academic' ); ?></p>
        <?php endif; ?>
    </div>
</main>
<?php get_footer(); ?>
