<?php
namespace CampusOS\Core\CPT;

if ( ! defined( 'ABSPATH' ) ) exit;

class CPT_Galeri extends CPT_Base {
    protected function get_slug(): string {
        return 'galeri';
    }

    protected function get_labels(): array {
        return [
            'name'               => __( 'Galeri', 'campusos-academic' ),
            'singular_name'      => __( 'Galeri', 'campusos-academic' ),
            'add_new_item'       => __( 'Tambah Galeri', 'campusos-academic' ),
            'edit_item'          => __( 'Edit Galeri', 'campusos-academic' ),
            'all_items'          => __( 'Semua Galeri', 'campusos-academic' ),
            'search_items'       => __( 'Cari Galeri', 'campusos-academic' ),
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
            'menu_icon'    => 'dashicons-format-gallery',
            'rewrite'      => [ 'slug' => 'galeri' ],
        ];
    }

    protected function get_meta_fields(): array {
        return [
            [ 'id' => 'galeri_kategori_galeri', 'label' => 'Kategori Galeri', 'type' => 'text' ],
            [ 'id' => 'galeri_tanggal_galeri', 'label' => 'Tanggal', 'type' => 'date' ],
        ];
    }
}
