<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>
<div class="post">
    <a class="profile-image" href="<?php the_permalink(); ?>">
    <?php
    the_post_thumbnail(
        'gtk-thumb',
        array(
            'alt' => the_title_attribute(
                array(
                    'echo' => false,
                )
            ),
        )
    );
    ?>
    </a>
    <div class="profile">
    <?php
    the_title( sprintf( '<h2 class="name"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' );
    if(!empty(get_post_meta(get_the_ID(), "gtk-position", true))){
        echo '<div class="position">'.get_post_meta(get_the_ID(), "gtk-position", true).'</div>';
    }

    if(!empty(get_post_meta(get_the_ID(), "gtk-phone", true)) || !empty(get_post_meta(get_the_ID(), "gtk-email", true))){
    ?>

    <div class="contact">
        <?php
        echo ((!empty(get_post_meta(get_the_ID(), "gtk-phone", true)))?'<a class="phone" href="tel:'.get_post_meta(get_the_ID(), "gtk-phone", true).'"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M21 16.42v3.536a1 1 0 0 1-.93.998c-.437.03-.794.046-1.07.046-8.837 0-16-7.163-16-16 0-.276.015-.633.046-1.07A1 1 0 0 1 4.044 3H7.58a.5.5 0 0 1 .498.45c.023.23.044.413.064.552A13.901 13.901 0 0 0 9.35 8.003c.095.2.033.439-.147.567l-2.158 1.542a13.047 13.047 0 0 0 6.844 6.844l1.54-2.154a.462.462 0 0 1 .573-.149 13.901 13.901 0 0 0 4 1.205c.139.02.322.042.55.064a.5.5 0 0 1 .449.498z"/></svg></a>':'');

        echo ((!empty(get_post_meta(get_the_ID(), "gtk-email", true)))?'<a class="email" href="mailto:'.get_post_meta(get_the_ID(), "gtk-email", true).'"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M3 3h18a1 1 0 0 1 1 1v16a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1zm9.06 8.683L5.648 6.238 4.353 7.762l7.72 6.555 7.581-6.56-1.308-1.513-6.285 5.439z"/></svg></a>':'');

        echo ((!empty(get_post_meta(get_the_ID(), "gtk-whatsapp", true)))?'<a class="whatsapp" href="https://api.whatsapp.com/send?phone='.get_post_meta(get_the_ID(), "gtk-whatsapp", true).'" rel="nofollow noopener" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M2.004 22l1.352-4.968A9.954 9.954 0 0 1 2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10a9.954 9.954 0 0 1-5.03-1.355L2.004 22zM8.391 7.308a.961.961 0 0 0-.371.1 1.293 1.293 0 0 0-.294.228c-.12.113-.188.211-.261.306A2.729 2.729 0 0 0 6.9 9.62c.002.49.13.967.33 1.413.409.902 1.082 1.857 1.971 2.742.214.213.423.427.648.626a9.448 9.448 0 0 0 3.84 2.046l.569.087c.185.01.37-.004.556-.013a1.99 1.99 0 0 0 .833-.231c.166-.088.244-.132.383-.22 0 0 .043-.028.125-.09.135-.1.218-.171.33-.288.083-.086.155-.187.21-.302.078-.163.156-.474.188-.733.024-.198.017-.306.014-.373-.004-.107-.093-.218-.19-.265l-.582-.261s-.87-.379-1.401-.621a.498.498 0 0 0-.177-.041.482.482 0 0 0-.378.127v-.002c-.005 0-.072.057-.795.933a.35.35 0 0 1-.368.13 1.416 1.416 0 0 1-.191-.066c-.124-.052-.167-.072-.252-.109l-.005-.002a6.01 6.01 0 0 1-1.57-1c-.126-.11-.243-.23-.363-.346a6.296 6.296 0 0 1-1.02-1.268l-.059-.095a.923.923 0 0 1-.102-.205c-.038-.147.061-.265.061-.265s.243-.266.356-.41a4.38 4.38 0 0 0 .263-.373c.118-.19.155-.385.093-.536-.28-.684-.57-1.365-.868-2.041-.059-.134-.234-.23-.393-.249-.054-.006-.108-.012-.162-.016a3.385 3.385 0 0 0-.403.004z"/></svg></a>':'');
        ?>
    </div>
    <?php
    }
    ?>
    </div>
</div>