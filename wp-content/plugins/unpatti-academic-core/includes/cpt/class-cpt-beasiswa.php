<?php
namespace UNPATTI\Core\CPT;

if ( ! defined( 'ABSPATH' ) ) exit;

class CPT_Beasiswa extends CPT_Base {
    protected function get_slug(): string {
        return 'beasiswa';
    }

    protected function get_labels(): array {
        return [
            'name'               => __( 'Beasiswa', 'unpatti-academic' ),
            'singular_name'      => __( 'Beasiswa', 'unpatti-academic' ),
            'add_new_item'       => __( 'Tambah Beasiswa', 'unpatti-academic' ),
            'edit_item'          => __( 'Edit Beasiswa', 'unpatti-academic' ),
            'all_items'          => __( 'Semua Beasiswa', 'unpatti-academic' ),
            'search_items'       => __( 'Cari Beasiswa', 'unpatti-academic' ),
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
