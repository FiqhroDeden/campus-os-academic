<?php
namespace UNPATTI\Core\CPT;

if ( ! defined( 'ABSPATH' ) ) exit;

class CPT_Fasilitas extends CPT_Base {
    protected function get_slug(): string {
        return 'fasilitas';
    }

    protected function get_labels(): array {
        return [
            'name'               => __( 'Fasilitas', 'unpatti-academic' ),
            'singular_name'      => __( 'Fasilitas', 'unpatti-academic' ),
            'add_new_item'       => __( 'Tambah Fasilitas', 'unpatti-academic' ),
            'edit_item'          => __( 'Edit Fasilitas', 'unpatti-academic' ),
            'all_items'          => __( 'Semua Fasilitas', 'unpatti-academic' ),
            'search_items'       => __( 'Cari Fasilitas', 'unpatti-academic' ),
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
            'menu_icon'    => 'dashicons-building',
            'rewrite'      => [ 'slug' => 'fasilitas' ],
        ];
    }

    protected function get_meta_fields(): array {
        return [
            [ 'id' => 'fasilitas_kapasitas', 'label' => 'Kapasitas', 'type' => 'text' ],
            [ 'id' => 'fasilitas_lokasi', 'label' => 'Lokasi', 'type' => 'text' ],
        ];
    }
}
