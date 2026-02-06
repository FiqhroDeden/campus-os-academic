<?php

class Latest_Announcement extends WP_Widget {
 
    function __construct() {

        $lang = get_locale();
        if ( $lang == 'id_ID' ){
            $widget_name = 'Pengumuman Terbaru';
        } else {
            $widget_name = 'Latest Announcement';
        }
 
        parent::__construct(
            'latest_announcement',
            $widget_name
        );
 
        add_action( 'widgets_init', function() {
            register_widget( 'Latest_Announcement' );
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
        echo '<section id="latest-announcement" class="widget widget_latest_announcement">';
        
        if ( ! empty( $instance['title'] ) ) {
            echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
        }

        if(empty($instance['announcecount'])){
            $announcecount = 5;
        }else{
            $announcecount = $instance['announcecount'];
        }
        $args = array(
            'posts_per_page' => $announcecount,
            'orderby' => 'date',
            'order' => 'DESC',
            'post_type' => 'pengumuman',
            'post_status' => 'publish'
        );
        $the_query = new WP_Query( $args ); 
        if ( $the_query->have_posts() ) : 
            while ( $the_query->have_posts() ) : $the_query->the_post();
        ?>
        <div class="post">
        <?php
        echo '<div class="date">';
        edunia_posted_on();
        echo '</div>';
        the_title( sprintf( '<h2 class="title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' );
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
        $announcecount = ! empty( $instance['announcecount'] ) ? $instance['announcecount'] : esc_html__( '', 'edunia' );
        ?>
        <p>
        <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php echo esc_html__( 'Title:', 'edunia' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <p>
        <label for="<?php echo esc_attr( $this->get_field_id( 'announcecount' ) ); ?>"><?php echo esc_html__( 'Count:', 'edunia' ); ?></label>
            <select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'announcecount' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'announcecount' ) ); ?>">
            <option value="1"<?php echo (($announcecount == 1)?' selected disabled':'');?>>1</option>
            <option value="2"<?php echo (($announcecount == 2)?' selected disabled':'');?>>2</option>
            <option value="3"<?php echo (($announcecount == 3)?' selected disabled':'');?>>3</option>
            <option value="4"<?php echo (($announcecount == 4)?' selected disabled':'');?>>4</option>
            <option value="5"<?php echo ((empty($announcecount) || $announcecount = 5)?' selected disabled':'');?>>5</option>
            <option value="6"<?php echo (($announcecount == 6)?' selected disabled':'');?>>6</option>
            <option value="7"<?php echo (($announcecount == 7)?' selected disabled':'');?>>7</option>
            <option value="8"<?php echo (($announcecount == 8)?' selected disabled':'');?>>8</option>
            </select>
        </p>
        <?php
 
    }
 
    public function update( $new_instance, $old_instance ) {
 
        $instance = array();
 
        $instance['title'] = ( !empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        $instance['announcecount'] = ( !empty( $new_instance['announcecount'] ) ) ? strip_tags( $new_instance['announcecount'] ) : '';
 
        return $instance;
    }
 
}
$latest_announcement = new Latest_Announcement();
?>