<?php
namespace CampusOS\Core\CPT;

if ( ! defined( 'ABSPATH' ) ) exit;

class CPT_Fasilitas extends CPT_Base {
    protected function get_slug(): string {
        return 'fasilitas';
    }

    protected function get_labels(): array {
        return [
            'name'               => __( 'Fasilitas', 'campusos-academic' ),
            'singular_name'      => __( 'Fasilitas', 'campusos-academic' ),
            'add_new_item'       => __( 'Tambah Fasilitas', 'campusos-academic' ),
            'edit_item'          => __( 'Edit Fasilitas', 'campusos-academic' ),
            'all_items'          => __( 'Semua Fasilitas', 'campusos-academic' ),
            'search_items'       => __( 'Cari Fasilitas', 'campusos-academic' ),
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
            'menu_icon'    => 'dashicons-building',
            'rewrite'      => [ 'slug' => 'fasilitas' ],
        ];
    }

    protected function get_meta_fields(): array {
        return [
            [ 'id' => 'fasilitas_kapasitas', 'label' => 'Kapasitas', 'type' => 'text' ],
            [ 'id' => 'fasilitas_lokasi', 'label' => 'Lokasi', 'type' => 'text' ],
            [ 'id' => 'fasilitas_deskripsi_fasilitas', 'label' => 'Deskripsi', 'type' => 'textarea' ],
        ];
    }
}
