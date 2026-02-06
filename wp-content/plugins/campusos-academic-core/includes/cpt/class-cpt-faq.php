<?php
namespace UNPATTI\Core\CPT;

if ( ! defined( 'ABSPATH' ) ) exit;

class CPT_Faq extends CPT_Base {
    protected function get_slug(): string {
        return 'faq';
    }

    protected function get_labels(): array {
        return [
            'name'               => __( 'FAQ', 'unpatti-academic' ),
            'singular_name'      => __( 'FAQ', 'unpatti-academic' ),
            'add_new_item'       => __( 'Tambah FAQ', 'unpatti-academic' ),
            'edit_item'          => __( 'Edit FAQ', 'unpatti-academic' ),
            'all_items'          => __( 'Semua FAQ', 'unpatti-academic' ),
            'search_items'       => __( 'Cari FAQ', 'unpatti-academic' ),
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
