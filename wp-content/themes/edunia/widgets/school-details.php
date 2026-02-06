<?php

class School_Details extends WP_Widget {

    function __construct() {

        $lang = get_locale();
        if ( $lang == 'id_ID' ){
            $widget_name = 'Detail Sekolah';
        } else {
            $widget_name = 'School Details';
        }
 
        parent::__construct(
            'school_details',
            $widget_name
        );

        add_action( 'widgets_init', function() {
            register_widget( 'School_Details' );
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
        echo '<section id="school-details" class="widget widget_school_details">';
 
        if ( ! empty( $instance['title'] ) ) {
            echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
        }
 
        echo ((!empty($instance['image_uri']))?'<img class="icon" src="'.$instance['image_uri'].'"/>':'');
        echo '<p class="address">'.$instance['address'].'</p>';
        if(!empty($instance['gmaplink']) || !empty($instance['gmaptitle']) || !empty($instance['phone']) || !empty($instance['email'])){
            echo '<ul class="contact-list">';
            if(!empty($instance['gmaplink']) && !empty($instance['gmaptitle'])){
                echo '<li><a href="'.$instance['gmaplink'].'"><span class="icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M18.364 17.364L12 23.728l-6.364-6.364a9 9 0 1 1 12.728 0zM12 15a4 4 0 1 0 0-8 4 4 0 0 0 0 8zm0-2a2 2 0 1 1 0-4 2 2 0 0 1 0 4z"/></svg></span><span class="text">'.$instance['gmaptitle'].'</span></a></li>';
            }
            echo ((!empty($instance['phone']))?'<li><a href="tel:'.$instance['phone'].'"><span class="icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M21 16.42v3.536a1 1 0 0 1-.93.998c-.437.03-.794.046-1.07.046-8.837 0-16-7.163-16-16 0-.276.015-.633.046-1.07A1 1 0 0 1 4.044 3H7.58a.5.5 0 0 1 .498.45c.023.23.044.413.064.552A13.901 13.901 0 0 0 9.35 8.003c.095.2.033.439-.147.567l-2.158 1.542a13.047 13.047 0 0 0 6.844 6.844l1.54-2.154a.462.462 0 0 1 .573-.149 13.901 13.901 0 0 0 4 1.205c.139.02.322.042.55.064a.5.5 0 0 1 .449.498z"/></svg></span><span class="text">'.$instance['phone'].'</span></a></li>':'');
            echo ((!empty($instance['email']))?'<li><a href="mailto:'.$instance['email'].'"><span class="icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M3 3h18a1 1 0 0 1 1 1v16a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1zm9.06 8.683L5.648 6.238 4.353 7.762l7.72 6.555 7.581-6.56-1.308-1.513-6.285 5.439z"/></svg></span><span class="text">'.$instance['email'].'</span></a></li>':'');
            echo '</ul>';
        }

        echo '</section>';
        // echo $args['after_widget'];
 
    }
 
    public function form( $instance ) {
 
        $title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( '', 'edunia' );
        $image_uri = ! empty( $instance['image_uri'] ) ? $instance['image_uri'] : esc_html__( '', 'edunia' );
        $address = ! empty( $instance['address'] ) ? $instance['address'] : esc_html__( '', 'edunia' );
        $gmaplink = ! empty( $instance['gmaplink'] ) ? $instance['gmaplink'] : esc_html__( '', 'edunia' );
        $gmaptitle = ! empty( $instance['gmaptitle'] ) ? $instance['gmaptitle'] : esc_html__( '', 'edunia' );
        $phone = ! empty( $instance['phone'] ) ? $instance['phone'] : esc_html__( '', 'edunia' );
        $email = ! empty( $instance['email'] ) ? $instance['email'] : esc_html__( '', 'edunia' );
        ?>
        <p>
        <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php echo esc_html__( 'Title:', 'edunia' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'image_uri' ) ); ?>"><?php echo esc_html__( 'Image:', 'edunia' ); ?></label>
            <img class="<?php echo esc_attr( $this->get_field_id( 'image_uri' ) ); ?>_img" src="<?= (!empty($instance['image_uri'])) ? $instance['image_uri'] : ''; ?>" style="margin:0;padding:0;max-width:100%;display:block"/>
            <input type="hidden" class="widefat <?php echo esc_attr( $this->get_field_id( 'image_uri' ) ); ?>_url" id="<?php echo esc_attr( $this->get_field_id( 'image_uri' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'image_uri' ) ); ?>" value="<?php echo esc_attr( $image_uri ); ?>" style="margin-top:5px;" />
            <input type="button" id="<?php echo esc_attr( $this->get_field_id( 'image_uri' ) ); ?>" class="button button-primary js_custom_upload_media" value="Upload Image" style="margin-top:5px;" />
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'address' ) ); ?>"><?php echo esc_html__( 'Address:', 'edunia' ); ?></label>
            <textarea class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'address' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'address' ) ); ?>" cols="30" rows="4"><?php echo esc_attr( $address ); ?></textarea>
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'gmaplink' ) ); ?>"><?php echo esc_html__( 'Google Map Link:', 'edunia' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'gmaplink' ) ); ?>" type="text" name="<?php echo esc_attr( $this->get_field_name( 'gmaplink' ) ); ?>" value="<?php echo esc_attr( $gmaplink ); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'gmaptitle' ) ); ?>"><?php echo esc_html__( 'Google Map Title:', 'edunia' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'gmaptitle' ) ); ?>" type="text" name="<?php echo esc_attr( $this->get_field_name( 'gmaptitle' ) ); ?>" value="<?php echo esc_attr( $gmaptitle ); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'phone' ) ); ?>"><?php echo esc_html__( 'Phone:', 'edunia' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'phone' ) ); ?>" type="text" name="<?php echo esc_attr( $this->get_field_name( 'phone' ) ); ?>" value="<?php echo esc_attr( $phone ); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'email' ) ); ?>"><?php echo esc_html__( 'Email:', 'edunia' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'email' ) ); ?>" type="text" name="<?php echo esc_attr( $this->get_field_name( 'email' ) ); ?>" value="<?php echo esc_attr( $email ); ?>">
        </p>
        <?php
 
    }
 
    public function update( $new_instance, $old_instance ) {
 
        $instance = array();
 
        $instance['title'] = ( !empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        $instance['image_uri'] = ( !empty( $new_instance['image_uri'] ) ) ? $new_instance['image_uri'] : '';
        $instance['address'] = ( !empty( $new_instance['address'] ) ) ? $new_instance['address'] : '';
        $instance['gmaplink'] = ( !empty( $new_instance['gmaplink'] ) ) ? $new_instance['gmaplink'] : '';
        $instance['gmaptitle'] = ( !empty( $new_instance['gmaptitle'] ) ) ? $new_instance['gmaptitle'] : '';
        $instance['phone'] = ( !empty( $new_instance['phone'] ) ) ? $new_instance['phone'] : '';
        $instance['email'] = ( !empty( $new_instance['email'] ) ) ? $new_instance['email'] : '';
 
        return $instance;
    }
 
}
$school_details = new School_Details();
?>