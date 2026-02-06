<?php
namespace UNPATTI\Core\Admin;

if ( ! defined( 'ABSPATH' ) ) exit;

class Admin_Settings {
    private $option_name = 'unpatti_settings';

    public function register() {
        add_action( 'admin_menu', [ $this, 'add_menu' ] );
        add_action( 'admin_init', [ $this, 'register_settings' ] );
    }

    public function add_menu() {
        add_menu_page(
            __( 'UNPATTI Academic', 'unpatti-academic' ),
            __( 'UNPATTI Academic', 'unpatti-academic' ),
            'manage_options',
            'unpatti-academic',
            [ $this, 'render_page' ],
            'dashicons-university',
            3
        );
    }

    public function register_settings() {
        register_setting( 'unpatti_settings_group', $this->option_name, [
            'sanitize_callback' => [ $this, 'sanitize_settings' ],
        ] );
    }

    public function sanitize_settings( $input ) {
        $sanitized = [];

        // Security tab
        $sanitized['security_xmlrpc_disabled']  = ! empty( $input['security_xmlrpc_disabled'] ) ? 1 : 0;
        $sanitized['security_user_enum_disabled'] = ! empty( $input['security_user_enum_disabled'] ) ? 1 : 0;
        $sanitized['security_file_edit_disabled'] = ! empty( $input['security_file_edit_disabled'] ) ? 1 : 0;
        $sanitized['security_scanner_enabled']  = ! empty( $input['security_scanner_enabled'] ) ? 1 : 0;
        $sanitized['security_scanner_keywords'] = sanitize_textarea_field( $input['security_scanner_keywords'] ?? '' );
        $sanitized['security_whitelist_domains'] = sanitize_textarea_field( $input['security_whitelist_domains'] ?? '' );

        // SSO tab
        $sanitized['sso_enabled']       = ! empty( $input['sso_enabled'] ) ? 1 : 0;
        $sanitized['sso_base_url']      = esc_url_raw( $input['sso_base_url'] ?? '' );
        $sanitized['sso_client_id']     = sanitize_text_field( $input['sso_client_id'] ?? '' );
        $sanitized['sso_client_secret'] = sanitize_text_field( $input['sso_client_secret'] ?? '' );
        $sanitized['sso_role_mapping']  = sanitize_textarea_field( $input['sso_role_mapping'] ?? '' );
        $sanitized['sso_fallback_admin'] = sanitize_user( $input['sso_fallback_admin'] ?? '' );

        // API tab
        $sanitized['api_siakad_url']   = esc_url_raw( $input['api_siakad_url'] ?? '' );
        $sanitized['api_siakad_key']   = sanitize_text_field( $input['api_siakad_key'] ?? '' );
        $sanitized['api_sigap_url']    = esc_url_raw( $input['api_sigap_url'] ?? '' );
        $sanitized['api_sigap_key']    = sanitize_text_field( $input['api_sigap_key'] ?? '' );
        $sanitized['api_cache_ttl']    = absint( $input['api_cache_ttl'] ?? 3600 );

        // Update server
        $sanitized['update_server_url'] = esc_url_raw( $input['update_server_url'] ?? '' );

        return $sanitized;
    }

    public function get_option( $key, $default = '' ) {
        $options = get_option( $this->option_name, [] );
        return $options[ $key ] ?? $default;
    }

    public function render_page() {
        $active_tab = isset( $_GET['tab'] ) ? sanitize_text_field( $_GET['tab'] ) : 'umum';
        $tabs = [
            'umum'      => __( 'Umum', 'unpatti-academic' ),
            'pages'     => __( 'Halaman', 'unpatti-academic' ),
            'tools'     => __( 'Tools', 'unpatti-academic' ),
            'keamanan'  => __( 'Keamanan', 'unpatti-academic' ),
            'sso'       => __( 'SSO', 'unpatti-academic' ),
            'api'       => __( 'Integrasi API', 'unpatti-academic' ),
            'export'    => __( 'Export / Import', 'unpatti-academic' ),
        ];
        ?>
        <div class="wrap">
            <h1><?php esc_html_e( 'UNPATTI Academic Settings', 'unpatti-academic' ); ?></h1>

            <h2 class="nav-tab-wrapper">
                <?php foreach ( $tabs as $tab_key => $tab_label ) : ?>
                    <a href="<?php echo esc_url( admin_url( 'admin.php?page=unpatti-academic&tab=' . $tab_key ) ); ?>"
                       class="nav-tab <?php echo $active_tab === $tab_key ? 'nav-tab-active' : ''; ?>">
                        <?php echo esc_html( $tab_label ); ?>
                    </a>
                <?php endforeach; ?>
            </h2>

            <form method="post" action="options.php">
                <?php settings_fields( 'unpatti_settings_group' ); ?>

                <?php
                switch ( $active_tab ) {
                    case 'umum':
                        $this->render_tab_umum();
                        break;
                    case 'pages':
                        $this->render_tab_pages();
                        break;
                    case 'tools':
                        $this->render_tab_tools();
                        break;
                    case 'keamanan':
                        $this->render_tab_keamanan();
                        break;
                    case 'sso':
                        $this->render_tab_sso();
                        break;
                    case 'api':
                        $this->render_tab_api();
                        break;
                    case 'export':
                        $this->render_tab_export();
                        break;
                }
                ?>

                <?php if ( ! in_array( $active_tab, [ 'export', 'umum', 'pages', 'tools' ], true ) ) : ?>
                    <?php submit_button(); ?>
                <?php endif; ?>
            </form>
        </div>
        <?php
    }

    private function render_tab_umum() {
        $mode = get_theme_mod( 'unpatti_site_mode', 'prodi' );
        $name = get_theme_mod( 'unpatti_institution_name', '' );
        ?>
        <table class="form-table">
            <tr>
                <th><?php esc_html_e( 'Mode Situs', 'unpatti-academic' ); ?></th>
                <td><strong><?php echo $mode === 'fakultas' ? 'Fakultas' : 'Program Studi'; ?></strong></td>
            </tr>
            <tr>
                <th><?php esc_html_e( 'Nama Institusi', 'unpatti-academic' ); ?></th>
                <td><?php echo esc_html( $name ?: '-' ); ?></td>
            </tr>
            <tr>
                <th><?php esc_html_e( 'Warna Primary', 'unpatti-academic' ); ?></th>
                <td><span style="display:inline-block;width:24px;height:24px;background:<?php echo esc_attr( get_theme_mod('unpatti_primary_color','#003d82') ); ?>;border-radius:3px;vertical-align:middle;"></span> <?php echo esc_html( get_theme_mod('unpatti_primary_color','#003d82') ); ?></td>
            </tr>
            <tr>
                <th><?php esc_html_e( 'Warna Secondary', 'unpatti-academic' ); ?></th>
                <td><span style="display:inline-block;width:24px;height:24px;background:<?php echo esc_attr( get_theme_mod('unpatti_secondary_color','#e67e22') ); ?>;border-radius:3px;vertical-align:middle;"></span> <?php echo esc_html( get_theme_mod('unpatti_secondary_color','#e67e22') ); ?></td>
            </tr>
        </table>
        <p><a href="<?php echo esc_url( admin_url( 'customize.php' ) ); ?>" class="button"><?php esc_html_e( 'Buka Customizer', 'unpatti-academic' ); ?></a></p>
        <?php
    }

    private function render_tab_pages() {
        $page_updater = new Page_Updater();
        $page_updater->render_tab();
    }

    private function render_tab_tools() {
        $post_fixer = new Post_Status_Fixer();
        $post_fixer->render_tools_tab();
    }

    private function render_tab_keamanan() {
        ?>
        <table class="form-table">
            <tr>
                <th><?php esc_html_e( 'Disable XML-RPC', 'unpatti-academic' ); ?></th>
                <td><label><input type="checkbox" name="<?php echo $this->option_name; ?>[security_xmlrpc_disabled]" value="1" <?php checked( $this->get_option('security_xmlrpc_disabled', 1) ); ?> /> <?php esc_html_e( 'Nonaktifkan XML-RPC', 'unpatti-academic' ); ?></label></td>
            </tr>
            <tr>
                <th><?php esc_html_e( 'Disable User Enumeration', 'unpatti-academic' ); ?></th>
                <td><label><input type="checkbox" name="<?php echo $this->option_name; ?>[security_user_enum_disabled]" value="1" <?php checked( $this->get_option('security_user_enum_disabled', 1) ); ?> /> <?php esc_html_e( 'Blokir enumerasi user via REST API', 'unpatti-academic' ); ?></label></td>
            </tr>
            <tr>
                <th><?php esc_html_e( 'Disable File Editor', 'unpatti-academic' ); ?></th>
                <td><label><input type="checkbox" name="<?php echo $this->option_name; ?>[security_file_edit_disabled]" value="1" <?php checked( $this->get_option('security_file_edit_disabled', 1) ); ?> /> <?php esc_html_e( 'Nonaktifkan editor file di admin', 'unpatti-academic' ); ?></label></td>
            </tr>
            <tr>
                <th><?php esc_html_e( 'Content Scanner', 'unpatti-academic' ); ?></th>
                <td><label><input type="checkbox" name="<?php echo $this->option_name; ?>[security_scanner_enabled]" value="1" <?php checked( $this->get_option('security_scanner_enabled', 1) ); ?> /> <?php esc_html_e( 'Aktifkan scanner konten otomatis (anti judi online)', 'unpatti-academic' ); ?></label></td>
            </tr>
            <tr>
                <th><?php esc_html_e( 'Keyword Tambahan', 'unpatti-academic' ); ?></th>
                <td>
                    <textarea name="<?php echo $this->option_name; ?>[security_scanner_keywords]" rows="4" class="large-text"><?php echo esc_textarea( $this->get_option('security_scanner_keywords') ); ?></textarea>
                    <p class="description"><?php esc_html_e( 'Satu keyword per baris. Ditambahkan ke daftar keyword bawaan.', 'unpatti-academic' ); ?></p>
                </td>
            </tr>
            <tr>
                <th><?php esc_html_e( 'Domain Whitelist', 'unpatti-academic' ); ?></th>
                <td>
                    <textarea name="<?php echo $this->option_name; ?>[security_whitelist_domains]" rows="4" class="large-text"><?php echo esc_textarea( $this->get_option('security_whitelist_domains') ); ?></textarea>
                    <p class="description"><?php esc_html_e( 'Domain yang diizinkan untuk outbound links. Satu per baris. Contoh: unpatti.ac.id', 'unpatti-academic' ); ?></p>
                </td>
            </tr>
        </table>
        <?php
    }

    private function render_tab_sso() {
        ?>
        <table class="form-table">
            <tr>
                <th><?php esc_html_e( 'Aktifkan SSO', 'unpatti-academic' ); ?></th>
                <td><label><input type="checkbox" name="<?php echo $this->option_name; ?>[sso_enabled]" value="1" <?php checked( $this->get_option('sso_enabled') ); ?> /> <?php esc_html_e( 'Login menggunakan SSO UNPATTI', 'unpatti-academic' ); ?></label></td>
            </tr>
            <tr>
                <th><?php esc_html_e( 'SSO Base URL', 'unpatti-academic' ); ?></th>
                <td><input type="url" name="<?php echo $this->option_name; ?>[sso_base_url]" value="<?php echo esc_attr( $this->get_option('sso_base_url', 'https://sso.unpatti.ac.id') ); ?>" class="regular-text" /></td>
            </tr>
            <tr>
                <th><?php esc_html_e( 'Client ID', 'unpatti-academic' ); ?></th>
                <td><input type="text" name="<?php echo $this->option_name; ?>[sso_client_id]" value="<?php echo esc_attr( $this->get_option('sso_client_id') ); ?>" class="regular-text" /></td>
            </tr>
            <tr>
                <th><?php esc_html_e( 'Client Secret', 'unpatti-academic' ); ?></th>
                <td><input type="password" name="<?php echo $this->option_name; ?>[sso_client_secret]" value="<?php echo esc_attr( $this->get_option('sso_client_secret') ); ?>" class="regular-text" /></td>
            </tr>
            <tr>
                <th><?php esc_html_e( 'Redirect URI', 'unpatti-academic' ); ?></th>
                <td><code><?php echo esc_html( admin_url( 'admin-ajax.php?action=unpatti_sso_callback' ) ); ?></code>
                <p class="description"><?php esc_html_e( 'Gunakan URL ini saat mendaftarkan aplikasi di SSO.', 'unpatti-academic' ); ?></p></td>
            </tr>
            <tr>
                <th><?php esc_html_e( 'Role Mapping', 'unpatti-academic' ); ?></th>
                <td>
                    <textarea name="<?php echo $this->option_name; ?>[sso_role_mapping]" rows="4" class="large-text"><?php echo esc_textarea( $this->get_option('sso_role_mapping', "Admin=administrator\nEditor=editor") ); ?></textarea>
                    <p class="description"><?php esc_html_e( 'Format: SSORole=wp_role (satu per baris). Contoh: Admin=administrator', 'unpatti-academic' ); ?></p>
                </td>
            </tr>
            <tr>
                <th><?php esc_html_e( 'Fallback Admin', 'unpatti-academic' ); ?></th>
                <td><input type="text" name="<?php echo $this->option_name; ?>[sso_fallback_admin]" value="<?php echo esc_attr( $this->get_option('sso_fallback_admin') ); ?>" class="regular-text" />
                <p class="description"><?php esc_html_e( 'Username admin lokal untuk emergency login (akses via ?fallback=1)', 'unpatti-academic' ); ?></p></td>
            </tr>
        </table>
        <?php
    }

    private function render_tab_api() {
        ?>
        <h3><?php esc_html_e( 'SIAKAD (Sistem Informasi Akademik)', 'unpatti-academic' ); ?></h3>
        <table class="form-table">
            <tr>
                <th><?php esc_html_e( 'Base URL', 'unpatti-academic' ); ?></th>
                <td><input type="url" name="<?php echo $this->option_name; ?>[api_siakad_url]" value="<?php echo esc_attr( $this->get_option('api_siakad_url') ); ?>" class="regular-text" /></td>
            </tr>
            <tr>
                <th><?php esc_html_e( 'API Key', 'unpatti-academic' ); ?></th>
                <td><input type="text" name="<?php echo $this->option_name; ?>[api_siakad_key]" value="<?php echo esc_attr( $this->get_option('api_siakad_key') ); ?>" class="regular-text" /></td>
            </tr>
        </table>
        <h3><?php esc_html_e( 'SIGAP (Sistem Informasi Kepegawaian)', 'unpatti-academic' ); ?></h3>
        <table class="form-table">
            <tr>
                <th><?php esc_html_e( 'Base URL', 'unpatti-academic' ); ?></th>
                <td><input type="url" name="<?php echo $this->option_name; ?>[api_sigap_url]" value="<?php echo esc_attr( $this->get_option('api_sigap_url') ); ?>" class="regular-text" /></td>
            </tr>
            <tr>
                <th><?php esc_html_e( 'API Key', 'unpatti-academic' ); ?></th>
                <td><input type="text" name="<?php echo $this->option_name; ?>[api_sigap_key]" value="<?php echo esc_attr( $this->get_option('api_sigap_key') ); ?>" class="regular-text" /></td>
            </tr>
        </table>
        <h3><?php esc_html_e( 'Cache', 'unpatti-academic' ); ?></h3>
        <table class="form-table">
            <tr>
                <th><?php esc_html_e( 'Cache TTL (detik)', 'unpatti-academic' ); ?></th>
                <td><input type="number" name="<?php echo $this->option_name; ?>[api_cache_ttl]" value="<?php echo esc_attr( $this->get_option('api_cache_ttl', 3600) ); ?>" class="small-text" /></td>
            </tr>
        </table>
        <p class="description"><em><?php esc_html_e( 'Integrasi API akan tersedia setelah endpoint SIAKAD dan SIGAP siap.', 'unpatti-academic' ); ?></em></p>
        <?php
    }

    private function render_tab_export() {
        if ( ! empty( $_GET['import_success'] ) ) {
            echo '<div class="notice notice-success"><p>' . sprintf( esc_html__( 'Import berhasil! %d item diproses.', 'unpatti-academic' ), absint( $_GET['import_success'] ) ) . '</p></div>';
        }
        if ( ! empty( $_GET['import_error'] ) ) {
            $errors = [
                'no_file'      => __( 'Tidak ada file yang diupload.', 'unpatti-academic' ),
                'invalid_file' => __( 'File harus berformat JSON.', 'unpatti-academic' ),
                'empty_file'   => __( 'File kosong.', 'unpatti-academic' ),
            ];
            $msg = $errors[ $_GET['import_error'] ] ?? __( 'Terjadi kesalahan.', 'unpatti-academic' );
            echo '<div class="notice notice-error"><p>' . esc_html( $msg ) . '</p></div>';
        }
        ?>
        <h3><?php esc_html_e( 'Export Data Situs', 'unpatti-academic' ); ?></h3>
        <p><?php esc_html_e( 'Export semua data situs (settings, CPT data, pages, menu) ke file JSON.', 'unpatti-academic' ); ?></p>
        <p><a href="<?php echo esc_url( wp_nonce_url( admin_url( 'admin-post.php?action=unpatti_export' ), 'unpatti_export' ) ); ?>" class="button button-secondary"><?php esc_html_e( 'Export JSON', 'unpatti-academic' ); ?></a></p>

        <hr/>
        <h3><?php esc_html_e( 'Import Data Situs', 'unpatti-academic' ); ?></h3>
        <p><?php esc_html_e( 'Import data dari file JSON yang sebelumnya di-export.', 'unpatti-academic' ); ?></p>
        </form>
        <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" enctype="multipart/form-data">
            <?php wp_nonce_field( 'unpatti_import' ); ?>
            <input type="hidden" name="action" value="unpatti_import" />
            <p><input type="file" name="import_file" accept=".json" /></p>
            <p><?php submit_button( __( 'Import JSON', 'unpatti-academic' ), 'secondary', 'submit', false ); ?></p>
        </form>
        <form method="post" action="options.php">
            <?php settings_fields( 'unpatti_settings_group' ); ?>

        <hr/>
        <h3><?php esc_html_e( 'Update Server', 'unpatti-academic' ); ?></h3>
        <table class="form-table">
            <tr>
                <th><?php esc_html_e( 'Update Server URL', 'unpatti-academic' ); ?></th>
                <td><input type="url" name="<?php echo $this->option_name; ?>[update_server_url]" value="<?php echo esc_attr( $this->get_option('update_server_url') ); ?>" class="regular-text" />
                <p class="description"><?php esc_html_e( 'URL server untuk auto-update tema dan plugin.', 'unpatti-academic' ); ?></p></td>
            </tr>
        </table>
        <?php
    }
}
