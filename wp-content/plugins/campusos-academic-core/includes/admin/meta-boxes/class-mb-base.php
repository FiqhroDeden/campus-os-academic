<?php
namespace CampusOS\Core\Admin\MetaBoxes;

if ( ! defined( 'ABSPATH' ) ) exit;

abstract class MB_Base {
    abstract protected function get_id(): string;
    abstract protected function get_title(): string;
    abstract protected function get_template(): string;
    abstract protected function get_fields(): array;

    public function register() {
        add_action( 'add_meta_boxes', [ $this, 'add_meta_box' ] );
        add_action( 'save_post_page', [ $this, 'save' ], 10, 2 );
        add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
    }

    public function add_meta_box() {
        add_meta_box(
            $this->get_id(),
            $this->get_title(),
            [ $this, 'render' ],
            'page',
            'normal',
            'high',
            [ '__back_compat_meta_box' => false ]
        );
    }

    public function render( $post ) {
        // Only show on correct template
        $template = get_page_template_slug( $post->ID );
        $expected = $this->get_template();
        echo "<div class='campusos-mb' data-template='{$expected}'" .
             ( $template !== $expected ? " style='display:none;'" : "" ) . ">";

        wp_nonce_field( $this->get_id() . '_nonce_action', $this->get_id() . '_nonce' );

        foreach ( $this->get_fields() as $field ) {
            $value = get_post_meta( $post->ID, '_' . $field['id'], true );
            $this->render_field( $field, $value );
        }
        echo '</div>';
    }

    protected function render_field( array $field, $value ) {
        $id    = esc_attr( $field['id'] );
        $label = esc_html( $field['label'] );

        echo '<div class="campusos-field" style="margin-bottom:15px;">';
        echo "<label style='display:block;font-weight:600;margin-bottom:4px;'>{$label}</label>";

        switch ( $field['type'] ) {
            case 'text':
                echo "<input type='text' name='{$id}' value='" . esc_attr( $value ) . "' class='widefat' />";
                break;
            case 'textarea':
                echo "<textarea name='{$id}' rows='4' class='widefat'>" . esc_textarea( $value ) . "</textarea>";
                break;
            case 'richtext':
                wp_editor( $value ?: '', $id, [ 'textarea_name' => $id, 'media_buttons' => true, 'textarea_rows' => 8 ] );
                break;
            case 'image':
                $img_url = $value ? wp_get_attachment_url( (int) $value ) : '';
                echo "<input type='hidden' name='{$id}' id='{$id}' value='" . esc_attr( $value ) . "' />";
                echo "<img src='" . esc_url( $img_url ) . "' style='max-width:300px;display:" . ($img_url ? 'block' : 'none') . ";margin-bottom:8px;' id='{$id}_preview' />";
                echo "<button type='button' class='button campusos-upload-btn' data-target='{$id}'>" . esc_html__('Pilih Gambar','campusos-academic') . "</button> ";
                echo "<button type='button' class='button campusos-remove-btn' data-target='{$id}' style='display:" . ($img_url ? 'inline-block' : 'none') . "'>" . esc_html__('Hapus','campusos-academic') . "</button>";
                break;
            case 'file':
                $file_url = $value ? wp_get_attachment_url( (int) $value ) : '';
                echo "<input type='hidden' name='{$id}' id='{$id}' value='" . esc_attr( $value ) . "' />";
                echo "<span id='{$id}_name'>" . ($file_url ? esc_html(basename($file_url)) : '') . "</span> ";
                echo "<button type='button' class='button campusos-upload-btn' data-target='{$id}' data-type='file'>" . esc_html__('Pilih File','campusos-academic') . "</button> ";
                echo "<button type='button' class='button campusos-remove-btn' data-target='{$id}' style='display:" . ($value ? 'inline-block' : 'none') . "'>" . esc_html__('Hapus','campusos-academic') . "</button>";
                break;
            case 'number':
                echo "<input type='number' name='{$id}' value='" . esc_attr( $value ) . "' class='widefat' />";
                break;
            case 'date':
                echo "<input type='date' name='{$id}' value='" . esc_attr( $value ) . "' class='widefat' />";
                break;
            case 'repeater':
                $this->render_repeater( $field, $value ?: [] );
                break;
        }
        if ( ! empty( $field['desc'] ) ) {
            echo "<p class='description'>" . esc_html( $field['desc'] ) . "</p>";
        }
        echo '</div>';
    }

    protected function render_repeater( array $field, $rows ) {
        $id = esc_attr( $field['id'] );
        if ( ! is_array( $rows ) ) $rows = [];
        echo "<div class='campusos-repeater' data-field='{$id}'>";
        echo "<div class='campusos-repeater-rows'>";
        foreach ( $rows as $i => $row ) {
            $this->render_repeater_row( $field, $id, $i, $row );
        }
        echo "</div>";
        echo "<button type='button' class='button campusos-repeater-add' data-field='{$id}'>" . esc_html__( 'Tambah Baris', 'campusos-academic' ) . "</button>";
        // Hidden template row
        echo "<div class='campusos-repeater-template' style='display:none;'>";
        $this->render_repeater_row( $field, $id, '__INDEX__', [] );
        echo "</div>";
        echo "</div>";
    }

    protected function render_repeater_row( array $field, string $id, $index, array $row ) {
        echo "<div class='campusos-repeater-row' style='border:1px solid #ddd;padding:10px;margin-bottom:8px;background:#fafafa;'>";
        foreach ( $field['sub_fields'] as $sub ) {
            $name  = $id . '[' . $index . '][' . $sub['id'] . ']';
            $value = $row[ $sub['id'] ] ?? '';
            echo "<div style='margin-bottom:8px;'>";
            echo "<label style='font-weight:500;font-size:13px;'>" . esc_html( $sub['label'] ) . "</label><br/>";
            if ( $sub['type'] === 'textarea' ) {
                echo "<textarea name='" . esc_attr($name) . "' rows='2' class='widefat'>" . esc_textarea( $value ) . "</textarea>";
            } else {
                echo "<input type='text' name='" . esc_attr($name) . "' value='" . esc_attr( $value ) . "' class='widefat' />";
            }
            echo "</div>";
        }
        echo "<button type='button' class='button campusos-repeater-remove' style='color:#a00;'>" . esc_html__( 'Hapus Baris', 'campusos-academic' ) . "</button>";
        echo "</div>";
    }

    public function enqueue_scripts( $hook ) {
        if ( ! in_array( $hook, [ 'post.php', 'post-new.php' ], true ) ) return;
        global $post_type;
        if ( $post_type !== 'page' ) return;

        wp_enqueue_media();
        wp_enqueue_script( 'campusos-admin-media', CAMPUSOS_CORE_URL . 'assets/js/admin-media.js', ['jquery'], CAMPUSOS_CORE_VERSION, true );
        wp_enqueue_script( 'campusos-admin-repeater', CAMPUSOS_CORE_URL . 'assets/js/admin-repeater.js', ['jquery'], CAMPUSOS_CORE_VERSION, true );
        wp_enqueue_script( 'campusos-admin-mb', CAMPUSOS_CORE_URL . 'assets/js/admin-metabox.js', ['jquery'], CAMPUSOS_CORE_VERSION, true );
    }

    public function save( $post_id, $post ) {
        if ( ! isset( $_POST[ $this->get_id() . '_nonce' ] ) ) return;
        if ( ! wp_verify_nonce( $_POST[ $this->get_id() . '_nonce' ], $this->get_id() . '_nonce_action' ) ) return;
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
        if ( ! current_user_can( 'edit_post', $post_id ) ) return;

        foreach ( $this->get_fields() as $field ) {
            $key = '_' . $field['id'];
            if ( $field['type'] === 'repeater' ) {
                $raw = isset( $_POST[ $field['id'] ] ) ? $_POST[ $field['id'] ] : [];
                $sanitized = [];
                if ( is_array( $raw ) ) {
                    foreach ( $raw as $row ) {
                        if ( ! is_array( $row ) ) continue;
                        $clean = [];
                        foreach ( $field['sub_fields'] as $sub ) {
                            $val = $row[ $sub['id'] ] ?? '';
                            $clean[ $sub['id'] ] = ( $sub['type'] === 'textarea' ) ? sanitize_textarea_field( $val ) : sanitize_text_field( $val );
                        }
                        $sanitized[] = $clean;
                    }
                }
                update_post_meta( $post_id, $key, $sanitized );
            } elseif ( $field['type'] === 'richtext' ) {
                $value = isset( $_POST[ $field['id'] ] ) ? wp_kses_post( $_POST[ $field['id'] ] ) : '';
                update_post_meta( $post_id, $key, $value );
            } elseif ( in_array( $field['type'], [ 'image', 'file', 'number' ] ) ) {
                $value = isset( $_POST[ $field['id'] ] ) ? absint( $_POST[ $field['id'] ] ) : 0;
                update_post_meta( $post_id, $key, $value );
            } else {
                $value = isset( $_POST[ $field['id'] ] ) ? sanitize_text_field( $_POST[ $field['id'] ] ) : '';
                update_post_meta( $post_id, $key, $value );
            }
        }
    }
}
