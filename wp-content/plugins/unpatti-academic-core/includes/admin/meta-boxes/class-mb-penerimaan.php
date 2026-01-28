<?php
namespace UNPATTI\Core\Admin\MetaBoxes;

if ( ! defined( 'ABSPATH' ) ) exit;

class MB_Penerimaan extends MB_Base {
    protected function get_id(): string { return 'mb_penerimaan'; }
    protected function get_title(): string { return 'Penerimaan Mahasiswa Baru'; }
    protected function get_template(): string { return 'templates/template-penerimaan.php'; }

    protected function get_fields(): array {
        return [
            [
                'id'         => 'jalur_penerimaan',
                'type'       => 'repeater',
                'label'      => 'Jalur Penerimaan',
                'sub_fields' => [
                    [ 'id' => 'nama', 'type' => 'text', 'label' => 'Nama Jalur' ],
                    [ 'id' => 'persyaratan', 'type' => 'textarea', 'label' => 'Persyaratan' ],
                    [ 'id' => 'link', 'type' => 'text', 'label' => 'Link Pendaftaran' ],
                ],
            ],
            [
                'id'    => 'biaya_pendaftaran',
                'type'  => 'text',
                'label' => 'Biaya Pendaftaran',
            ],
        ];
    }
}
