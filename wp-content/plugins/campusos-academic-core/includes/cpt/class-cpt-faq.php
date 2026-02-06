<?php
namespace CampusOS\Core\CPT;

if ( ! defined( 'ABSPATH' ) ) exit;

class CPT_Faq extends CPT_Base {
    protected function get_slug(): string {
        return 'faq';
    }

    protected function get_labels(): array {
        return [
            'name'               => __( 'FAQ', 'campusos-academic' ),
            'singular_name'      => __( 'FAQ', 'campusos-academic' ),
            'add_new_item'       => __( 'Tambah FAQ', 'campusos-academic' ),
            'edit_item'          => __( 'Edit FAQ', 'campusos-academic' ),
            'all_items'          => __( 'Semua FAQ', 'campusos-academic' ),
            'search_items'       => __( 'Cari FAQ', 'campusos-academic' ),
            'not_found'          => __( 'Tidak ditemukan', 'campusos-academic' ),
        ];
    }

    protected function get_args(): array {
        return [
            'labels'       => $this->get_labels(),
            'public'       => true,
            'has_archive'  => true,
            'show_in_rest' => true,
            'supports'     => [ 'title', 'editor' ],
            'menu_icon'    => 'dashicons-editor-help',
            'rewrite'      => [ 'slug' => 'faq' ],
        ];
    }

    protected function get_meta_fields(): array {
        return [
            [ 'id' => 'faq_urutan_faq', 'label' => 'Urutan Tampil', 'type' => 'number', 'desc' => 'Angka kecil tampil duluan' ],
            [ 'id' => 'faq_kategori_faq', 'label' => 'Kategori FAQ', 'type' => 'text' ],
        ];
    }
}
