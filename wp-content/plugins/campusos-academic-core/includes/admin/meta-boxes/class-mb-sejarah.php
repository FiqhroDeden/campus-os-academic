<?php
namespace CampusOS\Core\Admin\MetaBoxes;

if ( ! defined( 'ABSPATH' ) ) exit;

class MB_Sejarah extends MB_Base {
    protected function get_id(): string { return 'mb_sejarah'; }
    protected function get_title(): string { return 'Konten Sejarah'; }
    protected function get_template(): string { return 'templates/template-sejarah.php'; }

    protected function get_fields(): array {
        return [
            [
                'id'    => 'sejarah_content',
                'type'  => 'richtext',
                'label' => 'Isi Sejarah',
            ],
            [
                'id'         => 'sejarah_timeline',
                'type'       => 'repeater',
                'label'      => 'Timeline Sejarah',
                'sub_fields' => [
                    [ 'id' => 'tahun', 'type' => 'text', 'label' => 'Tahun' ],
                    [ 'id' => 'peristiwa', 'type' => 'text', 'label' => 'Peristiwa' ],
                ],
            ],
        ];
    }
}
