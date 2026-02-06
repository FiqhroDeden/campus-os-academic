<?php
namespace CampusOS\Core\Admin\MetaBoxes;

if ( ! defined( 'ABSPATH' ) ) exit;

class MB_CPL extends MB_Base {
    protected function get_id(): string { return 'mb_cpl'; }
    protected function get_title(): string { return 'Capaian Pembelajaran Lulusan'; }
    protected function get_template(): string { return 'templates/template-cpl.php'; }

    protected function get_fields(): array {
        return [
            [
                'id'         => 'cpl_sikap',
                'type'       => 'repeater',
                'label'      => 'Sikap',
                'sub_fields' => [
                    [ 'id' => 'item', 'type' => 'text', 'label' => 'Sikap' ],
                ],
            ],
            [
                'id'         => 'cpl_pengetahuan',
                'type'       => 'repeater',
                'label'      => 'Pengetahuan',
                'sub_fields' => [
                    [ 'id' => 'item', 'type' => 'text', 'label' => 'Pengetahuan' ],
                ],
            ],
            [
                'id'         => 'cpl_keterampilan_umum',
                'type'       => 'repeater',
                'label'      => 'Keterampilan Umum',
                'sub_fields' => [
                    [ 'id' => 'item', 'type' => 'text', 'label' => 'Keterampilan Umum' ],
                ],
            ],
            [
                'id'         => 'cpl_keterampilan_khusus',
                'type'       => 'repeater',
                'label'      => 'Keterampilan Khusus',
                'sub_fields' => [
                    [ 'id' => 'item', 'type' => 'text', 'label' => 'Keterampilan Khusus' ],
                ],
            ],
        ];
    }
}
