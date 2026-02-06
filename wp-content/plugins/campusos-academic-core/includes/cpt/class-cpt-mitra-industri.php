<?php
namespace UNPATTI\Core\CPT;

if ( ! defined( 'ABSPATH' ) ) exit;

class CPT_Mitra_Industri extends CPT_Base {
    protected function get_slug(): string {
        return 'mitra_industri';
    }

    protected function get_labels(): array {
        return [
            'name'               => __( 'Mitra Industri', 'unpatti-academic' ),
            'singular_name'      => __( 'Mitra Industri', 'unpatti-academic' ),
            'add_new_item'       => __( 'Tambah Mitra Industri', 'unpatti-academic' ),
            'edit_item'          => __( 'Edit Mitra Industri', 'unpatti-academic' ),
            'all_items'          => __( 'Semua Mitra Industri', 'unpatti-academic' ),
            'search_items'       => __( 'Cari Mitra Industri', 'unpatti-academic' ),
            'not_found'          => __( 'Tidak ditemukan', 'unpatti-academic' ),
        ];
    }

    protected function get_args(): array {
        return [
            'labels'       => $this->get_labels(),
            'public'       => true,
            'has_archive'  => true,
            'show_in_rest' => true,
            'supports'     => [ 'title', 'editor' ],
            'menu_icon'    => 'dashicons-store',
            'rewrite'      => [ 'slug' => 'mitra-industri' ],
        ];
    }

    protected function get_meta_fields(): array {
        return [
            [ 'id' => 'mitra_industri_logo_mitra_di', 'label' => 'Logo Mitra', 'type' => 'image' ],
            [ 'id' => 'mitra_industri_jenis_kerjasama_di', 'label' => 'Jenis Kerjasama', 'type' => 'text' ],
        ];
    }
}
