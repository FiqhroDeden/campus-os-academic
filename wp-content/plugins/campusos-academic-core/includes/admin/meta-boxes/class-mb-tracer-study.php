<?php
namespace UNPATTI\Core\Admin\MetaBoxes;

if ( ! defined( 'ABSPATH' ) ) exit;

class MB_Tracer_Study extends MB_Base {
    protected function get_id(): string { return 'mb_tracer_study'; }
    protected function get_title(): string { return 'Tracer Study'; }
    protected function get_template(): string { return 'templates/template-tracer-study.php'; }

    protected function get_fields(): array {
        return [
            [
                'id'         => 'link_survey',
                'type'       => 'repeater',
                'label'      => 'Link Survey',
                'sub_fields' => [
                    [ 'id' => 'nama', 'type' => 'text', 'label' => 'Nama Survey' ],
                    [ 'id' => 'url', 'type' => 'text', 'label' => 'URL Survey' ],
                ],
            ],
            [
                'id'    => 'dokumen_tracer',
                'type'  => 'file',
                'label' => 'Dokumen Hasil Tracer Study',
            ],
            [
                'id'    => 'statistik_alumni',
                'type'  => 'textarea',
                'label' => 'Statistik Alumni',
            ],
        ];
    }
}
