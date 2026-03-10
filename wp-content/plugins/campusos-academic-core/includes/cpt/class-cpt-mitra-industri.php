<?php
namespace CampusOS\Core\CPT;

if ( ! defined( 'ABSPATH' ) ) exit;

class CPT_Mitra_Industri extends CPT_Base {
    protected function get_slug(): string {
        return 'mitra_industri';
    }

    protected function get_labels(): array {
        return [
            'name'               => __( 'Mitra Industri', 'campusos-academic' ),
            'singular_name'      => __( 'Mitra Industri', 'campusos-academic' ),
            'add_new_item'       => __( 'Tambah Mitra Industri', 'campusos-academic' ),
            'edit_item'          => __( 'Edit Mitra Industri', 'campusos-academic' ),
            'all_items'          => __( 'Semua Mitra Industri', 'campusos-academic' ),
            'search_items'       => __( 'Cari Mitra Industri', 'campusos-academic' ),
            'not_found'          => __( 'Tidak ditemukan', 'campusos-academic' ),
        ];
    }

    protected function get_args(): array {
        return [
            'labels'       => $this->get_labels(),
            'public'       => true,
            'has_archive'  => true,
            'show_in_rest' => true,
            'supports'     => [ 'title' ],
            'menu_icon'    => 'dashicons-store',
            'rewrite'      => [ 'slug' => 'mitra-industri' ],
        ];
    }

    protected function get_meta_fields(): array {
        return [
            [ 'id' => 'mitra_industri_logo_mitra_di', 'label' => 'Logo Mitra', 'type' => 'image' ],
            [ 'id' => 'mitra_industri_jenis_kerjasama_di', 'label' => 'Jenis Kerjasama', 'type' => 'text' ],
            [ 'id' => 'mitra_industri_deskripsi_mitra_di', 'label' => 'Deskripsi', 'type' => 'textarea' ],
        ];
    }
}
