<?php
namespace UNPATTI\Core\CPT;

if ( ! defined( 'ABSPATH' ) ) exit;

abstract class CPT_Base {
    abstract protected function get_slug(): string;
    abstract protected function get_labels(): array;
    abstract protected function get_args(): array;
    abstract protected function get_meta_fields(): array;

    public function register() {
        add_action( 'init', [ $this, 'register_post_type' ] );
        add_action( 'add_meta_boxes', [ $this, 'add_meta_boxes' ] );
        add_action( 'save_post', [ $this, 'save_meta' ], 10, 2 );
        add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_admin_scripts' ] );
    }

    public function register_post_type() {
        register_post_type( $this->get_slug(), $this->get_args() );
    }

    public function enqueue_admin_scripts( $hook ) {
        if ( ! in_array( $hook, [ 'post.php', 'post-new.php' ], true ) ) return;
        global $post_type;
        if ( $post_type !== $this->get_slug() ) return;

        wp_enqueue_media();
        wp_enqueue_script(
            'unpatti-admin-media',
            UNPATTI_CORE_URL . 'assets/js/admin-media.js',
            [ 'jquery' ],
            UNPATTI_CORE_VERSION,
            true
        );
    }

    public function add_meta_boxes() {
        add_meta_box(
            $this->get_slug() . '_details',
            __( 'Detail', 'unpatti-academic' ),
            [ $this, 'render_meta_box' ],
            $this->get_slug(),
            'normal',
            'high'
        );
    }

    public function render_meta_box( $post ) {
        wp_nonce_field( $this->get_slug() . '_nonce_action', $this->get_slug() . '_nonce' );
        echo '<div class="unpatti-meta-fields">';
        foreach ( $this->get_meta_fields() as $field ) {
            $value = get_post_meta( $post->ID, '_' . $field['id'], true );
            $this->render_field( $field, $value );
        }
        echo '</div>';
    }

    protected function render_field( array $field, $value ) {
        $id    = esc_attr( $field['id'] );
        $label = esc_html( $field['label'] );
        $desc  = isset( $field['desc'] ) ? esc_html( $field['desc'] ) : '';

        echo '<div class="unpatti-field" style="margin-bottom:15px;">';
        echo "<label for='{$id}' style='display:block;font-weight:600;margin-bottom:4px;'>{$label}</label>";

        switch ( $field['type'] ) {
            case 'text':
            case 'url':
            case 'email':
            case 'number':
                $type = esc_attr( $field['type'] );
                $val  = esc_attr( $value );
                echo "<input type='{$type}' id='{$id}' name='{$id}' value='{$val}' class='widefat' />";
                break;

            case 'textarea':
                echo "<textarea id='{$id}' name='{$id}' rows='4' class='widefat'>" . esc_textarea( $value ) . "</textarea>";
                break;

            case 'select':
                echo "<select id='{$id}' name='{$id}' class='widefat'>";
                echo "<option value=''>" . esc_html__( '-- Pilih --', 'unpatti-academic' ) . "</option>";
                foreach ( $field['options'] as $k => $v ) {
                    $sel = selected( $value, $k, false );
                    echo "<option value='" . esc_attr( $k ) . "' {$sel}>" . esc_html( $v ) . "</option>";
                }
                echo "</select>";
                break;

            case 'image':
                $img_url = $value ? wp_get_attachment_url( (int) $value ) : '';
                echo "<div class='unpatti-image-field'>";
                echo "<input type='hidden' id='{$id}' name='{$id}' value='" . esc_attr( $value ) . "' />";
                echo "<img src='" . esc_url( $img_url ) . "' style='max-width:200px;display:" . ( $img_url ? 'block' : 'none' ) . ";margin-bottom:8px;' id='{$id}_preview' />";
                echo "<button type='button' class='button unpatti-upload-btn' data-target='{$id}'>" . esc_html__( 'Pilih Gambar', 'unpatti-academic' ) . "</button> ";
                echo "<button type='button' class='button unpatti-remove-btn' data-target='{$id}' style='display:" . ( $img_url ? 'inline-block' : 'none' ) . ";'>" . esc_html__( 'Hapus', 'unpatti-academic' ) . "</button>";
                echo "</div>";
                break;

            case 'file':
                $file_url = $value ? wp_get_attachment_url( (int) $value ) : '';
                echo "<input type='hidden' id='{$id}' name='{$id}' value='" . esc_attr( $value ) . "' />";
                echo "<span id='{$id}_name'>" . ( $file_url ? esc_html( basename( $file_url ) ) : '' ) . "</span> ";
                echo "<button type='button' class='button unpatti-upload-btn' data-target='{$id}' data-type='file'>" . esc_html__( 'Pilih File', 'unpatti-academic' ) . "</button> ";
                echo "<button type='button' class='button unpatti-remove-btn' data-target='{$id}' style='display:" . ( $value ? 'inline-block' : 'none' ) . ";'>" . esc_html__( 'Hapus', 'unpatti-academic' ) . "</button>";
                break;

            case 'date':
                $val = esc_attr( $value );
                echo "<input type='date' id='{$id}' name='{$id}' value='{$val}' class='widefat' />";
                break;
        }

        if ( $desc ) {
            echo "<p class='description'>{$desc}</p>";
        }
        echo '</div>';
    }

    public function save_meta( $post_id, $post ) {
        if ( $post->post_type !== $this->get_slug() ) return;
        if ( ! isset( $_POST[ $this->get_slug() . '_nonce' ] ) ) return;
        if ( ! wp_verify_nonce( $_POST[ $this->get_slug() . '_nonce' ], $this->get_slug() . '_nonce_action' ) ) return;
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
        if ( ! current_user_can( 'edit_post', $post_id ) ) return;

        foreach ( $this->get_meta_fields() as $field ) {
            $key   = '_' . $field['id'];
            $value = isset( $_POST[ $field['id'] ] ) ? $_POST[ $field['id'] ] : '';

            switch ( $field['type'] ) {
                case 'text':
                case 'date':
                    $value = sanitize_text_field( $value );
                    break;
                case 'textarea':
                    $value = sanitize_textarea_field( $value );
                    break;
                case 'url':
                    $value = esc_url_raw( $value );
                    break;
                case 'email':
                    $value = sanitize_email( $value );
                    break;
                case 'number':
                case 'image':
                case 'file':
                    $value = absint( $value );
                    break;
                case 'select':
                    $value = sanitize_text_field( $value );
                    break;
            }
            update_post_meta( $post_id, $key, $value );
        }
    }
}
