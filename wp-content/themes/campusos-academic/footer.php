<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<footer id="colophon" class="site-footer">
    <div class="footer-main">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-col">
                    <h3 class="footer-title"><?php echo esc_html( campusos_get_institution_name() ); ?></h3>
                    <?php if ( $address = get_theme_mod( 'campusos_address' ) ) : ?>
                        <p class="footer-address"><?php echo esc_html( $address ); ?></p>
                    <?php endif; ?>
                    <?php if ( $phone = get_theme_mod( 'campusos_phone' ) ) : ?>
                        <p class="footer-contact"><?php echo esc_html( $phone ); ?></p>
                    <?php endif; ?>
                    <?php if ( $email = get_theme_mod( 'campusos_email' ) ) : ?>
                        <p class="footer-contact"><?php echo esc_html( $email ); ?></p>
                    <?php endif; ?>
                </div>

                <div class="footer-col">
                    <h3 class="footer-title"><?php esc_html_e( 'Tautan', 'campusos-academic' ); ?></h3>
                    <?php
                    wp_nav_menu( [
                        'theme_location' => 'footer',
                        'menu_class'     => 'footer-menu',
                        'container'      => false,
                        'fallback_cb'    => false,
                        'depth'          => 1,
                    ] );
                    ?>
                </div>

                <div class="footer-col">
                    <h3 class="footer-title"><?php esc_html_e( 'Media Sosial', 'campusos-academic' ); ?></h3>
                    <div class="social-links">
                        <?php
                        $socials = [
                            'facebook'  => 'Facebook',
                            'instagram' => 'Instagram',
                            'youtube'   => 'YouTube',
                            'twitter'   => 'Twitter',
                            'tiktok'    => 'TikTok',
                        ];
                        foreach ( $socials as $key => $label ) :
                            $url = get_theme_mod( "campusos_social_{$key}" );
                            if ( $url ) :
                        ?>
                            <a href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener noreferrer" class="social-link" aria-label="<?php echo esc_attr( $label ); ?>"><?php echo esc_html( $label ); ?></a>
                        <?php endif; endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="footer-bottom">
        <div class="container">
            <p><?php echo wp_kses_post( get_theme_mod( 'campusos_footer_text', '&copy; ' . gmdate('Y') . ' ' ) ); ?></p>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
