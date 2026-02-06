<?php
namespace CampusOS\Core\CPT;

if ( ! defined( 'ABSPATH' ) ) exit;

class CPT_Dokumen extends CPT_Base {
    protected function get_slug(): string {
        return 'dokumen';
    }

    protected function get_labels(): array {
        return [
            'name'               => __( 'Dokumen', 'campusos-academic' ),
            'singular_name'      => __( 'Dokumen', 'campusos-academic' ),
            'add_new_item'       => __( 'Tambah Dokumen', 'campusos-academic' ),
            'edit_item'          => __( 'Edit Dokumen', 'campusos-academic' ),
            'all_items'          => __( 'Semua Dokumen', 'campusos-academic' ),
            'search_items'       => __( 'Cari Dokumen', 'campusos-academic' ),
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
            'menu_icon'    => 'dashicons-media-document',
            'rewrite'      => [ 'slug' => 'dokumen' ],
        ];
    }

    protected function get_meta_fields(): array {
        return [
            [ 'id' => 'dokumen_file_dokumen', 'label' => 'File Dokumen', 'type' => 'file' ],
            [ 'id' => 'dokumen_kategori_dokumen', 'label' => 'Kategori Dokumen', 'type' => 'select', 'options' => [ 'peraturan' => 'Peraturan Akademik', 'kalender' => 'Kalender Akademik', 'kurikulum' => 'Kurikulum', 'sop' => 'SOP', 'mutu' => 'Dokumen Mutu', 'akreditasi' => 'Akreditasi' ] ],
            [ 'id' => 'dokumen_tahun_dokumen', 'label' => 'Tahun Dokumen', 'type' => 'text' ],
            [ 'id' => 'dokumen_sumber_dokumen', 'label' => 'Sumber Dokumen', 'type' => 'select', 'options' => [ 'universitas' => 'Universitas', 'fakultas' => 'Fakultas', 'jurusan' => 'Jurusan' ] ],
        ];
    }
}
