<?php
namespace CampusOS\Core\CPT;

if ( ! defined( 'ABSPATH' ) ) exit;

class CPT_Pimpinan extends CPT_Base {
    protected function get_slug(): string {
        return 'pimpinan';
    }

    protected function get_labels(): array {
        return [
            'name'               => __( 'Pimpinan', 'campusos-academic' ),
            'singular_name'      => __( 'Pimpinan', 'campusos-academic' ),
            'add_new_item'       => __( 'Tambah Pimpinan', 'campusos-academic' ),
            'edit_item'          => __( 'Edit Pimpinan', 'campusos-academic' ),
            'all_items'          => __( 'Semua Pimpinan', 'campusos-academic' ),
            'search_items'       => __( 'Cari Pimpinan', 'campusos-academic' ),
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
            'menu_icon'    => 'dashicons-businessman',
            'rewrite'      => [ 'slug' => 'pimpinan' ],
        ];
    }

    protected function get_meta_fields(): array {
        return [
            [ 'id' => 'pimpinan_nip', 'label' => 'NIP', 'type' => 'text' ],
            [ 'id' => 'pimpinan_jabatan', 'label' => 'Jabatan', 'type' => 'text' ],
            [ 'id' => 'pimpinan_email', 'label' => 'Email', 'type' => 'email' ],
            [ 'id' => 'pimpinan_periode', 'label' => 'Periode', 'type' => 'text', 'desc' => 'Contoh: 2022-2026' ],
            [ 'id' => 'pimpinan_bio', 'label' => 'Bio Singkat', 'type' => 'textarea' ],
            [ 'id' => 'pimpinan_urutan', 'label' => 'Urutan Tampil', 'type' => 'number', 'desc' => 'Angka kecil tampil duluan' ],
        ];
    }
}
