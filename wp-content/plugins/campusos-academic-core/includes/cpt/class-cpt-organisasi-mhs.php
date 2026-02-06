<?php
namespace UNPATTI\Core\CPT;

if ( ! defined( 'ABSPATH' ) ) exit;

class CPT_Organisasi_Mhs extends CPT_Base {
    protected function get_slug(): string {
        return 'organisasi_mhs';
    }

    protected function get_labels(): array {
        return [
            'name'               => __( 'Organisasi Mahasiswa', 'unpatti-academic' ),
            'singular_name'      => __( 'Organisasi Mahasiswa', 'unpatti-academic' ),
            'add_new_item'       => __( 'Tambah Organisasi Mahasiswa', 'unpatti-academic' ),
            'edit_item'          => __( 'Edit Organisasi Mahasiswa', 'unpatti-academic' ),
            'all_items'          => __( 'Semua Organisasi Mahasiswa', 'unpatti-academic' ),
            'search_items'       => __( 'Cari Organisasi Mahasiswa', 'unpatti-academic' ),
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
        ];
    }
}
