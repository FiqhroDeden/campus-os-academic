<?php
namespace CampusOS\Core\CPT;

if ( ! defined( 'ABSPATH' ) ) exit;

class CPT_Agenda extends CPT_Base {
    protected function get_slug(): string {
        return 'agenda';
    }

    protected function get_labels(): array {
        return [
            'name'               => __( 'Agenda', 'campusos-academic' ),
            'singular_name'      => __( 'Agenda', 'campusos-academic' ),
            'add_new_item'       => __( 'Tambah Agenda', 'campusos-academic' ),
            'edit_item'          => __( 'Edit Agenda', 'campusos-academic' ),
            'all_items'          => __( 'Semua Agenda', 'campusos-academic' ),
            'search_items'       => __( 'Cari Agenda', 'campusos-academic' ),
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
            'menu_icon'    => 'dashicons-calendar-alt',
            'rewrite'      => [ 'slug' => 'agenda' ],
        ];
    }

    protected function get_meta_fields(): array {
        return [
            [ 'id' => 'agenda_tanggal_mulai_agenda', 'label' => 'Tanggal Mulai', 'type' => 'date' ],
            [ 'id' => 'agenda_tanggal_akhir_agenda', 'label' => 'Tanggal Akhir', 'type' => 'date' ],
            [ 'id' => 'agenda_lokasi_agenda', 'label' => 'Lokasi', 'type' => 'text' ],
            [ 'id' => 'agenda_poster_agenda', 'label' => 'Poster Agenda', 'type' => 'image' ],
            [ 'id' => 'agenda_deskripsi_agenda', 'label' => 'Deskripsi', 'type' => 'textarea' ],
        ];
    }
}
