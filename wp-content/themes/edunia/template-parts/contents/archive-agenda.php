<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
?>
<div class="post">
    <div class="time">
        <div class="month"><?php
        $agenda_date = date_create(get_post_meta(get_the_ID(), "agenda-date", true));
        echo __(date_format($agenda_date, 'M'), 'edunia');
        ?></div>
        <div class="date"><?php echo date_format($agenda_date, 'd'); ?></div>
    </div>
    <?php
    the_title( sprintf( '<h2 class="title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' );

    if(!empty(get_post_meta(get_the_ID(), "agenda-starttime", true)) || !empty(get_post_meta(get_the_ID(), "agenda-endtime", true)) || !empty(get_post_meta(get_the_ID(), "agenda-location", true))){
    ?>
    <div class="meta-agenda">
        <?php
        if(!empty(get_post_meta(get_the_ID(), "agenda-starttime", true)) && !empty(get_post_meta(get_the_ID(), "agenda-endtime", true))){
        ?>
        <div class="clock"><span class="icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10zm1-10V7h-2v7h6v-2h-4z"/></svg></span><?php
        echo get_post_meta(get_the_ID(), "agenda-starttime", true) . ' - ';
        if(get_post_meta(get_the_ID(), "agenda-endtime", true) == '00:00'){
            echo __('Finished', 'edunia');
        }else{
            echo get_post_meta(get_the_ID(), "agenda-endtime", true);
        }
        ?></div>
        <?php
        }
        if(!empty(get_post_meta(get_the_ID(), "agenda-location", true))){
        ?>
        <div class="place"><span class="icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M18.364 17.364L12 23.728l-6.364-6.364a9 9 0 1 1 12.728 0zM12 15a4 4 0 1 0 0-8 4 4 0 0 0 0 8zm0-2a2 2 0 1 1 0-4 2 2 0 0 1 0 4z"/></svg></span><?php echo get_post_meta(get_the_ID(), "agenda-location", true);?></div>
        <?php
        }
        ?>
    </div>
    <?php
    }
    ?>
</div>