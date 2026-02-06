<?php
namespace UNPATTI\Core\Admin\MetaBoxes;

if ( ! defined( 'ABSPATH' ) ) exit;

class MB_Sambutan extends MB_Base {
    protected function get_id(): string { return 'mb_sambutan'; }
    protected function get_title(): string { return 'Sambutan Pimpinan'; }
    protected function get_template(): string { return 'templates/template-sambutan.php'; }

    protected function get_fields(): array {
        return [
            [
                'id'    => 'sambutan_foto',
                'type'  => 'image',
                'label' => 'Foto Pimpinan',
            ],
            [
                'id'    => 'sambutan_nama',
                'type'  => 'text',
                'label' => 'Nama',
            ],
            [
                'id'    => 'sambutan_jabatan',
                'type'  => 'text',
                'label' => 'Jabatan',
            ],
            [
                'id'    => 'sambutan_isi',
                'type'  => 'richtext',
                'label' => 'Isi Sambutan',
            ],
        ];
    }
}
