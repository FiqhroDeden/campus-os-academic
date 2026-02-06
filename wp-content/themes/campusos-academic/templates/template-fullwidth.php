<?php
/*
 * Template Name: Full Width
 */
get_header(); ?>
<?php if ( ! is_front_page() ) : ?>
<div class="page-hero">
    <div class="container">
        <h1><?php the_title(); ?></h1>
    </div>
</div>
<?php endif; ?>
<main id="primary" class="site-main">
    <?php if ( is_front_page() ) : ?>
        <?php while ( have_posts() ) : the_post(); ?>
            <div class="entry-content entry-content-fullwidth"><?php the_content(); ?></div>
        <?php endwhile; ?>
    <?php else : ?>
        <div class="container container-wide">
            <?php while ( have_posts() ) : the_post(); ?>
                <div class="entry-content"><?php the_content(); ?></div>
            <?php endwhile; ?>
        </div>
    <?php endif; ?>
</main>
<?php get_footer(); ?>
