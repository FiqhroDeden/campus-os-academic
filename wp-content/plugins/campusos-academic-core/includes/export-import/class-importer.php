<?php
namespace CampusOS\Core\ExportImport;

if ( ! defined( 'ABSPATH' ) ) exit;

class Importer {

    public function init() {
        add_action( 'admin_post_campusos_import', [ $this, 'handle_import' ] );
    }

    public function handle_import() {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( __( 'Unauthorized', 'campusos-academic' ) );
        }

        check_admin_referer( 'campusos_import' );

        if ( empty( $_FILES['import_file']['tmp_name'] ) ) {
            wp_safe_redirect( admin_url( 'admin.php?page=campusos-academic&tab=export&import_error=no_file' ) );
            exit;
        }

        $file = $_FILES['import_file'];
        if ( $file['type'] !== 'application/json' && pathinfo( $file['name'], PATHINFO_EXTENSION ) !== 'json' ) {
            wp_safe_redirect( admin_url( 'admin.php?page=campusos-academic&tab=export&import_error=invalid_file' ) );
            exit;
        }

        $json_string = file_get_contents( $file['tmp_name'] );
        if ( empty( $json_string ) ) {
            wp_safe_redirect( admin_url( 'admin.php?page=campusos-academic&tab=export&import_error=empty_file' ) );
            exit;
        }

        $results = $this->import( $json_string );
        $count = 0;
        foreach ( $results as $section => $items ) {
            if ( is_array( $items ) ) {
                $count += count( $items );
            }
        }

        wp_safe_redirect( admin_url( 'admin.php?page=campusos-academic&tab=export&import_success=' . $count ) );
        exit;
    }

    public function import( $json_string ) {
        $data = json_decode( $json_string, true );
        if ( ! is_array( $data ) ) {
            return [ 'error' => 'Invalid JSON' ];
        }

        $results = [];

        if ( ! empty( $data['theme_mods'] ) ) {
            $results['theme_mods'] = $this->import_theme_mods( $data['theme_mods'] );
        }

        if ( ! empty( $data['options'] ) ) {
            $results['options'] = $this->import_options( $data['options'] );
        }

        if ( ! empty( $data['cpt_data'] ) ) {
            $results['cpt_data'] = $this->import_cpt_data( $data['cpt_data'] );
        }

        if ( ! empty( $data['pages'] ) ) {
            $results['pages'] = $this->import_pages( $data['pages'] );
        }

        if ( ! empty( $data['menus'] ) ) {
            $results['menus'] = $this->import_menus( $data['menus'] );
        }

        if ( ! empty( $data['widgets'] ) ) {
            $results['widgets'] = $this->import_widgets( $data['widgets'] );
        }

        return $results;
    }

    private function import_theme_mods( $mods ) {
        $imported = [];
        foreach ( $mods as $key => $value ) {
            set_theme_mod( $key, $value );
            $imported[] = $key;
        }
        return $imported;
    }

    private function import_options( $options ) {
        $existing = get_option( 'campusos_settings', [] );
        // Preserve existing secret
        if ( ! empty( $existing['sso_client_secret'] ) ) {
            $options['sso_client_secret'] = $existing['sso_client_secret'];
        }
        update_option( 'campusos_settings', $options );
        return [ 'campusos_settings' ];
    }

    private function import_cpt_data( $cpt_data ) {
        $imported = [];

        foreach ( $cpt_data as $post_type => $posts ) {
            foreach ( $posts as $post_data ) {
                // Skip if title already exists
                $existing = get_posts( [
                    'post_type'      => $post_type,
                    'title'          => $post_data['title'],
                    'posts_per_page' => 1,
                    'post_status'    => 'any',
                ] );

                if ( ! empty( $existing ) ) {
                    continue;
                }

                $id = wp_insert_post( [
                    'post_title'   => $post_data['title'],
                    'post_content' => $post_data['content'] ?? '',
                    'post_excerpt' => $post_data['excerpt'] ?? '',
                    'post_status'  => $post_data['status'] ?? 'publish',
                    'post_type'    => $post_type,
                    'post_name'    => $post_data['slug'] ?? '',
                    'menu_order'   => $post_data['menu_order'] ?? 0,
                ] );

                if ( ! is_wp_error( $id ) && ! empty( $post_data['meta'] ) ) {
                    foreach ( $post_data['meta'] as $key => $value ) {
                        if ( is_array( $value ) ) {
                            delete_post_meta( $id, $key );
                            foreach ( $value as $v ) {
                                add_post_meta( $id, $key, maybe_unserialize( $v ) );
                            }
                        } else {
                            update_post_meta( $id, $key, maybe_unserialize( $value ) );
                        }
                    }
                    $imported[] = $post_type . ':' . $post_data['title'];
                }
            }
        }

        return $imported;
    }

    private function import_pages( $pages ) {
        $imported = [];

        foreach ( $pages as $page_data ) {
            $existing = get_posts( [
                'post_type'      => 'page',
                'title'          => $page_data['title'],
                'posts_per_page' => 1,
                'post_status'    => 'any',
            ] );

            if ( ! empty( $existing ) ) {
                continue;
            }

            $id = wp_insert_post( [
                'post_title'   => $page_data['title'],
                'post_content' => $page_data['content'] ?? '',
                'post_excerpt' => $page_data['excerpt'] ?? '',
                'post_status'  => $page_data['status'] ?? 'publish',
                'post_type'    => 'page',
                'post_name'    => $page_data['slug'] ?? '',
            ] );

            if ( ! is_wp_error( $id ) ) {
                if ( ! empty( $page_data['template'] ) ) {
                    update_post_meta( $id, '_wp_page_template', $page_data['template'] );
                }
                if ( ! empty( $page_data['meta'] ) ) {
                    foreach ( $page_data['meta'] as $key => $value ) {
                        if ( $key === '_wp_page_template' ) continue;
                        if ( is_array( $value ) ) {
                            delete_post_meta( $id, $key );
                            foreach ( $value as $v ) {
                                add_post_meta( $id, $key, maybe_unserialize( $v ) );
                            }
                        } else {
                            update_post_meta( $id, $key, maybe_unserialize( $value ) );
                        }
                    }
                }
                $imported[] = $page_data['title'];
            }
        }

        return $imported;
    }

    private function import_menus( $menu_data ) {
        $imported = [];
        $menus_list = $menu_data['menus'] ?? $menu_data;
        $locations  = $menu_data['locations'] ?? [];

        if ( ! is_array( $menus_list ) ) {
            return $imported;
        }

        $location_map = [];

        foreach ( $menus_list as $menu ) {
            $existing = wp_get_nav_menu_object( $menu['name'] );
            if ( $existing ) {
                $menu_id = $existing->term_id;
            } else {
                $menu_id = wp_create_nav_menu( $menu['name'] );
            }

            if ( is_wp_error( $menu_id ) ) continue;

            $location_map[ $menu['slug'] ] = $menu_id;

            // Remove existing items if re-importing
            $old_items = wp_get_nav_menu_items( $menu_id );
            if ( $old_items ) {
                foreach ( $old_items as $old_item ) {
                    wp_delete_post( $old_item->ID, true );
                }
            }

            // Create items — first pass: no parents
            $title_to_id = [];
            foreach ( $menu['items'] as $item ) {
                $args = [
                    'menu-item-title'  => $item['title'],
                    'menu-item-url'    => $item['url'] ?? '',
                    'menu-item-type'   => $item['type'] ?? 'custom',
                    'menu-item-status' => 'publish',
                    'menu-item-position' => $item['menu_order'] ?? 0,
                    'menu-item-target' => $item['target'] ?? '',
                    'menu-item-classes'=> is_array( $item['classes'] ?? null ) ? implode( ' ', $item['classes'] ) : '',
                ];

                // Resolve object reference
                if ( $item['type'] === 'post_type' && ! empty( $item['object_slug'] ) ) {
                    $found = get_posts( [
                        'name'           => $item['object_slug'],
                        'post_type'      => $item['object'] ?? 'page',
                        'posts_per_page' => 1,
                        'post_status'    => 'any',
                    ] );
                    if ( ! empty( $found ) ) {
                        $args['menu-item-object']    = $item['object'];
                        $args['menu-item-object-id'] = $found[0]->ID;
                        $args['menu-item-type']      = 'post_type';
                    }
                }

                $item_id = wp_update_nav_menu_item( $menu_id, 0, $args );
                if ( ! is_wp_error( $item_id ) ) {
                    $title_to_id[ $item['title'] ] = $item_id;
                }
            }

            // Second pass: set parents
            foreach ( $menu['items'] as $item ) {
                if ( ! empty( $item['parent_title'] ) && ! empty( $title_to_id[ $item['title'] ] ) && ! empty( $title_to_id[ $item['parent_title'] ] ) ) {
                    $child_id  = $title_to_id[ $item['title'] ];
                    $parent_id = $title_to_id[ $item['parent_title'] ];
                    update_post_meta( $child_id, '_menu_item_menu_item_parent', $parent_id );
                }
            }

            $imported[] = $menu['name'];
        }

        // Set menu locations
        if ( ! empty( $locations ) ) {
            $current_locations = get_theme_mod( 'nav_menu_locations', [] );
            foreach ( $locations as $location => $old_menu_id ) {
                // Try to find matching menu by slug in location_map
                foreach ( $menus_list as $m ) {
                    if ( isset( $location_map[ $m['slug'] ] ) ) {
                        // Match location name to menu — use location_map
                    }
                }
                // Fallback: keep the location mapped to imported menu with same index
                if ( isset( $location_map ) ) {
                    $values = array_values( $location_map );
                    if ( ! empty( $values ) ) {
                        // Assign by order
                    }
                }
            }
            // Simpler: just assign by slug if location slug matches menu slug
            foreach ( $location_map as $slug => $mid ) {
                $current_locations[ $slug ] = $mid;
            }
            set_theme_mod( 'nav_menu_locations', $current_locations );
        }

        return $imported;
    }

    private function import_widgets( $widget_data ) {
        $imported = [];

        if ( ! empty( $widget_data['sidebars'] ) ) {
            update_option( 'sidebars_widgets', $widget_data['sidebars'] );
            $imported[] = 'sidebars_widgets';
        }

        if ( ! empty( $widget_data['widgets'] ) ) {
            foreach ( $widget_data['widgets'] as $type => $option_value ) {
                update_option( 'widget_' . $type, $option_value );
                $imported[] = 'widget_' . $type;
            }
        }

        return $imported;
    }
}
