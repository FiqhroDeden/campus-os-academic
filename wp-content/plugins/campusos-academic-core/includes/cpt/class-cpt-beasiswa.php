<?php
namespace CampusOS\Core\CPT;

if ( ! defined( 'ABSPATH' ) ) exit;

class CPT_Beasiswa extends CPT_Base {
    protected function get_slug(): string {
        return 'beasiswa';
    }

    protected function get_labels(): array {
        return [
            'name'               => __( 'Beasiswa', 'campusos-academic' ),
            'singular_name'      => __( 'Beasiswa', 'campusos-academic' ),
            'add_new_item'       => __( 'Tambah Beasiswa', 'campusos-academic' ),
            'edit_item'          => __( 'Edit Beasiswa', 'campusos-academic' ),
            'all_items'          => __( 'Semua Beasiswa', 'campusos-academic' ),
            'search_items'       => __( 'Cari Beasiswa', 'campusos-academic' ),
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
            'menu_icon'    => 'dashicons-money-alt',
            'rewrite'      => [ 'slug' => 'beasiswa' ],
        ];
    }

    protected function get_meta_fields(): array {
        return [
            [ 'id' => 'beasiswa_persyaratan_beasiswa', 'label' => 'Persyaratan', 'type' => 'textarea' ],
            [ 'id' => 'beasiswa_deadline_beasiswa', 'label' => 'Deadline', 'type' => 'date' ],
            [ 'id' => 'beasiswa_link_pendaftaran', 'label' => 'Link Pendaftaran', 'type' => 'url' ],
        ];
    }
}
