<?php
namespace UNPATTI\Core\Admin\MetaBoxes;

if ( ! defined( 'ABSPATH' ) ) exit;

class MB_Struktur_Org extends MB_Base {
    protected function get_id(): string { return 'mb_struktur_org'; }
    protected function get_title(): string { return 'Struktur Organisasi'; }
    protected function get_template(): string { return 'templates/template-struktur-organisasi.php'; }

    protected function get_fields(): array {
        return [
            [
                'id'    => 'bagan_organisasi',
                'type'  => 'image',
                'label' => 'Bagan Organisasi',
            ],
            [
                'id'    => 'deskripsi_struktur',
                'type'  => 'textarea',
                'label' => 'Deskripsi',
            ],
        ];
    }
}
