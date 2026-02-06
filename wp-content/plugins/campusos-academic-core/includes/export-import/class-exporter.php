<?php
namespace CampusOS\Core\ExportImport;

if ( ! defined( 'ABSPATH' ) ) exit;

class Exporter {

    private $cpt_slugs = [
        'pimpinan',
        'tenaga_pendidik',
        'kerjasama',
        'fasilitas',
        'prestasi',
        'dokumen',
        'agenda',
        'faq',
        'mata_kuliah',
        'organisasi_mhs',
        'mitra_industri',
        'publikasi',
        'beasiswa',
        'galeri',
    ];

    public function init() {
        add_action( 'admin_post_campusos_export', [ $this, 'handle_export' ] );
    }

    public function handle_export() {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( __( 'Unauthorized', 'campusos-academic' ) );
        }

        check_admin_referer( 'campusos_export' );

        $json = $this->export();
        $filename = 'campusos-export-' . date( 'Y-m-d-His' ) . '.json';

        header( 'Content-Type: application/json; charset=utf-8' );
        header( 'Content-Disposition: attachment; filename=' . $filename );
        header( 'Pragma: no-cache' );
        header( 'Expires: 0' );

        echo $json;
        exit;
    }

    public function export() {
        $data = [
            'version'    => '1.0',
            'generated'  => current_time( 'mysql' ),
            'site_url'   => home_url(),
            'theme_mods' => $this->export_theme_mods(),
            'options'    => $this->export_options(),
            'cpt_data'   => $this->export_cpt_data(),
            'pages'      => $this->export_pages(),
            'menus'      => $this->export_menus(),
            'widgets'    => $this->export_widgets(),
        ];

        return wp_json_encode( $data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE );
    }

    private function export_theme_mods() {
        $mods = get_theme_mods();
        if ( is_array( $mods ) ) {
            unset( $mods[0] ); // remove empty key if present
        }
        return $mods ?: [];
    }

    private function export_options() {
        $settings = get_option( 'campusos_settings', [] );
        // Remove sensitive data
        unset( $settings['sso_client_secret'] );
        return $settings;
    }

    private function export_cpt_data() {
        $cpt_data = [];

        foreach ( $this->cpt_slugs as $slug ) {
            $posts = get_posts( [
                'post_type'      => $slug,
                'posts_per_page' => -1,
                'post_status'    => 'any',
            ] );

            $cpt_data[ $slug ] = [];
            foreach ( $posts as $post ) {
                $meta = get_post_meta( $post->ID );
                // Flatten single-value meta
                $clean_meta = [];
                foreach ( $meta as $key => $values ) {
                    if ( strpos( $key, '_edit_' ) === 0 ) continue;
                    $clean_meta[ $key ] = count( $values ) === 1 ? $values[0] : $values;
                }

                $cpt_data[ $slug ][] = [
                    'title'     => $post->post_title,
                    'content'   => $post->post_content,
                    'excerpt'   => $post->post_excerpt,
                    'status'    => $post->post_status,
                    'slug'      => $post->post_name,
                    'menu_order'=> $post->menu_order,
                    'meta'      => $clean_meta,
                ];
            }
        }

        return $cpt_data;
    }

    private function export_pages() {
        $pages = get_posts( [
            'post_type'      => 'page',
            'posts_per_page' => -1,
            'post_status'    => 'any',
        ] );

        $page_data = [];
        foreach ( $pages as $page ) {
            $meta = get_post_meta( $page->ID );
            $clean_meta = [];
            foreach ( $meta as $key => $values ) {
                if ( strpos( $key, '_edit_' ) === 0 ) continue;
                $clean_meta[ $key ] = count( $values ) === 1 ? $values[0] : $values;
            }

            $page_data[] = [
                'title'    => $page->post_title,
                'content'  => $page->post_content,
                'excerpt'  => $page->post_excerpt,
                'status'   => $page->post_status,
                'slug'     => $page->post_name,
                'template' => get_page_template_slug( $page->ID ),
                'meta'     => $clean_meta,
            ];
        }

        return $page_data;
    }

    private function export_menus() {
        $menus = wp_get_nav_menus();
        $menu_data = [];

        foreach ( $menus as $menu ) {
            $items = wp_get_nav_menu_items( $menu->term_id, [ 'update_post_term_cache' => false ] );
            $menu_items = [];

            if ( $items ) {
                foreach ( $items as $item ) {
                    $menu_items[] = [
                        'title'       => $item->title,
                        'url'         => $item->url,
                        'type'        => $item->type,
                        'object'      => $item->object,
                        'object_slug' => $item->type === 'post_type' ? get_post_field( 'post_name', $item->object_id ) : '',
                        'parent_title'=> $item->menu_item_parent ? get_the_title( $item->menu_item_parent ) : '',
                        'menu_order'  => $item->menu_order,
                        'classes'     => $item->classes,
                        'target'      => $item->target,
                    ];
                }
            }

            $menu_data[] = [
                'name'  => $menu->name,
                'slug'  => $menu->slug,
                'items' => $menu_items,
            ];
        }

        // Include menu locations
        $menu_data_with_locations = [
            'menus'     => $menu_data,
            'locations' => get_theme_mod( 'nav_menu_locations', [] ),
        ];

        return $menu_data_with_locations;
    }

    private function export_widgets() {
        $sidebars = get_option( 'sidebars_widgets', [] );
        $widget_data = [ 'sidebars' => $sidebars, 'widgets' => [] ];

        // Get unique widget types
        $widget_types = [];
        foreach ( $sidebars as $sidebar_id => $widgets ) {
            if ( ! is_array( $widgets ) || $sidebar_id === 'wp_inactive_widgets' ) continue;
            foreach ( $widgets as $widget_id ) {
                $type = preg_replace( '/-\d+$/', '', $widget_id );
                $widget_types[ $type ] = true;
            }
        }

        foreach ( array_keys( $widget_types ) as $type ) {
            $option = get_option( 'widget_' . $type, [] );
            if ( ! empty( $option ) ) {
                $widget_data['widgets'][ $type ] = $option;
            }
        }

        return $widget_data;
    }
}
