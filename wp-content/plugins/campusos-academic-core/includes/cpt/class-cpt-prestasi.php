<?php
namespace CampusOS\Core\CPT;

if ( ! defined( 'ABSPATH' ) ) exit;

class CPT_Prestasi extends CPT_Base {
    protected function get_slug(): string {
        return 'prestasi';
    }

    protected function get_labels(): array {
        return [
            'name'               => __( 'Prestasi', 'campusos-academic' ),
            'singular_name'      => __( 'Prestasi', 'campusos-academic' ),
            'add_new_item'       => __( 'Tambah Prestasi', 'campusos-academic' ),
            'edit_item'          => __( 'Edit Prestasi', 'campusos-academic' ),
            'all_items'          => __( 'Semua Prestasi', 'campusos-academic' ),
            'search_items'       => __( 'Cari Prestasi', 'campusos-academic' ),
            'not_found'          => __( 'Tidak ditemukan', 'campusos-academic' ),
        ];
    }

    protected function get_args(): array {
        return [
            'labels'       => $this->get_labels(),
            'public'       => true,
            'has_archive'  => true,
            'show_in_rest' => true,
            'supports'     => [ 'title', 'editor', 'thumbnail' ],
            'menu_icon'    => 'dashicons-awards',
            'rewrite'      => [ 'slug' => 'prestasi' ],
        ];
    }

    protected function get_meta_fields(): array {
        return [
            [ 'id' => 'prestasi_tanggal_prestasi', 'label' => 'Tanggal Prestasi', 'type' => 'date' ],
            [ 'id' => 'prestasi_kategori_prestasi', 'label' => 'Kategori Prestasi', 'type' => 'select', 'options' => [ 'mahasiswa' => 'Mahasiswa', 'dosen' => 'Dosen' ] ],
            [ 'id' => 'prestasi_tingkat_prestasi', 'label' => 'Tingkat Prestasi', 'type' => 'select', 'options' => [ 'lokal' => 'Lokal', 'nasional' => 'Nasional', 'internasional' => 'Internasional' ] ],
            [ 'id' => 'prestasi_nama_peraih', 'label' => 'Nama Peraih', 'type' => 'text' ],
        ];
    }
}
