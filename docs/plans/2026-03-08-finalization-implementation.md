# CampusOS Academic Finalization Implementation Plan

> **For Claude:** REQUIRED SUB-SKILL: Use superpowers:executing-plans to implement this plan task-by-task.

**Goal:** Finalize CampusOS Academic for commercial distribution with optimization, licensing, packaging, and documentation.

**Architecture:** WordPress theme + plugin with a separate standalone PHP license server. The plugin communicates with the license server for activation/validation. A build script handles minification and ZIP packaging. Documentation is a self-contained HTML file with screenshots.

**Tech Stack:** PHP 8.0+, WordPress 6.9, vanilla CSS/JS, plain PHP license server (MySQL), Bash build script, Playwright for screenshots.

---

## Task 1: Version Sync — Bump Plugin to 1.2.2

**Files:**
- Modify: `wp-content/plugins/campusos-academic-core/campusos-academic-core.php:5,17`

**Step 1: Update plugin version in header and constant**

In `wp-content/plugins/campusos-academic-core/campusos-academic-core.php`:

Change line 5:
```php
 * Version: 1.1.0
```
to:
```php
 * Version: 1.2.2
```

Change line 17:
```php
define( 'CAMPUSOS_CORE_VERSION', '1.1.0' );
```
to:
```php
define( 'CAMPUSOS_CORE_VERSION', '1.2.2' );
```

**Step 2: Verify**

Open `https://wp-unpatti.local/wp-admin/plugins.php` and confirm plugin shows version 1.2.2.

**Step 3: Commit**

```bash
git add wp-content/plugins/campusos-academic-core/campusos-academic-core.php
git commit -m "chore: bump plugin version to 1.2.2 to match theme"
```

---

## Task 2: Optimize Asset Loading in Theme

**Files:**
- Modify: `wp-content/themes/campusos-academic/functions.php:37-90`

**Step 1: Update enqueue to use minified assets in production**

In `wp-content/themes/campusos-academic/functions.php`, replace lines 37-90 (the `wp_enqueue_scripts` callback) with:

```php
add_action( 'wp_enqueue_scripts', function() {
    $suffix = ( defined( 'WP_DEBUG' ) && WP_DEBUG ) ? '' : '.min';

    wp_enqueue_style( 'dashicons' );
    wp_enqueue_style( 'campusos-academic', CAMPUSOS_THEME_URI . '/assets/css/main' . $suffix . '.css', [], CAMPUSOS_THEME_VERSION );
    wp_enqueue_script( 'campusos-academic', CAMPUSOS_THEME_URI . '/assets/js/main' . $suffix . '.js', [], CAMPUSOS_THEME_VERSION, [ 'in_footer' => true, 'strategy' => 'defer' ] );

    // Google Font
    $font_family = get_theme_mod( 'campusos_font_family', 'Inter' );
    $font_slug   = str_replace( ' ', '+', $font_family );
    if ( $font_family !== 'Inter' ) {
        wp_enqueue_style( 'campusos-google-font', 'https://fonts.googleapis.com/css2?family=' . $font_slug . ':wght@400;500;600;700&display=swap', [], null );
    }

    // Dynamic color variables
    $primary   = get_theme_mod( 'campusos_primary_color', '#003d82' );
    $secondary = get_theme_mod( 'campusos_secondary_color', '#e67e22' );

    $pr = $pg = $pb = 0;
    sscanf( $primary, '#%02x%02x%02x', $pr, $pg, $pb );
    $primary_light = sprintf( '#%02x%02x%02x',
        $pr + (int) ( ( 255 - $pr ) * 0.9 ),
        $pg + (int) ( ( 255 - $pg ) * 0.9 ),
        $pb + (int) ( ( 255 - $pb ) * 0.9 )
    );
    $primary_dark = sprintf( '#%02x%02x%02x',
        max( 0, (int) ( $pr * 0.7 ) ),
        max( 0, (int) ( $pg * 0.7 ) ),
        max( 0, (int) ( $pb * 0.7 ) )
    );

    $sr = $sg = $sb = 0;
    sscanf( $secondary, '#%02x%02x%02x', $sr, $sg, $sb );
    $secondary_light = sprintf( '#%02x%02x%02x',
        $sr + (int) ( ( 255 - $sr ) * 0.9 ),
        $sg + (int) ( ( 255 - $sg ) * 0.9 ),
        $sb + (int) ( ( 255 - $sb ) * 0.9 )
    );
    $secondary_dark = sprintf( '#%02x%02x%02x',
        max( 0, (int) ( $sr * 0.7 ) ),
        max( 0, (int) ( $sg * 0.7 ) ),
        max( 0, (int) ( $sb * 0.7 ) )
    );

    $css = ":root {
        --campusos-primary: {$primary};
        --campusos-primary-light: {$primary_light};
        --campusos-primary-dark: {$primary_dark};
        --campusos-secondary: {$secondary};
        --campusos-secondary-light: {$secondary_light};
        --campusos-secondary-dark: {$secondary_dark};
        --campusos-font-family: '{$font_family}', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }
    body { font-family: var(--campusos-font-family); }";
    wp_add_inline_style( 'campusos-academic', $css );
} );
```

**Step 2: Add Google Fonts preconnect**

In `wp-content/themes/campusos-academic/functions.php`, add after the `wp_enqueue_scripts` block:

```php
// Preconnect for Google Fonts
add_action( 'wp_head', function() {
    $font_family = get_theme_mod( 'campusos_font_family', 'Inter' );
    if ( $font_family !== 'Inter' ) {
        echo '<link rel="preconnect" href="https://fonts.googleapis.com" />' . "\n";
        echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />' . "\n";
    }
}, 1 );
```

**Step 3: Verify**

Load `https://wp-unpatti.local/` and check that:
- CSS/JS still load correctly (using unminified since WP_DEBUG is likely true locally)
- No console errors

**Step 4: Commit**

```bash
git add wp-content/themes/campusos-academic/functions.php
git commit -m "feat(theme): optimize asset loading with minified support and font preconnect"
```

---

## Task 3: Extend Lazy Loading in Templates

**Files:**
- Modify: `wp-content/themes/campusos-academic/template-parts/homepage-gallery.php`
- Modify: `wp-content/themes/campusos-academic/template-parts/homepage-partner.php`
- Modify: `wp-content/themes/campusos-academic/template-parts/homepage-news.php`

**Step 1: Add loading="lazy" to all template images**

For each template file, find any `<img` tags and add `loading="lazy"` if not already present. Also add `decoding="async"`.

Example pattern — in `homepage-gallery.php`, find `the_post_thumbnail()` calls and add attributes:
```php
the_post_thumbnail( 'campusos-card', [ 'loading' => 'lazy', 'decoding' => 'async' ] );
```

In `homepage-partner.php`, find `the_post_thumbnail()` or raw `<img` tags and add the same attributes.

In `homepage-news.php`, same pattern.

**Step 2: Verify**

Load homepage, inspect images — they should have `loading="lazy" decoding="async"` attributes.

**Step 3: Commit**

```bash
git add wp-content/themes/campusos-academic/template-parts/
git commit -m "feat(theme): extend lazy loading to gallery, partner, and news templates"
```

---

## Task 4: Create License Client Class

**Files:**
- Create: `wp-content/plugins/campusos-academic-core/includes/license/class-license-client.php`

**Step 1: Create the license directory**

```bash
mkdir -p wp-content/plugins/campusos-academic-core/includes/license
```

**Step 2: Write the License Client class**

Create `wp-content/plugins/campusos-academic-core/includes/license/class-license-client.php`:

```php
<?php
namespace CampusOS\Core\License;

if ( ! defined( 'ABSPATH' ) ) exit;

class License_Client {

    private $option_key = 'campusos_license';

    public function init() {
        add_action( 'admin_notices', [ $this, 'license_notice' ] );
        add_action( 'campusos_license_revalidate', [ $this, 'revalidate' ] );

        // Schedule weekly revalidation
        if ( ! wp_next_scheduled( 'campusos_license_revalidate' ) ) {
            wp_schedule_event( time(), 'weekly', 'campusos_license_revalidate' );
        }
    }

    /**
     * Get stored license data.
     */
    public function get_license() {
        return get_option( $this->option_key, [
            'key'        => '',
            'status'     => 'inactive', // inactive, active, expired
            'expires_at' => '',
            'domain'     => '',
        ] );
    }

    /**
     * Check if license is currently valid (active and not expired).
     */
    public function is_valid() {
        $license = $this->get_license();
        if ( $license['status'] !== 'active' ) {
            return false;
        }
        if ( ! empty( $license['expires_at'] ) && strtotime( $license['expires_at'] ) < time() ) {
            // Mark as expired locally
            $license['status'] = 'expired';
            update_option( $this->option_key, $license );
            return false;
        }
        return true;
    }

    /**
     * Activate a license key with the remote server.
     */
    public function activate( $license_key ) {
        $server_url = $this->get_server_url();
        if ( empty( $server_url ) ) {
            return [ 'success' => false, 'message' => __( 'URL server lisensi belum dikonfigurasi.', 'campusos-academic' ) ];
        }

        $domain  = $this->get_site_domain();
        $payload = [
            'license_key' => sanitize_text_field( $license_key ),
            'domain'      => $domain,
            'product'     => 'campusos-academic',
        ];

        $signature = $this->sign_request( $payload );

        $response = wp_remote_post( trailingslashit( $server_url ) . 'api/activate', [
            'timeout' => 30,
            'headers' => [
                'Content-Type'    => 'application/json',
                'X-Signature'     => $signature,
            ],
            'body' => wp_json_encode( $payload ),
        ] );

        if ( is_wp_error( $response ) ) {
            return [ 'success' => false, 'message' => $response->get_error_message() ];
        }

        $code = wp_remote_retrieve_response_code( $response );
        $body = json_decode( wp_remote_retrieve_body( $response ), true );

        if ( $code === 200 && ! empty( $body['success'] ) ) {
            $license_data = [
                'key'        => sanitize_text_field( $license_key ),
                'status'     => 'active',
                'expires_at' => sanitize_text_field( $body['expires_at'] ?? '' ),
                'domain'     => $domain,
            ];
            update_option( $this->option_key, $license_data );
            return [ 'success' => true, 'message' => __( 'Lisensi berhasil diaktifkan.', 'campusos-academic' ) ];
        }

        return [
            'success' => false,
            'message' => $body['message'] ?? __( 'Gagal mengaktifkan lisensi.', 'campusos-academic' ),
        ];
    }

    /**
     * Deactivate the current license.
     */
    public function deactivate() {
        $license    = $this->get_license();
        $server_url = $this->get_server_url();

        if ( ! empty( $server_url ) && ! empty( $license['key'] ) ) {
            $payload = [
                'license_key' => $license['key'],
                'domain'      => $this->get_site_domain(),
            ];
            $signature = $this->sign_request( $payload );

            wp_remote_post( trailingslashit( $server_url ) . 'api/deactivate', [
                'timeout' => 15,
                'headers' => [
                    'Content-Type' => 'application/json',
                    'X-Signature'  => $signature,
                ],
                'body' => wp_json_encode( $payload ),
            ] );
        }

        delete_option( $this->option_key );
        return [ 'success' => true, 'message' => __( 'Lisensi dinonaktifkan.', 'campusos-academic' ) ];
    }

    /**
     * Revalidate license with server (weekly cron).
     */
    public function revalidate() {
        $license    = $this->get_license();
        $server_url = $this->get_server_url();

        if ( empty( $license['key'] ) || empty( $server_url ) ) {
            return;
        }

        $payload = [
            'license_key' => $license['key'],
            'domain'      => $this->get_site_domain(),
        ];
        $signature = $this->sign_request( $payload );

        $response = wp_remote_post( trailingslashit( $server_url ) . 'api/validate', [
            'timeout' => 15,
            'headers' => [
                'Content-Type' => 'application/json',
                'X-Signature'  => $signature,
            ],
            'body' => wp_json_encode( $payload ),
        ] );

        if ( is_wp_error( $response ) ) {
            return;
        }

        $body = json_decode( wp_remote_retrieve_body( $response ), true );

        if ( ! empty( $body['status'] ) ) {
            $license['status']     = sanitize_text_field( $body['status'] );
            $license['expires_at'] = sanitize_text_field( $body['expires_at'] ?? $license['expires_at'] );
            update_option( $this->option_key, $license );
        }
    }

    /**
     * Admin notice for license status.
     */
    public function license_notice() {
        $screen = get_current_screen();
        $license = $this->get_license();

        // Only show on admin pages, not AJAX
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        if ( empty( $license['key'] ) || $license['status'] === 'inactive' ) {
            echo '<div class="notice notice-warning"><p>';
            printf(
                /* translators: %s = settings URL */
                esc_html__( 'CampusOS Academic: Lisensi belum diaktifkan. %sAktifkan sekarang%s untuk mendapat auto-update.', 'campusos-academic' ),
                '<a href="' . esc_url( admin_url( 'admin.php?page=campusos-academic&tab=lisensi' ) ) . '">',
                '</a>'
            );
            echo '</p></div>';
        } elseif ( $license['status'] === 'expired' ) {
            echo '<div class="notice notice-error"><p>';
            printf(
                esc_html__( 'CampusOS Academic: Lisensi sudah expired pada %s. Perpanjang lisensi untuk mendapat update terbaru.', 'campusos-academic' ),
                esc_html( date_i18n( get_option( 'date_format' ), strtotime( $license['expires_at'] ) ) )
            );
            echo '</p></div>';
        }
    }

    /**
     * Get the license server URL from settings.
     */
    private function get_server_url() {
        $settings = get_option( 'campusos_settings', [] );
        return $settings['license_server_url'] ?? '';
    }

    /**
     * Get the clean site domain.
     */
    private function get_site_domain() {
        return wp_parse_url( home_url(), PHP_URL_HOST );
    }

    /**
     * Generate HMAC signature for a request payload.
     */
    private function sign_request( $payload ) {
        // Use a portion of the license key or a stored secret as HMAC key
        $secret = defined( 'CAMPUSOS_LICENSE_SECRET' ) ? CAMPUSOS_LICENSE_SECRET : 'campusos-default-secret';
        return hash_hmac( 'sha256', wp_json_encode( $payload ), $secret );
    }
}
```

**Step 3: Commit**

```bash
git add wp-content/plugins/campusos-academic-core/includes/license/
git commit -m "feat(license): add License_Client class for remote license validation"
```

---

## Task 5: Add License Tab to Admin Settings

**Files:**
- Modify: `wp-content/plugins/campusos-academic-core/includes/admin/class-admin-settings.php`

**Step 1: Add 'lisensi' tab as the first tab**

In `class-admin-settings.php`, in the `render_page()` method, change the `$tabs` array (around line 105) to insert 'lisensi' as the first entry:

```php
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
```

Also change the default tab on line 104:
```php
$active_tab = isset( $_GET['tab'] ) ? sanitize_text_field( $_GET['tab'] ) : 'lisensi';
```

**Step 2: Add the lisensi case in the switch statement**

In the `render_page()` method's switch (around line 132), add before the 'umum' case:

```php
case 'lisensi':
    $this->render_tab_lisensi();
    break;
```

**Step 3: Add AJAX handler for license activation/deactivation**

In the `register()` method, add:

```php
add_action( 'wp_ajax_campusos_license_activate', [ $this, 'ajax_license_activate' ] );
add_action( 'wp_ajax_campusos_license_deactivate', [ $this, 'ajax_license_deactivate' ] );
```

**Step 4: Write the render_tab_lisensi() method**

Add this private method to `Admin_Settings`:

```php
private function render_tab_lisensi() {
    $license_client = new \CampusOS\Core\License\License_Client();
    $license = $license_client->get_license();
    $is_active = $license_client->is_valid();
    ?>
    <div id="campusos-license-wrap">
        <table class="form-table">
            <tr>
                <th><?php esc_html_e( 'Status Lisensi', 'campusos-academic' ); ?></th>
                <td>
                    <span id="license-status" class="<?php echo $is_active ? 'license-active' : ( $license['status'] === 'expired' ? 'license-expired' : 'license-inactive' ); ?>">
                        <?php
                        if ( $is_active ) {
                            echo '<span style="color:#46b450;font-weight:bold;">&#10003; ' . esc_html__( 'Aktif', 'campusos-academic' ) . '</span>';
                            if ( ! empty( $license['expires_at'] ) ) {
                                echo ' — ' . sprintf( esc_html__( 'berlaku hingga %s', 'campusos-academic' ), esc_html( date_i18n( get_option( 'date_format' ), strtotime( $license['expires_at'] ) ) ) );
                            }
                        } elseif ( $license['status'] === 'expired' ) {
                            echo '<span style="color:#dc3232;font-weight:bold;">&#10007; ' . esc_html__( 'Expired', 'campusos-academic' ) . '</span>';
                            if ( ! empty( $license['expires_at'] ) ) {
                                echo ' — ' . esc_html( date_i18n( get_option( 'date_format' ), strtotime( $license['expires_at'] ) ) );
                            }
                        } else {
                            echo '<span style="color:#826200;font-weight:bold;">' . esc_html__( 'Belum Diaktifkan', 'campusos-academic' ) . '</span>';
                        }
                        ?>
                    </span>
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
                    <input type="url" name="<?php echo $this->option_name; ?>[license_server_url]"
                        value="<?php echo esc_attr( $this->get_option( 'license_server_url' ) ); ?>" class="regular-text" />
                    <p class="description"><?php esc_html_e( 'URL server untuk validasi lisensi.', 'campusos-academic' ); ?></p>
                </td>
            </tr>
        </table>
        <div id="license-message" style="display:none;" class="notice inline"></div>
    </div>

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
                _wpnonce: '<?php echo wp_create_nonce( 'campusos_license_action' ); ?>'
            }, function(res) {
                $spinner.removeClass('is-active');
                $btn.prop('disabled', false);
                var $msg = $('#license-message');
                $msg.show().removeClass('notice-success notice-error')
                    .addClass(res.success ? 'notice-success' : 'notice-error')
                    .html('<p>' + (res.data || '') + '</p>');
                if (res.success) { location.reload(); }
            });
        });

        $('#campusos-license-deactivate').on('click', function() {
            if (!confirm('<?php echo esc_js( __( 'Yakin ingin menonaktifkan lisensi?', 'campusos-academic' ) ); ?>')) return;
            var $btn = $(this), $spinner = $('#license-spinner');
            $btn.prop('disabled', true);
            $spinner.addClass('is-active');
            $.post(ajaxurl, {
                action: 'campusos_license_deactivate',
                _wpnonce: '<?php echo wp_create_nonce( 'campusos_license_action' ); ?>'
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
```

**Step 5: Add AJAX handler methods**

Add these methods to the `Admin_Settings` class:

```php
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
```

**Step 6: Add lisensi tab to sanitize_settings()**

In `sanitize_settings()`, add a new case:

```php
case 'lisensi':
    $sanitized['license_server_url'] = esc_url_raw( $input['license_server_url'] ?? '' );
    break;
```

**Step 7: Update the submit button exclusion list**

Change line 157 from:
```php
<?php if ( ! in_array( $active_tab, [ 'export', 'umum', 'pages', 'tools' ], true ) ) : ?>
```
to:
```php
<?php if ( ! in_array( $active_tab, [ 'lisensi', 'export', 'umum', 'pages', 'tools' ], true ) ) : ?>
```

Wait — the lisensi tab DOES have the license_server_url field that needs saving. So keep submit button visible, but also add an alternative: the license_server_url is a settings field saved via the form, while activate/deactivate use AJAX.

Actually, let's keep the submit button for the lisensi tab since it has the server URL field:
```php
<?php if ( ! in_array( $active_tab, [ 'export', 'umum', 'pages', 'tools' ], true ) ) : ?>
```
(No change needed — lisensi is not in the excluded list.)

**Step 8: Commit**

```bash
git add wp-content/plugins/campusos-academic-core/includes/admin/class-admin-settings.php
git commit -m "feat(admin): add License tab with activation/deactivation UI"
```

---

## Task 6: Register License Client in Plugin Loader

**Files:**
- Modify: `wp-content/plugins/campusos-academic-core/includes/class-plugin.php`

**Step 1: Add license client loading**

In `class-plugin.php`, in the `load_dependencies()` method, add after the SSO section (after line 115):

```php
// License
require_once CAMPUSOS_CORE_PATH . 'includes/license/class-license-client.php';
( new \CampusOS\Core\License\License_Client() )->init();
```

**Step 2: Commit**

```bash
git add wp-content/plugins/campusos-academic-core/includes/class-plugin.php
git commit -m "feat(plugin): register License_Client in plugin loader"
```

---

## Task 7: Gate Auto-Updates Behind License Check

**Files:**
- Modify: `wp-content/plugins/campusos-academic-core/includes/updater/class-plugin-updater.php`
- Modify: `wp-content/plugins/campusos-academic-core/includes/updater/class-theme-updater.php`

**Step 1: Add license check to Plugin_Updater::init()**

In `class-plugin-updater.php`, modify the `init()` method to add license check. After `if ( empty( $this->update_url ) ) return;` (line 15), add:

```php
// Check if license is valid before enabling updates
$license_client = new \CampusOS\Core\License\License_Client();
if ( ! $license_client->is_valid() ) return;
```

**Step 2: Same for Theme_Updater::init()**

In `class-theme-updater.php`, after `if ( empty( $this->update_url ) ) return;` (line 14), add:

```php
$license_client = new \CampusOS\Core\License\License_Client();
if ( ! $license_client->is_valid() ) return;
```

**Step 3: Add license_key to update check requests**

In `Plugin_Updater::check_update()`, add `'license_key'` to the query args:

```php
$license = ( new \CampusOS\Core\License\License_Client() )->get_license();
$response = wp_remote_get( add_query_arg( [
    'slug'        => $this->plugin_slug,
    'version'     => $current_version,
    'type'        => 'plugin',
    'license_key' => $license['key'] ?? '',
], trailingslashit( $this->update_url ) . 'api/check' ), [ 'timeout' => 15 ] );
```

Same pattern for `Theme_Updater::check_update()`.

**Step 4: Commit**

```bash
git add wp-content/plugins/campusos-academic-core/includes/updater/
git commit -m "feat(updater): gate auto-updates behind valid license check"
```

---

## Task 8: Create License Server — Database & Config

**Files:**
- Create: `license-server/config.php`
- Create: `license-server/db.php`
- Create: `license-server/schema.sql`
- Create: `license-server/.htaccess`

**Step 1: Create license-server directory**

```bash
mkdir -p license-server/api license-server/admin
```

**Step 2: Create schema.sql**

Create `license-server/schema.sql`:

```sql
CREATE TABLE IF NOT EXISTS `licenses` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `license_key` VARCHAR(64) UNIQUE NOT NULL,
    `customer_email` VARCHAR(255) DEFAULT '',
    `customer_name` VARCHAR(255) DEFAULT '',
    `product_type` ENUM('theme','bundle') DEFAULT 'bundle',
    `max_activations` INT DEFAULT 1,
    `activated_domain` VARCHAR(255) DEFAULT NULL,
    `activated_at` DATETIME DEFAULT NULL,
    `expires_at` DATETIME DEFAULT NULL,
    `status` ENUM('active','expired','revoked','inactive') DEFAULT 'inactive',
    `notes` TEXT DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_status` (`status`),
    INDEX `idx_domain` (`activated_domain`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `admin_users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(100) UNIQUE NOT NULL,
    `password_hash` VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Default admin user (password: admin — CHANGE THIS after first login)
INSERT INTO `admin_users` (`username`, `password_hash`)
VALUES ('admin', '$2y$10$placeholder')
ON DUPLICATE KEY UPDATE `id`=`id`;

CREATE TABLE IF NOT EXISTS `api_logs` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `endpoint` VARCHAR(50),
    `license_key` VARCHAR(64),
    `domain` VARCHAR(255),
    `ip_address` VARCHAR(45),
    `response_code` INT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

**Step 3: Create config.php**

Create `license-server/config.php`:

```php
<?php
/**
 * License Server Configuration
 * Copy this to config.php and update with your values.
 */
return [
    'db' => [
        'host'     => 'localhost',
        'name'     => 'campusos_licenses',
        'user'     => 'root',
        'password' => '',
        'charset'  => 'utf8mb4',
    ],
    'secret_key'  => 'CHANGE-THIS-TO-A-RANDOM-STRING', // Used for HMAC verification
    'admin_token' => 'CHANGE-THIS-ADMIN-TOKEN',         // Simple admin auth token
    'products' => [
        'campusos-academic' => [
            'name'            => 'CampusOS Academic',
            'current_version' => '1.2.2',
            'download_url'    => '', // URL to ZIP file
            'changelog_url'   => '',
        ],
    ],
    'rate_limit' => [
        'max_requests' => 60,
        'window'       => 60, // seconds
    ],
];
```

**Step 4: Create db.php**

Create `license-server/db.php`:

```php
<?php
class DB {
    private static $pdo = null;

    public static function connect() {
        if ( self::$pdo !== null ) return self::$pdo;

        $config = require __DIR__ . '/config.php';
        $db     = $config['db'];

        try {
            self::$pdo = new PDO(
                "mysql:host={$db['host']};dbname={$db['name']};charset={$db['charset']}",
                $db['user'],
                $db['password'],
                [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ]
            );
        } catch ( PDOException $e ) {
            http_response_code( 500 );
            echo json_encode( [ 'error' => 'Database connection failed' ] );
            exit;
        }

        return self::$pdo;
    }

    public static function query( $sql, $params = [] ) {
        $stmt = self::connect()->prepare( $sql );
        $stmt->execute( $params );
        return $stmt;
    }

    public static function fetch( $sql, $params = [] ) {
        return self::query( $sql, $params )->fetch();
    }

    public static function fetchAll( $sql, $params = [] ) {
        return self::query( $sql, $params )->fetchAll();
    }
}
```

**Step 5: Create .htaccess**

Create `license-server/.htaccess`:

```apache
RewriteEngine On

# Route all API requests through index.php
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php [QSA,L]

# Security: deny access to config files
<FilesMatch "(config\.php|db\.php|schema\.sql)">
    Order Allow,Deny
    Deny from all
</FilesMatch>

# Prevent directory listing
Options -Indexes
```

**Step 6: Commit**

```bash
git add license-server/
git commit -m "feat(license-server): add database schema, config, and DB helper"
```

---

## Task 9: Create License Server — API Endpoints

**Files:**
- Create: `license-server/index.php`
- Create: `license-server/api/activate.php`
- Create: `license-server/api/validate.php`
- Create: `license-server/api/deactivate.php`
- Create: `license-server/api/check-update.php`

**Step 1: Create the router (index.php)**

Create `license-server/index.php`:

```php
<?php
/**
 * CampusOS License Server
 * Simple router for API and admin endpoints.
 */
header( 'Content-Type: application/json; charset=utf-8' );

$uri    = parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH );
$method = $_SERVER['REQUEST_METHOD'];

// Remove trailing slash
$uri = rtrim( $uri, '/' );

// Remove base path if deployed in subdirectory
$base = dirname( $_SERVER['SCRIPT_NAME'] );
if ( $base !== '/' ) {
    $uri = substr( $uri, strlen( $base ) );
}

// API routes
$routes = [
    'POST /api/activate'      => 'api/activate.php',
    'POST /api/validate'      => 'api/validate.php',
    'POST /api/deactivate'    => 'api/deactivate.php',
    'GET /api/check-update'   => 'api/check-update.php',
    'GET /api/check'          => 'api/check-update.php', // alias for WP updater
];

$route_key = $method . ' ' . $uri;

if ( isset( $routes[ $route_key ] ) ) {
    require __DIR__ . '/' . $routes[ $route_key ];
} elseif ( strpos( $uri, '/admin' ) === 0 ) {
    // Admin routes are HTML pages, not JSON
    header( 'Content-Type: text/html; charset=utf-8' );
    $admin_file = __DIR__ . '/admin' . substr( $uri, 6 ) . '.php';
    if ( $uri === '/admin' || $uri === '/admin/' ) {
        $admin_file = __DIR__ . '/admin/index.php';
    }
    if ( file_exists( $admin_file ) ) {
        require $admin_file;
    } else {
        http_response_code( 404 );
        echo '<h1>404 Not Found</h1>';
    }
} else {
    http_response_code( 404 );
    echo json_encode( [ 'error' => 'Not found' ] );
}
```

**Step 2: Create api/activate.php**

Create `license-server/api/activate.php`:

```php
<?php
require_once __DIR__ . '/../db.php';

$input = json_decode( file_get_contents( 'php://input' ), true );

$license_key = trim( $input['license_key'] ?? '' );
$domain      = trim( $input['domain'] ?? '' );
$product     = trim( $input['product'] ?? '' );

if ( empty( $license_key ) || empty( $domain ) ) {
    http_response_code( 400 );
    echo json_encode( [ 'success' => false, 'message' => 'License key and domain are required.' ] );
    exit;
}

// Find license
$license = DB::fetch(
    'SELECT * FROM licenses WHERE license_key = ?',
    [ $license_key ]
);

if ( ! $license ) {
    http_response_code( 404 );
    echo json_encode( [ 'success' => false, 'message' => 'License key tidak valid.' ] );
    exit;
}

if ( $license['status'] === 'revoked' ) {
    http_response_code( 403 );
    echo json_encode( [ 'success' => false, 'message' => 'License telah dicabut.' ] );
    exit;
}

// Check if already activated on a different domain
if ( ! empty( $license['activated_domain'] ) && $license['activated_domain'] !== $domain ) {
    http_response_code( 409 );
    echo json_encode( [
        'success' => false,
        'message' => 'License sudah aktif di domain lain: ' . $license['activated_domain'] . '. Nonaktifkan dulu sebelum pindah domain.',
    ] );
    exit;
}

// Activate: set domain, set expiry to 1 year from now (or from existing if re-activating)
$now        = date( 'Y-m-d H:i:s' );
$expires_at = date( 'Y-m-d H:i:s', strtotime( '+1 year' ) );

// If re-activating same domain with existing expiry, keep the old expiry
if ( ! empty( $license['expires_at'] ) && $license['activated_domain'] === $domain ) {
    $expires_at = $license['expires_at'];
}

DB::query(
    'UPDATE licenses SET activated_domain = ?, activated_at = ?, expires_at = ?, status = ? WHERE id = ?',
    [ $domain, $now, $expires_at, 'active', $license['id'] ]
);

// Log
DB::query(
    'INSERT INTO api_logs (endpoint, license_key, domain, ip_address, response_code) VALUES (?, ?, ?, ?, ?)',
    [ 'activate', $license_key, $domain, $_SERVER['REMOTE_ADDR'] ?? '', 200 ]
);

echo json_encode( [
    'success'    => true,
    'message'    => 'License activated successfully.',
    'status'     => 'active',
    'expires_at' => $expires_at,
    'domain'     => $domain,
] );
```

**Step 3: Create api/validate.php**

Create `license-server/api/validate.php`:

```php
<?php
require_once __DIR__ . '/../db.php';

$input = json_decode( file_get_contents( 'php://input' ), true );

$license_key = trim( $input['license_key'] ?? '' );
$domain      = trim( $input['domain'] ?? '' );

if ( empty( $license_key ) ) {
    http_response_code( 400 );
    echo json_encode( [ 'success' => false, 'message' => 'License key is required.' ] );
    exit;
}

$license = DB::fetch(
    'SELECT * FROM licenses WHERE license_key = ?',
    [ $license_key ]
);

if ( ! $license ) {
    echo json_encode( [ 'success' => false, 'status' => 'invalid', 'message' => 'License key tidak ditemukan.' ] );
    exit;
}

// Check expiry
$status = $license['status'];
if ( $status === 'active' && ! empty( $license['expires_at'] ) && strtotime( $license['expires_at'] ) < time() ) {
    $status = 'expired';
    DB::query( 'UPDATE licenses SET status = ? WHERE id = ?', [ 'expired', $license['id'] ] );
}

// Check domain match
$domain_match = empty( $domain ) || $license['activated_domain'] === $domain;

echo json_encode( [
    'success'      => true,
    'status'       => $status,
    'expires_at'   => $license['expires_at'] ?? '',
    'domain'       => $license['activated_domain'] ?? '',
    'domain_match' => $domain_match,
] );
```

**Step 4: Create api/deactivate.php**

Create `license-server/api/deactivate.php`:

```php
<?php
require_once __DIR__ . '/../db.php';

$input = json_decode( file_get_contents( 'php://input' ), true );

$license_key = trim( $input['license_key'] ?? '' );
$domain      = trim( $input['domain'] ?? '' );

if ( empty( $license_key ) ) {
    http_response_code( 400 );
    echo json_encode( [ 'success' => false, 'message' => 'License key is required.' ] );
    exit;
}

$license = DB::fetch(
    'SELECT * FROM licenses WHERE license_key = ?',
    [ $license_key ]
);

if ( ! $license ) {
    http_response_code( 404 );
    echo json_encode( [ 'success' => false, 'message' => 'License key tidak ditemukan.' ] );
    exit;
}

// Clear domain binding
DB::query(
    'UPDATE licenses SET activated_domain = NULL, activated_at = NULL, status = ? WHERE id = ?',
    [ 'inactive', $license['id'] ]
);

// Log
DB::query(
    'INSERT INTO api_logs (endpoint, license_key, domain, ip_address, response_code) VALUES (?, ?, ?, ?, ?)',
    [ 'deactivate', $license_key, $domain, $_SERVER['REMOTE_ADDR'] ?? '', 200 ]
);

echo json_encode( [
    'success' => true,
    'message' => 'License deactivated. Domain binding cleared.',
] );
```

**Step 5: Create api/check-update.php**

Create `license-server/api/check-update.php`:

```php
<?php
require_once __DIR__ . '/../db.php';

$config = require __DIR__ . '/../config.php';

$slug        = $_GET['slug'] ?? '';
$version     = $_GET['version'] ?? '';
$type        = $_GET['type'] ?? 'theme';
$license_key = $_GET['license_key'] ?? '';

// Find product config
$product = $config['products'][ $slug ] ?? null;
if ( ! $product ) {
    echo json_encode( [] );
    exit;
}

// Check if there's a newer version
if ( ! empty( $version ) && version_compare( $product['current_version'], $version, '<=' ) ) {
    echo json_encode( [] ); // No update available
    exit;
}

// Optionally verify license for download URL
$download_url = '';
if ( ! empty( $license_key ) ) {
    $license = DB::fetch(
        'SELECT * FROM licenses WHERE license_key = ? AND status = ?',
        [ $license_key, 'active' ]
    );
    if ( $license && ( empty( $license['expires_at'] ) || strtotime( $license['expires_at'] ) > time() ) ) {
        $download_url = $product['download_url'];
    }
}

echo json_encode( [
    'name'          => $product['name'],
    'version'       => $product['current_version'],
    'download_url'  => $download_url,
    'changelog_url' => $product['changelog_url'],
    'tested'        => '6.9',
    'requires'      => '6.0',
    'requires_php'  => '8.0',
] );
```

**Step 6: Commit**

```bash
git add license-server/
git commit -m "feat(license-server): add API endpoints for activate, validate, deactivate, check-update"
```

---

## Task 10: Create License Server — Admin Dashboard

**Files:**
- Create: `license-server/admin/index.php`
- Create: `license-server/admin/login.php`
- Create: `license-server/admin/licenses.php`

**Step 1: Create admin/login.php**

Create `license-server/admin/login.php`:

```php
<?php
session_start();
require_once __DIR__ . '/../db.php';

if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) {
    $username = trim( $_POST['username'] ?? '' );
    $password = $_POST['password'] ?? '';

    $user = DB::fetch( 'SELECT * FROM admin_users WHERE username = ?', [ $username ] );

    if ( $user && password_verify( $password, $user['password_hash'] ) ) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_user']      = $username;
        header( 'Location: index.php' );
        exit;
    }
    $error = 'Username atau password salah.';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - CampusOS License Server</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #f0f2f5; display: flex; align-items: center; justify-content: center; min-height: 100vh; }
        .login-box { background: #fff; padding: 2rem; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); width: 100%; max-width: 400px; }
        h1 { font-size: 1.5rem; margin-bottom: 1.5rem; color: #003d82; }
        label { display: block; margin-bottom: 0.25rem; font-weight: 500; font-size: 0.9rem; }
        input[type="text"], input[type="password"] { width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #ccc; border-radius: 4px; font-size: 1rem; margin-bottom: 1rem; }
        button { background: #003d82; color: #fff; border: none; padding: 0.6rem 1.5rem; border-radius: 4px; font-size: 1rem; cursor: pointer; width: 100%; }
        button:hover { background: #002a5c; }
        .error { color: #dc3232; margin-bottom: 1rem; font-size: 0.9rem; }
    </style>
</head>
<body>
    <div class="login-box">
        <h1>CampusOS License Server</h1>
        <?php if ( ! empty( $error ) ) : ?>
            <p class="error"><?= htmlspecialchars( $error ) ?></p>
        <?php endif; ?>
        <form method="POST">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required />
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required />
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>
```

**Step 2: Create admin/index.php (Dashboard)**

Create `license-server/admin/index.php`:

```php
<?php
session_start();
if ( empty( $_SESSION['admin_logged_in'] ) ) {
    header( 'Location: login.php' );
    exit;
}
require_once __DIR__ . '/../db.php';

// Stats
$total     = DB::fetch( 'SELECT COUNT(*) as c FROM licenses' )['c'];
$active    = DB::fetch( 'SELECT COUNT(*) as c FROM licenses WHERE status = "active"' )['c'];
$expired   = DB::fetch( 'SELECT COUNT(*) as c FROM licenses WHERE status = "expired"' )['c'];
$inactive  = DB::fetch( 'SELECT COUNT(*) as c FROM licenses WHERE status = "inactive"' )['c'];

// Handle generate license
if ( $_SERVER['REQUEST_METHOD'] === 'POST' && ! empty( $_POST['action'] ) ) {
    if ( $_POST['action'] === 'generate' ) {
        $key = strtoupper( implode( '-', str_split( bin2hex( random_bytes( 16 ) ), 4 ) ) );
        DB::query(
            'INSERT INTO licenses (license_key, customer_email, customer_name, product_type, status) VALUES (?, ?, ?, ?, ?)',
            [ $key, trim( $_POST['email'] ?? '' ), trim( $_POST['name'] ?? '' ), $_POST['product_type'] ?? 'bundle', 'inactive' ]
        );
        $success = "License key dibuat: {$key}";
    } elseif ( $_POST['action'] === 'revoke' && ! empty( $_POST['license_id'] ) ) {
        DB::query( 'UPDATE licenses SET status = "revoked" WHERE id = ?', [ (int) $_POST['license_id'] ] );
        $success = 'License dicabut.';
    }
}

// List recent licenses
$licenses = DB::fetchAll( 'SELECT * FROM licenses ORDER BY created_at DESC LIMIT 50' );
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - CampusOS License Server</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #f0f2f5; }
        .header { background: #003d82; color: #fff; padding: 1rem 2rem; display: flex; justify-content: space-between; align-items: center; }
        .header h1 { font-size: 1.25rem; }
        .header a { color: #fff; text-decoration: none; opacity: 0.8; }
        .container { max-width: 1200px; margin: 2rem auto; padding: 0 1rem; }
        .stats { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; margin-bottom: 2rem; }
        .stat-card { background: #fff; padding: 1.5rem; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); text-align: center; }
        .stat-card .number { font-size: 2rem; font-weight: 700; color: #003d82; }
        .stat-card .label { color: #666; font-size: 0.85rem; margin-top: 0.25rem; }
        .card { background: #fff; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); padding: 1.5rem; margin-bottom: 1.5rem; }
        .card h2 { font-size: 1.1rem; margin-bottom: 1rem; color: #333; }
        table { width: 100%; border-collapse: collapse; font-size: 0.9rem; }
        th, td { padding: 0.6rem 0.75rem; text-align: left; border-bottom: 1px solid #eee; }
        th { background: #f8f9fa; font-weight: 600; }
        .badge { display: inline-block; padding: 0.15rem 0.5rem; border-radius: 12px; font-size: 0.75rem; font-weight: 600; }
        .badge-active { background: #d4edda; color: #155724; }
        .badge-expired { background: #f8d7da; color: #721c24; }
        .badge-inactive { background: #fff3cd; color: #856404; }
        .badge-revoked { background: #e2e3e5; color: #383d41; }
        form.inline { display: inline; }
        input[type="text"], input[type="email"], select { padding: 0.4rem 0.6rem; border: 1px solid #ccc; border-radius: 4px; font-size: 0.9rem; }
        button, .btn { background: #003d82; color: #fff; border: none; padding: 0.4rem 1rem; border-radius: 4px; cursor: pointer; font-size: 0.85rem; }
        button:hover { background: #002a5c; }
        .btn-danger { background: #dc3545; }
        .btn-danger:hover { background: #c82333; }
        .btn-sm { padding: 0.2rem 0.5rem; font-size: 0.8rem; }
        .success { background: #d4edda; color: #155724; padding: 0.75rem 1rem; border-radius: 4px; margin-bottom: 1rem; }
        .form-row { display: flex; gap: 0.5rem; align-items: end; flex-wrap: wrap; }
        .form-group { display: flex; flex-direction: column; gap: 0.25rem; }
        .form-group label { font-size: 0.8rem; font-weight: 500; }
        code { background: #f4f4f4; padding: 0.1rem 0.3rem; border-radius: 3px; font-size: 0.85rem; }
    </style>
</head>
<body>
    <div class="header">
        <h1>CampusOS License Server</h1>
        <a href="?logout=1" onclick="<?php if(!empty($_GET['logout'])){session_destroy();header('Location:login.php');exit;} ?>">Logout (<?= htmlspecialchars( $_SESSION['admin_user'] ) ?>)</a>
    </div>

    <?php if ( ! empty( $_GET['logout'] ) ) { session_destroy(); header( 'Location: login.php' ); exit; } ?>

    <div class="container">
        <?php if ( ! empty( $success ) ) : ?>
            <div class="success"><?= htmlspecialchars( $success ) ?></div>
        <?php endif; ?>

        <div class="stats">
            <div class="stat-card"><div class="number"><?= $total ?></div><div class="label">Total Lisensi</div></div>
            <div class="stat-card"><div class="number"><?= $active ?></div><div class="label">Aktif</div></div>
            <div class="stat-card"><div class="number"><?= $expired ?></div><div class="label">Expired</div></div>
            <div class="stat-card"><div class="number"><?= $inactive ?></div><div class="label">Belum Aktif</div></div>
        </div>

        <div class="card">
            <h2>Buat License Key Baru</h2>
            <form method="POST">
                <input type="hidden" name="action" value="generate" />
                <div class="form-row">
                    <div class="form-group">
                        <label>Nama Customer</label>
                        <input type="text" name="name" placeholder="Nama" />
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" placeholder="email@example.com" />
                    </div>
                    <div class="form-group">
                        <label>Tipe Produk</label>
                        <select name="product_type">
                            <option value="bundle">Bundle (Theme + Plugin)</option>
                            <option value="theme">Theme Only</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <button type="submit">Generate Key</button>
                    </div>
                </div>
            </form>
        </div>

        <div class="card">
            <h2>Daftar Lisensi</h2>
            <table>
                <thead>
                    <tr>
                        <th>License Key</th>
                        <th>Customer</th>
                        <th>Domain</th>
                        <th>Status</th>
                        <th>Expires</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ( $licenses as $lic ) : ?>
                    <tr>
                        <td><code><?= htmlspecialchars( $lic['license_key'] ) ?></code></td>
                        <td><?= htmlspecialchars( $lic['customer_name'] ?: $lic['customer_email'] ?: '-' ) ?></td>
                        <td><?= htmlspecialchars( $lic['activated_domain'] ?: '-' ) ?></td>
                        <td>
                            <span class="badge badge-<?= $lic['status'] ?>">
                                <?= ucfirst( $lic['status'] ) ?>
                            </span>
                        </td>
                        <td><?= $lic['expires_at'] ? date( 'd M Y', strtotime( $lic['expires_at'] ) ) : '-' ?></td>
                        <td>
                            <?php if ( $lic['status'] !== 'revoked' ) : ?>
                            <form method="POST" class="inline" onsubmit="return confirm('Yakin cabut lisensi ini?')">
                                <input type="hidden" name="action" value="revoke" />
                                <input type="hidden" name="license_id" value="<?= $lic['id'] ?>" />
                                <button type="submit" class="btn-danger btn-sm">Revoke</button>
                            </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
```

**Step 3: Commit**

```bash
git add license-server/admin/
git commit -m "feat(license-server): add admin dashboard with login, stats, and license management"
```

---

## Task 11: Create Build Script

**Files:**
- Create: `build.sh`

**Step 1: Write the build script**

Create `build.sh` at project root:

```bash
#!/bin/bash
set -e

# CampusOS Academic Build Script
# Usage: ./build.sh [version]
# Example: ./build.sh 1.2.2

VERSION="${1:-$(grep "define( 'CAMPUSOS_THEME_VERSION'" wp-content/themes/campusos-academic/functions.php | grep -oP "'[0-9]+\.[0-9]+\.[0-9]+'" | tr -d "'" )}"

if [ -z "$VERSION" ]; then
    echo "Error: Could not determine version. Pass as argument: ./build.sh 1.2.2"
    exit 1
fi

echo "=== CampusOS Academic Build v${VERSION} ==="

THEME_DIR="wp-content/themes/campusos-academic"
PLUGIN_DIR="wp-content/plugins/campusos-academic-core"
DIST_DIR="dist"
TEMP_DIR="/tmp/campusos-build-$$"

# 1. Sync versions
echo "[1/6] Syncing versions to ${VERSION}..."

# Theme style.css
sed -i '' "s/^Version: .*/Version: ${VERSION}/" "${THEME_DIR}/style.css"

# Theme functions.php
sed -i '' "s/define( 'CAMPUSOS_THEME_VERSION', '.*'/define( 'CAMPUSOS_THEME_VERSION', '${VERSION}'/" "${THEME_DIR}/functions.php"

# Plugin main file header
sed -i '' "s/^ \* Version: .*/ * Version: ${VERSION}/" "${PLUGIN_DIR}/campusos-academic-core.php"

# Plugin version constant
sed -i '' "s/define( 'CAMPUSOS_CORE_VERSION', '.*'/define( 'CAMPUSOS_CORE_VERSION', '${VERSION}'/" "${PLUGIN_DIR}/campusos-academic-core.php"

echo "   Versions synced."

# 2. Minify CSS
echo "[2/6] Minifying CSS..."
CSS_INPUT="${THEME_DIR}/assets/css/main.css"
CSS_OUTPUT="${THEME_DIR}/assets/css/main.min.css"

# PHP-based CSS minifier (no npm required)
php -r "
\$css = file_get_contents('${CSS_INPUT}');
// Remove comments
\$css = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', \$css);
// Remove whitespace
\$css = str_replace([\"\\r\\n\", \"\\r\", \"\\n\", \"\\t\"], '', \$css);
// Remove extra spaces
\$css = preg_replace('/\\s+/', ' ', \$css);
\$css = preg_replace('/\\s*([{}:;,>+~])\\s*/', '\\1', \$css);
// Remove last semicolons
\$css = str_replace(';}', '}', \$css);
\$css = trim(\$css);
file_put_contents('${CSS_OUTPUT}', \$css);
echo '   ' . strlen(file_get_contents('${CSS_INPUT}')) . ' -> ' . strlen(\$css) . ' bytes' . PHP_EOL;
"

# 3. Minify JS
echo "[3/6] Minifying JS..."
JS_INPUT="${THEME_DIR}/assets/js/main.js"
JS_OUTPUT="${THEME_DIR}/assets/js/main.min.js"

php -r "
\$js = file_get_contents('${JS_INPUT}');
// Remove single-line comments (but not URLs with //)
\$js = preg_replace('#(?<!:)//(?!/).*$#m', '', \$js);
// Remove multi-line comments
\$js = preg_replace('!/\*.*?\*/!s', '', \$js);
// Remove whitespace
\$js = preg_replace('/\\s+/', ' ', \$js);
\$js = preg_replace('/\\s*([{}();,=+\\-*/<>!&|:?])\\s*/', '\\1', \$js);
\$js = trim(\$js);
file_put_contents('${JS_OUTPUT}', \$js);
echo '   ' . strlen(file_get_contents('${JS_INPUT}')) . ' -> ' . strlen(\$js) . ' bytes' . PHP_EOL;
"

# 4. Prepare distribution directories
echo "[4/6] Preparing distribution..."
rm -rf "${TEMP_DIR}"
mkdir -p "${TEMP_DIR}/campusos-academic"
mkdir -p "${TEMP_DIR}/campusos-academic-core"
mkdir -p "${DIST_DIR}"

# 5. Copy theme files (excluding dev files)
echo "[5/6] Packaging theme..."
rsync -a --exclude='.git' \
    --exclude='.gitignore' \
    --exclude='CLAUDE.md' \
    --exclude='docs/' \
    --exclude='agent-skills/' \
    --exclude='build.sh' \
    --exclude='.claude/' \
    --exclude='node_modules/' \
    --exclude='*.log' \
    "${THEME_DIR}/" "${TEMP_DIR}/campusos-academic/"

# Copy plugin files
echo "   Packaging plugin..."
rsync -a --exclude='.git' \
    --exclude='node_modules/' \
    --exclude='*.log' \
    "${PLUGIN_DIR}/" "${TEMP_DIR}/campusos-academic-core/"

# 6. Create ZIP files
echo "[6/6] Creating ZIP files..."
cd "${TEMP_DIR}"
zip -rq "${OLDPWD}/${DIST_DIR}/campusos-academic-theme-v${VERSION}.zip" "campusos-academic/"
zip -rq "${OLDPWD}/${DIST_DIR}/campusos-academic-core-v${VERSION}.zip" "campusos-academic-core/"
cd "${OLDPWD}"

# Cleanup
rm -rf "${TEMP_DIR}"

echo ""
echo "=== Build Complete ==="
echo "Theme: ${DIST_DIR}/campusos-academic-theme-v${VERSION}.zip"
echo "Plugin: ${DIST_DIR}/campusos-academic-core-v${VERSION}.zip"
ls -lh "${DIST_DIR}/"*.zip
```

**Step 2: Make it executable**

```bash
chmod +x build.sh
```

**Step 3: Test the build**

```bash
./build.sh 1.2.2
```

Expected output: Two ZIP files in `dist/` directory.

**Step 4: Verify ZIP contents**

```bash
unzip -l dist/campusos-academic-theme-v1.2.2.zip | head -30
unzip -l dist/campusos-academic-core-v1.2.2.zip | head -20
```

Confirm: No `.git`, `CLAUDE.md`, `agent-skills/`, `docs/plans/` in the ZIP.

**Step 5: Add dist/ to .gitignore and commit**

```bash
echo "dist/" >> .gitignore
git add build.sh .gitignore
git commit -m "feat: add build script for minification and ZIP packaging"
```

---

## Task 12: Add Deactivation Hook for License Cron

**Files:**
- Modify: `wp-content/plugins/campusos-academic-core/campusos-academic-core.php`

**Step 1: Add license cron cleanup to deactivation hook**

In `campusos-academic-core.php`, in the `register_deactivation_hook` callback (around line 34-38), add:

```php
wp_clear_scheduled_hook( 'campusos_license_revalidate' );
```

**Step 2: Commit**

```bash
git add wp-content/plugins/campusos-academic-core/campusos-academic-core.php
git commit -m "fix(plugin): clear license revalidation cron on deactivation"
```

---

## Task 13: Capture Screenshots with Playwright

**Files:**
- Create: `docs/images/` (directory with screenshots)

**Step 1: Login to WordPress admin**

Navigate to `https://wp-unpatti.local/wp-login.php`, login with `dedhens` / `admin123`.

**Step 2: Capture screenshots of these pages**

Save each to `docs/images/`:

1. `dashboard.png` — WordPress Dashboard
2. `settings-lisensi.png` — CampusOS Settings → Lisensi tab
3. `settings-umum.png` — CampusOS Settings → Umum tab
4. `settings-keamanan.png` — CampusOS Settings → Keamanan tab
5. `settings-sso.png` — CampusOS Settings → SSO tab
6. `settings-export.png` — CampusOS Settings → Export/Import tab
7. `cpt-tenaga-pendidik.png` — Tenaga Pendidik list
8. `cpt-agenda.png` — Agenda list
9. `customizer.png` — Appearance → Customize
10. `homepage.png` — Frontend homepage
11. `pimpinan-settings.png` — Pimpinan settings page

**Step 3: Commit**

```bash
git add docs/images/
git commit -m "docs: add admin panel screenshots for documentation"
```

---

## Task 14: Create HTML Documentation

**Files:**
- Create: `docs/panduan-penggunaan.html`

**Step 1: Write the complete HTML documentation**

Create `docs/panduan-penggunaan.html` — a self-contained single-page HTML document in Bahasa Indonesia with:

- Sticky sidebar navigation
- Responsive design
- Inline CSS (no external dependencies)
- Images referenced from `images/` subdirectory
- Sections as defined in the design doc:
  1. Pendahuluan
  2. Persyaratan Sistem
  3. Instalasi (theme + plugin upload, license activation)
  4. Konfigurasi Awal (setup wizard, customizer — colors, fonts, institution info)
  5. Manajemen Konten (for each CPT: tenaga_pendidik, agenda, pengumuman, faq, galeri, dokumen, prestasi, kerjasama, fasilitas, mitra_industri, mata_kuliah, publikasi, beasiswa, testimonial, video, organisasi_mhs)
  6. Halaman Statis (visi-misi, sejarah, sambutan, akreditasi, CPL, penerimaan, biaya-ukt, statistik, tracer-study, struktur-org)
  7. Shortcodes (list all 18 with usage examples)
  8. Elementor Widgets (list all 11)
  9. Pengaturan Keamanan
  10. SSO
  11. Integrasi API
  12. Export/Import
  13. Troubleshooting

Each section should have:
- Brief description of what it does
- Step-by-step instructions
- Relevant screenshot (`<img src="images/...">`)

**Step 2: Commit**

```bash
git add docs/panduan-penggunaan.html
git commit -m "docs: add comprehensive HTML user guide in Bahasa Indonesia"
```

---

## Task 15: Final Testing & ZIP Generation

**Step 1: Run the build script**

```bash
./build.sh 1.2.2
```

**Step 2: Verify the ZIP contents**

```bash
unzip -l dist/campusos-academic-theme-v1.2.2.zip
unzip -l dist/campusos-academic-core-v1.2.2.zip
```

Confirm:
- Theme ZIP has `main.min.css` and `main.min.js`
- No dev files (CLAUDE.md, docs/plans, agent-skills, build.sh)
- Plugin ZIP has license module

**Step 3: Test install from ZIP**

If possible, install the ZIP on a fresh WordPress to verify it works.

**Step 4: Copy documentation into dist for distribution**

```bash
cp -r docs/panduan-penggunaan.html docs/images/ dist/
```

**Step 5: Final commit**

```bash
git add -A
git commit -m "chore: finalize v1.2.2 for distribution"
```

---

## Summary

| Task | Description | Files |
|------|-------------|-------|
| 1 | Version sync (plugin 1.1.0 → 1.2.2) | plugin main file |
| 2 | Optimize asset loading (minified, defer, preconnect) | functions.php |
| 3 | Extend lazy loading to templates | 3 template parts |
| 4 | License Client class | new: class-license-client.php |
| 5 | License tab in admin settings | class-admin-settings.php |
| 6 | Register License Client in plugin | class-plugin.php |
| 7 | Gate auto-updates behind license | 2 updater classes |
| 8 | License server — DB & config | new: license-server/ |
| 9 | License server — API endpoints | 4 API files |
| 10 | License server — Admin dashboard | 3 admin files |
| 11 | Build script | new: build.sh |
| 12 | Deactivation hook cleanup | plugin main file |
| 13 | Screenshots via Playwright | docs/images/ |
| 14 | HTML documentation | docs/panduan-penggunaan.html |
| 15 | Final testing & ZIP | dist/ |
