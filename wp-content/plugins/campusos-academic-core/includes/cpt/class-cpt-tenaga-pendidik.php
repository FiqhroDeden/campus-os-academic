<?php
namespace UNPATTI\Core\CPT;

if ( ! defined( 'ABSPATH' ) ) exit;

class CPT_Tenaga_Pendidik extends CPT_Base {
    protected function get_slug(): string {
        return 'tenaga_pendidik';
    }

    protected function get_labels(): array {
        return [
            'name'               => __( 'Tenaga Pendidik', 'unpatti-academic' ),
            'singular_name'      => __( 'Tenaga Pendidik', 'unpatti-academic' ),
            'add_new_item'       => __( 'Tambah Tenaga Pendidik', 'unpatti-academic' ),
            'edit_item'          => __( 'Edit Tenaga Pendidik', 'unpatti-academic' ),
            'all_items'          => __( 'Semua Tenaga Pendidik', 'unpatti-academic' ),
            'search_items'       => __( 'Cari Tenaga Pendidik', 'unpatti-academic' ),
            'not_found'          => __( 'Tidak ditemukan', 'unpatti-academic' ),
        ];
    }

    protected function get_args(): array {
        return [
            'labels'       => $this->get_labels(),
            'public'       => true,
            'has_archive'  => true,
            'show_in_rest' => true,
            'supports'     => [ 'title', 'thumbnail' ],
            'menu_icon'    => 'dashicons-welcome-learn-more',
            'rewrite'      => [ 'slug' => 'tenaga-pendidik' ],
        ];
    }

    protected function get_meta_fields(): array {
        return [
            [ 'id' => 'tenaga_pendidik_nidn', 'label' => 'NIDN', 'type' => 'text' ],
            [ 'id' => 'tenaga_pendidik_jabatan_fungsional', 'label' => 'Jabatan Fungsional', 'type' => 'select', 'options' => [ 'guru_besar' => 'Guru Besar', 'lektor_kepala' => 'Lektor Kepala', 'lektor' => 'Lektor', 'asisten_ahli' => 'Asisten Ahli' ] ],
            [ 'id' => 'tenaga_pendidik_sertifikasi', 'label' => 'Sertifikasi', 'type' => 'text' ],
            [ 'id' => 'tenaga_pendidik_bidang_keahlian', 'label' => 'Bidang Keahlian', 'type' => 'text' ],
            [ 'id' => 'tenaga_pendidik_pendidikan', 'label' => 'Pendidikan', 'type' => 'text', 'desc' => 'Pendidikan terakhir' ],
            [ 'id' => 'tenaga_pendidik_email_dosen', 'label' => 'Email Dosen', 'type' => 'email' ],
            [ 'id' => 'tenaga_pendidik_link_profil', 'label' => 'Link Profil', 'type' => 'url' ],
        ];
    }
}
