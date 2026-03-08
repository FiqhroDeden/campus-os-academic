<?php
namespace CampusOS\Core\Admin;

if ( ! defined( 'ABSPATH' ) ) exit;

class Admin_Settings {
    private $option_name = 'campusos_settings';

    public function register() {
        add_action( 'admin_menu', [ $this, 'add_menu' ] );
        add_action( 'admin_init', [ $this, 'register_settings' ] );
        add_action( 'wp_ajax_campusos_license_activate', [ $this, 'ajax_license_activate' ] );
        add_action( 'wp_ajax_campusos_license_deactivate', [ $this, 'ajax_license_deactivate' ] );
    }

    public function add_menu() {
        add_menu_page(
            __( 'CampusOS Academic', 'campusos-academic' ),
            __( 'CampusOS Academic', 'campusos-academic' ),
            'manage_options',
            'campusos-academic',
            [ $this, 'render_page' ],
            'dashicons-university',
            3
        );
    }

    public function register_settings() {
        register_setting( 'campusos_settings_group', $this->option_name, [
            'sanitize_callback' => [ $this, 'sanitize_settings' ],
        ] );
    }

    public function sanitize_settings( $input ) {
        // Merge with existing options so saving one tab doesn't wipe other tabs
        $existing  = get_option( $this->option_name, [] );
        $sanitized = is_array( $existing ) ? $existing : [];
        $tab       = $input['_active_tab'] ?? '';

        // Remove internal field
        unset( $sanitized['_active_tab'] );

        switch ( $tab ) {
            case 'lisensi':
                $sanitized['license_server_url'] = esc_url_raw( $input['license_server_url'] ?? '' );
                break;

            case 'keamanan':
                $sanitized['security_xmlrpc_disabled']   = ! empty( $input['security_xmlrpc_disabled'] ) ? 1 : 0;
                $sanitized['security_user_enum_disabled'] = ! empty( $input['security_user_enum_disabled'] ) ? 1 : 0;
                $sanitized['security_file_edit_disabled'] = ! empty( $input['security_file_edit_disabled'] ) ? 1 : 0;
                $sanitized['security_scanner_enabled']   = ! empty( $input['security_scanner_enabled'] ) ? 1 : 0;
                $sanitized['security_scanner_keywords']  = sanitize_textarea_field( $input['security_scanner_keywords'] ?? '' );
                $sanitized['security_whitelist_domains']  = sanitize_textarea_field( $input['security_whitelist_domains'] ?? '' );
                break;

            case 'sso':
                $sanitized['sso_enabled']        = ! empty( $input['sso_enabled'] ) ? 1 : 0;
                $sanitized['sso_base_url']       = esc_url_raw( $input['sso_base_url'] ?? '' );
                $sanitized['sso_client_id']      = sanitize_text_field( $input['sso_client_id'] ?? '' );
                $sanitized['sso_client_secret']  = sanitize_text_field( $input['sso_client_secret'] ?? '' );
                $sanitized['sso_role_mapping']   = sanitize_textarea_field( $input['sso_role_mapping'] ?? '' );
                $sanitized['sso_fallback_admin'] = sanitize_user( $input['sso_fallback_admin'] ?? '' );
                break;

            case 'api':
                $sanitized['api_siakad_url'] = esc_url_raw( $input['api_siakad_url'] ?? '' );
                $sanitized['api_siakad_key'] = sanitize_text_field( $input['api_siakad_key'] ?? '' );
                $sanitized['api_sigap_url']  = esc_url_raw( $input['api_sigap_url'] ?? '' );
                $sanitized['api_sigap_key']  = sanitize_text_field( $input['api_sigap_key'] ?? '' );
                $sanitized['api_cache_ttl']  = absint( $input['api_cache_ttl'] ?? 3600 );
                break;

            case 'export':
                $sanitized['update_server_url'] = esc_url_raw( $input['update_server_url'] ?? '' );
                break;

            default:
                // Fallback: sanitize all fields (backwards compat)
                $sanitized['security_xmlrpc_disabled']   = ! empty( $input['security_xmlrpc_disabled'] ) ? 1 : 0;
                $sanitized['security_user_enum_disabled'] = ! empty( $input['security_user_enum_disabled'] ) ? 1 : 0;
                $sanitized['security_file_edit_disabled'] = ! empty( $input['security_file_edit_disabled'] ) ? 1 : 0;
                $sanitized['security_scanner_enabled']   = ! empty( $input['security_scanner_enabled'] ) ? 1 : 0;
                $sanitized['security_scanner_keywords']  = sanitize_textarea_field( $input['security_scanner_keywords'] ?? '' );
                $sanitized['security_whitelist_domains']  = sanitize_textarea_field( $input['security_whitelist_domains'] ?? '' );
                $sanitized['sso_enabled']        = ! empty( $input['sso_enabled'] ) ? 1 : 0;
                $sanitized['sso_base_url']       = esc_url_raw( $input['sso_base_url'] ?? '' );
                $sanitized['sso_client_id']      = sanitize_text_field( $input['sso_client_id'] ?? '' );
                $sanitized['sso_client_secret']  = sanitize_text_field( $input['sso_client_secret'] ?? '' );
                $sanitized['sso_role_mapping']   = sanitize_textarea_field( $input['sso_role_mapping'] ?? '' );
                $sanitized['sso_fallback_admin'] = sanitize_user( $input['sso_fallback_admin'] ?? '' );
                $sanitized['api_siakad_url'] = esc_url_raw( $input['api_siakad_url'] ?? '' );
                $sanitized['api_siakad_key'] = sanitize_text_field( $input['api_siakad_key'] ?? '' );
                $sanitized['api_sigap_url']  = esc_url_raw( $input['api_sigap_url'] ?? '' );
                $sanitized['api_sigap_key']  = sanitize_text_field( $input['api_sigap_key'] ?? '' );
                $sanitized['api_cache_ttl']  = absint( $input['api_cache_ttl'] ?? 3600 );
                $sanitized['update_server_url'] = esc_url_raw( $input['update_server_url'] ?? '' );
                break;
        }

        return $sanitized;
    }

    public function get_option( $key, $default = '' ) {
        $options = get_option( $this->option_name, [] );
        return $options[ $key ] ?? $default;
    }

    public function render_page() {
        $active_tab = isset( $_GET['tab'] ) ? sanitize_text_field( $_GET['tab'] ) : 'lisensi';
        $tabs = [
            'lisensi'   => __( 'Lisensi', 'campusos-academic' ),
            'umum'      => __( 'Umum', 'campusos-academic' ),
            'pages'     => __( 'Halaman', 'campusos-academic' ),
            'tools'     => __( 'Tools', 'campusos-academic' ),
            'keamanan'  => __( 'Keamanan', 'campusos-academic' ),
            'sso'       => __( 'SSO', 'campusos-academic' ),
            'api'       => __( 'Integrasi API', 'campusos-academic' ),
            'export'    => __( 'Export / Import', 'campusos-academic' ),
        ];
        ?>
        <div class="wrap">
            <h1><?php esc_html_e( 'CampusOS Academic Settings', 'campusos-academic' ); ?></h1>

            <h2 class="nav-tab-wrapper">
                <?php foreach ( $tabs as $tab_key => $tab_label ) : ?>
                    <a href="<?php echo esc_url( admin_url( 'admin.php?page=campusos-academic&tab=' . $tab_key ) ); ?>"
                       class="nav-tab <?php echo $active_tab === $tab_key ? 'nav-tab-active' : ''; ?>">
                        <?php echo esc_html( $tab_label ); ?>
                    </a>
                <?php endforeach; ?>
            </h2>

            <form method="post" action="options.php">
                <?php settings_fields( 'campusos_settings_group' ); ?>
                <input type="hidden" name="<?php echo esc_attr( $this->option_name ); ?>[_active_tab]" value="<?php echo esc_attr( $active_tab ); ?>" />

                <?php
                switch ( $active_tab ) {
                    case 'lisensi':
                        $this->render_tab_lisensi();
                        break;
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
        $mode = get_theme_mod( 'campusos_site_mode', 'prodi' );
        $name = get_theme_mod( 'campusos_institution_name', '' );
        ?>
        <table class="form-table">
            <tr>
                <th><?php esc_html_e( 'Mode Situs', 'campusos-academic' ); ?></th>
                <td><strong><?php echo $mode === 'fakultas' ? 'Fakultas' : 'Program Studi'; ?></strong></td>
            </tr>
            <tr>
                <th><?php esc_html_e( 'Nama Institusi', 'campusos-academic' ); ?></th>
                <td><?php echo esc_html( $name ?: '-' ); ?></td>
            </tr>
            <tr>
                <th><?php esc_html_e( 'Warna Primary', 'campusos-academic' ); ?></th>
                <td><span style="display:inline-block;width:24px;height:24px;background:<?php echo esc_attr( get_theme_mod('campusos_primary_color','#003d82') ); ?>;border-radius:3px;vertical-align:middle;"></span> <?php echo esc_html( get_theme_mod('campusos_primary_color','#003d82') ); ?></td>
            </tr>
            <tr>
                <th><?php esc_html_e( 'Warna Secondary', 'campusos-academic' ); ?></th>
                <td><span style="display:inline-block;width:24px;height:24px;background:<?php echo esc_attr( get_theme_mod('campusos_secondary_color','#e67e22') ); ?>;border-radius:3px;vertical-align:middle;"></span> <?php echo esc_html( get_theme_mod('campusos_secondary_color','#e67e22') ); ?></td>
            </tr>
        </table>
        <p><a href="<?php echo esc_url( admin_url( 'customize.php' ) ); ?>" class="button"><?php esc_html_e( 'Buka Customizer', 'campusos-academic' ); ?></a></p>
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
                <th><?php esc_html_e( 'Disable XML-RPC', 'campusos-academic' ); ?></th>
                <td><label><input type="checkbox" name="<?php echo $this->option_name; ?>[security_xmlrpc_disabled]" value="1" <?php checked( $this->get_option('security_xmlrpc_disabled', 1) ); ?> /> <?php esc_html_e( 'Nonaktifkan XML-RPC', 'campusos-academic' ); ?></label></td>
            </tr>
            <tr>
                <th><?php esc_html_e( 'Disable User Enumeration', 'campusos-academic' ); ?></th>
                <td><label><input type="checkbox" name="<?php echo $this->option_name; ?>[security_user_enum_disabled]" value="1" <?php checked( $this->get_option('security_user_enum_disabled', 1) ); ?> /> <?php esc_html_e( 'Blokir enumerasi user via REST API', 'campusos-academic' ); ?></label></td>
            </tr>
            <tr>
                <th><?php esc_html_e( 'Disable File Editor', 'campusos-academic' ); ?></th>
                <td><label><input type="checkbox" name="<?php echo $this->option_name; ?>[security_file_edit_disabled]" value="1" <?php checked( $this->get_option('security_file_edit_disabled', 1) ); ?> /> <?php esc_html_e( 'Nonaktifkan editor file di admin', 'campusos-academic' ); ?></label></td>
            </tr>
            <tr>
                <th><?php esc_html_e( 'Content Scanner', 'campusos-academic' ); ?></th>
                <td><label><input type="checkbox" name="<?php echo $this->option_name; ?>[security_scanner_enabled]" value="1" <?php checked( $this->get_option('security_scanner_enabled', 1) ); ?> /> <?php esc_html_e( 'Aktifkan scanner konten otomatis (anti judi online)', 'campusos-academic' ); ?></label></td>
            </tr>
            <tr>
                <th><?php esc_html_e( 'Keyword Tambahan', 'campusos-academic' ); ?></th>
                <td>
                    <textarea name="<?php echo $this->option_name; ?>[security_scanner_keywords]" rows="4" class="large-text"><?php echo esc_textarea( $this->get_option('security_scanner_keywords') ); ?></textarea>
                    <p class="description"><?php esc_html_e( 'Satu keyword per baris. Ditambahkan ke daftar keyword bawaan.', 'campusos-academic' ); ?></p>
                </td>
            </tr>
            <tr>
                <th><?php esc_html_e( 'Domain Whitelist', 'campusos-academic' ); ?></th>
                <td>
                    <textarea name="<?php echo $this->option_name; ?>[security_whitelist_domains]" rows="4" class="large-text"><?php echo esc_textarea( $this->get_option('security_whitelist_domains') ); ?></textarea>
                    <p class="description"><?php esc_html_e( 'Domain yang diizinkan untuk outbound links. Satu per baris. Contoh: university.ac.id', 'campusos-academic' ); ?></p>
                </td>
            </tr>
        </table>
        <?php
    }

    private function render_tab_sso() {
        ?>
        <table class="form-table">
            <tr>
                <th><?php esc_html_e( 'Aktifkan SSO', 'campusos-academic' ); ?></th>
                <td><label><input type="checkbox" name="<?php echo $this->option_name; ?>[sso_enabled]" value="1" <?php checked( $this->get_option('sso_enabled') ); ?> /> <?php esc_html_e( 'Login menggunakan SSO', 'campusos-academic' ); ?></label></td>
            </tr>
            <tr>
                <th><?php esc_html_e( 'SSO Base URL', 'campusos-academic' ); ?></th>
                <td><input type="url" name="<?php echo $this->option_name; ?>[sso_base_url]" value="<?php echo esc_attr( $this->get_option('sso_base_url', '') ); ?>" class="regular-text" /></td>
            </tr>
            <tr>
                <th><?php esc_html_e( 'Client ID', 'campusos-academic' ); ?></th>
                <td><input type="text" name="<?php echo $this->option_name; ?>[sso_client_id]" value="<?php echo esc_attr( $this->get_option('sso_client_id') ); ?>" class="regular-text" /></td>
            </tr>
            <tr>
                <th><?php esc_html_e( 'Client Secret', 'campusos-academic' ); ?></th>
                <td><input type="password" name="<?php echo $this->option_name; ?>[sso_client_secret]" value="<?php echo esc_attr( $this->get_option('sso_client_secret') ); ?>" class="regular-text" /></td>
            </tr>
            <tr>
                <th><?php esc_html_e( 'Redirect URI', 'campusos-academic' ); ?></th>
                <td><code><?php echo esc_html( admin_url( 'admin-ajax.php?action=campusos_sso_callback' ) ); ?></code>
                <p class="description"><?php esc_html_e( 'Gunakan URL ini saat mendaftarkan aplikasi di SSO.', 'campusos-academic' ); ?></p></td>
            </tr>
            <tr>
                <th><?php esc_html_e( 'Role Mapping', 'campusos-academic' ); ?></th>
                <td>
                    <?php $role_mapping_default = "administrator=administrator\neditor=editor\nauthor=author\ncontributor=contributor\nsubscriber=subscriber"; ?>
                    <textarea name="<?php echo $this->option_name; ?>[sso_role_mapping]" rows="6" class="large-text"><?php echo esc_textarea( $this->get_option('sso_role_mapping') ?: $role_mapping_default ); ?></textarea>
                    <p class="description"><?php esc_html_e( 'Format: SSORole=wp_role (satu per baris). Contoh: administrator=administrator', 'campusos-academic' ); ?></p>
                </td>
            </tr>
            <tr>
                <th><?php esc_html_e( 'Fallback Admin', 'campusos-academic' ); ?></th>
                <td><input type="text" name="<?php echo $this->option_name; ?>[sso_fallback_admin]" value="<?php echo esc_attr( $this->get_option('sso_fallback_admin') ); ?>" class="regular-text" />
                <p class="description"><?php esc_html_e( 'Username admin lokal untuk emergency login (akses via ?fallback=1)', 'campusos-academic' ); ?></p></td>
            </tr>
        </table>
        <?php
    }

    private function render_tab_api() {
        ?>
        <h3><?php esc_html_e( 'SIAKAD (Sistem Informasi Akademik)', 'campusos-academic' ); ?></h3>
        <table class="form-table">
            <tr>
                <th><?php esc_html_e( 'Base URL', 'campusos-academic' ); ?></th>
                <td><input type="url" name="<?php echo $this->option_name; ?>[api_siakad_url]" value="<?php echo esc_attr( $this->get_option('api_siakad_url') ); ?>" class="regular-text" /></td>
            </tr>
            <tr>
                <th><?php esc_html_e( 'API Key', 'campusos-academic' ); ?></th>
                <td><input type="text" name="<?php echo $this->option_name; ?>[api_siakad_key]" value="<?php echo esc_attr( $this->get_option('api_siakad_key') ); ?>" class="regular-text" /></td>
            </tr>
        </table>
        <h3><?php esc_html_e( 'SIGAP (Sistem Informasi Kepegawaian)', 'campusos-academic' ); ?></h3>
        <table class="form-table">
            <tr>
                <th><?php esc_html_e( 'Base URL', 'campusos-academic' ); ?></th>
                <td><input type="url" name="<?php echo $this->option_name; ?>[api_sigap_url]" value="<?php echo esc_attr( $this->get_option('api_sigap_url') ); ?>" class="regular-text" /></td>
            </tr>
            <tr>
                <th><?php esc_html_e( 'API Key', 'campusos-academic' ); ?></th>
                <td><input type="text" name="<?php echo $this->option_name; ?>[api_sigap_key]" value="<?php echo esc_attr( $this->get_option('api_sigap_key') ); ?>" class="regular-text" /></td>
            </tr>
        </table>
        <h3><?php esc_html_e( 'Cache', 'campusos-academic' ); ?></h3>
        <table class="form-table">
            <tr>
                <th><?php esc_html_e( 'Cache TTL (detik)', 'campusos-academic' ); ?></th>
                <td><input type="number" name="<?php echo $this->option_name; ?>[api_cache_ttl]" value="<?php echo esc_attr( $this->get_option('api_cache_ttl', 3600) ); ?>" class="small-text" /></td>
            </tr>
        </table>
        <p class="description"><em><?php esc_html_e( 'Integrasi API akan tersedia setelah endpoint SIAKAD dan SIGAP siap.', 'campusos-academic' ); ?></em></p>
        <?php
    }

    private function render_tab_export() {
        if ( ! empty( $_GET['import_success'] ) ) {
            echo '<div class="notice notice-success"><p>' . sprintf( esc_html__( 'Import berhasil! %d item diproses.', 'campusos-academic' ), absint( $_GET['import_success'] ) ) . '</p></div>';
        }
        if ( ! empty( $_GET['import_error'] ) ) {
            $errors = [
                'no_file'      => __( 'Tidak ada file yang diupload.', 'campusos-academic' ),
                'invalid_file' => __( 'File harus berformat JSON.', 'campusos-academic' ),
                'empty_file'   => __( 'File kosong.', 'campusos-academic' ),
            ];
            $msg = $errors[ $_GET['import_error'] ] ?? __( 'Terjadi kesalahan.', 'campusos-academic' );
            echo '<div class="notice notice-error"><p>' . esc_html( $msg ) . '</p></div>';
        }
        ?>
        <h3><?php esc_html_e( 'Export Data Situs', 'campusos-academic' ); ?></h3>
        <p><?php esc_html_e( 'Export semua data situs (settings, CPT data, pages, menu) ke file JSON.', 'campusos-academic' ); ?></p>
        <p><a href="<?php echo esc_url( wp_nonce_url( admin_url( 'admin-post.php?action=campusos_export' ), 'campusos_export' ) ); ?>" class="button button-secondary"><?php esc_html_e( 'Export JSON', 'campusos-academic' ); ?></a></p>

        <hr/>
        <h3><?php esc_html_e( 'Import Data Situs', 'campusos-academic' ); ?></h3>
        <p><?php esc_html_e( 'Import data dari file JSON yang sebelumnya di-export.', 'campusos-academic' ); ?></p>
        </form>
        <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" enctype="multipart/form-data">
            <?php wp_nonce_field( 'campusos_import' ); ?>
            <input type="hidden" name="action" value="campusos_import" />
            <p><input type="file" name="import_file" accept=".json" /></p>
            <p><?php submit_button( __( 'Import JSON', 'campusos-academic' ), 'secondary', 'submit', false ); ?></p>
        </form>
        <form method="post" action="options.php">
            <?php settings_fields( 'campusos_settings_group' ); ?>
            <input type="hidden" name="<?php echo esc_attr( $this->option_name ); ?>[_active_tab]" value="export" />

        <hr/>
        <h3><?php esc_html_e( 'Update Server', 'campusos-academic' ); ?></h3>
        <table class="form-table">
            <tr>
                <th><?php esc_html_e( 'Update Server URL', 'campusos-academic' ); ?></th>
                <td><input type="url" name="<?php echo $this->option_name; ?>[update_server_url]" value="<?php echo esc_attr( $this->get_option('update_server_url') ); ?>" class="regular-text" />
                <p class="description"><?php esc_html_e( 'URL server untuk auto-update tema dan plugin.', 'campusos-academic' ); ?></p></td>
            </tr>
        </table>
        <?php
    }

    private function render_tab_lisensi() {
        $license_client = new \CampusOS\Core\License\License_Client();
        $license = $license_client->get_license();
        $is_active = $license_client->is_valid();
        ?>
        <table class="form-table">
            <tr>
                <th><?php esc_html_e( 'Status Lisensi', 'campusos-academic' ); ?></th>
                <td>
                    <?php if ( $is_active ) : ?>
                        <span style="color:#46b450;font-weight:bold;">&#10003; <?php esc_html_e( 'Aktif', 'campusos-academic' ); ?></span>
                        <?php if ( ! empty( $license['expires_at'] ) ) : ?>
                            &mdash; <?php printf( esc_html__( 'berlaku hingga %s', 'campusos-academic' ), esc_html( date_i18n( get_option( 'date_format' ), strtotime( $license['expires_at'] ) ) ) ); ?>
                        <?php endif; ?>
                    <?php elseif ( $license['status'] === 'expired' ) : ?>
                        <span style="color:#dc3232;font-weight:bold;">&#10007; <?php esc_html_e( 'Expired', 'campusos-academic' ); ?></span>
                        <?php if ( ! empty( $license['expires_at'] ) ) : ?>
                            &mdash; <?php echo esc_html( date_i18n( get_option( 'date_format' ), strtotime( $license['expires_at'] ) ) ); ?>
                        <?php endif; ?>
                    <?php else : ?>
                        <span style="color:#826200;font-weight:bold;"><?php esc_html_e( 'Belum Diaktifkan', 'campusos-academic' ); ?></span>
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <th><?php esc_html_e( 'License Key', 'campusos-academic' ); ?></th>
                <td>
                    <input type="text" id="campusos-license-key" value="<?php echo esc_attr( $license['key'] ); ?>"
                        class="regular-text" placeholder="XXXX-XXXX-XXXX-XXXX"
                        <?php echo $is_active ? 'readonly' : ''; ?> />
                    <?php if ( ! $is_active ) : ?>
                        <button type="button" id="campusos-license-activate" class="button button-primary"><?php esc_html_e( 'Aktifkan', 'campusos-academic' ); ?></button>
                    <?php else : ?>
                        <button type="button" id="campusos-license-deactivate" class="button"><?php esc_html_e( 'Nonaktifkan', 'campusos-academic' ); ?></button>
                    <?php endif; ?>
                    <span id="license-spinner" class="spinner" style="float:none;"></span>
                    <div id="license-message" style="display:none;margin-top:8px;" class="notice inline"></div>
                </td>
            </tr>
            <?php if ( ! empty( $license['domain'] ) ) : ?>
            <tr>
                <th><?php esc_html_e( 'Domain Terdaftar', 'campusos-academic' ); ?></th>
                <td><code><?php echo esc_html( $license['domain'] ); ?></code></td>
            </tr>
            <?php endif; ?>
            <tr>
                <th><?php esc_html_e( 'URL Server Lisensi', 'campusos-academic' ); ?></th>
                <td>
                    <input type="url" name="<?php echo esc_attr( $this->option_name ); ?>[license_server_url]"
                        value="<?php echo esc_attr( $this->get_option( 'license_server_url' ) ); ?>" class="regular-text" />
                    <p class="description"><?php esc_html_e( 'URL server untuk validasi lisensi.', 'campusos-academic' ); ?></p>
                </td>
            </tr>
        </table>

        <script>
        jQuery(function($) {
            $('#campusos-license-activate').on('click', function() {
                var key = $('#campusos-license-key').val().trim();
                if (!key) { alert('Masukkan license key.'); return; }
                var $btn = $(this), $spinner = $('#license-spinner');
                $btn.prop('disabled', true);
                $spinner.addClass('is-active');
                $.post(ajaxurl, {
                    action: 'campusos_license_activate',
                    license_key: key,
                    _wpnonce: '<?php echo esc_js( wp_create_nonce( "campusos_license_action" ) ); ?>'
                }, function(res) {
                    $spinner.removeClass('is-active');
                    $btn.prop('disabled', false);
                    var $msg = $('#license-message');
                    $msg.show().removeClass('notice-success notice-error')
                        .addClass(res.success ? 'notice-success' : 'notice-error')
                        .html('<p>' + (res.data || '') + '</p>');
                    if (res.success) { setTimeout(function(){ location.reload(); }, 1000); }
                });
            });
            $('#campusos-license-deactivate').on('click', function() {
                if (!confirm('<?php echo esc_js( __( "Yakin ingin menonaktifkan lisensi?", "campusos-academic" ) ); ?>')) return;
                var $btn = $(this), $spinner = $('#license-spinner');
                $btn.prop('disabled', true);
                $spinner.addClass('is-active');
                $.post(ajaxurl, {
                    action: 'campusos_license_deactivate',
                    _wpnonce: '<?php echo esc_js( wp_create_nonce( "campusos_license_action" ) ); ?>'
                }, function(res) {
                    $spinner.removeClass('is-active');
                    $btn.prop('disabled', false);
                    if (res.success) { location.reload(); }
                });
            });
        });
        </script>
        <?php
    }

    public function ajax_license_activate() {
        check_ajax_referer( 'campusos_license_action' );
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( __( 'Akses ditolak.', 'campusos-academic' ) );
        }
        $key = sanitize_text_field( $_POST['license_key'] ?? '' );
        if ( empty( $key ) ) {
            wp_send_json_error( __( 'License key tidak boleh kosong.', 'campusos-academic' ) );
        }
        $client = new \CampusOS\Core\License\License_Client();
        $result = $client->activate( $key );
        if ( $result['success'] ) {
            wp_send_json_success( $result['message'] );
        } else {
            wp_send_json_error( $result['message'] );
        }
    }

    public function ajax_license_deactivate() {
        check_ajax_referer( 'campusos_license_action' );
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( __( 'Akses ditolak.', 'campusos-academic' ) );
        }
        $client = new \CampusOS\Core\License\License_Client();
        $result = $client->deactivate();
        wp_send_json_success( $result['message'] );
    }
}
