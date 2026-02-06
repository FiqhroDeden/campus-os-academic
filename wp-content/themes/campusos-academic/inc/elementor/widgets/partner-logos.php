<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class CampusOS_Partner_Logos extends \Elementor\Widget_Base {

    public function get_name() { return 'campusos_partner_logos'; }
    public function get_title() { return __( 'Partner Logos', 'campusos-academic' ); }
    public function get_icon() { return 'eicon-gallery-grid'; }
    public function get_categories() { return [ 'campusos-academic' ]; }

    protected function register_controls() {
        $this->start_controls_section( 'content_section', [
            'label' => __( 'Partners', 'campusos-academic' ),
            'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
        ] );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control( 'logo', [
            'label'   => __( 'Logo', 'campusos-academic' ),
            'type'    => \Elementor\Controls_Manager::MEDIA,
            'default' => [ 'url' => \Elementor\Utils::get_placeholder_image_src() ],
        ] );

        $repeater->add_control( 'name', [
            'label'   => __( 'Partner Name', 'campusos-academic' ),
            'type'    => \Elementor\Controls_Manager::TEXT,
            'default' => __( 'Partner', 'campusos-academic' ),
        ] );

        $repeater->add_control( 'link', [
            'label'       => __( 'Link', 'campusos-academic' ),
            'type'        => \Elementor\Controls_Manager::URL,
            'placeholder' => __( 'https://example.com', 'campusos-academic' ),
        ] );

        $this->add_control( 'partners', [
            'label'   => __( 'Partners', 'campusos-academic' ),
            'type'    => \Elementor\Controls_Manager::REPEATER,
            'fields'  => $repeater->get_controls(),
            'default' => [],
            'title_field' => '{{{ name }}}',
        ] );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $partners = $settings['partners'];
        if ( empty( $partners ) ) return;

        $id = 'campusos-partners-' . $this->get_id();
        ?>
        <style>
            #<?php echo esc_attr( $id ); ?> {
                display: flex; flex-wrap: wrap; align-items: center; justify-content: center; gap: 30px; padding: 30px 0;
            }
            #<?php echo esc_attr( $id ); ?> .campusos-partner-item img {
                max-height: 70px; width: auto; filter: grayscale(100%); opacity: 0.7;
                transition: all .3s;
            }
            #<?php echo esc_attr( $id ); ?> .campusos-partner-item:hover img {
                filter: grayscale(0%); opacity: 1;
            }
        </style>
        <div id="<?php echo esc_attr( $id ); ?>">
            <?php foreach ( $partners as $partner ) :
                $img_url    = ! empty( $partner['logo']['url'] ) ? $partner['logo']['url'] : '';
                $link_url   = ! empty( $partner['link']['url'] ) ? $partner['link']['url'] : '';
                $target     = ! empty( $partner['link']['is_external'] ) ? ' target="_blank"' : '';
                $nofollow   = ! empty( $partner['link']['nofollow'] ) ? ' rel="nofollow"' : '';
                $name       = ! empty( $partner['name'] ) ? $partner['name'] : '';
            ?>
                <div class="campusos-partner-item">
                    <?php if ( $link_url ) : ?>
                        <a href="<?php echo esc_url( $link_url ); ?>"<?php echo $target . $nofollow; ?>>
                    <?php endif; ?>
                        <img src="<?php echo esc_url( $img_url ); ?>" alt="<?php echo esc_attr( $name ); ?>">
                    <?php if ( $link_url ) : ?>
                        </a>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
        <?php
    }
}
