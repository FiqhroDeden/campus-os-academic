<?php
namespace UNPATTI\Core\CPT;

if ( ! defined( 'ABSPATH' ) ) exit;

class CPT_Galeri extends CPT_Base {
    protected function get_slug(): string {
        return 'galeri';
    }

    protected function get_labels(): array {
        return [
            'name'               => __( 'Galeri', 'unpatti-academic' ),
            'singular_name'      => __( 'Galeri', 'unpatti-academic' ),
            'add_new_item'       => __( 'Tambah Galeri', 'unpatti-academic' ),
            'edit_item'          => __( 'Edit Galeri', 'unpatti-academic' ),
            'all_items'          => __( 'Semua Galeri', 'unpatti-academic' ),
            'search_items'       => __( 'Cari Galeri', 'unpatti-academic' ),
            'not_found'          => __( 'Tidak ditemukan', 'unpatti-academic' ),
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
