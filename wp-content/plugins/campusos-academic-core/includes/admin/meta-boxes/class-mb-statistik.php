<?php
namespace CampusOS\Core\Admin\MetaBoxes;

if ( ! defined( 'ABSPATH' ) ) exit;

class MB_Statistik extends MB_Base {
    protected function get_id(): string { return 'mb_statistik'; }
    protected function get_title(): string { return 'Statistik'; }
    protected function get_template(): string { return 'templates/template-statistik.php'; }

    protected function get_fields(): array {
        return [
            [
                'id'    => 'stat_mahasiswa_aktif',
                'type'  => 'number',
                'label' => 'Mahasiswa Aktif',
            ],
            [
                'id'    => 'stat_lulusan',
                'type'  => 'number',
                'label' => 'Total Lulusan',
            ],
            [
                'id'    => 'stat_dosen',
                'type'  => 'number',
                'label' => 'Jumlah Dosen',
            ],
            [
                'id'    => 'stat_beasiswa',
                'type'  => 'number',
                'label' => 'Penerima Beasiswa',
            ],
            [
                'id'         => 'stat_tambahan',
                'type'       => 'repeater',
                'label'      => 'Statistik Tambahan',
                'sub_fields' => [
                    [ 'id' => 'label', 'type' => 'text', 'label' => 'Label' ],
                    [ 'id' => 'angka', 'type' => 'text', 'label' => 'Angka' ],
                ],
            ],
        ];
    }
}
