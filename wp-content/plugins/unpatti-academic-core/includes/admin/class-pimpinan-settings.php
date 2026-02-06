<?php
namespace UNPATTI\Core\Admin;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Pimpinan Settings Page
 * Single configuration for Ketua Program Studi
 */
class Pimpinan_Settings {

    private $option_name = 'unpatti_pimpinan_settings';

    public function __construct() {
        add_action( 'admin_menu', [ $this, 'add_menu_page' ] );
        add_action( 'admin_init', [ $this, 'register_settings' ] );
        add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
    }

    /**
     * Add menu page - replaces CPT menu
     */
    public function add_menu_page() {
        add_menu_page(
            __( 'Pimpinan', 'unpatti-academic' ),
            __( 'Pimpinan', 'unpatti-academic' ),
            'manage_options',
            'unpatti-pimpinan',
            [ $this, 'render_page' ],
            'dashicons-businessman',
            26
        );
    }

    /**
     * Enqueue media scripts for image upload
     */
    public function enqueue_scripts( $hook ) {
        if ( $hook !== 'toplevel_page_unpatti-pimpinan' ) {
            return;
        }
        wp_enqueue_media();
        wp_enqueue_script(
            'unpatti-pimpinan-settings',
            UNPATTI_CORE_URL . 'assets/js/pimpinan-settings.js',
            [ 'jquery' ],
            UNPATTI_CORE_VERSION,
            true
        );
        wp_enqueue_style(
            'unpatti-pimpinan-settings',
            UNPATTI_CORE_URL . 'assets/css/pimpinan-settings.css',
            [],
            UNPATTI_CORE_VERSION
        );
    }

    /**
     * Register settings
     */
    public function register_settings() {
        register_setting(
            'unpatti_pimpinan_group',
            $this->option_name,
            [ $this, 'sanitize_settings' ]
        );
    }

    /**
     * Sanitize settings
     */
    public function sanitize_settings( $input ) {
        $sanitized = [];

        $sanitized['foto_id'] = isset( $input['foto_id'] ) ? absint( $input['foto_id'] ) : 0;
        $sanitized['nama'] = isset( $input['nama'] ) ? sanitize_text_field( $input['nama'] ) : '';
        $sanitized['gelar_depan'] = isset( $input['gelar_depan'] ) ? sanitize_text_field( $input['gelar_depan'] ) : '';
        $sanitized['gelar_belakang'] = isset( $input['gelar_belakang'] ) ? sanitize_text_field( $input['gelar_belakang'] ) : '';
        $sanitized['nip'] = isset( $input['nip'] ) ? sanitize_text_field( $input['nip'] ) : '';
        $sanitized['jabatan'] = isset( $input['jabatan'] ) ? sanitize_text_field( $input['jabatan'] ) : '';
        $sanitized['email'] = isset( $input['email'] ) ? sanitize_email( $input['email'] ) : '';
        $sanitized['periode'] = isset( $input['periode'] ) ? sanitize_text_field( $input['periode'] ) : '';
        $sanitized['bio'] = isset( $input['bio'] ) ? wp_kses_post( $input['bio'] ) : '';
        $sanitized['sambutan'] = isset( $input['sambutan'] ) ? wp_kses_post( $input['sambutan'] ) : '';

        return $sanitized;
    }

    /**
     * Get settings
     */
    public static function get_settings() {
        $defaults = [
            'foto_id' => 0,
            'nama' => '',
            'gelar_depan' => '',
            'gelar_belakang' => '',
            'nip' => '',
            'jabatan' => 'Ketua Program Studi',
            'email' => '',
            'periode' => '',
            'bio' => '',
            'sambutan' => '',
        ];

        $settings = get_option( 'unpatti_pimpinan_settings', [] );
        return wp_parse_args( $settings, $defaults );
    }

    /**
     * Render settings page
     */
    public function render_page() {
        $settings = self::get_settings();
        $foto_url = $settings['foto_id'] ? wp_get_attachment_image_url( $settings['foto_id'], 'medium' ) : '';
        ?>
        <div class="wrap unpatti-pimpinan-settings">
            <h1><?php esc_html_e( 'Pengaturan Pimpinan', 'unpatti-academic' ); ?></h1>
            <p class="description"><?php esc_html_e( 'Konfigurasi data Ketua Program Studi yang akan ditampilkan di halaman Sambutan.', 'unpatti-academic' ); ?></p>

            <form method="post" action="options.php">
                <?php settings_fields( 'unpatti_pimpinan_group' ); ?>

                <div class="pimpinan-form-container">
                    <div class="pimpinan-photo-section">
                        <h2><?php esc_html_e( 'Foto', 'unpatti-academic' ); ?></h2>
                        <div class="pimpinan-photo-wrapper">
                            <div class="pimpinan-photo-preview" id="foto-preview" style="<?php echo $foto_url ? '' : 'display:none;'; ?>">
                                <img src="<?php echo esc_url( $foto_url ); ?>" alt="" id="foto-preview-img">
                            </div>
                            <div class="pimpinan-photo-placeholder" id="foto-placeholder" style="<?php echo $foto_url ? 'display:none;' : ''; ?>">
                                <span class="dashicons dashicons-admin-users"></span>
                                <span><?php esc_html_e( 'Belum ada foto', 'unpatti-academic' ); ?></span>
                            </div>
                            <input type="hidden" name="<?php echo esc_attr( $this->option_name ); ?>[foto_id]" id="foto_id" value="<?php echo esc_attr( $settings['foto_id'] ); ?>">
                            <div class="pimpinan-photo-buttons">
                                <button type="button" class="button" id="upload-foto-btn"><?php esc_html_e( 'Pilih Foto', 'unpatti-academic' ); ?></button>
                                <button type="button" class="button" id="remove-foto-btn" style="<?php echo $foto_url ? '' : 'display:none;'; ?>"><?php esc_html_e( 'Hapus', 'unpatti-academic' ); ?></button>
                            </div>
                        </div>
                    </div>

                    <div class="pimpinan-info-section">
                        <h2><?php esc_html_e( 'Informasi Pimpinan', 'unpatti-academic' ); ?></h2>

                        <table class="form-table">
                            <tr>
                                <th scope="row">
                                    <label for="nama"><?php esc_html_e( 'Nama Lengkap', 'unpatti-academic' ); ?></label>
                                </th>
                                <td>
                                    <input type="text" id="nama" name="<?php echo esc_attr( $this->option_name ); ?>[nama]" value="<?php echo esc_attr( $settings['nama'] ); ?>" class="regular-text" placeholder="Contoh: John Doe">
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <label for="gelar_depan"><?php esc_html_e( 'Gelar Depan', 'unpatti-academic' ); ?></label>
                                </th>
                                <td>
                                    <input type="text" id="gelar_depan" name="<?php echo esc_attr( $this->option_name ); ?>[gelar_depan]" value="<?php echo esc_attr( $settings['gelar_depan'] ); ?>" class="regular-text" placeholder="Contoh: Dr., Prof., Ir.">
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <label for="gelar_belakang"><?php esc_html_e( 'Gelar Belakang', 'unpatti-academic' ); ?></label>
                                </th>
                                <td>
                                    <input type="text" id="gelar_belakang" name="<?php echo esc_attr( $this->option_name ); ?>[gelar_belakang]" value="<?php echo esc_attr( $settings['gelar_belakang'] ); ?>" class="regular-text" placeholder="Contoh: S.Kom., M.Cs., Ph.D.">
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <label for="nip"><?php esc_html_e( 'NIP', 'unpatti-academic' ); ?></label>
                                </th>
                                <td>
                                    <input type="text" id="nip" name="<?php echo esc_attr( $this->option_name ); ?>[nip]" value="<?php echo esc_attr( $settings['nip'] ); ?>" class="regular-text">
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <label for="jabatan"><?php esc_html_e( 'Jabatan', 'unpatti-academic' ); ?></label>
                                </th>
                                <td>
                                    <input type="text" id="jabatan" name="<?php echo esc_attr( $this->option_name ); ?>[jabatan]" value="<?php echo esc_attr( $settings['jabatan'] ); ?>" class="regular-text" placeholder="Contoh: Ketua Program Studi">
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <label for="email"><?php esc_html_e( 'Email', 'unpatti-academic' ); ?></label>
                                </th>
                                <td>
                                    <input type="email" id="email" name="<?php echo esc_attr( $this->option_name ); ?>[email]" value="<?php echo esc_attr( $settings['email'] ); ?>" class="regular-text">
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <label for="periode"><?php esc_html_e( 'Periode Jabatan', 'unpatti-academic' ); ?></label>
                                </th>
                                <td>
                                    <input type="text" id="periode" name="<?php echo esc_attr( $this->option_name ); ?>[periode]" value="<?php echo esc_attr( $settings['periode'] ); ?>" class="regular-text" placeholder="Contoh: 2022-2026">
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <label for="bio"><?php esc_html_e( 'Bio Singkat', 'unpatti-academic' ); ?></label>
                                </th>
                                <td>
                                    <textarea id="bio" name="<?php echo esc_attr( $this->option_name ); ?>[bio]" rows="3" class="large-text"><?php echo esc_textarea( $settings['bio'] ); ?></textarea>
                                    <p class="description"><?php esc_html_e( 'Riwayat singkat pimpinan (pendidikan, keahlian, dll).', 'unpatti-academic' ); ?></p>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="pimpinan-sambutan-section">
                    <h2><?php esc_html_e( 'Teks Sambutan', 'unpatti-academic' ); ?></h2>
                    <p class="description"><?php esc_html_e( 'Tulis sambutan lengkap dari Ketua Program Studi yang akan ditampilkan di halaman Sambutan.', 'unpatti-academic' ); ?></p>
                    <?php
                    wp_editor( $settings['sambutan'], 'sambutan', [
                        'textarea_name' => $this->option_name . '[sambutan]',
                        'textarea_rows' => 15,
                        'media_buttons' => false,
                        'teeny'         => false,
                        'quicktags'     => true,
                    ] );
                    ?>
                </div>

                <?php submit_button( __( 'Simpan Pengaturan', 'unpatti-academic' ) ); ?>
            </form>
        </div>
        <?php
    }
}
