<?php
namespace UNPATTI\Core\CPT;

if ( ! defined( 'ABSPATH' ) ) exit;

class CPT_Video extends CPT_Base {
    protected function get_slug(): string {
        return 'video';
    }

    protected function get_labels(): array {
        return [
            'name'               => __( 'Video', 'unpatti-academic' ),
            'singular_name'      => __( 'Video', 'unpatti-academic' ),
            'add_new_item'       => __( 'Tambah Video', 'unpatti-academic' ),
            'edit_item'          => __( 'Edit Video', 'unpatti-academic' ),
            'all_items'          => __( 'Semua Video', 'unpatti-academic' ),
            'search_items'       => __( 'Cari Video', 'unpatti-academic' ),
            'not_found'          => __( 'Tidak ditemukan', 'unpatti-academic' ),
        ];
    }

    protected function get_args(): array {
        return [
            'labels'       => $this->get_labels(),
            'public'       => true,
            'has_archive'  => true,
            'show_in_rest' => true,
            'supports'     => [ 'title', 'editor', 'thumbnail' ],
            'menu_icon'    => 'dashicons-format-video',
            'rewrite'      => [ 'slug' => 'video' ],
        ];
    }

    protected function get_meta_fields(): array {
        return [
            [ 'id' => 'video_youtube_url', 'label' => 'URL YouTube', 'type' => 'url' ],
            [ 'id' => 'video_video_duration', 'label' => 'Durasi Video', 'type' => 'text' ],
        ];
    }
}
