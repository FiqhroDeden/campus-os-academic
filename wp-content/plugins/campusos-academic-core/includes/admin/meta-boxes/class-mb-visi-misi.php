<?php
namespace CampusOS\Core\Admin\MetaBoxes;

if ( ! defined( 'ABSPATH' ) ) exit;

class MB_Visi_Misi extends MB_Base {
    protected function get_id(): string { return 'mb_visi_misi'; }
    protected function get_title(): string { return 'Visi Misi Tujuan Sasaran'; }
    protected function get_template(): string { return 'templates/template-visi-misi.php'; }

    protected function get_fields(): array {
        return [
            [
                'id'    => 'visi',
                'type'  => 'textarea',
                'label' => 'Visi',
            ],
            [
                'id'         => 'misi',
                'type'       => 'repeater',
                'label'      => 'Misi',
                'sub_fields' => [
                    [ 'id' => 'item', 'type' => 'text', 'label' => 'Misi' ],
                ],
            ],
            [
                'id'         => 'tujuan',
                'type'       => 'repeater',
                'label'      => 'Tujuan',
                'sub_fields' => [
                    [ 'id' => 'item', 'type' => 'text', 'label' => 'Tujuan' ],
                ],
            ],
            [
                'id'         => 'sasaran',
                'type'       => 'repeater',
                'label'      => 'Sasaran',
                'sub_fields' => [
                    [ 'id' => 'item', 'type' => 'text', 'label' => 'Sasaran' ],
                ],
            ],
        ];
    }
}
