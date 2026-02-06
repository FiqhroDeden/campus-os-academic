<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link sr-only" href="#primary"><?php esc_html_e( 'Skip to content', 'campusos-academic' ); ?></a>

<header id="masthead" class="site-header">
    <?php if ( get_theme_mod( 'campusos_show_topbar', true ) ) : ?>
    <div class="header-top">
        <div class="container">
            <div class="header-top-inner">
                <span class="header-top-text"><?php echo esc_html( get_theme_mod( 'campusos_address', '' ) ); ?></span>
                <div class="header-top-right">
                    <?php if ( $phone = get_theme_mod( 'campusos_phone' ) ) : ?>
                        <span class="header-contact"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/></svg> <?php echo esc_html( $phone ); ?></span>
                    <?php endif; ?>
                    <?php if ( $email = get_theme_mod( 'campusos_email' ) ) : ?>
                        <span class="header-contact"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M22 7l-10 7L2 7"/></svg> <?php echo esc_html( $email ); ?></span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <div class="header-main">
        <div class="container">
            <div class="header-main-inner">
                <div class="site-branding">
                    <?php if ( has_custom_logo() ) : ?>
                        <?php the_custom_logo(); ?>
                    <?php endif; ?>
                    <div class="site-branding-text">
                        <h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo esc_html( campusos_get_institution_name() ); ?></a></h1>
                        <p class="site-description"><?php bloginfo( 'description' ); ?></p>
                    </div>
                </div>

                <button class="menu-toggle" aria-controls="primary-navigation" aria-expanded="false" aria-label="<?php esc_attr_e( 'Menu', 'campusos-academic' ); ?>">
                    <span class="hamburger"></span>
                </button>

                <nav id="primary-navigation" class="main-navigation" aria-label="<?php esc_attr_e( 'Menu Utama', 'campusos-academic' ); ?>">
                    <?php
                    wp_nav_menu( [
                        'theme_location' => 'primary',
                        'menu_class'     => 'primary-menu',
                        'container'      => false,
                        'fallback_cb'    => false,
                        'depth'          => 3,
                    ] );
                    ?>
                </nav>
            </div>
        </div>
    </div>
</header>
<?php campusos_breadcrumbs(); ?>
