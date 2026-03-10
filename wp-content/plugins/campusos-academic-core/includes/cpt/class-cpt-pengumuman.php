<?php
namespace CampusOS\Core\CPT;

if ( ! defined( 'ABSPATH' ) ) exit;

class CPT_Pengumuman extends CPT_Base {
    protected function get_slug(): string {
        return 'pengumuman';
    }

    protected function get_labels(): array {
        return [
            'name'               => __( 'Pengumuman', 'campusos-academic' ),
            'singular_name'      => __( 'Pengumuman', 'campusos-academic' ),
            'add_new_item'       => __( 'Tambah Pengumuman', 'campusos-academic' ),
            'edit_item'          => __( 'Edit Pengumuman', 'campusos-academic' ),
            'all_items'          => __( 'Semua Pengumuman', 'campusos-academic' ),
            'search_items'       => __( 'Cari Pengumuman', 'campusos-academic' ),
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
            'menu_icon'    => 'dashicons-megaphone',
            'rewrite'      => [ 'slug' => 'pengumuman' ],
        ];
    }

    protected function get_meta_fields(): array {
        return [
            [ 'id' => 'pengumuman_tanggal_berlaku', 'label' => 'Tanggal Berlaku', 'type' => 'date' ],
            [ 'id' => 'pengumuman_file_lampiran', 'label' => 'File Lampiran', 'type' => 'file' ],
            [ 'id' => 'pengumuman_deskripsi_pengumuman', 'label' => 'Deskripsi', 'type' => 'textarea' ],
        ];
    }
}
