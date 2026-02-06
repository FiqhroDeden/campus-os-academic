<?php
namespace CampusOS\Core\Admin\MetaBoxes;

if ( ! defined( 'ABSPATH' ) ) exit;

class MB_Biaya_UKT extends MB_Base {
    protected function get_id(): string { return 'mb_biaya_ukt'; }
    protected function get_title(): string { return 'Biaya Pendidikan / UKT'; }
    protected function get_template(): string { return 'templates/template-biaya-ukt.php'; }

    protected function get_fields(): array {
        return [
            [
                'id'    => 'deskripsi_ukt',
                'type'  => 'textarea',
                'label' => 'Deskripsi',
            ],
            [
                'id'         => 'tabel_ukt',
                'type'       => 'repeater',
                'label'      => 'Tabel UKT',
                'sub_fields' => [
                    [ 'id' => 'kategori', 'type' => 'text', 'label' => 'Kategori/Kelompok' ],
                    [ 'id' => 'nominal', 'type' => 'text', 'label' => 'Nominal' ],
                ],
            ],
        ];
    }
}
