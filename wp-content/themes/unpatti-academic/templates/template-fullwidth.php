<?php
/*
 * Template Name: Full Width
 */
get_header(); ?>
<div class="page-hero">
    <div class="container">
        <h1><?php the_title(); ?></h1>
    </div>
</div>
<main id="primary" class="site-main">
    <div class="container container-wide">
        <?php while ( have_posts() ) : the_post(); ?>
            <div class="entry-content"><?php the_content(); ?></div>
        <?php endwhile; ?>
    </div>
</main>
<?php get_footer(); ?>
