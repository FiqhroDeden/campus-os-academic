<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class UNPATTI_Agenda_Calendar extends \Elementor\Widget_Base {

    public function get_name() { return 'unpatti_agenda_calendar'; }
    public function get_title() { return __( 'Agenda Calendar', 'unpatti-academic' ); }
    public function get_icon() { return 'eicon-calendar'; }
    public function get_categories() { return [ 'unpatti-academic' ]; }

    protected function register_controls() {
        $this->start_controls_section( 'content_section', [
            'label' => __( 'Settings', 'unpatti-academic' ),
            'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
        ] );

        $this->add_control( 'count', [
            'label'   => __( 'Count', 'unpatti-academic' ),
            'type'    => \Elementor\Controls_Manager::NUMBER,
            'default' => 5,
            'min'     => 1,
            'max'     => 20,
        ] );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $id       = 'unpatti-agenda-' . $this->get_id();

        $query = new \WP_Query( [
            'post_type'      => 'agenda',
            'posts_per_page' => intval( $settings['count'] ),
            'post_status'    => 'publish',
            'meta_key'       => '_tanggal_mulai_agenda',
            'orderby'        => 'meta_value',
            'order'          => 'DESC',
        ] );
        ?>
        <style>
            #<?php echo esc_attr( $id ); ?> { list-style: none; margin: 0; padding: 0; }
            #<?php echo esc_attr( $id ); ?> li {
                display: flex; align-items: flex-start; gap: 15px;
                padding: 15px 0; border-bottom: 1px solid #eee;
            }
            #<?php echo esc_attr( $id ); ?> .unpatti-agenda-date {
                flex-shrink: 0; width: 65px; height: 65px;
                background: var(--unpatti-secondary, #e67e22); color: #fff;
                display: flex; flex-direction: column; align-items: center; justify-content: center;
                border-radius: 6px; line-height: 1.2;
            }
            #<?php echo esc_attr( $id ); ?> .unpatti-agenda-date .day { font-size: 1.5rem; font-weight: 700; }
            #<?php echo esc_attr( $id ); ?> .unpatti-agenda-date .month { font-size: 0.75rem; text-transform: uppercase; }
            #<?php echo esc_attr( $id ); ?> .unpatti-agenda-info h4 { margin: 0 0 5px; font-size: 1rem; }
            #<?php echo esc_attr( $id ); ?> .unpatti-agenda-info h4 a { color: #222; text-decoration: none; }
            #<?php echo esc_attr( $id ); ?> .unpatti-agenda-info h4 a:hover { color: var(--unpatti-primary, #003d82); }
            #<?php echo esc_attr( $id ); ?> .unpatti-agenda-location { font-size: 0.85rem; color: #777; }
        </style>
        <ul id="<?php echo esc_attr( $id ); ?>">
            <?php if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post();
                $tanggal  = get_post_meta( get_the_ID(), '_tanggal_mulai_agenda', true );
                $lokasi   = get_post_meta( get_the_ID(), '_lokasi_agenda', true );
                $ts       = strtotime( $tanggal );
            ?>
                <li>
                    <div class="unpatti-agenda-date">
                        <span class="day"><?php echo $ts ? date_i18n( 'j', $ts ) : '--'; ?></span>
                        <span class="month"><?php echo $ts ? date_i18n( 'M', $ts ) : '--'; ?></span>
                    </div>
                    <div class="unpatti-agenda-info">
                        <h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                        <?php if ( $lokasi ) : ?>
                            <div class="unpatti-agenda-location">📍 <?php echo esc_html( $lokasi ); ?></div>
                        <?php endif; ?>
                    </div>
                </li>
            <?php endwhile; wp_reset_postdata(); else : ?>
                <li><?php esc_html_e( 'No agenda items found.', 'unpatti-academic' ); ?></li>
            <?php endif; ?>
        </ul>
        <?php
    }
}
