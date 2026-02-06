<?php
namespace UNPATTI\Core\Admin;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Post Status Fixer - Fixes scheduled posts issue for CPTs and Pages
 */
class Post_Status_Fixer {

    /**
     * CPT slugs managed by this plugin
     */
    private $cpt_slugs = [
        'pimpinan',
        'tenaga_pendidik',
        'kerjasama',
        'fasilitas',
        'prestasi',
        'dokumen',
        'agenda',
        'faq',
        'mata_kuliah',
        'organisasi_mhs',
        'mitra_industri',
        'publikasi',
        'beasiswa',
        'galeri',
        'video',
        'pengumuman',
        'testimonial',
    ];

    /**
     * All post types to fix (CPTs + page)
     */
    private $all_post_types = [];

    /**
     * Initialize hooks
     */
    public function init() {
        // Include 'page' in the list of post types to fix
        $this->all_post_types = array_merge( $this->cpt_slugs, [ 'page', 'post' ] );

        // Add "Published" option to status dropdown for scheduled posts
        add_action( 'admin_footer-post.php', [ $this, 'add_publish_status_option' ] );
        add_action( 'admin_footer-post-new.php', [ $this, 'add_publish_status_option' ] );

        // Handle publish all scheduled action
        add_action( 'admin_post_unpatti_publish_scheduled', [ $this, 'handle_publish_scheduled' ] );

        // Prevent future posts from becoming scheduled - Classic Editor
        add_filter( 'wp_insert_post_data', [ $this, 'prevent_scheduled_status' ], 99, 2 );

        // Prevent future posts from becoming scheduled - Gutenberg/REST API
        // Add filters for all post types
        foreach ( $this->all_post_types as $post_type ) {
            add_filter( "rest_pre_insert_{$post_type}", [ $this, 'fix_rest_post_status' ], 99, 2 );
            add_action( "rest_after_insert_{$post_type}", [ $this, 'fix_post_after_rest_insert' ], 99, 3 );
        }

        // Add admin notices
        add_action( 'admin_notices', [ $this, 'scheduled_posts_notice' ] );
        add_action( 'admin_notices', [ $this, 'bulk_publish_notice' ] );

        // Add "Published" to Quick Edit dropdown
        add_action( 'admin_footer-edit.php', [ $this, 'add_publish_to_quick_edit' ] );

        // Add Bulk Action to publish scheduled posts
        foreach ( $this->all_post_types as $post_type ) {
            add_filter( "bulk_actions-edit-{$post_type}", [ $this, 'add_publish_bulk_action' ] );
            add_filter( "handle_bulk_actions-edit-{$post_type}", [ $this, 'handle_publish_bulk_action' ], 10, 3 );
        }

        // Add Gutenberg editor script to fix status
        add_action( 'enqueue_block_editor_assets', [ $this, 'enqueue_block_editor_fix' ] );
    }

    /**
     * Show notice after bulk publish
     */
    public function bulk_publish_notice() {
        if ( ! isset( $_GET['bulk_published'] ) ) {
            return;
        }

        $count = absint( $_GET['bulk_published'] );
        ?>
        <div class="notice notice-success is-dismissible">
            <p>
                <?php
                printf(
                    esc_html( _n( '%d post berhasil di-publish.', '%d posts berhasil di-publish.', $count, 'unpatti-academic' ) ),
                    $count
                );
                ?>
            </p>
        </div>
        <?php
    }

    /**
     * Add "Publish Now" to bulk actions dropdown
     */
    public function add_publish_bulk_action( $bulk_actions ) {
        $bulk_actions['unpatti_publish_now'] = __( 'Publish Sekarang', 'unpatti-academic' );
        return $bulk_actions;
    }

    /**
     * Handle bulk publish action
     */
    public function handle_publish_bulk_action( $redirect_to, $action, $post_ids ) {
        if ( $action !== 'unpatti_publish_now' ) {
            return $redirect_to;
        }

        $count = 0;
        foreach ( $post_ids as $post_id ) {
            $result = wp_update_post( [
                'ID'            => $post_id,
                'post_status'   => 'publish',
                'post_date'     => current_time( 'mysql' ),
                'post_date_gmt' => current_time( 'mysql', 1 ),
            ] );

            if ( $result && ! is_wp_error( $result ) ) {
                $count++;
            }
        }

        $redirect_to = add_query_arg( 'bulk_published', $count, $redirect_to );
        return $redirect_to;
    }

    /**
     * Add "Published" option to Quick Edit status dropdown
     */
    public function add_publish_to_quick_edit() {
        global $post_type;

        if ( ! in_array( $post_type, $this->all_post_types, true ) ) {
            return;
        }
        ?>
        <script type="text/javascript">
        jQuery(document).ready(function($) {
            // Add "Published" option to Quick Edit status dropdown
            var $quickEditStatus = $('select[name="_status"]');

            // Function to add publish option if not exists
            function addPublishOption($select) {
                if ($select.find('option[value="publish"]').length === 0) {
                    $select.prepend('<option value="publish"><?php echo esc_js( __( 'Published', 'unpatti-academic' ) ); ?></option>');
                }
            }

            // Add to existing dropdowns
            addPublishOption($quickEditStatus);

            // Also watch for Quick Edit to be opened (dynamically created)
            $(document).on('click', '.editinline', function() {
                setTimeout(function() {
                    $('select[name="_status"]').each(function() {
                        addPublishOption($(this));
                    });
                }, 100);
            });

            // When Quick Edit form is submitted with publish status, also fix the date
            $(document).on('click', '.inline-edit-save .save', function() {
                var $row = $(this).closest('.inline-edit-row');
                var $status = $row.find('select[name="_status"]');

                if ($status.val() === 'publish') {
                    // Set date to current time
                    var now = new Date();
                    var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

                    $row.find('select[name="mm"]').val(months[now.getMonth()]);
                    $row.find('input[name="jj"]').val(now.getDate());
                    $row.find('input[name="aa"]').val(now.getFullYear());
                    $row.find('input[name="hh"]').val(('0' + now.getHours()).slice(-2));
                    $row.find('input[name="mn"]').val(('0' + now.getMinutes()).slice(-2));
                }
            });
        });
        </script>
        <?php
    }

    /**
     * Enqueue block editor script to fix scheduled status
     */
    public function enqueue_block_editor_fix() {
        global $post;

        if ( ! $post ) {
            return;
        }

        // Inline script to prevent scheduled status in Gutenberg
        $script = "
        ( function( wp ) {
            if ( ! wp || ! wp.data ) return;

            var lastStatus = null;

            wp.data.subscribe( function() {
                var editor = wp.data.select( 'core/editor' );
                if ( ! editor ) return;

                var post = editor.getCurrentPost();
                var editedStatus = editor.getEditedPostAttribute( 'status' );

                // If user set status to publish but it changed to future, fix it
                if ( lastStatus === 'publish' && editedStatus === 'future' ) {
                    // Set date to now
                    var now = new Date();
                    wp.data.dispatch( 'core/editor' ).editPost( {
                        status: 'publish',
                        date: now.toISOString()
                    } );
                }

                lastStatus = editedStatus;
            } );

            // Also fix on save
            var originalSavePost = wp.data.dispatch( 'core/editor' ).savePost;
            if ( originalSavePost ) {
                wp.data.dispatch( 'core/editor' ).savePost = function( options ) {
                    var editor = wp.data.select( 'core/editor' );
                    var status = editor.getEditedPostAttribute( 'status' );

                    if ( status === 'publish' ) {
                        var postDate = new Date( editor.getEditedPostAttribute( 'date' ) );
                        var now = new Date();

                        if ( postDate > now ) {
                            wp.data.dispatch( 'core/editor' ).editPost( {
                                date: now.toISOString()
                            } );
                        }
                    }

                    return originalSavePost.apply( this, arguments );
                };
            }
        } )( window.wp );
        ";

        wp_add_inline_script( 'wp-edit-post', $script );
    }

    /**
     * Fix post status in REST API request
     */
    public function fix_rest_post_status( $prepared_post, $request ) {
        if ( ! isset( $prepared_post->post_status ) ) {
            return $prepared_post;
        }

        // If status is publish but date is in future, set date to now
        if ( $prepared_post->post_status === 'publish' && isset( $prepared_post->post_date ) ) {
            $post_date = strtotime( $prepared_post->post_date );
            $now = current_time( 'timestamp' );

            if ( $post_date > $now ) {
                $prepared_post->post_date = current_time( 'mysql' );
                $prepared_post->post_date_gmt = current_time( 'mysql', 1 );
            }
        }

        // If status is future but we want publish, change it
        if ( $prepared_post->post_status === 'future' ) {
            $request_status = $request->get_param( 'status' );
            if ( $request_status === 'publish' ) {
                $prepared_post->post_status = 'publish';
                $prepared_post->post_date = current_time( 'mysql' );
                $prepared_post->post_date_gmt = current_time( 'mysql', 1 );
            }
        }

        return $prepared_post;
    }

    /**
     * Fix post after REST API insert if it became scheduled
     */
    public function fix_post_after_rest_insert( $post, $request, $creating ) {
        // If post is now future/scheduled but request wanted publish, fix it
        if ( $post->post_status === 'future' ) {
            $request_status = $request->get_param( 'status' );

            // If user didn't explicitly set to future, change to publish
            if ( $request_status === 'publish' || empty( $request_status ) ) {
                wp_update_post( [
                    'ID'            => $post->ID,
                    'post_status'   => 'publish',
                    'post_date'     => current_time( 'mysql' ),
                    'post_date_gmt' => current_time( 'mysql', 1 ),
                ] );
            }
        }
    }

    /**
     * Add JavaScript to include "Published" option in status dropdown (Classic Editor)
     */
    public function add_publish_status_option() {
        global $post;

        if ( ! $post || ! in_array( $post->post_type, $this->all_post_types, true ) ) {
            return;
        }

        if ( $post->post_status !== 'future' ) {
            return;
        }
        ?>
        <script type="text/javascript">
        jQuery(document).ready(function($) {
            // Add "Published" option to status dropdown
            var $statusSelect = $('#post_status');
            if ($statusSelect.length && $statusSelect.find('option[value="publish"]').length === 0) {
                $statusSelect.prepend('<option value="publish"><?php echo esc_js( __( 'Published', 'unpatti-academic' ) ); ?></option>');
            }

            // Update the display text when publish is selected
            $statusSelect.on('change', function() {
                if ($(this).val() === 'publish') {
                    $('#post-status-display').text('<?php echo esc_js( __( 'Published', 'unpatti-academic' ) ); ?>');
                }
            });

            // Also add a quick publish button
            var $publishActions = $('#misc-publishing-actions');
            if ($publishActions.length) {
                $publishActions.append(
                    '<div class="misc-pub-section" style="border-top: 1px solid #ddd; padding-top: 10px; margin-top: 10px;">' +
                    '<a href="#" id="unpatti-quick-publish" class="button button-primary" style="width: 100%;">' +
                    '<?php echo esc_js( __( 'Publish Sekarang', 'unpatti-academic' ) ); ?>' +
                    '</a></div>'
                );

                $('#unpatti-quick-publish').on('click', function(e) {
                    e.preventDefault();
                    $statusSelect.val('publish').trigger('change');
                    // Set date to now
                    var now = new Date();
                    $('#aa').val(now.getFullYear());
                    $('#mm').val(('0' + (now.getMonth() + 1)).slice(-2));
                    $('#jj').val(('0' + now.getDate()).slice(-2));
                    $('#hh').val(('0' + now.getHours()).slice(-2));
                    $('#mn').val(('0' + now.getMinutes()).slice(-2));
                    // Click OK to save date
                    $('.save-timestamp').trigger('click');
                    // Submit the form
                    $('#publish').trigger('click');
                });
            }
        });
        </script>
        <?php
    }

    /**
     * Prevent future date from causing scheduled status for our post types
     */
    public function prevent_scheduled_status( $data, $postarr ) {
        // For all managed post types (CPTs + page + post)
        if ( ! in_array( $data['post_type'], $this->all_post_types, true ) ) {
            return $data;
        }

        // If trying to publish but date is in future, set to now
        if ( $data['post_status'] === 'publish' ) {
            $post_date = strtotime( $data['post_date'] );
            $now = current_time( 'timestamp' );

            if ( $post_date > $now ) {
                // Set post date to current time
                $data['post_date'] = current_time( 'mysql' );
                $data['post_date_gmt'] = current_time( 'mysql', 1 );
            }
        }

        // If status is future and user wants to publish, change to publish
        if ( $data['post_status'] === 'future' ) {
            // Check various ways the user might have requested publish
            $wants_publish = false;

            if ( isset( $_POST['post_status'] ) && $_POST['post_status'] === 'publish' ) {
                $wants_publish = true;
            }

            // Check if original post was published (user just clicked update)
            if ( ! empty( $postarr['ID'] ) ) {
                $original_post = get_post( $postarr['ID'] );
                if ( $original_post && $original_post->post_status === 'publish' ) {
                    $wants_publish = true;
                }
            }

            if ( $wants_publish ) {
                $data['post_status'] = 'publish';
                $data['post_date'] = current_time( 'mysql' );
                $data['post_date_gmt'] = current_time( 'mysql', 1 );
            }
        }

        return $data;
    }

    /**
     * Handle publish all scheduled posts action
     */
    public function handle_publish_scheduled() {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( __( 'Unauthorized', 'unpatti-academic' ) );
        }

        check_admin_referer( 'unpatti_publish_scheduled' );

        $post_type = isset( $_GET['post_type'] ) ? sanitize_text_field( $_GET['post_type'] ) : '';

        $count = $this->publish_all_scheduled( $post_type );

        $redirect = add_query_arg( [
            'page' => 'unpatti-academic',
            'tab' => 'tools',
            'published' => $count,
        ], admin_url( 'admin.php' ) );

        wp_safe_redirect( $redirect );
        exit;
    }

    /**
     * Publish all scheduled posts
     */
    public function publish_all_scheduled( $post_type = '' ) {
        $args = [
            'post_status'    => 'future',
            'posts_per_page' => -1,
            'fields'         => 'ids',
        ];

        if ( $post_type && in_array( $post_type, $this->all_post_types, true ) ) {
            $args['post_type'] = $post_type;
        } else {
            $args['post_type'] = $this->all_post_types;
        }

        $scheduled_posts = get_posts( $args );
        $count = 0;

        foreach ( $scheduled_posts as $post_id ) {
            $result = wp_update_post( [
                'ID'            => $post_id,
                'post_status'   => 'publish',
                'post_date'     => current_time( 'mysql' ),
                'post_date_gmt' => current_time( 'mysql', 1 ),
            ] );

            if ( $result && ! is_wp_error( $result ) ) {
                $count++;
            }
        }

        return $count;
    }

    /**
     * Get count of scheduled posts
     */
    public function get_scheduled_count( $post_type = '' ) {
        $args = [
            'post_status'    => 'future',
            'posts_per_page' => -1,
            'fields'         => 'ids',
        ];

        if ( $post_type && in_array( $post_type, $this->all_post_types, true ) ) {
            $args['post_type'] = $post_type;
        } else {
            $args['post_type'] = $this->all_post_types;
        }

        return count( get_posts( $args ) );
    }

    /**
     * Show admin notice if there are scheduled posts
     */
    public function scheduled_posts_notice() {
        $screen = get_current_screen();

        // Only show on edit.php screens for our post types
        if ( ! $screen || $screen->base !== 'edit' ) {
            return;
        }

        if ( ! in_array( $screen->post_type, $this->all_post_types, true ) ) {
            return;
        }

        $count = $this->get_scheduled_count( $screen->post_type );

        if ( $count === 0 ) {
            return;
        }

        $post_type_obj = get_post_type_object( $screen->post_type );
        $type_name = $post_type_obj ? $post_type_obj->labels->name : $screen->post_type;

        $publish_url = wp_nonce_url(
            admin_url( 'admin-post.php?action=unpatti_publish_scheduled&post_type=' . $screen->post_type ),
            'unpatti_publish_scheduled'
        );
        ?>
        <div class="notice notice-warning is-dismissible">
            <p>
                <?php
                printf(
                    esc_html__( 'Ada %d %s dengan status "Scheduled". ', 'unpatti-academic' ),
                    $count,
                    esc_html( $type_name )
                );
                ?>
                <a href="<?php echo esc_url( $publish_url ); ?>" class="button button-small">
                    <?php esc_html_e( 'Publish Semua Sekarang', 'unpatti-academic' ); ?>
                </a>
            </p>
        </div>
        <?php
    }

    /**
     * Render tools tab content
     */
    public function render_tools_tab() {
        // Show success message
        if ( isset( $_GET['published'] ) ) {
            $count = absint( $_GET['published'] );
            echo '<div class="notice notice-success"><p>' .
                 sprintf( esc_html__( '%d post berhasil di-publish.', 'unpatti-academic' ), $count ) .
                 '</p></div>';
        }
        ?>
        <h3><?php esc_html_e( 'Perbaiki Post Scheduled', 'unpatti-academic' ); ?></h3>
        <p><?php esc_html_e( 'Post dengan status "Scheduled" tidak akan muncul di frontend. Gunakan tombol di bawah untuk mengubah semua post scheduled menjadi published.', 'unpatti-academic' ); ?></p>

        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th><?php esc_html_e( 'Post Type', 'unpatti-academic' ); ?></th>
                    <th style="width: 100px;"><?php esc_html_e( 'Scheduled', 'unpatti-academic' ); ?></th>
                    <th style="width: 150px;"><?php esc_html_e( 'Aksi', 'unpatti-academic' ); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php
                $total_scheduled = 0;
                foreach ( $this->all_post_types as $cpt ) :
                    $post_type_obj = get_post_type_object( $cpt );
                    if ( ! $post_type_obj ) continue;

                    $count = $this->get_scheduled_count( $cpt );
                    $total_scheduled += $count;
                    ?>
                    <tr>
                        <td>
                            <strong><?php echo esc_html( $post_type_obj->labels->name ); ?></strong>
                            <br>
                            <a href="<?php echo esc_url( admin_url( 'edit.php?post_type=' . $cpt ) ); ?>" class="row-actions">
                                <?php esc_html_e( 'Lihat Semua', 'unpatti-academic' ); ?>
                            </a>
                        </td>
                        <td>
                            <?php if ( $count > 0 ) : ?>
                                <span style="color: #d63638; font-weight: 600;"><?php echo esc_html( $count ); ?></span>
                            <?php else : ?>
                                <span style="color: #00a32a;">0</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ( $count > 0 ) : ?>
                                <a href="<?php echo esc_url( wp_nonce_url(
                                    admin_url( 'admin-post.php?action=unpatti_publish_scheduled&post_type=' . $cpt ),
                                    'unpatti_publish_scheduled'
                                ) ); ?>" class="button button-small">
                                    <?php esc_html_e( 'Publish', 'unpatti-academic' ); ?>
                                </a>
                            <?php else : ?>
                                <button class="button button-small" disabled><?php esc_html_e( 'Publish', 'unpatti-academic' ); ?></button>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <th><strong><?php esc_html_e( 'Total', 'unpatti-academic' ); ?></strong></th>
                    <th>
                        <?php if ( $total_scheduled > 0 ) : ?>
                            <span style="color: #d63638; font-weight: 600;"><?php echo esc_html( $total_scheduled ); ?></span>
                        <?php else : ?>
                            <span style="color: #00a32a;">0</span>
                        <?php endif; ?>
                    </th>
                    <th>
                        <?php if ( $total_scheduled > 0 ) : ?>
                            <a href="<?php echo esc_url( wp_nonce_url(
                                admin_url( 'admin-post.php?action=unpatti_publish_scheduled' ),
                                'unpatti_publish_scheduled'
                            ) ); ?>" class="button button-primary button-small">
                                <?php esc_html_e( 'Publish Semua', 'unpatti-academic' ); ?>
                            </a>
                        <?php endif; ?>
                    </th>
                </tr>
            </tfoot>
        </table>

        <hr style="margin: 30px 0;">

        <h3><?php esc_html_e( 'Informasi Timezone', 'unpatti-academic' ); ?></h3>
        <table class="form-table">
            <tr>
                <th><?php esc_html_e( 'Timezone WordPress', 'unpatti-academic' ); ?></th>
                <td><code><?php echo esc_html( wp_timezone_string() ); ?></code></td>
            </tr>
            <tr>
                <th><?php esc_html_e( 'Waktu Server Saat Ini', 'unpatti-academic' ); ?></th>
                <td><code><?php echo esc_html( current_time( 'Y-m-d H:i:s' ) ); ?></code></td>
            </tr>
            <tr>
                <th><?php esc_html_e( 'Waktu UTC', 'unpatti-academic' ); ?></th>
                <td><code><?php echo esc_html( current_time( 'Y-m-d H:i:s', true ) ); ?></code></td>
            </tr>
        </table>
        <p>
            <a href="<?php echo esc_url( admin_url( 'options-general.php' ) ); ?>" class="button">
                <?php esc_html_e( 'Ubah Pengaturan Timezone', 'unpatti-academic' ); ?>
            </a>
        </p>
        <?php
    }
}
