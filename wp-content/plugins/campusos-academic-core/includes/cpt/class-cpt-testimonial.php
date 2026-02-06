<?php
namespace UNPATTI\Core\CPT;

if ( ! defined( 'ABSPATH' ) ) exit;

class CPT_Testimonial extends CPT_Base {
    protected function get_slug(): string {
        return 'testimonial';
    }

    protected function get_labels(): array {
        return [
            'name'               => __( 'Testimonial', 'unpatti-academic' ),
            'singular_name'      => __( 'Testimonial', 'unpatti-academic' ),
            'add_new_item'       => __( 'Tambah Testimonial', 'unpatti-academic' ),
            'edit_item'          => __( 'Edit Testimonial', 'unpatti-academic' ),
            'all_items'          => __( 'Semua Testimonial', 'unpatti-academic' ),
            'search_items'       => __( 'Cari Testimonial', 'unpatti-academic' ),
            'not_found'          => __( 'Tidak ditemukan', 'unpatti-academic' ),
        ];
    }

    protected function get_args(): array {
        return [
            'labels'       => $this->get_labels(),
            'public'       => true,
            'has_archive'  => false,
            'show_in_rest' => true,
            'supports'     => [ 'title', 'thumbnail' ],
            'menu_icon'    => 'dashicons-format-quote',
            'rewrite'      => [ 'slug' => 'testimonial' ],
        ];
    }

    protected function get_meta_fields(): array {
        return [
            [ 'id' => 'testimonial_jabatan', 'label' => 'Jabatan', 'type' => 'text' ],
            [ 'id' => 'testimonial_instansi', 'label' => 'Instansi', 'type' => 'text' ],
            [ 'id' => 'testimonial_rating', 'label' => 'Rating', 'type' => 'number' ],
        ];
    }
}
