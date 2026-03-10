<?php
namespace CampusOS\Core\CPT;

if ( ! defined( 'ABSPATH' ) ) exit;

class CPT_Organisasi_Mhs extends CPT_Base {
    protected function get_slug(): string {
        return 'organisasi_mhs';
    }

    protected function get_labels(): array {
        return [
            'name'               => __( 'Organisasi Mahasiswa', 'campusos-academic' ),
            'singular_name'      => __( 'Organisasi Mahasiswa', 'campusos-academic' ),
            'add_new_item'       => __( 'Tambah Organisasi Mahasiswa', 'campusos-academic' ),
            'edit_item'          => __( 'Edit Organisasi Mahasiswa', 'campusos-academic' ),
            'all_items'          => __( 'Semua Organisasi Mahasiswa', 'campusos-academic' ),
            'search_items'       => __( 'Cari Organisasi Mahasiswa', 'campusos-academic' ),
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
            'menu_icon'    => 'dashicons-groups',
            'rewrite'      => [ 'slug' => 'organisasi-mahasiswa' ],
        ];
    }

    protected function get_meta_fields(): array {
        return [
            [ 'id' => 'organisasi_mhs_logo_org', 'label' => 'Logo Organisasi', 'type' => 'image' ],
            [ 'id' => 'organisasi_mhs_struktur_org', 'label' => 'Struktur Organisasi', 'type' => 'image', 'desc' => 'Bagan struktur organisasi' ],
            [ 'id' => 'organisasi_mhs_tupoksi', 'label' => 'Tupoksi', 'type' => 'textarea' ],
            [ 'id' => 'organisasi_mhs_program_kerja', 'label' => 'Program Kerja', 'type' => 'textarea' ],
            [ 'id' => 'organisasi_mhs_deskripsi_org', 'label' => 'Deskripsi', 'type' => 'textarea' ],
        ];
    }
}
