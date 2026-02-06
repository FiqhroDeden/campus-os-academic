<?php
 
class Latest_Agenda extends WP_Widget {
 
    function __construct() {
 
        $lang = get_locale();
        if ( $lang == 'id_ID' ){
            $widget_name = 'Agenda Terbaru';
        } else {
            $widget_name = 'Latest Agenda';
        }

        parent::__construct(
            'latest_agenda',
            $widget_name
        );
 
        add_action( 'widgets_init', function() {
            register_widget( 'Latest_Agenda' );
        });
 
    }
 
    public $args = array(
        'before_title'  => '<h4 class="widgettitle">',
        'after_title'   => '</h4>',
        'before_widget' => '<div class="widget-wrap">',
        'after_widget'  => '</div></div>'
    );
 
    public function widget( $args, $instance ) {
 
        // echo $args['before_widget'];
        echo '<section id="latest-agenda" class="widget widget_latest_agenda">';
 
        if ( ! empty( $instance['title'] ) ) {
            echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
        }

        if(empty($instance['agendacount'])){
            $agendacount = 5;
        }else{
            $agendacount = $instance['agendacount'];
        }
        $args = array(
            'posts_per_page' => $agendacount,
            'orderby' => 'date',
            'order' => 'DESC',
            'post_type' => 'agenda',
            'post_status' => 'publish'
        );
        $the_query = new WP_Query( $args ); 
        if ( $the_query->have_posts() ) : 
            while ( $the_query->have_posts() ) : $the_query->the_post();
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
        <?php
            endwhile;
        endif;
        wp_reset_postdata();

        echo '</section>';
        // echo $args['after_widget'];
 
    }
 
    public function form( $instance ) {
 
        $title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( '', 'edunia' );
        $agendacount = ! empty( $instance['agendacount'] ) ? $instance['agendacount'] : esc_html__( '', 'edunia' );
        ?>
        <p>
        <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php echo esc_html__( 'Title:', 'edunia' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <p>
        <label for="<?php echo esc_attr( $this->get_field_id( 'agendacount' ) ); ?>"><?php echo esc_html__( 'Count:', 'edunia' ); ?></label>
            <select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'agendacount' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'agendacount' ) ); ?>">
            <option value="1"<?php echo (($agendacount == 1)?' selected disabled':'');?>>1</option>
            <option value="2"<?php echo (($agendacount == 2)?' selected disabled':'');?>>2</option>
            <option value="3"<?php echo (($agendacount == 3)?' selected disabled':'');?>>3</option>
            <option value="4"<?php echo (($agendacount == 4)?' selected disabled':'');?>>4</option>
            <option value="5"<?php echo ((empty($agendacount) || $agendacount = 5)?' selected disabled':'');?>>5</option>
            <option value="6"<?php echo (($agendacount == 6)?' selected disabled':'');?>>6</option>
            <option value="7"<?php echo (($agendacount == 7)?' selected disabled':'');?>>7</option>
            <option value="8"<?php echo (($agendacount == 8)?' selected disabled':'');?>>8</option>
            </select>
        </p>
        <?php
 
    }
 
    public function update( $new_instance, $old_instance ) {
 
        $instance = array();
 
        $instance['title'] = ( !empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        $instance['agendacount'] = ( !empty( $new_instance['agendacount'] ) ) ? strip_tags( $new_instance['agendacount'] ) : '';
 
        return $instance;
    }
 
}
$latest_agenda = new Latest_Agenda();
?>