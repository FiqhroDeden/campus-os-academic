<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class CampusOS_Video_Grid_Widget extends CampusOS_Widget_Base {

    public function get_name() { return 'campusos_video_grid'; }
    public function get_title() { return __( 'Video', 'campusos-academic' ); }
    public function get_icon() { return 'eicon-play'; }

    protected function register_controls() {
        // Content
        $this->start_controls_section( 'content_section', [
            'label' => __( 'Settings', 'campusos-academic' ),
            'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
        ] );

        $this->register_content_count_control( 6 );
        $this->register_columns_control( 3 );

        $this->end_controls_section();

        // Style
        $this->register_style_card_section();
        $this->register_style_image_section();
        $this->register_style_spacing_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $id       = $this->get_scoped_id( 'campusos-video' );
        $cols     = intval( $settings['columns'] );

        $args = [
            'post_type'      => 'video',
            'posts_per_page' => intval( $settings['count'] ),
            'post_status'    => 'publish',
            'orderby'        => 'date',
            'order'          => 'DESC',
        ];

        $query = new \WP_Query( $args );

        if ( ! $query->have_posts() ) {
            $this->render_empty_state( __( 'Belum ada video.', 'campusos-academic' ) );
            return;
        }

        $this->render_responsive_grid_css( $id, $cols );
        $this->render_base_card_css( $id );
        ?>
        <style>
            #<?php echo esc_attr( $id ); ?> .campusos-w-image {
                position: relative;
            }
            #<?php echo esc_attr( $id ); ?> .campusos-video-play {
                position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);
                width: 56px; height: 56px; background: rgba(0,0,0,0.6); border-radius: 50%;
                display: flex; align-items: center; justify-content: center;
                transition: background .3s;
            }
            #<?php echo esc_attr( $id ); ?> .campusos-w-card:hover .campusos-video-play {
                background: var(--campusos-primary, #003d82);
            }
            #<?php echo esc_attr( $id ); ?> .campusos-video-play::after {
                content: ''; display: block; width: 0; height: 0;
                border-style: solid; border-width: 10px 0 10px 18px;
                border-color: transparent transparent transparent #fff; margin-left: 3px;
            }
            #<?php echo esc_attr( $id ); ?> .campusos-video-duration {
                position: absolute; bottom: 8px; right: 8px;
                background: rgba(0,0,0,0.75); color: #fff; padding: 2px 8px;
                border-radius: 4px; font-size: 0.75rem;
            }
        </style>
        <div id="<?php echo esc_attr( $id ); ?>" class="campusos-w-grid">
            <?php while ( $query->have_posts() ) : $query->the_post();
                $pid        = get_the_ID();
                $youtube_url = get_post_meta( $pid, '_video_youtube_url', true );
                $duration    = get_post_meta( $pid, '_video_video_duration', true );

                // Extract YouTube ID
                $youtube_id  = '';
                if ( $youtube_url && preg_match( '/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]+)/', $youtube_url, $matches ) ) {
                    $youtube_id = $matches[1];
                }

                // Determine thumbnail
                $thumb_url = '';
                if ( has_post_thumbnail() ) {
                    $thumb_url = get_the_post_thumbnail_url( $pid, 'campusos-card' );
                } elseif ( $youtube_id ) {
                    $thumb_url = 'https://img.youtube.com/vi/' . $youtube_id . '/mqdefault.jpg';
                }

                // Build video link
                $video_link = $youtube_url ? $youtube_url : get_the_permalink();
            ?>
                <div class="campusos-w-card">
                    <a href="<?php echo esc_url( $video_link ); ?>" target="_blank" rel="noopener">
                        <div class="campusos-w-image">
                            <?php if ( $thumb_url ) : ?>
                                <img src="<?php echo esc_url( $thumb_url ); ?>" alt="<?php the_title_attribute(); ?>">
                            <?php endif; ?>
                            <div class="campusos-video-play"></div>
                            <?php if ( $duration ) : ?>
                                <span class="campusos-video-duration"><?php echo esc_html( $duration ); ?></span>
                            <?php endif; ?>
                        </div>
                    </a>
                    <div class="campusos-w-card-content">
                        <h4 class="campusos-w-title">
                            <a href="<?php echo esc_url( $video_link ); ?>" target="_blank" rel="noopener"><?php the_title(); ?></a>
                        </h4>
                    </div>
                </div>
            <?php endwhile; wp_reset_postdata(); ?>
        </div>
        <?php
    }
}
