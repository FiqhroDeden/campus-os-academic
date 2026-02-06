<?php get_header(); ?>
<div class="page-hero">
    <div class="container">
        <h1><?php the_title(); ?></h1>
        <div class="breadcrumb">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Beranda', 'unpatti-academic' ); ?></a> &raquo;
            <?php the_title(); ?>
        </div>
    </div>
</div>
<main id="primary" class="site-main">
    <div class="container">
        <div class="content-sidebar-wrap">
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <?php while ( have_posts() ) : the_post(); ?>
                    <?php if ( has_post_thumbnail() ) : ?>
                        <div class="entry-thumbnail"><?php the_post_thumbnail( 'large' ); ?></div>
                    <?php endif; ?>
                    <div class="entry-meta">
                        <span class="posted-on"><?php echo get_the_date(); ?></span>
                        <span class="posted-by"><?php the_author(); ?></span>
                    </div>
                    <div class="entry-content"><?php the_content(); ?></div>
                    <?php unpatti_social_share(); ?>
                <?php endwhile; ?>
            </article>
            <?php get_sidebar(); ?>
        </div>
    </div>
</main>
<?php get_footer(); ?>
