<?php
namespace CampusOS\Core\CPT;

if ( ! defined( 'ABSPATH' ) ) exit;

class CPT_Video extends CPT_Base {
    protected function get_slug(): string {
        return 'video';
    }

    protected function get_labels(): array {
        return [
            'name'               => __( 'Video', 'campusos-academic' ),
            'singular_name'      => __( 'Video', 'campusos-academic' ),
            'add_new_item'       => __( 'Tambah Video', 'campusos-academic' ),
            'edit_item'          => __( 'Edit Video', 'campusos-academic' ),
            'all_items'          => __( 'Semua Video', 'campusos-academic' ),
            'search_items'       => __( 'Cari Video', 'campusos-academic' ),
            'not_found'          => __( 'Tidak ditemukan', 'campusos-academic' ),
        ];
    }

    protected function get_args(): array {
        return [
            'labels'       => $this->get_labels(),
            'public'       => true,
            'has_archive'  => true,
            'show_in_rest' => true,
            'supports'     => [ 'title', 'thumbnail' ],
            'menu_icon'    => 'dashicons-format-video',
            'rewrite'      => [ 'slug' => 'video' ],
        ];
    }

    protected function get_meta_fields(): array {
        return [
            [ 'id' => 'video_youtube_url', 'label' => 'URL YouTube', 'type' => 'url' ],
            [ 'id' => 'video_video_duration', 'label' => 'Durasi Video', 'type' => 'text' ],
            [ 'id' => 'video_deskripsi_video', 'label' => 'Deskripsi', 'type' => 'textarea' ],
        ];
    }
}
