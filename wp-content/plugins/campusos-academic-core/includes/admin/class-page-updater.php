<?php
namespace CampusOS\Core\Admin;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Page Updater - Updates Elementor pages with dynamic shortcodes
 */
class Page_Updater {

    /**
     * Page ID to shortcode mapping
     */
    private $page_shortcodes = [
        27 => '[campusos_pimpinan columns="4"]',      // Pimpinan
        29 => '[campusos_kerjasama columns="3"]',     // Kerjasama
        30 => '[campusos_fasilitas columns="3"]',     // Fasilitas
        31 => '[campusos_dokumen]',                    // Dokumen
        33 => '[campusos_mata_kuliah]',               // CPL - Mata Kuliah
        34 => '[campusos_beasiswa columns="2"]',      // Penerimaan
        36 => '[campusos_testimonial columns="3"]',   // Tracer Study
    ];

    /**
     * Additional shortcodes for Statistik page (32)
     */
    private $statistik_shortcode = '[campusos_prestasi limit="3"]';

    /**
     * Initialize the updater
     */
    public function init() {
        add_action( 'admin_post_campusos_update_pages', [ $this, 'handle_update_pages' ] );
        add_action( 'admin_post_campusos_update_single_page', [ $this, 'handle_update_single_page' ] );
    }

    /**
     * Get all pages that can be updated
     */
    public function get_updatable_pages() {
        $pages = [
            27 => [ 'title' => 'Pimpinan', 'shortcode' => '[campusos_pimpinan columns="4"]', 'description' => '4 data pimpinan dengan foto' ],
            29 => [ 'title' => 'Kerjasama', 'shortcode' => '[campusos_kerjasama columns="3"]', 'description' => '5 data kerjasama/MoU' ],
            30 => [ 'title' => 'Fasilitas', 'shortcode' => '[campusos_fasilitas columns="3"]', 'description' => '6 data fasilitas' ],
            31 => [ 'title' => 'Dokumen', 'shortcode' => '[campusos_dokumen]', 'description' => '5 dokumen download' ],
            32 => [ 'title' => 'Statistik', 'shortcode' => '[campusos_prestasi limit="3"]', 'description' => 'Tambah section prestasi', 'append' => true ],
            33 => [ 'title' => 'CPL', 'shortcode' => '[campusos_mata_kuliah]', 'description' => 'Tabel mata kuliah' ],
            34 => [ 'title' => 'Penerimaan', 'shortcode' => '[campusos_beasiswa columns="2"]', 'description' => 'Info beasiswa' ],
            36 => [ 'title' => 'Tracer Study', 'shortcode' => '[campusos_testimonial columns="3"]', 'description' => 'Testimonial alumni' ],
        ];

        // Check if pages exist
        foreach ( $pages as $id => &$page ) {
            $post = get_post( $id );
            $page['exists'] = $post && $post->post_type === 'page';
            $page['has_elementor'] = $page['exists'] && get_post_meta( $id, '_elementor_data', true );
        }

        return $pages;
    }

    /**
     * Handle update all pages request
     */
    public function handle_update_pages() {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( __( 'Unauthorized', 'campusos-academic' ) );
        }

        check_admin_referer( 'campusos_update_pages' );

        $results = $this->update_all_pages();

        $success_count = count( array_filter( $results, function( $r ) { return $r['success']; } ) );
        $redirect = add_query_arg( [
            'page' => 'campusos-academic',
            'tab' => 'pages',
            'updated' => $success_count,
            'total' => count( $results ),
        ], admin_url( 'admin.php' ) );

        wp_safe_redirect( $redirect );
        exit;
    }

    /**
     * Handle update single page request
     */
    public function handle_update_single_page() {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( __( 'Unauthorized', 'campusos-academic' ) );
        }

        check_admin_referer( 'campusos_update_single_page' );

        $page_id = isset( $_GET['page_id'] ) ? absint( $_GET['page_id'] ) : 0;

        if ( ! $page_id ) {
            wp_die( __( 'Invalid page ID', 'campusos-academic' ) );
        }

        $result = $this->update_page( $page_id );

        $redirect = add_query_arg( [
            'page' => 'campusos-academic',
            'tab' => 'pages',
            'single_updated' => $result['success'] ? 1 : 0,
            'page_title' => urlencode( $result['title'] ?? '' ),
        ], admin_url( 'admin.php' ) );

        wp_safe_redirect( $redirect );
        exit;
    }

    /**
     * Update all pages with shortcodes
     */
    public function update_all_pages() {
        $results = [];
        $pages = $this->get_updatable_pages();

        foreach ( $pages as $page_id => $page_info ) {
            if ( $page_info['exists'] ) {
                $results[ $page_id ] = $this->update_page( $page_id );
            }
        }

        // Clear Elementor cache
        $this->clear_elementor_cache();

        return $results;
    }

    /**
     * Update a single page with its shortcode
     */
    public function update_page( $page_id ) {
        $pages = $this->get_updatable_pages();

        if ( ! isset( $pages[ $page_id ] ) ) {
            return [ 'success' => false, 'message' => 'Page not in update list' ];
        }

        $page_info = $pages[ $page_id ];
        $post = get_post( $page_id );

        if ( ! $post ) {
            return [ 'success' => false, 'message' => 'Page not found', 'title' => $page_info['title'] ];
        }

        $shortcode = $page_info['shortcode'];
        $append = isset( $page_info['append'] ) && $page_info['append'];

        // Check if page uses Elementor
        $elementor_data = get_post_meta( $page_id, '_elementor_data', true );

        if ( $elementor_data ) {
            // Update Elementor content
            $result = $this->update_elementor_page( $page_id, $shortcode, $append );
        } else {
            // Update regular page content
            $result = $this->update_regular_page( $page_id, $shortcode, $append );
        }

        $result['title'] = $page_info['title'];
        return $result;
    }

    /**
     * Update Elementor page with shortcode widget
     */
    private function update_elementor_page( $page_id, $shortcode, $append = false ) {
        $elementor_data = get_post_meta( $page_id, '_elementor_data', true );

        if ( ! $elementor_data ) {
            return [ 'success' => false, 'message' => 'No Elementor data found' ];
        }

        // Decode JSON
        $data = json_decode( $elementor_data, true );

        if ( ! is_array( $data ) ) {
            return [ 'success' => false, 'message' => 'Invalid Elementor data' ];
        }

        // Create shortcode widget
        $shortcode_widget = $this->create_shortcode_widget( $shortcode );

        if ( $append ) {
            // Append shortcode section at the end
            $new_section = $this->create_shortcode_section( $shortcode, 'Prestasi Terbaru' );
            $data[] = $new_section;
        } else {
            // Find content section (usually second section, after hero)
            // Replace its content with shortcode widget
            $data = $this->replace_content_section( $data, $shortcode_widget );
        }

        // Encode back to JSON
        $new_data = wp_json_encode( $data );

        // Update post meta
        update_post_meta( $page_id, '_elementor_data', wp_slash( $new_data ) );

        // Also update post content for non-Elementor viewing
        wp_update_post( [
            'ID' => $page_id,
            'post_content' => $shortcode,
        ] );

        return [ 'success' => true, 'message' => 'Updated successfully' ];
    }

    /**
     * Update regular (non-Elementor) page with shortcode
     */
    private function update_regular_page( $page_id, $shortcode, $append = false ) {
        $post = get_post( $page_id );

        if ( $append ) {
            $content = $post->post_content . "\n\n" . $shortcode;
        } else {
            $content = $shortcode;
        }

        $result = wp_update_post( [
            'ID' => $page_id,
            'post_content' => $content,
        ] );

        if ( is_wp_error( $result ) ) {
            return [ 'success' => false, 'message' => $result->get_error_message() ];
        }

        return [ 'success' => true, 'message' => 'Updated successfully' ];
    }

    /**
     * Create Elementor shortcode widget
     */
    private function create_shortcode_widget( $shortcode ) {
        return [
            'id' => $this->generate_elementor_id(),
            'elType' => 'widget',
            'settings' => [
                'shortcode' => $shortcode,
            ],
            'elements' => [],
            'widgetType' => 'shortcode',
        ];
    }

    /**
     * Create Elementor section with shortcode
     */
    private function create_shortcode_section( $shortcode, $heading = '' ) {
        $column_elements = [];

        // Add heading if provided
        if ( $heading ) {
            $column_elements[] = [
                'id' => $this->generate_elementor_id(),
                'elType' => 'widget',
                'settings' => [
                    'title' => $heading,
                    'header_size' => 'h2',
                    'align' => 'center',
                ],
                'elements' => [],
                'widgetType' => 'heading',
            ];
        }

        // Add shortcode widget
        $column_elements[] = $this->create_shortcode_widget( $shortcode );

        return [
            'id' => $this->generate_elementor_id(),
            'elType' => 'section',
            'settings' => [
                'padding' => [
                    'unit' => 'px',
                    'top' => '60',
                    'right' => '0',
                    'bottom' => '60',
                    'left' => '0',
                    'isLinked' => false,
                ],
            ],
            'elements' => [
                [
                    'id' => $this->generate_elementor_id(),
                    'elType' => 'column',
                    'settings' => [
                        '_column_size' => 100,
                    ],
                    'elements' => $column_elements,
                ],
            ],
        ];
    }

    /**
     * Replace content section with shortcode widget
     * Keeps the first section (hero) and replaces subsequent content
     */
    private function replace_content_section( $data, $shortcode_widget ) {
        if ( empty( $data ) ) {
            return $data;
        }

        // If there's only one section, add shortcode to it
        if ( count( $data ) === 1 ) {
            // Find the first column and add/replace content
            if ( isset( $data[0]['elements'][0] ) ) {
                // Keep any heading widget, replace other content
                $column = &$data[0]['elements'][0];
                $new_elements = [];

                foreach ( $column['elements'] as $widget ) {
                    // Keep headings and text that look like page titles
                    if ( isset( $widget['widgetType'] ) &&
                         in_array( $widget['widgetType'], [ 'heading', 'text-editor' ], true ) ) {
                        $new_elements[] = $widget;
                        break; // Keep only the first heading/title
                    }
                }

                // Add shortcode widget
                $new_elements[] = $shortcode_widget;
                $column['elements'] = $new_elements;
            }
            return $data;
        }

        // Multiple sections: keep first (hero), modify second (content)
        $new_data = [ $data[0] ]; // Keep hero section

        // Create new content section with shortcode
        $content_section = [
            'id' => $this->generate_elementor_id(),
            'elType' => 'section',
            'settings' => [
                'padding' => [
                    'unit' => 'px',
                    'top' => '40',
                    'right' => '0',
                    'bottom' => '60',
                    'left' => '0',
                    'isLinked' => false,
                ],
            ],
            'elements' => [
                [
                    'id' => $this->generate_elementor_id(),
                    'elType' => 'column',
                    'settings' => [
                        '_column_size' => 100,
                    ],
                    'elements' => [ $shortcode_widget ],
                ],
            ],
        ];

        $new_data[] = $content_section;

        return $new_data;
    }

    /**
     * Generate unique Elementor element ID
     */
    private function generate_elementor_id() {
        return substr( md5( uniqid( wp_rand(), true ) ), 0, 7 );
    }

    /**
     * Clear Elementor cache
     */
    private function clear_elementor_cache() {
        // Clear Elementor CSS cache
        if ( class_exists( '\Elementor\Plugin' ) ) {
            \Elementor\Plugin::$instance->files_manager->clear_cache();
        }

        // Delete Elementor CSS files transients
        delete_transient( 'elementor_css_data' );

        // Clear any page cache
        if ( function_exists( 'wp_cache_flush' ) ) {
            wp_cache_flush();
        }
    }

    /**
     * Render the pages tab in admin settings
     */
    public function render_tab() {
        $pages = $this->get_updatable_pages();

        // Show success messages
        if ( isset( $_GET['updated'] ) ) {
            $updated = absint( $_GET['updated'] );
            $total = absint( $_GET['total'] );
            echo '<div class="notice notice-success"><p>' .
                 sprintf( esc_html__( '%d dari %d halaman berhasil diperbarui.', 'campusos-academic' ), $updated, $total ) .
                 '</p></div>';
        }

        if ( isset( $_GET['single_updated'] ) ) {
            $success = absint( $_GET['single_updated'] );
            $title = isset( $_GET['page_title'] ) ? sanitize_text_field( urldecode( $_GET['page_title'] ) ) : '';
            if ( $success ) {
                echo '<div class="notice notice-success"><p>' .
                     sprintf( esc_html__( 'Halaman "%s" berhasil diperbarui.', 'campusos-academic' ), esc_html( $title ) ) .
                     '</p></div>';
            } else {
                echo '<div class="notice notice-error"><p>' .
                     sprintf( esc_html__( 'Gagal memperbarui halaman "%s".', 'campusos-academic' ), esc_html( $title ) ) .
                     '</p></div>';
            }
        }
        ?>
        <h3><?php esc_html_e( 'Update Halaman dengan Shortcode Dinamis', 'campusos-academic' ); ?></h3>
        <p><?php esc_html_e( 'Perbarui halaman Elementor dengan shortcode yang menampilkan data dari Custom Post Types.', 'campusos-academic' ); ?></p>

        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th style="width: 50px;">ID</th>
                    <th><?php esc_html_e( 'Halaman', 'campusos-academic' ); ?></th>
                    <th><?php esc_html_e( 'Shortcode', 'campusos-academic' ); ?></th>
                    <th><?php esc_html_e( 'Keterangan', 'campusos-academic' ); ?></th>
                    <th><?php esc_html_e( 'Status', 'campusos-academic' ); ?></th>
                    <th style="width: 120px;"><?php esc_html_e( 'Aksi', 'campusos-academic' ); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ( $pages as $page_id => $page ) : ?>
                <tr>
                    <td><?php echo esc_html( $page_id ); ?></td>
                    <td>
                        <strong><?php echo esc_html( $page['title'] ); ?></strong>
                        <?php if ( $page['exists'] ) : ?>
                            <br><a href="<?php echo esc_url( get_permalink( $page_id ) ); ?>" target="_blank" class="row-actions">
                                <?php esc_html_e( 'Lihat', 'campusos-academic' ); ?>
                            </a>
                            |
                            <a href="<?php echo esc_url( get_edit_post_link( $page_id ) ); ?>" class="row-actions">
                                <?php esc_html_e( 'Edit', 'campusos-academic' ); ?>
                            </a>
                        <?php endif; ?>
                    </td>
                    <td><code><?php echo esc_html( $page['shortcode'] ); ?></code></td>
                    <td><?php echo esc_html( $page['description'] ); ?></td>
                    <td>
                        <?php if ( ! $page['exists'] ) : ?>
                            <span style="color: #dc3232;">
                                <span class="dashicons dashicons-warning"></span>
                                <?php esc_html_e( 'Halaman tidak ditemukan', 'campusos-academic' ); ?>
                            </span>
                        <?php elseif ( $page['has_elementor'] ) : ?>
                            <span style="color: #0073aa;">
                                <span class="dashicons dashicons-yes"></span>
                                <?php esc_html_e( 'Elementor', 'campusos-academic' ); ?>
                            </span>
                        <?php else : ?>
                            <span style="color: #82878c;">
                                <span class="dashicons dashicons-editor-code"></span>
                                <?php esc_html_e( 'Standard', 'campusos-academic' ); ?>
                            </span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ( $page['exists'] ) : ?>
                            <a href="<?php echo esc_url( wp_nonce_url(
                                admin_url( 'admin-post.php?action=campusos_update_single_page&page_id=' . $page_id ),
                                'campusos_update_single_page'
                            ) ); ?>" class="button button-small">
                                <?php esc_html_e( 'Update', 'campusos-academic' ); ?>
                            </a>
                        <?php else : ?>
                            <button class="button button-small" disabled>
                                <?php esc_html_e( 'Update', 'campusos-academic' ); ?>
                            </button>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <p style="margin-top: 20px;">
            <a href="<?php echo esc_url( wp_nonce_url( admin_url( 'admin-post.php?action=campusos_update_pages' ), 'campusos_update_pages' ) ); ?>"
               class="button button-primary button-hero"
               onclick="return confirm('<?php esc_attr_e( 'Apakah Anda yakin ingin memperbarui semua halaman? Konten dummy akan diganti dengan shortcode dinamis.', 'campusos-academic' ); ?>');">
                <span class="dashicons dashicons-update" style="margin-top: 5px;"></span>
                <?php esc_html_e( 'Update Semua Halaman', 'campusos-academic' ); ?>
            </a>
        </p>

        <hr style="margin: 30px 0;">

        <h3><?php esc_html_e( 'Shortcode Reference', 'campusos-academic' ); ?></h3>
        <p><?php esc_html_e( 'Gunakan shortcode berikut di halaman atau widget manapun:', 'campusos-academic' ); ?></p>

        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th><?php esc_html_e( 'Shortcode', 'campusos-academic' ); ?></th>
                    <th><?php esc_html_e( 'Parameter', 'campusos-academic' ); ?></th>
                    <th><?php esc_html_e( 'Keterangan', 'campusos-academic' ); ?></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><code>[campusos_pimpinan]</code></td>
                    <td>columns="4", limit="-1"</td>
                    <td><?php esc_html_e( 'Grid pimpinan dengan foto dan jabatan', 'campusos-academic' ); ?></td>
                </tr>
                <tr>
                    <td><code>[campusos_tenaga_pendidik]</code></td>
                    <td>columns="3", limit="-1"</td>
                    <td><?php esc_html_e( 'Grid dosen dengan NIDN dan bidang keahlian', 'campusos-academic' ); ?></td>
                </tr>
                <tr>
                    <td><code>[campusos_kerjasama]</code></td>
                    <td>columns="3", limit="-1"</td>
                    <td><?php esc_html_e( 'Grid kerjasama/MoU dengan logo mitra', 'campusos-academic' ); ?></td>
                </tr>
                <tr>
                    <td><code>[campusos_fasilitas]</code></td>
                    <td>columns="3", limit="-1"</td>
                    <td><?php esc_html_e( 'Grid fasilitas dengan foto dan kapasitas', 'campusos-academic' ); ?></td>
                </tr>
                <tr>
                    <td><code>[campusos_dokumen]</code></td>
                    <td>limit="-1", kategori=""</td>
                    <td><?php esc_html_e( 'List dokumen dengan download link', 'campusos-academic' ); ?></td>
                </tr>
                <tr>
                    <td><code>[campusos_prestasi]</code></td>
                    <td>columns="3", limit="-1"</td>
                    <td><?php esc_html_e( 'Grid prestasi dengan peraih dan tingkat', 'campusos-academic' ); ?></td>
                </tr>
                <tr>
                    <td><code>[campusos_mata_kuliah]</code></td>
                    <td>limit="-1", semester=""</td>
                    <td><?php esc_html_e( 'Tabel mata kuliah dengan kode dan SKS', 'campusos-academic' ); ?></td>
                </tr>
                <tr>
                    <td><code>[campusos_beasiswa]</code></td>
                    <td>columns="2", limit="-1"</td>
                    <td><?php esc_html_e( 'Grid beasiswa dengan deadline', 'campusos-academic' ); ?></td>
                </tr>
                <tr>
                    <td><code>[campusos_testimonial]</code></td>
                    <td>columns="3", limit="6"</td>
                    <td><?php esc_html_e( 'Grid testimonial alumni', 'campusos-academic' ); ?></td>
                </tr>
                <tr>
                    <td><code>[campusos_agenda]</code></td>
                    <td>limit="5", style="list|grid"</td>
                    <td><?php esc_html_e( 'List atau grid agenda/event', 'campusos-academic' ); ?></td>
                </tr>
                <tr>
                    <td><code>[campusos_pengumuman]</code></td>
                    <td>limit="5"</td>
                    <td><?php esc_html_e( 'List pengumuman terbaru', 'campusos-academic' ); ?></td>
                </tr>
                <tr>
                    <td><code>[campusos_faq]</code></td>
                    <td>limit="-1"</td>
                    <td><?php esc_html_e( 'Accordion FAQ', 'campusos-academic' ); ?></td>
                </tr>
                <tr>
                    <td><code>[campusos_galeri]</code></td>
                    <td>columns="4", limit="12"</td>
                    <td><?php esc_html_e( 'Grid galeri foto', 'campusos-academic' ); ?></td>
                </tr>
                <tr>
                    <td><code>[campusos_video]</code></td>
                    <td>columns="3", limit="6"</td>
                    <td><?php esc_html_e( 'Grid video YouTube', 'campusos-academic' ); ?></td>
                </tr>
                <tr>
                    <td><code>[campusos_publikasi]</code></td>
                    <td>limit="10"</td>
                    <td><?php esc_html_e( 'List publikasi ilmiah', 'campusos-academic' ); ?></td>
                </tr>
                <tr>
                    <td><code>[campusos_mitra_industri]</code></td>
                    <td>columns="5", limit="-1"</td>
                    <td><?php esc_html_e( 'Grid logo mitra industri', 'campusos-academic' ); ?></td>
                </tr>
                <tr>
                    <td><code>[campusos_organisasi_mhs]</code></td>
                    <td>columns="3", limit="-1"</td>
                    <td><?php esc_html_e( 'Grid organisasi mahasiswa', 'campusos-academic' ); ?></td>
                </tr>
            </tbody>
        </table>
        <?php
    }
}
