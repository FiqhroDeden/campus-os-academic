<?php
namespace CampusOS\Core\CPT;

if ( ! defined( 'ABSPATH' ) ) exit;

class CPT_Kerjasama extends CPT_Base {
    protected function get_slug(): string {
        return 'kerjasama';
    }

    protected function get_labels(): array {
        return [
            'name'               => __( 'Kerjasama', 'campusos-academic' ),
            'singular_name'      => __( 'Kerjasama', 'campusos-academic' ),
            'add_new_item'       => __( 'Tambah Kerjasama', 'campusos-academic' ),
            'edit_item'          => __( 'Edit Kerjasama', 'campusos-academic' ),
            'all_items'          => __( 'Semua Kerjasama', 'campusos-academic' ),
            'search_items'       => __( 'Cari Kerjasama', 'campusos-academic' ),
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
            'menu_icon'    => 'dashicons-networking',
            'rewrite'      => [ 'slug' => 'kerjasama' ],
        ];
    }

    protected function get_meta_fields(): array {
        return [
            [ 'id' => 'kerjasama_logo_mitra', 'label' => 'Logo Mitra', 'type' => 'image' ],
            [ 'id' => 'kerjasama_jenis_kerjasama', 'label' => 'Jenis Kerjasama', 'type' => 'select', 'options' => [ 'dn' => 'Dalam Negeri', 'ln' => 'Luar Negeri' ] ],
            [ 'id' => 'kerjasama_tanggal_mulai', 'label' => 'Tanggal Mulai', 'type' => 'date' ],
            [ 'id' => 'kerjasama_tanggal_akhir', 'label' => 'Tanggal Akhir', 'type' => 'date' ],
            [ 'id' => 'kerjasama_dokumen_mou', 'label' => 'Dokumen MOU', 'type' => 'file' ],
            [ 'id' => 'kerjasama_deskripsi_kerjasama', 'label' => 'Deskripsi', 'type' => 'textarea' ],
        ];
    }
}
