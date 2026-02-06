<?php
namespace CampusOS\Core\CPT;

if ( ! defined( 'ABSPATH' ) ) exit;

class CPT_Mata_Kuliah extends CPT_Base {
    protected function get_slug(): string {
        return 'mata_kuliah';
    }

    protected function get_labels(): array {
        return [
            'name'               => __( 'Mata Kuliah', 'campusos-academic' ),
            'singular_name'      => __( 'Mata Kuliah', 'campusos-academic' ),
            'add_new_item'       => __( 'Tambah Mata Kuliah', 'campusos-academic' ),
            'edit_item'          => __( 'Edit Mata Kuliah', 'campusos-academic' ),
            'all_items'          => __( 'Semua Mata Kuliah', 'campusos-academic' ),
            'search_items'       => __( 'Cari Mata Kuliah', 'campusos-academic' ),
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
            'menu_icon'    => 'dashicons-book',
            'rewrite'      => [ 'slug' => 'mata-kuliah' ],
        ];
    }

    protected function get_meta_fields(): array {
        return [
            [ 'id' => 'mata_kuliah_kode_mk', 'label' => 'Kode MK', 'type' => 'text' ],
            [ 'id' => 'mata_kuliah_sks', 'label' => 'SKS', 'type' => 'number' ],
            [ 'id' => 'mata_kuliah_semester', 'label' => 'Semester', 'type' => 'number' ],
            [ 'id' => 'mata_kuliah_konsentrasi', 'label' => 'Konsentrasi', 'type' => 'text' ],
            [ 'id' => 'mata_kuliah_link_rps', 'label' => 'Link RPS', 'type' => 'url' ],
            [ 'id' => 'mata_kuliah_link_silabus', 'label' => 'Link Silabus', 'type' => 'url' ],
        ];
    }
}
