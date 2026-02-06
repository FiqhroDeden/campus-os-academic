<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class UNPATTI_Setup_Wizard {

    private $steps = [];
    private $current_step = '';

    public function __construct() {
        add_action( 'after_switch_theme', [ $this, 'on_theme_activate' ] );
        add_action( 'admin_init', [ $this, 'redirect_to_wizard' ] );
        add_action( 'admin_menu', [ $this, 'add_wizard_page' ] );
        add_action( 'admin_post_unpatti_wizard_save', [ $this, 'handle_save' ] );
        add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_assets' ] );
    }

    public function on_theme_activate() {
        if ( get_transient( 'unpatti_setup_wizard_done' ) ) {
            return;
        }
        set_transient( 'unpatti_setup_wizard_redirect', true, 30 );
    }

    public function redirect_to_wizard() {
        if ( ! get_transient( 'unpatti_setup_wizard_redirect' ) ) {
            return;
        }
        delete_transient( 'unpatti_setup_wizard_redirect' );

        if ( get_transient( 'unpatti_setup_wizard_done' ) ) {
            return;
        }

        if ( wp_doing_ajax() || is_network_admin() || isset( $_GET['activate-multi'] ) ) {
            return;
        }

        wp_safe_redirect( admin_url( 'admin.php?page=unpatti-setup-wizard' ) );
        exit;
    }

    public function add_wizard_page() {
        add_submenu_page(
            null,
            __( 'Setup Wizard', 'unpatti-academic' ),
            __( 'Setup Wizard', 'unpatti-academic' ),
            'manage_options',
            'unpatti-setup-wizard',
            [ $this, 'render_wizard' ]
        );
    }

    public function enqueue_assets( $hook ) {
        if ( 'admin_page_unpatti-setup-wizard' !== $hook ) {
            return;
        }
        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_script( 'wp-color-picker' );
        wp_enqueue_media();
        wp_enqueue_style( 'unpatti-wizard', UNPATTI_THEME_URI . '/inc/setup-wizard/wizard.css', [], UNPATTI_THEME_VERSION );
    }

    private function get_steps() {
        return [
            'welcome'   => __( 'Welcome', 'unpatti-academic' ),
            'warna'     => __( 'Warna', 'unpatti-academic' ),
            'identitas' => __( 'Identitas', 'unpatti-academic' ),
            'demo'      => __( 'Demo Content', 'unpatti-academic' ),
            'selesai'   => __( 'Selesai', 'unpatti-academic' ),
        ];
    }

    public function render_wizard() {
        $steps = $this->get_steps();
        $step_keys = array_keys( $steps );
        $this->current_step = isset( $_GET['step'] ) ? sanitize_key( $_GET['step'] ) : 'welcome';

        if ( ! isset( $steps[ $this->current_step ] ) ) {
            $this->current_step = 'welcome';
        }

        $current_index = array_search( $this->current_step, $step_keys );
        ?>
        <div class="wrap unpatti-setup-wizard">
            <h1><?php esc_html_e( 'UNPATTI Academic — Setup Wizard', 'unpatti-academic' ); ?></h1>

            <ul class="wizard-steps">
                <?php foreach ( $steps as $key => $label ) :
                    $idx = array_search( $key, $step_keys );
                    $class = '';
                    if ( $key === $this->current_step ) $class = 'active';
                    elseif ( $idx < $current_index ) $class = 'done';
                ?>
                    <li class="<?php echo esc_attr( $class ); ?>"><?php echo esc_html( $label ); ?></li>
                <?php endforeach; ?>
            </ul>

            <div class="wizard-content">
                <?php
                $method = 'render_step_' . $this->current_step;
                if ( method_exists( $this, $method ) ) {
                    $this->$method();
                }
                ?>
            </div>
        </div>
        <style>
        .unpatti-setup-wizard { max-width: 700px; margin: 40px auto; }
        .wizard-steps { display: flex; list-style: none; padding: 0; margin: 0 0 30px; gap: 0; }
        .wizard-steps li { flex: 1; text-align: center; padding: 12px 8px; background: #f0f0f1; border-bottom: 3px solid #ddd; font-size: 13px; }
        .wizard-steps li.active { background: #fff; border-bottom-color: #2271b1; font-weight: 600; }
        .wizard-steps li.done { background: #f0f7ed; border-bottom-color: #00a32a; }
        .wizard-content { background: #fff; padding: 30px; border: 1px solid #ddd; }
        .wizard-content h2 { margin-top: 0; }
        .wizard-nav { margin-top: 20px; display: flex; justify-content: space-between; }
        </style>
        <?php
    }

    private function render_step_welcome() {
        $mode = get_theme_mod( 'unpatti_site_mode', 'prodi' );
        ?>
        <h2><?php esc_html_e( 'Selamat Datang!', 'unpatti-academic' ); ?></h2>
        <p><?php esc_html_e( 'Wizard ini akan membantu Anda mengatur tema UNPATTI Academic. Pilih mode situs Anda:', 'unpatti-academic' ); ?></p>
        <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
            <?php wp_nonce_field( 'unpatti_wizard_welcome' ); ?>
            <input type="hidden" name="action" value="unpatti_wizard_save" />
            <input type="hidden" name="wizard_step" value="welcome" />
            <table class="form-table">
                <tr>
                    <th><?php esc_html_e( 'Mode Situs', 'unpatti-academic' ); ?></th>
                    <td>
                        <label><input type="radio" name="site_mode" value="fakultas" <?php checked( $mode, 'fakultas' ); ?> /> <?php esc_html_e( 'Fakultas', 'unpatti-academic' ); ?></label><br/>
                        <label><input type="radio" name="site_mode" value="prodi" <?php checked( $mode, 'prodi' ); ?> /> <?php esc_html_e( 'Program Studi', 'unpatti-academic' ); ?></label>
                    </td>
                </tr>
            </table>
            <div class="wizard-nav">
                <span></span>
                <?php submit_button( __( 'Lanjut &rarr;', 'unpatti-academic' ), 'primary', 'submit', false ); ?>
            </div>
        </form>
        <?php
    }

    private function render_step_warna() {
        $primary   = get_theme_mod( 'unpatti_primary_color', '#003d82' );
        $secondary = get_theme_mod( 'unpatti_secondary_color', '#e67e22' );
        ?>
        <h2><?php esc_html_e( 'Warna Situs', 'unpatti-academic' ); ?></h2>
        <p><?php esc_html_e( 'Pilih warna utama dan sekunder untuk situs Anda.', 'unpatti-academic' ); ?></p>
        <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
            <?php wp_nonce_field( 'unpatti_wizard_warna' ); ?>
            <input type="hidden" name="action" value="unpatti_wizard_save" />
            <input type="hidden" name="wizard_step" value="warna" />
            <table class="form-table">
                <tr>
                    <th><?php esc_html_e( 'Warna Primary', 'unpatti-academic' ); ?></th>
                    <td><input type="text" name="primary_color" value="<?php echo esc_attr( $primary ); ?>" class="unpatti-color-field" /></td>
                </tr>
                <tr>
                    <th><?php esc_html_e( 'Warna Secondary', 'unpatti-academic' ); ?></th>
                    <td><input type="text" name="secondary_color" value="<?php echo esc_attr( $secondary ); ?>" class="unpatti-color-field" /></td>
                </tr>
            </table>
            <script>jQuery(document).ready(function($){ $('.unpatti-color-field').wpColorPicker(); });</script>
            <div class="wizard-nav">
                <a href="<?php echo esc_url( admin_url( 'admin.php?page=unpatti-setup-wizard&step=welcome' ) ); ?>" class="button"><?php esc_html_e( '&larr; Kembali', 'unpatti-academic' ); ?></a>
                <?php submit_button( __( 'Lanjut &rarr;', 'unpatti-academic' ), 'primary', 'submit', false ); ?>
            </div>
        </form>
        <?php
    }

    private function render_step_identitas() {
        $logo_id = get_theme_mod( 'custom_logo', '' );
        $name    = get_theme_mod( 'unpatti_institution_name', '' );
        $address = get_theme_mod( 'unpatti_institution_address', '' );
        ?>
        <h2><?php esc_html_e( 'Identitas Institusi', 'unpatti-academic' ); ?></h2>
        <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
            <?php wp_nonce_field( 'unpatti_wizard_identitas' ); ?>
            <input type="hidden" name="action" value="unpatti_wizard_save" />
            <input type="hidden" name="wizard_step" value="identitas" />
            <table class="form-table">
                <tr>
                    <th><?php esc_html_e( 'Logo', 'unpatti-academic' ); ?></th>
                    <td>
                        <input type="hidden" name="logo_id" id="unpatti-logo-id" value="<?php echo esc_attr( $logo_id ); ?>" />
                        <div id="unpatti-logo-preview">
                            <?php if ( $logo_id ) : ?>
                                <?php echo wp_get_attachment_image( $logo_id, 'medium' ); ?>
                            <?php endif; ?>
                        </div>
                        <button type="button" class="button" id="unpatti-upload-logo"><?php esc_html_e( 'Upload Logo', 'unpatti-academic' ); ?></button>
                        <button type="button" class="button" id="unpatti-remove-logo" <?php echo empty( $logo_id ) ? 'style="display:none"' : ''; ?>><?php esc_html_e( 'Hapus', 'unpatti-academic' ); ?></button>
                        <script>
                        jQuery(document).ready(function($){
                            var frame;
                            $('#unpatti-upload-logo').on('click', function(e){
                                e.preventDefault();
                                if (frame) { frame.open(); return; }
                                frame = wp.media({ title: 'Pilih Logo', multiple: false, library: { type: 'image' } });
                                frame.on('select', function(){
                                    var attachment = frame.state().get('selection').first().toJSON();
                                    $('#unpatti-logo-id').val(attachment.id);
                                    $('#unpatti-logo-preview').html('<img src="'+attachment.url+'" style="max-width:200px;"/>');
                                    $('#unpatti-remove-logo').show();
                                });
                                frame.open();
                            });
                            $('#unpatti-remove-logo').on('click', function(){
                                $('#unpatti-logo-id').val('');
                                $('#unpatti-logo-preview').html('');
                                $(this).hide();
                            });
                        });
                        </script>
                    </td>
                </tr>
                <tr>
                    <th><?php esc_html_e( 'Nama Institusi', 'unpatti-academic' ); ?></th>
                    <td><input type="text" name="institution_name" value="<?php echo esc_attr( $name ); ?>" class="regular-text" /></td>
                </tr>
                <tr>
                    <th><?php esc_html_e( 'Alamat', 'unpatti-academic' ); ?></th>
                    <td><textarea name="institution_address" rows="3" class="large-text"><?php echo esc_textarea( $address ); ?></textarea></td>
                </tr>
            </table>
            <div class="wizard-nav">
                <a href="<?php echo esc_url( admin_url( 'admin.php?page=unpatti-setup-wizard&step=warna' ) ); ?>" class="button"><?php esc_html_e( '&larr; Kembali', 'unpatti-academic' ); ?></a>
                <?php submit_button( __( 'Lanjut &rarr;', 'unpatti-academic' ), 'primary', 'submit', false ); ?>
            </div>
        </form>
        <?php
    }

    private function render_step_demo() {
        ?>
        <h2><?php esc_html_e( 'Demo Content', 'unpatti-academic' ); ?></h2>
        <p><?php esc_html_e( 'Klik tombol di bawah untuk membuat halaman-halaman starter dan menu utama.', 'unpatti-academic' ); ?></p>
        <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
            <?php wp_nonce_field( 'unpatti_wizard_demo' ); ?>
            <input type="hidden" name="action" value="unpatti_wizard_save" />
            <input type="hidden" name="wizard_step" value="demo" />
            <?php submit_button( __( 'Import Demo Content', 'unpatti-academic' ), 'primary', 'submit', false ); ?>
            <p class="description"><?php esc_html_e( 'Halaman yang sudah ada (berdasarkan judul) tidak akan diduplikasi.', 'unpatti-academic' ); ?></p>
            <div class="wizard-nav" style="margin-top:20px;">
                <a href="<?php echo esc_url( admin_url( 'admin.php?page=unpatti-setup-wizard&step=identitas' ) ); ?>" class="button"><?php esc_html_e( '&larr; Kembali', 'unpatti-academic' ); ?></a>
                <a href="<?php echo esc_url( admin_url( 'admin.php?page=unpatti-setup-wizard&step=selesai' ) ); ?>" class="button"><?php esc_html_e( 'Lewati &rarr;', 'unpatti-academic' ); ?></a>
            </div>
        </form>
        <?php
    }

    private function render_step_selesai() {
        set_transient( 'unpatti_setup_wizard_done', true, 0 );
        ?>
        <h2><?php esc_html_e( 'Setup Selesai!', 'unpatti-academic' ); ?></h2>
        <p><?php esc_html_e( 'Tema UNPATTI Academic sudah siap digunakan. Anda dapat melakukan kustomisasi lebih lanjut melalui Customizer.', 'unpatti-academic' ); ?></p>
        <div class="wizard-nav">
            <a href="<?php echo esc_url( admin_url( 'customize.php' ) ); ?>" class="button button-primary"><?php esc_html_e( 'Buka Customizer', 'unpatti-academic' ); ?></a>
            <a href="<?php echo esc_url( admin_url() ); ?>" class="button"><?php esc_html_e( 'Ke Dashboard', 'unpatti-academic' ); ?></a>
        </div>
        <?php
    }

    public function handle_save() {
        $step = isset( $_POST['wizard_step'] ) ? sanitize_key( $_POST['wizard_step'] ) : '';

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( __( 'Unauthorized', 'unpatti-academic' ) );
        }

        $steps = $this->get_steps();
        $step_keys = array_keys( $steps );

        switch ( $step ) {
            case 'welcome':
                check_admin_referer( 'unpatti_wizard_welcome' );
                $mode = sanitize_text_field( $_POST['site_mode'] ?? 'prodi' );
                if ( in_array( $mode, [ 'fakultas', 'prodi' ], true ) ) {
                    set_theme_mod( 'unpatti_site_mode', $mode );
                }
                $next = 'warna';
                break;

            case 'warna':
                check_admin_referer( 'unpatti_wizard_warna' );
                $primary = sanitize_hex_color( $_POST['primary_color'] ?? '#003d82' );
                $secondary = sanitize_hex_color( $_POST['secondary_color'] ?? '#e67e22' );
                if ( $primary ) set_theme_mod( 'unpatti_primary_color', $primary );
                if ( $secondary ) set_theme_mod( 'unpatti_secondary_color', $secondary );
                $next = 'identitas';
                break;

            case 'identitas':
                check_admin_referer( 'unpatti_wizard_identitas' );
                $logo_id = absint( $_POST['logo_id'] ?? 0 );
                if ( $logo_id ) {
                    set_theme_mod( 'custom_logo', $logo_id );
                } else {
                    remove_theme_mod( 'custom_logo' );
                }
                set_theme_mod( 'unpatti_institution_name', sanitize_text_field( $_POST['institution_name'] ?? '' ) );
                set_theme_mod( 'unpatti_institution_address', sanitize_textarea_field( $_POST['institution_address'] ?? '' ) );
                $next = 'demo';
                break;

            case 'demo':
                check_admin_referer( 'unpatti_wizard_demo' );
                $this->create_demo_content();
                $next = 'selesai';
                break;

            default:
                $next = 'welcome';
                break;
        }

        wp_safe_redirect( admin_url( 'admin.php?page=unpatti-setup-wizard&step=' . $next ) );
        exit;
    }

    private function create_demo_content() {
        $pages = [
            'Beranda'             => 'page-beranda.php',
            'Sejarah'             => 'page-sejarah.php',
            'Visi Misi'           => 'page-visi-misi.php',
            'Struktur Organisasi' => 'page-struktur-organisasi.php',
            'Sambutan'            => 'page-sambutan.php',
            'Akreditasi'          => 'page-akreditasi.php',
            'CPL'                 => 'page-cpl.php',
            'Penerimaan'          => 'page-penerimaan.php',
            'Biaya UKT'           => 'page-biaya-ukt.php',
            'Statistik'           => 'page-statistik.php',
            'Tracer Study'        => 'page-tracer-study.php',
        ];

        $page_ids = [];

        foreach ( $pages as $title => $template ) {
            $existing = get_page_by_title( $title, OBJECT, 'page' );
            if ( $existing ) {
                $page_ids[ $title ] = $existing->ID;
                continue;
            }

            $id = wp_insert_post( [
                'post_title'   => $title,
                'post_content' => '',
                'post_status'  => 'publish',
                'post_type'    => 'page',
            ] );

            if ( ! is_wp_error( $id ) ) {
                update_post_meta( $id, '_wp_page_template', $template );
                $page_ids[ $title ] = $id;
            }
        }

        // Set Beranda as static front page
        if ( ! empty( $page_ids['Beranda'] ) ) {
            update_option( 'show_on_front', 'page' );
            update_option( 'page_on_front', $page_ids['Beranda'] );
        }

        // Create primary menu
        $menu_name = 'Menu Utama';
        $menu_exists = wp_get_nav_menu_object( $menu_name );
        if ( ! $menu_exists ) {
            $menu_id = wp_create_nav_menu( $menu_name );
        } else {
            $menu_id = $menu_exists->term_id;
        }

        if ( ! is_wp_error( $menu_id ) ) {
            // Assign to primary location
            $locations = get_theme_mod( 'nav_menu_locations', [] );
            $locations['primary'] = $menu_id;
            set_theme_mod( 'nav_menu_locations', $locations );

            // Only add items if menu was just created
            if ( ! $menu_exists ) {
                // Beranda
                $beranda_item = wp_update_nav_menu_item( $menu_id, 0, [
                    'menu-item-title'     => 'Beranda',
                    'menu-item-object'    => 'page',
                    'menu-item-object-id' => $page_ids['Beranda'] ?? 0,
                    'menu-item-type'      => 'post_type',
                    'menu-item-status'    => 'publish',
                ] );

                // Profil (parent)
                $profil_item = wp_update_nav_menu_item( $menu_id, 0, [
                    'menu-item-title'  => 'Profil',
                    'menu-item-url'    => '#',
                    'menu-item-type'   => 'custom',
                    'menu-item-status' => 'publish',
                ] );

                // Profil children
                $profil_children = [ 'Sejarah', 'Visi Misi', 'Struktur Organisasi', 'Sambutan' ];
                foreach ( $profil_children as $child ) {
                    if ( ! empty( $page_ids[ $child ] ) ) {
                        wp_update_nav_menu_item( $menu_id, 0, [
                            'menu-item-title'      => $child,
                            'menu-item-object'     => 'page',
                            'menu-item-object-id'  => $page_ids[ $child ],
                            'menu-item-type'       => 'post_type',
                            'menu-item-status'     => 'publish',
                            'menu-item-parent-id'  => $profil_item,
                        ] );
                    }
                }

                // Akademik (parent)
                $akademik_item = wp_update_nav_menu_item( $menu_id, 0, [
                    'menu-item-title'  => 'Akademik',
                    'menu-item-url'    => '#',
                    'menu-item-type'   => 'custom',
                    'menu-item-status' => 'publish',
                ] );

                $akademik_children = [ 'Akreditasi', 'CPL' ];
                foreach ( $akademik_children as $child ) {
                    if ( ! empty( $page_ids[ $child ] ) ) {
                        wp_update_nav_menu_item( $menu_id, 0, [
                            'menu-item-title'      => $child,
                            'menu-item-object'     => 'page',
                            'menu-item-object-id'  => $page_ids[ $child ],
                            'menu-item-type'       => 'post_type',
                            'menu-item-status'     => 'publish',
                            'menu-item-parent-id'  => $akademik_item,
                        ] );
                    }
                }

                // Kemahasiswaan (parent)
                $mhs_item = wp_update_nav_menu_item( $menu_id, 0, [
                    'menu-item-title'  => 'Kemahasiswaan',
                    'menu-item-url'    => '#',
                    'menu-item-type'   => 'custom',
                    'menu-item-status' => 'publish',
                ] );

                $mhs_children = [ 'Penerimaan', 'Biaya UKT' ];
                foreach ( $mhs_children as $child ) {
                    if ( ! empty( $page_ids[ $child ] ) ) {
                        wp_update_nav_menu_item( $menu_id, 0, [
                            'menu-item-title'      => $child,
                            'menu-item-object'     => 'page',
                            'menu-item-object-id'  => $page_ids[ $child ],
                            'menu-item-type'       => 'post_type',
                            'menu-item-status'     => 'publish',
                            'menu-item-parent-id'  => $mhs_item,
                        ] );
                    }
                }

                // Data (parent)
                $data_item = wp_update_nav_menu_item( $menu_id, 0, [
                    'menu-item-title'  => 'Data',
                    'menu-item-url'    => '#',
                    'menu-item-type'   => 'custom',
                    'menu-item-status' => 'publish',
                ] );

                $data_children = [ 'Statistik', 'Tracer Study' ];
                foreach ( $data_children as $child ) {
                    if ( ! empty( $page_ids[ $child ] ) ) {
                        wp_update_nav_menu_item( $menu_id, 0, [
                            'menu-item-title'      => $child,
                            'menu-item-object'     => 'page',
                            'menu-item-object-id'  => $page_ids[ $child ],
                            'menu-item-type'       => 'post_type',
                            'menu-item-status'     => 'publish',
                            'menu-item-parent-id'  => $data_item,
                        ] );
                    }
                }
            }
        }
    }
}
