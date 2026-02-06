<?php
namespace CampusOS\Core\CPT;

if ( ! defined( 'ABSPATH' ) ) exit;

class CPT_Publikasi extends CPT_Base {
    protected function get_slug(): string {
        return 'publikasi';
    }

    protected function get_labels(): array {
        return [
            'name'               => __( 'Publikasi', 'campusos-academic' ),
            'singular_name'      => __( 'Publikasi', 'campusos-academic' ),
            'add_new_item'       => __( 'Tambah Publikasi', 'campusos-academic' ),
            'edit_item'          => __( 'Edit Publikasi', 'campusos-academic' ),
            'all_items'          => __( 'Semua Publikasi', 'campusos-academic' ),
            'search_items'       => __( 'Cari Publikasi', 'campusos-academic' ),
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
            'menu_icon'    => 'dashicons-media-text',
            'rewrite'      => [ 'slug' => 'publikasi' ],
        ];
    }

    protected function get_meta_fields(): array {
        return [
            [ 'id' => 'publikasi_penulis_pub', 'label' => 'Penulis', 'type' => 'text' ],
            [ 'id' => 'publikasi_jenis_pub', 'label' => 'Jenis Publikasi', 'type' => 'select', 'options' => [ 'jurnal' => 'Jurnal', 'prosiding' => 'Prosiding', 'buku' => 'Buku', 'hki' => 'HKI' ] ],
            [ 'id' => 'publikasi_tahun_pub', 'label' => 'Tahun Publikasi', 'type' => 'text' ],
            [ 'id' => 'publikasi_link_pub', 'label' => 'Link Publikasi', 'type' => 'url' ],
            [ 'id' => 'publikasi_doi_pub', 'label' => 'DOI', 'type' => 'text' ],
            [ 'id' => 'publikasi_kategori_pub', 'label' => 'Kategori', 'type' => 'select', 'options' => [ 'dosen' => 'Dosen', 'mahasiswa' => 'Mahasiswa' ] ],
        ];
    }
}
