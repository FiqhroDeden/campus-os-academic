<?php
namespace UNPATTI\Core\Admin\MetaBoxes;

if ( ! defined( 'ABSPATH' ) ) exit;

class MB_Akreditasi extends MB_Base {
    protected function get_id(): string { return 'mb_akreditasi'; }
    protected function get_title(): string { return 'Akreditasi'; }
    protected function get_template(): string { return 'templates/template-akreditasi.php'; }

    protected function get_fields(): array {
        return [
            [
                'id'    => 'akreditasi_status',
                'type'  => 'text',
                'label' => 'Status Akreditasi',
                'desc'  => 'Contoh: Akreditasi B',
            ],
            [
                'id'    => 'akreditasi_no_sk',
                'type'  => 'text',
                'label' => 'Nomor SK',
            ],
            [
                'id'    => 'akreditasi_tanggal',
                'type'  => 'date',
                'label' => 'Tanggal SK',
            ],
            [
                'id'    => 'akreditasi_sertifikat',
                'type'  => 'file',
                'label' => 'Upload Sertifikat PDF',
            ],
            [
                'id'    => 'akreditasi_deskripsi',
                'type'  => 'richtext',
                'label' => 'Deskripsi / Instrumen',
            ],
        ];
    }
}
